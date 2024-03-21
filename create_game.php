<?php
/*TO DO:

*/

include 'header.php';
$homeTeam = $awayTeam = $date = $week = $year = '';
$homeErr = $awayErr = $dateErr = $weekErr = $yearErr = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['homeTeam'])) {
        $homeErr = 'Home Team is required';
    } else {
        $homeTeam = $_POST['homeTeam'];
    }
    if (empty($_POST['awayTeam'])) {
        $awayErr = 'Away Team is required';
    } else {
        $awayTeam = $_POST['awayTeam'];
    }
    if (empty($_POST['date'])) {
        $dateErr = 'Date is required';
    } else {
        $date = $_POST['date'];
    }
    if (empty($_POST['season_week'])) {
        $weekErr = 'Week is required';
    } else {
        $week = $_POST['season_week'];
    }
    if (empty($_POST['season_year'])) {
        $yearErr = 'Year is required';
    } else {
        $year = $_POST['season_year'];
    }
    if (empty($homeErr) && empty($awayErr) && empty($dateErr) && empty($weekErr) && empty($yearErr)) {
        $stmt = mysqli_prepare($link, "INSERT INTO games (Away, Home, date, season_week, year) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssi", $awayTeam, $homeTeam, $date, $week, $year);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Game Successfully Created";
        } else {
            echo 'Error: ' . mysqli_stmt_error($stmt);
        } 
    } else {
        echo $awayErr . '</br>'. $homeErr .'</br>' . $dateErr . '</br>' . $weekErr . '</br>' . $yearErr;
    } 
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
        <form action ="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label>Away Team</label><br>
            <input type="text" name="awayTeam"><br>
        </div>
        <div>
            <label>Home Team</label><br>
            <input type="text" name="homeTeam"><br>
        </div>
        <div>
            <label>Date</label><br>
            <input type="date" name="date"><br>
        </div>
        <div>
            <label>Season Week</label><br>
            <input type="Week" name="season_week" ><br>
        </div>
        <div>
            <label>Year</label><br>
            <input type="year" name="season_year"><br>
        </div>
        <div><br>
            <input type="submit" name="submit" value="Submit">
        </div>
        </form>
</body> 
</html>