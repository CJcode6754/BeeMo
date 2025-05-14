<?php

use Core\Session;

const BASE_PATH = __DIR__ . '/../';

session_start();

require BASE_PATH . 'Core/function.php';
spl_autoload_register(function ($class){
    // Core\DAtabase
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("{$class}.php");
});
require base_path('bootstrap.php');
$router =  new \Core\Router();

$routes = require base_path('routes.php');
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);

Session::unflash();