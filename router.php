<?php 
$routes = require('routes.php');

//compare request uri to defined routes
function routeToController($uri, $routes){
    if(array_key_exists($uri, $routes)){
        require $routes[$uri];
    }else{
        abort();
    }
}

//abort when the user go to unidentified page
function abort($code = 404){
    http_response_code($code);

    require "views/partials/{$code}.php";

    die();
}

//avoid the error when user add commad in url
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

routeToController($uri, $routes);