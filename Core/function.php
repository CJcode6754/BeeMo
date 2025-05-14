<?php

use Core\Response;
use Core\Session;

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "<pre>";

    die();
}

function URLS($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404)
{
    http_response_code($code);

    require base_path("views/partials/{$code}.php");

    die();
}

function authorize($condition, $status = Response::FORBIDDEN)
{
    if (!$condition) {
        abort($status);
    }
}

function base_path($path)
{
    return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
    extract($attributes);
    require base_path('views/admin/' . $path);
}

function login($path, $attributes = [])
{
    extract($attributes);
    require base_path('views/auth/' . $path);
}

function redirect($path){
    header("location: {$path}");
    exit();
}

function old($key, $default = ''){
    return Session::get('old')[$key] ?? $default;
}