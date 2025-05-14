<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$currentAdminID = 10;

$worker = $db->query('SELECT * FROM user_table where id = :id', [
    'id' => $_GET['id']]
    )->findOrFail();

authorize($worker['admin_id'] == $currentAdminID);

view("/worker/show.php", [
    'heading' => 'Worker',
    'worker' => $worker
]);