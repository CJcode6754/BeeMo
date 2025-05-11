<?php

use Core\App;
use Core\Validator;
use Core\Database;

$db = App::resolve(Database::class);

$errors = [];

if (!Validator::string($_POST['name'], 1, 255) === 0) {
    $errors['name'] = 'Name field is required';
}

if (!Validator::email($_POST['email'])) {
    $errors['email'] = 'Email field is required';
}

if (!Validator::string($_POST['number'], 11, 11)) {
    $errors['number'] = '11 number required for phone';
}

if (!Validator::string($_POST['password'], 6)) {
    $errors['password'] = 'Minimum password length is 6';
}

if (empty($errors)) {
    return view("/worker/create.php", [
        'heading' => 'Create Worker'
    ]);
}

$db->query('INSERT INTO user_table(name, email, number, password, admin_id) VALUES (:name, :email, :number, :password, :admin_id)', [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'number' => $_POST['number'],
    'password' => $_POST['password'],
    'admin_id' => 10,
]);

header('Location: /workers');
exit();
