<?php 
include "functions.php";
$user_ID = 4;
$stmt = mysqli_prepare($link, "SELECT * FROM predictions WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_ID);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
echo mysqli_stmt_num_rows($stmt);
?>