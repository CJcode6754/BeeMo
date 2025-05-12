<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);
$currentAdminID = 10;

$worker = $db->query(
    'SELECT * FROM user_table where id = :id',
    [
        'id' => $_POST['id']
    ]
)->findOrFail();

authorize($worker['admin_id'] == $currentAdminID);

$errors = [];

if (!Validator::string($_POST['name'], 1, 255)) {
    $errors['name'] = 'Name field is required';
}

if (!Validator::email($_POST['email'])) {
    $errors['email'] = 'Email field is required';
}

if (!Validator::string($_POST['number'], 11, 11)) {
    $errors['number'] = 'Please provide eleven character number & it must start with 09.';
}

if (!Validator::string($_POST['password'], 6)) {
    $errors['password'] = 'Please provide a password of at least six character.';
}

if (count($errors)) {
    return view("/worker/edit.php", [
        'heading' => 'Edit Worker',
        'errors' => $errors,
        'worker' => $worker
    ]);
}

$db->query('UPDATE user_table SET name = :name, email = :email, number = :number, password = :password WHERE id = :id',[
    'id' => $_POST['id'],
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'number' => $_POST['number'],
    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
]);

header('location: /workers');
die();