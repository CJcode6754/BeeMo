<?php
require base_path('core/Database.php');

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cycleId = $_POST['CycleID'] ?? null;

    if (!$cycleId) {
        http_response_code(400);
        echo json_encode(['error' => 'Cycle ID is required']);
        exit;
    }

    // ✅ Update cycle status = 1 (complete) and set end date to today
    $db->query(
        "UPDATE harvest_cycle 
         SET status = 1, end_of_cycle = CURDATE() 
         WHERE id = :id",
        ['id' => $cycleId]
    );

    // Redirect back
    header("Location: /harvestCycle");
    exit;
}
