<?php
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$admin_id = $_SESSION['user']['id'];

$hives = $db->query('SELECT * FROM hivenumber where admin_id = :admin_id', [
    'admin_id' => $admin_id
])->get();

view("hive/index.php", [
    'heading' => 'Choose Hive',
    'hives' => $hives
]);