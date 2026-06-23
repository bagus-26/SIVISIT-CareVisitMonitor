<?php
require_once '../config.php';

callAPI('POST', '/logout');

session_unset();
session_destroy();

header("Location: login.php");
exit;
?>
