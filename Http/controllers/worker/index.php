<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$admin_id = $_SESSION['user']['id'];
$workers = $db->query('SELECT * FROM user_table where admin_id = :admin_id', [
    'admin_id' => $admin_id
])->get();

view("worker/index.php", [
    'heading' => 'Worker',
    'workers' => $workers
]);