<?php 
/*TO DO:
Additional
User can submit multiple games at a time

*/
require 'functions.php';
include_once "header.php";
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
}
$game_id = $user_id = $ouctome = "";
$gameErr = $userErr = "";

if (isset($_POST["predict"])) {
    if(empty($_POST["game_id"])){
        $gameErr = "Game ID Required";
    } else {
        $game_id = $_POST["game_id"];
    }
    if(empty($_SESSION['id'])){
        $userErr = "Please Log In";
    } else {
        $user_id = $_SESSION['id'];
    }
    $outcome = $_POST["Prediction"];
    if(empty($gameErr) && empty($userErr)) {
        $stmt = mysqli_prepare($link, "SELECT * FROM predictions WHERE user_id = ? AND game_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $game_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 0) {
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($link, "INSERT INTO predictions (game_id, user_id, prediction) VALUES (?,?,?)");
            mysqli_stmt_bind_param($stmt, "iis" , $game_id, $user_id, $outcome);
        } elseif(mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($link, "UPDATE predictions SET game_id = ?, user_id = ?, prediction = ? WHERE game_id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt, "iisii", $game_id, $user_id, $outcome, $game_id, $user_id);
        } else {
            echo "An error has occurred, try again later";
        }
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Prediction Submitted";
            mysqli_stmt_close($stmt);
        } else {
            echo "Error, prediction not submitted." . mysqli_stmt_errno($stmt);
            mysqli_stmt_close($stmt);
        }
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
            <label for="Prediction">Who Will Win?</label>
            <select name="Prediction", id="Prediction">
                <option value="Away">Away</option>
                <option value="Home">Home</option>
                <option value="Draw">Draw</option>
            </select>
        </div>
        <div><br>
            <input type="submit" name="predict" value="Predict">
        </div>
        </form>
</body>
</html>