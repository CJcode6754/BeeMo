<?php
if (isset($_POST['hive_id'])) {
    $_SESSION['hiveID'] = $_POST['hive_id'];
    header('Location: /parameterMonitoring');
    exit();
}
