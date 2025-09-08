<?php
// Turn off error display for clean JSON output
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

session_start();

// Set content type before any output
header('Content-Type: application/json');

// Function to safely output JSON and exit
function safeJsonExit($data)
{
    echo json_encode($data);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BeeMo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    safeJsonExit(['error' => 'Connection failed: ' . $conn->connect_error]);
}

$input = file_get_contents('php://input');
if (!$input) {
    safeJsonExit(['error' => 'No input data received']);
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    safeJsonExit(['error' => 'Invalid JSON data: ' . json_last_error_msg()]);
}

$adminID = 16 ?? null;
$hiveID = 1 ?? null;

if (!$adminID) {
    safeJsonExit(['error' => 'Admin ID not found']);
}

if (!$hiveID) {
    safeJsonExit(['error' => 'Hive ID is required']);
}

// Check for monthly data request
if (isset($data['start_date'], $data['end_date'], $data['cycle_id'])) {
    $startDateStr = $data['start_date'];
    $endDateStr = $data['end_date'];
    $cycleID = (int)$data['cycle_id'];

    // Validate the dates
    if (empty($startDateStr) || empty($endDateStr)) {
        safeJsonExit(['error' => 'Start date and end date are required']);
    }

    // Query the harvest_cycle table to get the cycle's start and end dates
    $cycleQuery = "SELECT start_of_cycle, end_of_cycle 
                   FROM harvest_cycle 
                   WHERE id = ? AND hiveID = ?";
    $stmtCycle = $conn->prepare($cycleQuery);

    if (!$stmtCycle) {
        safeJsonExit(['error' => 'SQL prepare error: ' . $conn->error]);
    }

    $stmtCycle->bind_param("ii", $cycleID, $hiveID);

    if (!$stmtCycle->execute()) {
        safeJsonExit(['error' => 'SQL execute error: ' . $stmtCycle->error]);
    }

    $cycleResult = $stmtCycle->get_result();
    if ($cycleResult->num_rows === 0) {
        safeJsonExit(['error' => 'Invalid cycle ID']);
    }

    // Fetch cycle dates
    $cycle = $cycleResult->fetch_assoc();
    $cycleStartDate = new DateTime($cycle['start_of_cycle']);
    $cycleEndDate = new DateTime($cycle['end_of_cycle']);
    $cycleEndDate->setTime(23, 59, 59);

    // Convert frontend start and end dates to DateTime objects
    $startDate = new DateTime($startDateStr);
    $endDate = new DateTime($endDateStr);
    $endDate->setTime(23, 59, 59);

    // Adjust dates to ensure they fall within the cycle boundaries
    if ($startDate < $cycleStartDate) {
        $startDate = $cycleStartDate;
    }
    if ($endDate > $cycleEndDate) {
        $endDate = $cycleEndDate;
    }

    // Prepare SQL query for monthly data
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d') AS period,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM aggregated_hive_data
        WHERE timestamp >= ? AND timestamp <= ? AND adminID = ? AND hiveID = ?
        GROUP BY period
        ORDER BY period
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        safeJsonExit(['error' => 'SQL prepare error: ' . $conn->error]);
    }

    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    $adminID = (int)$adminID;
    $hiveID = (int)$hiveID;

    $stmt->bind_param("ssii", $startDateFormatted, $endDateFormatted, $adminID, $hiveID);

    if (!$stmt->execute()) {
        safeJsonExit(['error' => 'SQL execute error: ' . $stmt->error]);
    }

    $result = $stmt->get_result();
    $data_array = [];
    $temperature = [];
    $humidity = [];
    $weight = [];

    while ($row = $result->fetch_assoc()) {
        $data_array[] = $row;
        $temperature[] = $row['avg_temperature'];
        $humidity[] = $row['avg_humidity'];
        $weight[] = $row['avg_weight'];
    }

    // Calculate statistics
    $stats = [
        'temperature' => [
            'average' => count($temperature) ? (float)array_sum($temperature) / count($temperature) : null,
            'min' => count($temperature) ? (float)min($temperature) : null,
            'max' => count($temperature) ? (float)max($temperature) : null
        ],
        'humidity' => [
            'average' => count($humidity) ? (float)array_sum($humidity) / count($humidity) : null,
            'min' => count($humidity) ? (float)min($humidity) : null,
            'max' => count($humidity) ? (float)max($humidity) : null
        ],
        'weight' => [
            'average' => count($weight) ? (float)array_sum($weight) / count($weight) : null,
            'min' => count($weight) ? (float)min($weight) : null,
            'max' => count($weight) ? (float)max($weight) : null,
            'previous' => null,
            'gain' => null,
            'fullcycle_previous' => null,
            'fullcycle_gain' => null
        ]
    ];

    $lastDayOfSelectedMonth = date('Y-m-t', strtotime($startDateFormatted));
    $lastDayOfPreviousMonth = date('Y-m-t', strtotime($startDateFormatted . ' -1 month'));

    // Get previous weight
    $previousWeightSql = "
        SELECT AVG(weight) AS previous_avg_weight
        FROM aggregated_hive_data
        WHERE DATE(timestamp) = ? AND adminID = ? AND hiveID = ?
    ";

    $previousWeightStmt = $conn->prepare($previousWeightSql);
    if (!$previousWeightStmt) {
        safeJsonExit(['error' => 'SQL prepare error: ' . $conn->error]);
    }
    $previousWeightStmt->bind_param("sii", $lastDayOfPreviousMonth, $adminID, $hiveID);

    if (!$previousWeightStmt->execute()) {
        safeJsonExit(['error' => 'SQL execute error: ' . $previousWeightStmt->error]);
    }

    $previousWeightResult = $previousWeightStmt->get_result();
    $previousWeight = $previousWeightResult->fetch_assoc();
    $previousAvgWeight = $previousWeight['previous_avg_weight'] ?? null;

    // Get current weight
    $currentWeightSql = "
        SELECT AVG(weight) AS current_avg_weight
        FROM aggregated_hive_data
        WHERE DATE(timestamp) = ? AND adminID = ? AND hiveID = ?
    ";

    $currentWeightStmt = $conn->prepare($currentWeightSql);
    if (!$currentWeightStmt) {
        safeJsonExit(['error' => 'SQL prepare error: ' . $conn->error]);
    }
    $currentWeightStmt->bind_param("sii", $lastDayOfSelectedMonth, $adminID, $hiveID);

    if (!$currentWeightStmt->execute()) {
        safeJsonExit(['error' => 'SQL execute error: ' . $currentWeightStmt->error]);
    }

    $currentWeightResult = $currentWeightStmt->get_result();
    $currentWeight = $currentWeightResult->fetch_assoc();
    $previousAvgWeight = $previousWeight['previous_avg_weight'] ? (float)$previousWeight['previous_avg_weight'] : null;
    $currentAvgWeight = $currentWeight['current_avg_weight'] ? (float)$currentWeight['current_avg_weight'] : 0;

    $weightGain = $currentAvgWeight - ($previousAvgWeight ?? 0);

    $stats['weight']['previous'] = $previousAvgWeight;
    $stats['weight']['gain'] = (float)$weightGain;

    // Calculate full cycle weight gain
    $fullCycleStartDate = $cycleStartDate->format('Y-m-d');
    $fullCycleEndDate = $cycleEndDate->format('Y-m-d');

    $fullCycleWeightSql = "
        SELECT 
            AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS start_cycle_weight,
            AVG(CASE WHEN DATE(timestamp) = ? THEN weight END) AS end_cycle_weight
        FROM aggregated_hive_data
        WHERE DATE(timestamp) IN (?, ?) AND adminID = ? AND hiveID = ?
    ";

    $fullCycleWeightStmt = $conn->prepare($fullCycleWeightSql);
    if (!$fullCycleWeightStmt) {
        safeJsonExit(['error' => 'SQL prepare error: ' . $conn->error]);
    }

    $fullCycleWeightStmt->bind_param(
        'ssssii',
        $fullCycleStartDate,
        $fullCycleEndDate,
        $fullCycleStartDate,
        $fullCycleEndDate,
        $adminID,
        $hiveID
    );

    if (!$fullCycleWeightStmt->execute()) {
        safeJsonExit(['error' => 'SQL execute error: ' . $fullCycleWeightStmt->error]);
    }

    $fullCycleWeightResult = $fullCycleWeightStmt->get_result()->fetch_assoc();

    $startCycleWeight = $fullCycleWeightResult['start_cycle_weight'] ? (float)$fullCycleWeightResult['start_cycle_weight'] : 0;
    $endCycleWeight = $fullCycleWeightResult['end_cycle_weight'] ? (float)$fullCycleWeightResult['end_cycle_weight'] : 0;
    $fullCycleWeightGain = $endCycleWeight - $startCycleWeight;

    $stats['weight']['fullcycle_previous'] = $startCycleWeight;
    $stats['weight']['fullcycle_gain'] = (float)$fullCycleWeightGain;

    // Generate insights
    $insights = generateBeeInsights($stats, $data_array);
    $fullInsights = generateFullCycleInsights($stats, $data_array);

    safeJsonExit([
        'data' => $data_array,
        'stats' => $stats,
        'insights' => $insights,
        'fullInsights' => $fullInsights
    ]);
}

function generateBeeInsights($stats, $data)
{
    $insights = [
        'temperature' => [],
        'humidity' => [],
        'weight' => [],
        'overall' => []
    ];

    $avgTemp = $stats['temperature']['average'];
    $avgHumidity = $stats['humidity']['average'];
    $weightGain = $stats['weight']['gain'];

    if ($avgTemp < 32) {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is below the optimal range for stingless bees (32–35°C). When the temperature falls below 32°C, stingless bees consume their stored honey to generate energy. This behavior negatively impacts honey production and hive growth.";
    } elseif ($avgTemp > 35) {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is above the optimal range. When the temperature exceeds 35°C, stingless bees beat their wings to lower the hive temperature. This activity consumes energy, reducing their efficiency in producing and storing honey.";
    } else {
        $insights['temperature'][] = "The average temperature (" . number_format($avgTemp, 2) . "°C) is within the optimal range for stingless bees (32–35°C), supporting efficient honey production and healthy colony growth.";
    }

    if ($avgHumidity < 50) {
        $insights['humidity'][] = "Low humidity (" . number_format($avgHumidity, 2) . "%) may cause stress to the bee colony, potentially affecting their metabolic processes and honey production.";
    } elseif ($avgHumidity > 60) {
        $insights['humidity'][] = "High humidity (" . number_format($avgHumidity, 2) . "%) might create conditions favorable for mold growth and could affect honey quality.";
        $insights['overall'][] = "Excessive moisture in the hive could pose risks to honey storage and bee health.";
    } else {
        $insights['humidity'][] = "The humidity (" . number_format($avgHumidity, 2) . "%) is within the optimal range, supporting bee colony health.";
    }

    if ($weightGain > 0 && ($avgTemp <= 35 && $avgTemp >= 32)) {
        $previousWeight = $stats['weight']['previous'];
        $gainPercentage = ($weightGain / ($previousWeight ?: 1)) * 100;
        $insights['weight'][] = "Positive Hive Growth: The hive gained " . number_format($weightGain, 2) . " grams, with weight increased by " . number_format($gainPercentage, 2) . "%. The average temperature is " . number_format($avgTemp, 2) . "°C, which falls within the optimal range of 32°C to 35°C for honeybee activity. These favorable conditions play a critical role in promoting efficient honey production and the overall development of the colony.";
    }

    if ($weightGain < 0) {
        $previousWeight = $stats['weight']['previous'] ?: 1;
        $lossPercentage = ($previousWeight > 0)
            ? (abs($weightGain) / $previousWeight) * 100
            : 0;
        $insights['weight'][] = "Negative Hive Growth: The hive lost "
            . number_format(abs($weightGain), 2) . " grams ("
            . number_format($lossPercentage, 2) . "% decrease). This weight loss may indicate that bees consumed stored honey, possibly due to insufficient nectar flow, unfavorable weather, or stress factors inside the hive.";
        $insights['overall'][] = "A negative weight trend suggests the colony may be under stress or in a dearth period. Consider checking food sources, hive health, and environmental conditions.";
    }



    return $insights;
}

function generateFullCycleInsights($stats, $data)
{
    $fullInsights = [
        'temperature' => [],
        'humidity' => [],
        'weight' => [],
        'overall' => []
    ];

    $avgTempF = $stats['temperature']['average'];
    $avgHumidityF = $stats['humidity']['average'];
    $fullWeightGain = $stats['weight']['fullcycle_gain'];

    if ($avgTempF < 32) {
        $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is below the optimal range for stingless bees (32–35°C). When the temperature falls below 32°C, stingless bees consume their stored honey to generate energy. This behavior negatively impacts honey production and hive growth.";
    } elseif ($avgTempF > 35) {
        $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is above the optimal range. When the temperature exceeds 35°C, stingless bees beat their wings to lower the hive temperature. This activity consumes energy, reducing their efficiency in producing and storing honey.";
    } else {
        $fullInsights['temperature'][] = "The average temperature (" . number_format($avgTempF, 2) . "°C) is within the optimal range for stingless bees (32–35°C), supporting efficient honey production and healthy colony growth.";
    }

    if ($avgHumidityF < 50) {
        $fullInsights['humidity'][] = "Low humidity (" . number_format($avgHumidityF, 2) . "%) may cause stress to the bee colony, potentially affecting their metabolic processes and honey production.";
    } elseif ($avgHumidityF > 60) {
        $fullInsights['humidity'][] = "High humidity (" . number_format($avgHumidityF, 2) . "%) might create conditions favorable for mold growth and could affect honey quality.";
        $fullInsights['overall'][] = "Excessive moisture in the hive could pose risks to honey storage and bee health.";
    } else {
        $fullInsights['humidity'][] = "The humidity (" . number_format($avgHumidityF, 2) . "%) is within the optimal range, supporting bee colony health.";
    }

    if ($fullWeightGain > 0) {
        $fullPreviousWeight = $stats['weight']['fullcycle_previous'];
        $gainPercentage = ($fullWeightGain / ($fullPreviousWeight ?: 1)) * 100;
        $fullInsights['weight'][] = "Positive Hive Growth: The hive gained " . number_format($fullWeightGain, 2) . " grams, with weight increased by " . number_format($gainPercentage, 2) . "%. The average temperature is " . number_format($avgTempF, 2) . "°C, which falls within the optimal range of 32°C to 35°C for honeybee activity. These favorable conditions play a critical role in promoting efficient honey production and the overall development of the colony.";
    }

    if ($fullWeightGain < 0) {
        $fullPreviousWeight = $stats['weight']['fullcycle_previous'] ?: 1;
        $lossPercentage = ($fullPreviousWeight > 0)
            ? (abs($fullWeightGain) / $fullPreviousWeight) * 100
            : 0;
        $fullInsights['weight'][] = "Negative Hive Growth: Over the full cycle, the hive lost "
            . number_format(abs($fullWeightGain), 2) . " grams ("
            . number_format($lossPercentage, 2) . "% decrease). This suggests the colony relied heavily on stored honey, potentially due to poor forage availability, seasonal changes, or stress.";
        $fullInsights['overall'][] = "Sustained weight loss during a cycle may reduce honey yields and affect colony strength. Supplemental feeding or hive inspection may be necessary.";
    }



    return $fullInsights;
}
