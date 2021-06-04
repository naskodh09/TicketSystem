<?php
    require 'php/autoloader.php';
    $_SESSION = [];
    session_destroy();
    header('location: index.php');
?>
