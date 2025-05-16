<?php

use Core\App;
use Core\Validator;
use Core\Database;

$db = App::resolve(Database::class);

$currentAdminID = $_SESSION['user']['id'];
$errors = [];

if (!Validator::number($_POST['hiveNum'])) {
    $errors['number'] = 'Hive number must be a number.';
}

if (count($errors)) {
    return view("/hive/index.php", [
        'heading' => 'Choose Hive'
    ]);
}

$db->query('INSERT INTO hivenumber(hiveNum, admin_id) VALUES (:hiveNum, :admin_id)', [
    'hiveNum' => $_POST['hiveNum'],
    'admin_id' => $_POST['adminID'],
]);

header('Location: /chooseHive');
exit();
