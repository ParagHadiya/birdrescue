<?php

require_once 'config/app.php';
$page = $_GET['page'] ?? 'home';
include "pages/$page.php";
?>
