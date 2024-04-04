<?php
include "header.php";
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("Location: localhost/pick-ems/index.php");
}
$name = $conf = $div = '';
$nameErr = $confErr = $divErr = '';
if(isset($_POST["sumbit"])) {
    if (empty($_POST['name'])) {
        $nameErr = 'Name is required';
    } else {
        $name = $_POST['name'];
    }
    if (empty($_POST['conference'])) {
        $confErr = 'Conference is required';
    } else {
        $conf = $_POST['conference'];
    }
    if (empty($_POST['division'])) {
        $divErr = 'Division is required';
    } else {
        $div = $_POST['division'];
    }
    if(empty($nameErr) && empty($confErr) && empty($divErr)) {
        $stmt = mysqli_prepare($link, "SELECT * FROM teams WHERE name = ?, conference = ?, division = ?");
        mysqli_stmt_bind_param($stmt, "sss", $name, $conf, $div);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0) {
            echo "Team already exists";
        } elseif(mysqli_stmt_num_rows($stmt) == 0) {
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($link, "INSERT INTO teams(name, conference, division) VALUES (?,?,?)");
            mysqli_stmt_bind_param($stmt , "sss", $name, $conf, $div);
            mysqli_stmt_execute($stmt);
            if(mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Team successfully created";
            } else {
                echo "Error:" . mysqli_stmt_error($stmt);
            }
        }  else {
            echo "Error:" . mysqli_stmt_error($stmt);
        }
    } else {
        echo $nameErr . "</br>" . $confErr . "</br>" . $divErr;
    }
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
        <form action ="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label>Team Name</label><br>
            <input type="text" name="name"><br>
        </div>
        <div>
            <label>Conference</label><br>
            <input type="text" name="conference"><br>
        </div>
        <div>
            <label>Division</label><br>
            <input type="text" name="division"><br>
        </div>
        <div><br>
            <input type="submit" name="submit" value="Submit">
        </div>
        </form>
</body> 
</html>