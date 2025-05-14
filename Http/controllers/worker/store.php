<?php

use Core\App;
use Core\Validator;
use Core\Database;

$db = App::resolve(Database::class);

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
    return view("/worker/create.php", [
        'heading' => 'Create Worker'
    ]);
}

$db->query('INSERT INTO users(name, email, number, password, admin_id) VALUES (:name, :email, :number, :password, :admin_id)', [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'number' => $_POST['number'],
    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
    'admin_id' => 10,
]);

header('Location: /workers');
exit();
