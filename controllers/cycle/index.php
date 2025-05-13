<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$admin_cycles = $db->query('SELECT * FROM harvest_cycle where admin_id = 10')->get();
$worker_cycles = $db->query('SELECT * FROM user_harvest_cycle where admin_id = 10')->get();
view("cycle/index.php", [
    'heading' => 'Harvest Cycle',
    'admin_cycles' => $admin_cycles,
    'worker_cycles' => $worker_cycles
]);