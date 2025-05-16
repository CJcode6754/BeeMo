<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$admin_id = $_SESSION['user']['id'] ?? null;
$hive_id = $_SESSION['hiveID'] ?? null;

if (!$admin_id || !$hive_id) {
    die("Admin ID or Hive ID is missing.");
}

// Fetch latest hive data
$query = "SELECT temperature, humidity, weight FROM hive1 
          WHERE adminID = :adminID AND hiveID = :hiveID 
          ORDER BY id DESC LIMIT 1";
$params = ['adminID' => $admin_id, 'hiveID' => $hive_id];
$data = $db->query($query, $params)->find();

$alerts = [];

if ($data) {
    $temp = $data['temperature'];
    $humid = $data['humidity'];
    $weight = $data['weight'];
    $netWeightkg = $weight - 1;
    $now = time();

    $prevTemp = $_SESSION['previousTemperature'] ?? null;
    $prevHumid = $_SESSION['previousHumidity'] ?? null;
    $prevWeight = $_SESSION['previousWeight'] ?? null;
    $lastNotifyTime = $_SESSION['lastNotificationTime'] ?? null;
    $prevHiveID = $_SESSION['previousHiveID'] ?? null;
    $weightSent = $_SESSION['weightNotificationSent'] ?? false;

    // Check temperature
    if ($temp > 36 && $temp !== $prevTemp) {
        $alerts[] = "⚠️ Temperature exceeds optimal range!";
    } elseif ($temp < 32 && $temp !== $prevTemp) {
        $alerts[] = "⚠️ Temperature below optimal range!";
    }

    // Check humidity
    if ($humid > 60 && $humid !== $prevHumid) {
        $alerts[] = "⚠️ Humidity exceeds optimal range!";
    } elseif ($humid < 50 && $humid !== $prevHumid) {
        $alerts[] = "⚠️ Humidity below optimal range!";
    }

    // Reset weight alert if needed
    if (($lastNotifyTime && ($now - $lastNotifyTime) >= 3600) || $hive_id !== $prevHiveID) {
        $_SESSION['weightNotificationSent'] = false;
        $weightSent = false;
    }

    if ($netWeightkg >= 2 && !$weightSent) {
        $alerts[] = "✅ Hive $hive_id is ready for harvest!";
        $_SESSION['weightNotificationSent'] = true;
        $_SESSION['lastNotificationTime'] = $now;
        $_SESSION['previousHiveID'] = $hive_id;
    }

    // Store for future comparison
    $_SESSION['previousTemperature'] = $temp;
    $_SESSION['previousHumidity'] = $humid;
    $_SESSION['previousWeight'] = $weight;

} else {
    $data = [
        'temperature' => 'No data',
        'humidity' => 'No data',
        'weight' => 'No data'
    ];
}

view("parameterMonitoring.php", [
    'heading' => 'Parameter Monitoring',
    'data' => $data,
    'alerts' => $alerts
]);
