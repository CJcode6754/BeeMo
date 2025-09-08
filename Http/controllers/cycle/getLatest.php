<?php
// harvestCycle/getLatest.php

use Core\App;
use Core\Database;

header('Content-Type: application/json');

try {
    $db = App::resolve(Database::class);
    $id = $_SESSION['user']['id'];

    // Get last cycle of this admin
    $latestCycle = $db->query(
        "SELECT cycle_number, end_of_cycle 
         FROM harvest_cycle 
         WHERE admin_id = :admin_id 
         ORDER BY cycle_number DESC 
         LIMIT 1",
        ['admin_id' => $id]
    )->find();

    if ($latestCycle) {
        $lastEnd = new DateTime($latestCycle['end_of_cycle']);
        $newStart = clone $lastEnd;
        $newStart->modify('+1 day'); // next cycle starts after last end
        $newEnd = clone $newStart;
        $newEnd->modify('+6 months'); // example: fixed 30-day cycle

        echo json_encode([
            'cycle_number' => $latestCycle['cycle_number'],
            'start_date'   => $newStart->format('Y-m-d'),
            'end_date'     => $newEnd->format('Y-m-d')
        ]);
    } else {
        // No previous cycle
        echo json_encode([
            'cycle_number' => null,
            'start_date'   => date('Y-m-d'),
            'end_date'     => date('Y-m-d', strtotime('+6 months'))
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
