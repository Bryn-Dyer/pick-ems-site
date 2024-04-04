<?php  
    define("DB_HOST","localhost");
    define("DB_USER","bryn");
    define("DB_PASS","!LY5B]@sd8]RQ1AE");
    define("DB_NAME","pick_ems"); 


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed". $conn->connect_error);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
databaseInitialise($link);
?>