<?php include 'header.php';
function getWeek($link) {
    $stmt = mysqli_prepare($link, "SELECT season_week,year FROM GAMES ORDER BY ABS(DATEDIFF(date, NOW())) DESC LIMIT 1");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $week, $year);
    mysqli_stmt_fetch($stmt);
    return [$week, $year];
}

function getResults($link, $week = null, $year = null) {
    if (($week === null) && ($year === null)){
        [$week,$year] = getWeek($link);
    }
    $stmt = mysqli_prepare($link, "SELECT * FROM games WHERE season_week = ? AND year = ?");
    mysqli_stmt_bind_param($stmt, "si", $week, $year);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $gameID, $away, $home, $date, $seasonWeek, $seasonYear);
    $index = 0;
    $games = [];
    while (mysqli_stmt_fetch($stmt)){
        $game = ["game_id" => $gameID, "Away" => $away, "Home" => $home, "Date" => $date, "Week" => $seasonWeek, "Year" => $seasonYear];
        $games[$index] = $game;
        $index ++;
    }
    return $games;
}

function yearRange($link, $select) {
    if ($select == 0) {
        $stmt = mysqli_prepare($link, "SELECT year FROM games ORDER BY year ASC LIMIT 1");
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $year);
    } elseif ($select == 1) {
        $stmt = mysqli_prepare($link, "SELECT year FROM games ORDER BY year DESC LIMIT 1");
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $year);
    }
    mysqli_stmt_fetch($stmt);
    return $year;
}

function getGame($link, $week = null, $year = null) {
    if (($week === null) && ($year === null)){
        [$week,$year] = getWeek($link);
    }
    $query = "SELECT game_id, Away, Home FROM games WHERE season_week = '$week' AND year = '$year'";
    $result = mysqli_execute_query($link, $query);
    if (isset($result)) {
        $games = [];
        $index = 0;
        foreach ($result as $row) {
            $game = ["game_id" => $row["game_id"], "Away" => $row["Away"],"Home" => $row["Home"]];
            $games[$index] = $game;
            $index ++;
        }
        return $games;
    } else {
        return null;
    }
}

function printResults($link) {
    if(isset($_GET['submit'])) {
        $week = $_GET["season_week"];
        $year = $_GET["year"];
        setcookie("Week", "$week");
        setcookie("Year", "$year");
        $results = getResults($link,$week,$year);
        if(empty($results)) {
            echo "No Games Found";
        } else {
            foreach ($results as $row) {
                printf("%s @ %s - Date: %s - Game ID: %s <br />", $row["Away"], $row["Home"], $row["Date"], $row["game_id"]);
            }
        }
    } elseif(!isset($_GET['submit']) && (isset($_COOKIE["Week"]) & isset($_COOKIE["Year"]))) {
        $results = getResults($link,$_COOKIE["Week"],$_COOKIE["Year"]); 
            foreach ($results as $row){
                printf("%s @ %s - Date: %s - Game ID: %s <br />", $row["Away"], $row["Home"], $row["Date"], $row["game_id"]);
            }
    } elseif(!isset($_GET['submit'])) {
        $results = getResults($link);
        foreach ($results as $row) {
            printf("%s @ %s - Date: %s - Game ID: %s <br />", $row["Away"], $row["Home"], $row["Date"], $row["game_id"]);
        }
    }
}

function getUserID($link, $user) {
    $stmt = mysqli_prepare($link, "SELECT user_id FROM users WHERE Name = ?");
    mysqli_stmt_bind_param($stmt,"s",$user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userID);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $userID;
}