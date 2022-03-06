<?php
session_start();

$_SESSION = array();
$_SESSION['id'] = '';

session_destroy();
header("location: login.php");

exit;
?>