<?php
use Core\Validator;
use Core\Database;

require base_path('Core/Validator.php');

$config = require base_path('config.php');
$db = new Database($config['database']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = [];

    if(!Validator::string($_POST['name'], 1, 255) === 0){
        $errors['name'] = 'Name field is required';
    }

    if(!Validator::email($_POST['email'])){
        $errors['email'] = 'Email field is required';
    }

    if(!Validator::string($_POST['number'], 11, 11)){
        $errors['number'] = '11 number required for phone';
    }

    if(!Validator::string($_POST['password'], 6)){
        $errors['password'] = 'Minimum password length is 6';
    }

    if(empty($errors)){
        $db->query('INSERT INTO user_table(name, email, number, password, admin_id) VALUES (:name, :email, :number, :password, :admin_id)', [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'number' => $_POST['number'],
        'password' => $_POST['password'],
        'admin_id' => 10,
        ]);

        header('Location: /admin/worker/create');
        exit();
    }   
}

view("/worker/create.php", [
    'heading' => 'Create Worker'
]);