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
        echo $userID;
    } elseif(!empty($_GET['User'])) {
        // Fill from get
        $user = $_GET['User'];
    } else {
        $userErr = "User is required";
    }
    if(empty($userErr)) {
        if(empty($userID)) {
            $userID = getUserID($link, $user);
            echo $userID;
        }
        if(empty($predErr)) {
            echo "Test 5";
            //$accuracy = ($correctSum/$predSum);
            // echo $user . "has a %" . $accuracy . "accuracy"; 
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