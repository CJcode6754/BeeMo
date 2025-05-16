<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$currentAdminID =  $_SESSION['user']['id'];

$cycle = $db->query('SELECT * FROM hivenumber WHERE hiveID = :id', [
    'id' => $_POST['id']
])->findOrFail();

authorize($cycle['admin_id'] === $currentAdminID);

$db->query('DELETE FROM hivenumber WHERE hiveNum = :hiveNum', [
    'hiveNum' => $_POST['hiveNum']
]);

header('location: /chooseHive');
exit();
