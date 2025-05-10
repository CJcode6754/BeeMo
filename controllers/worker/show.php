<?php
use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

$currentAdminID = 10;
$worker = $db->query('SELECT * FROM user_table where id = :id', ['id' => $_GET['id']])->findOrFail();

authorize($worker['id']!=$worker);

view("/worker/show.php", [
    'heading' => 'Worker',
    'worker' => $worker
]);