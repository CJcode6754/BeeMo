<?php

function dd(){
    echo "<pre>";
    var_dump($_SERVER);
    echo "<pre>";

    die();
}

function URLS($value){
    return $_SERVER['REQUEST_URI'] === $value;
}