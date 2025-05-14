<?php

use Core\Session;

login("login.php", [
    'errors' => Session::get('errors')
]);