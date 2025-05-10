<?php

use Core\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_delete'])) {
    $config = require base_path('config.php');
    $db = new Database($config['database']);
    $currentAdminID = 10;

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
