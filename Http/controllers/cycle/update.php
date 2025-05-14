<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$id= $_SESSION['user']['id'];


$cycle = $db->query(
    'SELECT * FROM harvest_cycle where id = :id',
    [
        'id' => $_POST['id']
    ]
)->findOrFail();

authorize($cycle['admin_id'] == $id);

$db->query('UPDATE harvest_cycle SET cycle_number = :cycle_num, start_of_cycle = :start_date, end_of_cycle = :end_date WHERE id = :id',[
    'id' => $_POST['id'],
    'cycle_num' => $_POST['cycle_num'],
    'start_date' => $_POST['start_date'],
    'end_date' => $_POST['end_date'],
]);

header('location: /harvestCycle');
die();