<?php

namespace Core;

class Authenticator
{
    public function attempt($email, $password)
    {
        $user = App::resolve(Database::class)->query('select * from users where email = :email', [
            'email' => $email
        ])->find();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->signin([
                    'email' => $email,
                    'id' => $user['id']
                ]);

                return true;
            }
        }

        return false;
    }

    public function signin($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email'],
            'id' => $user['id']
        ];

        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::destroy();

        header('Location: /login');
        exit();
    }
}
