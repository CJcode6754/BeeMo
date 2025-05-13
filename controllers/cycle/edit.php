<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$id = $_SESSION['user']['id'];

$cycle = $db->query(
    'SELECT * FROM harvest_cycle where id = :id',
    [
        'id' => $_GET['id']
    ]
)->findOrFail();

authorize($cycle['admin_id'] == $id);

view("/cycle/edit.php", [
    'heading' => 'Edit Cycle',
    'errors' => [],
    'cycle' => $cycle
]);
