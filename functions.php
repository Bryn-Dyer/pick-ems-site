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

function getUserInfo($link, $input, $selector) {
    if($selector == 0) {
        $stmt = mysqli_prepare($link, "SELECT user_id FROM users WHERE Name = ?");
        mysqli_stmt_bind_param($stmt,"s",$input);
    }
    if($selector == 1) {
        $stmt = mysqli_prepare($link, "SELECT Name FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt,"i",$input);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userID);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $userID;
}

function getUserPredictionInfo($link, $userID, $selector) {
    if($selector == 0) {
        $stmt = mysqli_prepare($link, "SELECT * FROM predictions WHERE user_id = ?");
    } elseif($selector == 1) {
        $stmt = mysqli_prepare($link, "SELECT * FROM predictions INNER JOIN results ON predictions.prediction = results.result AND predictions.game_id = results.game_id WHERE user_id = ?");
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $numRows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $numRows;
}

function databaseInitialise($link) {
    $stmt = mysqli_prepare($link,"CREATE DATABASE IF NOT EXISTS pick_ems");
    initialiseExecute($stmt,$link);
        $stmt =  mysqli_prepare($link,"CREATE TABLE IF NOT EXISTS pick_ems.teams (
            team_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            name VARCHAR(255) NOT NULL,
            conference VARCHAR(255) NOT NULL,
            division VARCHAR(255) NOT NULL
            )");
        initialiseExecute($stmt, $link);
        $stmt = mysqli_prepare($link, "CREATE TABLE IF NOT EXISTS pick_ems.users (
            user_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            Name VARCHAR(255) NOT NULL,
            hash VARCHAR(255) NOT NULL,
            Access_Level INT(11) NOT NULL
            )");
        initialiseExecute($stmt, $link);
    $stmt = mysqli_prepare($link, "CREATE TABLE IF NOT EXISTS pick_ems.games(
        game_id INT(11) AUTO_INCREMENT NOT NULL,
        Away INT(11) NOT NULL ,
        Home INT(11) NOT NULL ,
        date DATE NOT NULL ,
        season_week enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','Wild Card','Divisional','Conference','Super Bowl') NOT NULL ,
        year INT(11) NOT NULL ,
        PRIMARY KEY (game_id),
        FOREIGN KEY (Away) REFERENCES teams(team_id) on DELETE restrict ON UPDATE cascade,
        FOREIGN KEY (Home) REFERENCES teams(team_id) ON DELETE restrict ON UPDATE cascade
        )");
    initialiseExecute($stmt, $link);
    $stmt = mysqli_prepare($link, "CREATE TABLE IF NOT EXISTS pick_ems.predictions(
        game_id INT(11) NOT NULL ,
        user_id INT(11) NOT NULL ,
        prediction enum('Away','Home','Draw') NOT NULL,
        PRIMARY KEY (game_id, user_id),
        FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE cascade,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE cascade
        )");
    initialiseExecute($stmt, $link);
    $stmt = mysqli_prepare($link, "CREATE TABLE IF NOT EXISTS pick_ems.results(
        game_id INT(11) NOT NULL ,
        result enum('Away','Home','Draw') NOT NULL ,
        PRIMARY KEY (game_id),
        FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE cascade
        )");
    initialiseExecute($stmt, $link);
}

function initialiseExecute($stmt, $link) {
    try {
    mysqli_stmt_execute($stmt);
    } catch(Exception $error) {
        echo $error . "</br>" . mysqli_errno($link);
    }
}