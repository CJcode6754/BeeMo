<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BeeMo_db";

// $servername = "localhost";
// $username = "u497761604_BeeMo";
// $password = "NewPassword@6789054321";
// $dbname = "u497761604_BeeMo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$adminID = 10 ?? null;  // Ensure adminID is set
$hiveID = 1 ?? null; // Get hiveID from the session

if (!$adminID) {
    echo json_encode(['error' => 'Admin ID not found']);
    exit;
}

if (!$hiveID) {
    echo json_encode(['error' => 'Hive ID is required']);
    exit;
}

// Check for monthly data request
if (isset($data['start_date'], $data['end_date'], $data['cycle_id'])) {
    $startDateStr = $data['start_date'];
    $endDateStr = $data['end_date'];
    $cycleID = (int)$data['cycle_id']; // Get cycle ID from request

    // Validate the dates
    if (empty($startDateStr) || empty($endDateStr)) {
        echo json_encode(['error' => 'Start date and end date are required']);
        exit;
    }

    // Query the harvest_cycle table to get the cycle's start and end dates
    $cycleQuery = "SELECT start_of_cycle, end_of_cycle 
                   FROM harvest_cycle 
                   WHERE id = ? AND hiveID = ?";
    $stmtCycle = $conn->prepare($cycleQuery);
    
    if (!$stmtCycle) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }
    
    // Bind the parameters for cycleID and hiveID
    $stmtCycle->bind_param("ii", $cycleID, $hiveID);
    
    if (!$stmtCycle->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $stmtCycle->error]);
        exit;
    }

    $cycleResult = $stmtCycle->get_result();
    if ($cycleResult->num_rows === 0) {
        echo json_encode(['error' => 'Invalid cycle ID']);
        exit;
    }

    // Fetch cycle dates
    $cycle = $cycleResult->fetch_assoc();
    $cycleStartDate = new DateTime($cycle['start_of_cycle']);
    $cycleEndDate = new DateTime($cycle['end_of_cycle']);
    $cycleEndDate->setTime(23, 59, 59); // End of the cycle day

    // Convert frontend start and end dates to DateTime objects
    $startDate = new DateTime($startDateStr);
    $endDate = new DateTime($endDateStr);
    $endDate->setTime(23, 59, 59); // End of the day

    // Adjust dates to ensure they fall within the cycle boundaries
    if ($startDate < $cycleStartDate) {
        $startDate = $cycleStartDate; // Ensure start date is not before cycle start
    }
    if ($endDate > $cycleEndDate) {
        $endDate = $cycleEndDate; // Ensure end date is not after cycle end
    }

    // Prepare SQL query for monthly data including hiveID
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d') AS period,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE timestamp >= ? AND timestamp <= ? AND adminID = ? AND hiveID = ?
        GROUP BY period
        ORDER BY period
    ";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    // Format dates for binding
    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    // Ensure adminID and hiveID are valid integers
    $adminID = (int)$adminID;
    $hiveID = (int)$hiveID;

    // Bind the parameters
    $stmt->bind_param("ssii", $startDateFormatted, $endDateFormatted, $adminID, $hiveID);

    // Execute the query and check for errors
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $data = [];
    $temperature = [];
    $humidity = [];
    $weight = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $temperature[] = $row['avg_temperature'];
        $humidity[] = $row['avg_humidity'];
        $weight[] = $row['avg_weight'];
    }

    // Calculate statistics
    $stats = [
        'temperature' => [
            'average' => count($temperature) ? array_sum($temperature) / count($temperature) : null,
            'min' => count($temperature) ? min($temperature) : null,
            'max' => count($temperature) ? max($temperature) : null
        ],
        'humidity' => [
            'average' => count($humidity) ? array_sum($humidity) / count($humidity) : null,
            'min' => count($humidity) ? min($humidity) : null,
            'max' => count($humidity) ? max($humidity) : null
        ],
        'weight' => [
            'average' => count($weight) ? array_sum($weight) / count($weight) : null,
            'min' => count($weight) ? min($weight) : null,
            'max' => count($weight) ? max($weight) : null,
            'previous' => null,  // Placeholder for previous weight
            'gain' => null,       // Placeholder for weight gain
            'fullcycle_previous' => null,
            'fullcycle_gain' => null
        ]
    ];
    
    $lastDayOfSelectedMonth = date('Y-m-t', strtotime($startDateFormatted));
    
    // Get the last day of the previous month
    $lastDayOfPreviousMonth = date('Y-m-t', strtotime($startDateFormatted . ' -1 month'));
    
    // Get previous weight (average of last day of previous month)
    $previousWeightSql = "
        SELECT AVG(weight) AS previous_avg_weight
        FROM subdata
        WHERE 
            DATE(timestamp) = ? 
            AND adminID = ? 
            AND hiveID = ?
    ";
    
    // Prepare and execute the previous weight query
    $previousWeightStmt = $conn->prepare($previousWeightSql);
    if (!$previousWeightStmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }
    $previousWeightStmt->bind_param("sii", $lastDayOfPreviousMonth, $adminID, $hiveID);
    
    // Execute the query and check for errors
    if (!$previousWeightStmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $previousWeightStmt->error]);
        exit;
    }
    
    $previousWeightResult = $previousWeightStmt->get_result();
    $previousWeight = $previousWeightResult->fetch_assoc();
    $previousAvgWeight = $previousWeight['previous_avg_weight'] ?? null;
    
    // Get current max weight (average of the last day of the selected month)
    $currentWeightSql = "
        SELECT AVG(weight) AS current_avg_weight
        FROM subdata
        WHERE DATE(timestamp) = ? 
        AND adminID = ? 
        AND hiveID = ?
    ";
    
    // Prepare and execute the current weight query
    $currentWeightStmt = $conn->prepare($currentWeightSql);
    if (!$currentWeightStmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }
    $currentWeightStmt->bind_param("sii", $lastDayOfSelectedMonth, $adminID, $hiveID);
    
    // Execute the query and check for errors
    if (!$currentWeightStmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $currentWeightStmt->error]);
        exit;
    }
    
    $currentWeightResult = $currentWeightStmt->get_result();
    $currentWeight = $currentWeightResult->fetch_assoc();
    $currentAvgWeight = $currentWeight['current_avg_weight'] ?? 0;
    
    // Calculate weight gain (current max weight - previous weight)
    $weightGain = $currentAvgWeight - ($previousAvgWeight ?? 0);
    
    $stats['weight']['previous'] = $previousAvgWeight;
    $stats['weight']['gain'] = $weightGain;
    
    // Calculate full cycle weight gain
    $fullCycleStartDate = $cycleStartDate->format('Y-m-d');
    $fullCycleEndDate = $cycleEndDate->format('Y-m-d');
    
    $fullCycleWeightSql = "
        SELECT 
            AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
            AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
        FROM subdata
        WHERE 
            DATE(timestamp) IN (?, ?)
            AND adminID = ?
            AND hiveID = ?
    ";
    
    $fullCycleWeightStmt = $conn->prepare($fullCycleWeightSql);
    if (!$fullCycleWeightStmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }
    
    $fullCycleWeightStmt->bind_param(
        'ssssii', 
        $fullCycleStartDate,   // Start of cycle date
        $fullCycleEndDate,     // End of cycle date
        $fullCycleStartDate,   // First date again for start weight
        $fullCycleEndDate,     // Last date for end weight
        $adminID, 
        $hiveID
    );
    
    if (!$fullCycleWeightStmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $fullCycleWeightStmt->error]);
        exit;
    }
    
    $fullCycleWeightResult = $fullCycleWeightStmt->get_result()->fetch_assoc();
    
    $startCycleWeight = $fullCycleWeightResult['start_cycle_weight'] ?? 0;
    $endCycleWeight = $fullCycleWeightResult['end_cycle_weight'] ?? 0;
    $fullCycleWeightGain = $endCycleWeight - $startCycleWeight;
    
    // Use different keys to avoid overwriting monthly weight gain
    $stats['weight']['fullcycle_previous'] = $startCycleWeight;
    $stats['weight']['fullcycle_gain'] = $fullCycleWeightGain;

// // Function to interpret environmental correlation
//  function interpretEnvironmentalCorrelation($correlation, $factor) {
//    $strength = abs($correlation);
//      $strengthText = $strength < 0.3 ? "weak" : ($strength < 0.7 ? "moderate" : "strong");
    
//      if ($factor === 'temperature') {
//          if ($correlation > 0) {
//              return "There is a {$strengthText} positive correlation (Correlation Coefficient: " . number_format($correlation, 2) . ") " .
//                     "between temperature and hive weight. As temperature is in the optimal range, bees increased foraging activity and honey production.";
//          } else {
//              return "There is a {$strengthText} negative correlation (Correlation Coefficient: " . number_format($correlation, 2) . ") " .
//                     "between temperature and hive weight. The temperature conditions may be causing increased energy expenditure, either from cooling activities in high temperatures, leading to higher honey consumption.";
//          }
//      } else { // humidity
//          if ($correlation > 0) {
//              return "There is a {$strengthText} positive correlation (Correlation Coefficient: " . number_format($correlation, 2) . ") " .
//                     "between humidity and hive weight. The current humidity levels are supporting optimal honey production, allowing bees to efficiently process and store honey.";
//          } else {
//              return "There is a {$strengthText} negative correlation (Correlation Coefficient: " . number_format($correlation, 2) . ") " .
//                     "between humidity and hive weight. The humidity conditions may be affecting nectar processing efficiency that impacts the colony's honey production and storage capabilities.";
//         }
//      }
//  }

function generateBeeInsights($stats, $data) {
    $insights = [
        'temperature' => [],
        'humidity' => [],
        'weight' => [],
        'overall' => []
    ];

    // Temperature insights
    $avgTemp = $stats['temperature']['average'];
    $minTemp = $stats['temperature']['min'];
    $maxTemp = $stats['temperature']['max'];

    if ($avgTemp < 32) {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is below the optimal range for stingless bees (32–35°C). When the temperature falls below 32°C, stingless bees consume their stored honey to generate energy. This behavior negatively impacts honey production and hive growth.";
    } elseif ($avgTemp > 35) {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is above the optimal range. When the temperature exceeds 35°C, stingless bees beat their wings to lower the hive temperature. This activity consumes energy, reducing their efficiency in producing and storing honey.";
    } else {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is within the optimal range for stingless bees (32–35°C), supporting efficient honey production and healthy colony growth.";
    }

    // Humidity insights
    $avgHumidity = $stats['humidity']['average'];
    $minHumidity = $stats['humidity']['min'];
    $maxHumidity = $stats['humidity']['max'];

    if ($avgHumidity < 50) {
        $insights['humidity'][] = "Low humidity (" . number_format($avgHumidity, 2) . "%) may cause stress to the bee colony, potentially affecting their metabolic processes and honey production.";
    } elseif ($avgHumidity > 60) {
        $insights['humidity'][] = "High humidity (" . number_format($avgHumidity, 2) . "%) might create conditions favorable for mold growth and could affect honey quality.";
        $insights['overall'][] = "Excessive moisture in the hive could pose risks to honey storage and bee health.";
    } else {
        $insights['humidity'][] = "The humidity (" . number_format($avgHumidity, 2) . "%) is within the optimal range, supporting bee colony health.";
    }

    // Weight gain insights with detailed environmental correlation
    $weightGain = $stats['weight']['gain'];
    $currentMaxWeight = $stats['weight']['min'];
    $previousWeight = $stats['weight']['previous'];

    // Correlation analysis
    // $temperatureWeightCorrelation = calculateCorrelation($data, 'avg_temperature', 'avg_weight');
    // $humidityWeightCorrelation = calculateCorrelation($data, 'avg_humidity', 'avg_weight');

    // Enhanced Weight Loss and Temperature Effects Analysis
    $weightLossInsights = [];

    // Gradual Weight Loss Analysis
    $weightLossPercentage = $previousWeight > 0 ? (($previousWeight - $currentMaxWeight) / $previousWeight) * 100 : 0;
    
    
    // Existing weight gain analysis (kept from previous implementation)
    if ($weightGain > 0) {
        $gainPercentage = ($weightGain / ($previousWeight ?: 1)) * 100;
        $insights['weight'][] = "Positive Hive Growth: The hive gained " . number_format($weightGain, 2) . " grams, with weight increased by " . number_format($gainPercentage, 2) . "%. " . 
            "The average temperature is " . number_format($avgTemp, 2) . "°C, which falls within the optimal range of 32°C to 35°C for honeybee activity. These favorable conditions play a critical role in promoting efficient honey production and the overall development of the colony.";
    }
    
    if ($weightLossPercentage > 0 && $weightLossPercentage < 10) {
        $gradualWeightLossReasons = [];

        if ($avgTemp < 32) {
            $gradualWeightLossReasons[] = "Low temperatures forcing bees to consume stored honey for energy maintenance.";
        } elseif ($avgTemp > 35) {
            $gradualWeightLossReasons[] = "High temperatures causing increased energy expenditure for hive cooling.";
        }

        if ($avgHumidity > 60) {
            $gradualWeightLossReasons[] = "High humidity potentially impacting honey preservation and bee metabolism.";
        }

        $weightLossInsights[] = "Gradual Weight Loss Detected: " . number_format($weightLossPercentage, 2) . "% reduction. " .
            "Potential factors include: " . implode(" ", $gradualWeightLossReasons) . " " .
            "This slight decline in weight indicates that the colony may be under mild stress, likely due to environmental conditions. The decrease in weight may also result from stingless bees consuming stored honey to meet their energy needs during challenging conditions.";
    }

    // Sudden Weight Loss Analysis
    if ($previousWeight > 0 && $currentMaxWeight < ($previousWeight * 0.9)) {  // More than 10% weight loss
        $suddenWeightLossReasons = [
            "Extreme temperature fluctuations causing significant energy expenditure.",
            "Potential predator or environmental disturbance.",
            "Possible disease outbreak or parasite infestation.",
            "Reduced foraging opportunities due to environmental changes."
        ];

        $weightLossInsights[] = "Critical Weight Loss Alert: Sudden drop of " . number_format($weightLossPercentage, 2) . "% detected. " .
            "This substantial weight reduction indicates severe colony stress. Potential causes include: " . 
            implode(" ", $suddenWeightLossReasons) . " " .
            "Immediate intervention and comprehensive hive assessment are strongly recommended.";

        // Add high-risk warning to overall insights
        $insights['overall'][] = "HIGH-RISK COLONY STATUS: Significant weight loss detected. Urgent investigation required.";
    }
    
    if (!empty($weightLossInsights)) {
        $insights['weight'] = array_merge($insights['weight'], $weightLossInsights);
    }
    
    // if (!empty($weightLossInsights) || $weightGain > 0) {
    //     $insights['weight'][] = "Environmental Impact Analysis:\n" .
    //         "Temperature Impact: " . interpretEnvironmentalCorrelation($temperatureWeightCorrelation, 'temperature') . "\n" .
    //         "Humidity Impact: " . interpretEnvironmentalCorrelation($humidityWeightCorrelation, 'humidity');
    // }
    
    return $insights;
}
    
    function generateFullCycleInsights($stats, $data) {
        $fullInsights = [
            'temperature' => [],
            'humidity' => [],
            'weight' => [],
            'overall' => []
        ];

        // Temperature insights
        $avgTempF = $stats['temperature']['average'];
        $minTempF = $stats['temperature']['min'];
        $maxTempF = $stats['temperature']['max'];

        if ($avgTempF < 32) {
            $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is below the optimal range for stingless bees (32–35°C). When the temperature falls below 32°C, stingless bees consume their stored honey to generate energy. This behavior negatively impacts honey production and hive growth.";
        } elseif ($avgTempF > 35) {
            $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is above the optimal range. When the temperature exceeds 35°C, stingless bees beat their wings to lower the hive temperature. This activity consumes energy, reducing their efficiency in producing and storing honey.";
        } else {
            $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is within the optimal range for stingless bees (32–35°C), supporting efficient honey production and healthy colony growth.";
        }

        // Humidity insights
        $avgHumidityF = $stats['humidity']['average'];
        $minHumidityF = $stats['humidity']['min'];
        $maxHumidityF = $stats['humidity']['max'];

        if ($avgHumidityF < 50) {
            $fullInsights['humidity'][] = "Low humidity (" . number_format($avgHumidityF, 2) . "%) may cause stress to the bee colony, potentially affecting their metabolic processes and honey production.";
        } elseif ($avgHumidityF > 60) {
            $fullInsights['humidity'][] = "High humidity (" . number_format($avgHumidityF, 2) . "%) might create conditions favorable for mold growth and could affect honey quality.";
            $fullInsights['overall'][] = "Excessive moisture in the hive could pose risks to honey storage and bee health.";
        } else {
            $fullInsights['humidity'][] = "The humidity (" . number_format($avgHumidityF, 2) . "%) is within the optimal range, supporting bee colony health.";
        }

        // Full Cycle Weight gain insights with detailed environmental correlation
        $fullWeightGain = $stats['weight']['fullcycle_gain'];
        $fullCurrentMaxWeight = $stats['weight']['min']; // Assuming 'fullcycle_min' for full cycle minimum weight
        $fullPreviousWeight = $stats['weight']['fullcycle_previous'];
        
        // Correlation analysis for full cycle
        // $temperatureWeightCorrelation = calculateCorrelation($data, 'avg_temperature', 'avg_weight');
        // $humidityWeightCorrelation = calculateCorrelation($data, 'avg_humidity', 'avg_weight');
        
        // Enhanced Weight Loss and Temperature Effects Analysis for full cycle
        $fullWeightLossInsights = [];
        
        // Gradual Weight Loss Analysis for full cycle
        $fullWeightLossPercentage = $fullPreviousWeight > 0 ? (($fullPreviousWeight - $fullCurrentMaxWeight) / $fullPreviousWeight) * 100 : 0;
        
        // Existing weight gain analysis for full cycle
        if ($fullWeightGain > 0 && ($avgTempF <= 35 && $avgTempF >= 32)) {
            $gainPercentage = ($fullWeightGain / ($fullPreviousWeight ?: 1)) * 100;
            $fullInsights['weight'][] = "Positive Hive Growth: The hive gained " . number_format($fullWeightGain, 2) . " grams, with weight increased by " . number_format($gainPercentage, 2) . "%. " .
                "The average temperature is " . number_format($avgTempF, 2) . "°C, which falls within the optimal range of 32°C to 35°C for honeybee activity. These favorable conditions play a critical role in promoting efficient honey production and the overall development of the colony.";
        }
        
        if ($fullWeightLossPercentage > 0 && $fullWeightLossPercentage < 10) {
            $gradualWeightLossReasons = [];
        
            if ($avgTempF < 32) {
                $gradualWeightLossReasons[] = "Low temperatures forcing bees to consume stored honey for energy maintenance.";
            } elseif ($avgTempF > 35) {
                $gradualWeightLossReasons[] = "High temperatures causing increased energy expenditure for hive cooling.";
            }
        
            if ($avgHumidityF > 60) {
                $gradualWeightLossReasons[] = "High humidity potentially impacting honey preservation and bee metabolism.";
            }
        
            $fullWeightLossInsights[] = "Gradual Weight Loss Detected: " . number_format($fullWeightLossPercentage, 2) . "% reduction. " .
                "Potential factors include: " . implode(" ", $gradualWeightLossReasons) . " " .
                "This slight decline in weight indicates that the colony may be under mild stress, likely due to environmental conditions. The decrease in weight may also result from stingless bees consuming stored honey to meet their energy needs during challenging conditions.";
        }
        
        // Sudden Weight Loss Analysis for full cycle
        if ($fullPreviousWeight > 0 && $fullCurrentMaxWeight < ($fullPreviousWeight * 0.9)) {  // More than 10% weight loss
            $suddenWeightLossReasons = [
                "Extreme temperature fluctuations causing significant energy expenditure.",
                "Potential predator or environmental disturbance.",
                "Possible disease outbreak or parasite infestation.",
                "Reduced foraging opportunities due to environmental changes."
            ];
        
            $fullWeightLossInsights[] = "Critical Weight Loss Alert: Sudden drop of " . number_format($fullWeightLossPercentage, 2) . "% detected. " .
                "This substantial weight reduction indicates severe colony stress. Potential causes include: " .
                implode(" ", $suddenWeightLossReasons) . " " .
                "Immediate intervention and comprehensive hive assessment are strongly recommended.";
        
            // Add high-risk warning to overall insights
            $fullInsights['overall'][] = "HIGH-RISK COLONY STATUS: Significant weight loss detected. Urgent investigation required.";
        }
        
        // Always merge weight loss insights if they exist
        if (!empty($fullWeightLossInsights)) {
            $fullInsights['weight'] = array_merge($fullInsights['weight'], $fullWeightLossInsights);
        }
    
        // // Full Cycle Weight Gain/Loss Comparative Analysis
        // if (!empty($fullWeightLossInsights) || $fullWeightGain > 0) {
        //     $fullInsights['weight'][] = "Environmental Impact Analysis:\n" .
        //         "Temperature Impact: " . interpretEnvironmentalCorrelation($temperatureWeightCorrelation, 'temperature') . "\n" .
        //         "Humidity Impact: " . interpretEnvironmentalCorrelation($humidityWeightCorrelation, 'humidity');
        // }

        return $fullInsights;
    }
    
    // // Function to calculate Pearson correlation coefficient
    //  function calculateCorrelation($data, $xKey, $yKey) {
    //      if (empty($data)) return 0;
    
    //      $x = array_column($data, $xKey);
    //      $y = array_column($data, $yKey);
    
    //     $n = count($x);
    //      $sumX = array_sum($x);
    //      $sumY = array_sum($y);
    //      $sumXX = array_sum(array_map(function($val) { return $val * $val; }, $x));
    //      $sumYY = array_sum(array_map(function($val) { return $val * $val; }, $y));
    //      $sumXY = array_sum(array_map(function($a, $b) { return $a * $b; }, $x, $y));
    
    //      $numerator = $n * $sumXY - $sumX * $sumY;
    //      $denominator = sqrt(($n * $sumXX - $sumX * $sumX) * ($n * $sumYY - $sumY * $sumY));
    
    //      return $denominator != 0 ? $numerator / $denominator : 0;
    //  }
    
    // // // Function to interpret correlation coefficient
    //  function interpretCorrelation($correlation) {
    //      $absCorrelation = abs($correlation);
    //      if ($absCorrelation < 0.3) return "Weak correlation";
    //      if ($absCorrelation < 0.7) return "Moderate correlation";
    //      return "Strong correlation";
    // }
    
    // Modify the existing JSON response to include insights
    $insights = generateBeeInsights($stats, $data);
    $fullInsights = generateFullCycleInsights($stats, $data);
    
    // Return data as JSON with added insights
    echo json_encode([
        'data' => $data,
        'stats' => $stats,
        'insights' => $insights,
        'fullInsights'=> $fullInsights
    ]);

    $conn->close(); 
} 
?>
