<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$id = $_SESSION['user']['id'];
$admin_cycles = $db->query("SELECT * FROM harvest_cycle where admin_id = {$id}")->get();
$worker_cycles = $db->query("SELECT * FROM user_harvest_cycle where admin_id = {$id}")->get();
view("cycle/index.php", [
    'heading' => 'Harvest Cycle',
    'admin_cycles' => $admin_cycles,
    'worker_cycles' => $worker_cycles
]);