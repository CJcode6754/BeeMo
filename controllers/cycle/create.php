<?php
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$hiveID = 1;
$db->query('INSERT INTO harvest_cycle (cycle_number, start_of_cycle, end_of_cycle, admin_id, hiveID) VALUES (:cycle_number, :start, :end, :admin_id, :hiveID)', [
    'cycle_number' => $_POST['cycle_num'],
    'start' => $_POST['start_date'],
    'end' => $_POST['end_date'],
    'admin_id' => $_POST['id'],
    'hiveID' => $hiveID,
]);

header('location: /harvestCycle');
exit();