<?php 
//compare request uri to defined routes
function routeToController($uri, $routes){
    if(array_key_exists($uri, $routes)){
        require base_path($routes[$uri]);
    }else{
        abort();
    }
}

//abort when the user go to unidentified page
function abort($code = 404){
    http_response_code($code);

    require base_path("views/partials/{$code}.php");

    die();
}

$routes = require base_path('routes.php');
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

routeToController($uri, $routes);