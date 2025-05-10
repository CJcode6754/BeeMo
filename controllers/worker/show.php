<?php
use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);
$currentAdminID = 10;

$worker = $db->query('SELECT * FROM user_table where id = :id', [
    'id' => $_GET['id']]
    )->findOrFail();

authorize($worker['admin_id'] == $currentAdminID);

view("/worker/show.php", [
    'heading' => 'Worker',
    'worker' => $worker
]);