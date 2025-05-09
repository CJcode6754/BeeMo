<?php
$config = require('config.php');
$db = new Database($config['database']);

$heading = "Worker";

$workers = $db->query('SELECT * FROM user_table where adminID = 10')->get();

require"views/admin/worker/index.php";