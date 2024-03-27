<?php 
/*TO DO:
Create Form that allows the user to create a query
Use php to generate the MySQL query based on the form submission
Minimum: 
Query users to see record
Additional
Query multiple users records at the same time
See users records for a given team(s) / division(s) / ect.

*/
include 'functions.php';
$userErr = $predErr = '';
$user = $predSum = $correctSum = $userID = '';
session_start();
if(isset($_GET["submit"])) {
    if(empty($_GET['User']) && !empty($_SESSION['id'])) {
        // Fill in from session
        $userID = $_SESSION['id'];
        $user = $_SESSION['username'];
    } elseif(!empty($_GET['User'])) {
        // Fill from get
        $user = $_GET['User'];
    } else {
        $userErr = "User is required";
    }
    if(empty($userErr)) {
        if(empty($userID)) {
            $userID = getUserID($link, $user);
        }
        $stmt = mysqli_prepare($link, "SELECT * FROM predictions WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 0 ) {
            $predErr = 'This user has not made any predictions';
            mysqli_stmt_close($stmt);
        } else {
            $predSum = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($link, "SELECT * FROM predictions INNER JOIN results ON predictions.prediction = results.result AND predictions.game_id = results.game_id WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $userID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $correctSum = mysqli_stmt_num_rows($stmt);
        }
        if(empty($predErr)) {
            $accuracy = round(($correctSum / $predSum), 4) * 100;
            echo $user . " has a " . $accuracy . "% accuracy over all predictions.";
        } else {
            echo $predErr;
        }
    } else {
        echo $userErr;
    }
}
?>

<!DOCTYPE html>
<html lang="english">
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <div>
        <label>User</label><br>
        <input type="text" name="User"><br>
        </div>
        <div>

        </div>
        <input type="submit" name="submit" value="Search">
        </form> 
    </body>
</html>