<?php
namespace Core\Middleware;

class Hive{
    public function handle(){
        if(!isset($_SESSION['hiveID'])){
            header('location: /');
            exit();
        }
    }
}
