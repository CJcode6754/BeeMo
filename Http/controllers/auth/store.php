<?php

use Core\App;
use Core\Database;
use Http\Forms\LoginForm;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

if (! $form->validate($email, $password)) {
    return login('create.php', [
        'errors' => $form->errors()
    ]);
}

$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    if (password_verify($password, $user['password'])) {
        signin([
            'email' => $email,
            'id' => $user['id']
        ]);

        header('location: /');
        exit();
    }
}

return login('login.php', [
    'errors' => [
        'email' => 'No matching account for that email address and password.'
    ]
]);
