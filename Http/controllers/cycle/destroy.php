<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$currentAdminID =  $_SESSION['user']['id'];

$cycle = $db->query('SELECT * FROM harvest_cycle WHERE id = :id', [
    'id' => $_POST['id']
])->findOrFail();

authorize($cycle['admin_id'] === $currentAdminID);

$db->query('DELETE FROM harvest_cycle WHERE id = :id', [
    'id' => $_POST['id']
]);

header('location: /harvestCycle');
exit();
