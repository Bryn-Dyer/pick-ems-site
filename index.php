<?php 
include "functions.php";
include_once "header.php";
databaseInitialise($link);
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("Location: localhost/pick-ems/login.php");
} else {
    $stmt = mysqli_prepare($link, "SELECT Access_Level FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $accessLevel);
    mysqli_stmt_fetch($stmt);
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
    <div>
        <?php if($accessLevel >= 1):?>
        <a href=create_team.php>Create Team</a></br>
        <a href=create_game.php>Create Game</a></br>
        <?php endif?>
        <a href=schedule/php>Schedule</a></br>
        <a href=predict.php>Predict</a></br>
        <a href=results.php>Results</a></br>
        <a href=records.php>Records</a></br>
    </div>
</body>
</html>