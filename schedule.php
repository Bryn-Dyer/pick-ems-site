<?php
require 'functions.php';
include_once 'header.php';
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("Location: localhost/pick-ems/index.php");
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
        <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <div>
            <label>Year</label>
            <input type="number" name="year" min="<?php echo yearRange($link,0);?>" max="<?php echo yearRange($link,1);?>" step="1" value="<?php $year = getWeek($link); echo $year[1]; ?>"/>
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
    <?php 
    if(isset($_GET['submit'])) {
        $results = getResults($link,$_GET['season_week'],$_GET['year']);
        if(empty($results)) {
            echo "No Games Found";
        } else {
            foreach ($results as $row) {
                printf("%s @ %s, %s, %s, %s <br />", $row["Away"], $row["Home"], $row["Date"], $row["Week"], $row["Year"]);
            }
        }
    } elseif(!isset($_GET['submit'])) {
        $results = getResults($link); 
            foreach ($results as $row){
                printf("%s @ %s, %s, %s, %s <br />", $row["Away"], $row["Home"], $row["Date"], $row["Week"], $row["Year"]);
            }
        }
    ?>
</body>
</html>