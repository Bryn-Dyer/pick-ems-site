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

if(isset($_GET["submit"])) {

}
?>

<!DOCTYPE html>
<html lang="english">
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <div>

        </div>
        <div>

        </div>
        <input type="submit" name="submit" value="Submit">
        </form> 
    </body>
</html>