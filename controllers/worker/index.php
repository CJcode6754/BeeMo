<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$workers = $db->query('SELECT * FROM user_table where admin_id = 10')->get();

view("worker/index.php", [
    'heading' => 'Worker',
    'workers' => $workers
]);