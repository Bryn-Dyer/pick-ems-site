<?php 
include "functions.php";
$stmt = mysqli_prepare($link, "SELECT * FROM predictions");
// mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
echo mysqli_stmt_num_rows($stmt);
?>