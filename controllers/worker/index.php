<?php
use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

$workers = $db->query('SELECT * FROM user_table where admin_id = 10')->get();

view("worker/index.php", [
    'heading' => 'Worker',
    'workers' => $workers
]);