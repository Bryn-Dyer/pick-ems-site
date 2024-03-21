<?php 
include "functions.php";
session_start();
echo "Hello " . $_SESSION["username"];
?>