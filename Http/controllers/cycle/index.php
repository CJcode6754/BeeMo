<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$id = $_SESSION['user']['id'];

/**
 * Helper function: calculate honey production from aggregate data
 */
function calculateHoney($db, $hiveId, $start, $end)
{
    $startWeight = $db->query(
        "SELECT weight 
         FROM aggregated_hive_data 
         WHERE hiveID = :hive 
           AND timestamp >= :start 
           AND (:end IS NULL OR timestamp <= :end)
         ORDER BY timestamp ASC
         LIMIT 1",
        ['hive' => $hiveId, 'start' => $start, 'end' => $end]
    )->find();

    $endWeight = $db->query(
        "SELECT weight 
         FROM aggregated_hive_data 
         WHERE hiveID = :hive 
           AND timestamp >= :start 
           AND (:end IS NULL OR timestamp <= :end)
         ORDER BY timestamp DESC
         LIMIT 1",
        ['hive' => $hiveId, 'start' => $start, 'end' => $end]
    )->find();

    $honey = null;
    if ($startWeight && $endWeight) {
        $honey = $startWeight['weight'] - $endWeight['weight'];
    }

    return [
        'honey' => $honey,
        'start_weight' => $startWeight['weight'] ?? null,
        'end_weight'   => $endWeight['weight'] ?? null,
    ];
}

/**
 * Admin cycles (harvest_cycle)
 */
$admin_cycles_raw = $db->query(
    "SELECT * FROM harvest_cycle WHERE admin_id = :id",
    ['id' => $id]
)->get();

$admin_cycles = [];
foreach ($admin_cycles_raw as $cycle) {
    $calc = calculateHoney($db, $cycle['hiveID'], $cycle['start_of_cycle'], $cycle['end_of_cycle']);
    $admin_cycles[] = [
        'cycle' => $cycle,
        'honey' => $calc['honey'],
        'start_weight' => $calc['start_weight'],
        'end_weight'   => $calc['end_weight'],
    ];
}

/**
 * Worker cycles (user_harvest_cycle)
 */
$worker_cycles_raw = $db->query(
    "SELECT * FROM user_harvest_cycle WHERE admin_id = :id",
    ['id' => $id]
)->get();

$worker_cycles = [];
foreach ($worker_cycles_raw as $cycle) {
    $calc = calculateHoney($db, $cycle['hiveID'], $cycle['start_of_cycle'], $cycle['end_of_cycle']);
    $worker_cycles[] = [
        'cycle' => $cycle,
        'honey' => $calc['honey'],
        'start_weight' => $calc['start_weight'],
        'end_weight'   => $calc['end_weight'],
    ];
}

view("cycle/index.php", [
    'heading' => 'Harvest Cycle',
    'admin_cycles' => $admin_cycles,
    'worker_cycles' => $worker_cycles
]);
