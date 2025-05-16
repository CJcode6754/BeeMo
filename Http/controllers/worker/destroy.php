<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$currentAdminID = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_delete'])) {

    $worker = $db->query('SELECT * FROM user_table WHERE id = :id', [
        'id' => $_POST['id']
    ])->findOrFail();

    authorize($worker['admin_id'] === $currentAdminID);

    $db->query('DELETE FROM user_table WHERE id = :id', [
        'id' => $_POST['id']
    ]);

    header('location: /workers');
    exit();
}
