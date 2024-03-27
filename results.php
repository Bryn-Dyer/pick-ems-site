<?php
require 'functions.php';
include_once "header.php";
/*TO DO:
Ability to submit multiple games at a time
*/
$game_id = $outcome = "";
$gameErr = '';
if (isset($_POST["submit_result"])) {
    if(empty($_POST["game_id"])){
        $gameErr = "Game ID Required";
    } else {
        $game_id = $_POST["game_id"];
    }
    $outcome = $_POST["Outcome"];
    if(empty($gameErr)) {
        $stmt = mysqli_prepare($link, "SELECT game_id FROM results WHERE ?");
        mysqli_stmt_bind_param($stmt, "i", $game_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0 ) {
            $stmt = mysqli_prepare($link, "UPDATE results SET result = ? WHERE game_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $outcome, $game_id);
        } else {
            $stmt = mysqli_prepare($link, "INSERT INTO results (game_id,results) VALUES (?,?)");
            mysqli_stmt_bind_param($stmt, "is", $game_id, $outcome);
        }
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Result Successfully Submitted";
        } else {
            echo 'Error: ' . mysqli_stmt_error($stmt);
        } 
    } else {
        echo $gameErr;
    }
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
        <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <div>
                <label>Year</label>
                <input type="number" name="year" min="<?php echo yearRange($conn,0);?>" max="<?php echo yearRange($conn,1);?>" step="1" value="<?php $year = getWeek($link); echo $year[1]; ?>"/>
            </div>
            <div>
                <label for="week">Week</label>
                <select name="season_week", id="week" value ="<?php echo $week?>">
                    <?php $x = 1 ?>
                    <?php while ($x <= 18): ?>
                    <option value=<?php echo $x ?>><?php echo $x ?></option>
                    <?php $x++?>
                    <?php endwhile ?>
                    <option value="Wild Card">Wild Card</option>
                    <option value="Divisional">Divisional</option>
                    <option value="Conference">Conference</option>
                    <option value="Super Bowl">Super Bowl</option>
                </select>
            </div>
            <div>
                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
    <div>
    <?php printResults($link);?>
    </div>
        <form action ="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label>Enter Game ID:</label>
            <input type="number" id="game_id" name="game_id">
        </div>
        <div>
            <label for="Outcome">Who Won?</label>
            <select name="Outcome", id="Outcome">
                <option value="Away">Away</option>
                <option value="Home">Home</option>
                <option value="Draw">Draw</option>
            </select>
        </div>
        <div><br>
            <input type="submit" name="submit_result" value="Submit Result">
        </div>
        </form>
</body>
</html>