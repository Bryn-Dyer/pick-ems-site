<?php 
include "header.php";
include "functions.php";
databaseInitialise($link);
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("Location: localhost/pick-ems/login.php");
} else {
    $stmt = mysqli_prepare($link, "SELECT Access_Level FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($smtt);
    mysqli_stmt_bind_result($stmt, $accessLevel);
    mysqli_stmt_fetch($stmt);
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
    <div>
        <?php if($accessLevel >= 1):?>
        <a href=localhost/pick-ems/create_team.php>Create Team</a></br>
        <a href=localhost/pick-ems/create_game.php>Create Game</a></br>
        <?php endif?>
        <a href=localhost/pick-ems/schedule/php>Schedule</a></br>
        <a href=localhost/pick-ems/predict.php>Predict</a></br>
        <a href=localhost/pick-ems/results.php>Results</a></br>
        <a href=localhost/pick-ems/records.php>records</a></br>
    </div>
</body>
</html>