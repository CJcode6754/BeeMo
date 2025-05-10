<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'function.php';
require base_path('Response.php');
require base_path('Database.php');
require base_path('router.php');