<?php 
include 'header.php';
$usernameErr = $passwordErr = "";
if(isset($_POST["login"])) {
    if(empty($_POST['Username'])) {
        $usernameErr = "Username is Required";
    } else {
        $paramUsername = $_POST['Username'];
    } 
    if(empty($_POST['Password'])) {
        $passwordErr = "Password is Required";
    } 
    if(empty($usernameErr) && empty($passwordErr)){
        $stmt = mysqli_prepare($link, "SELECT user_id,Name,hash FROM users WHERE Name = ?");
        mysqli_stmt_bind_param($stmt, "s", $paramUsername);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashedPassword);
                if(mysqli_stmt_fetch($stmt)) {
                    if(password_verify($_POST['Password'], $hashedPassword)) {
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        echo "Successful Login";
                        header("Location: index.php");
                    } else {
                        echo "Invalid username or password";
                    }

                }
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Soemthing Went Wrong.";
        }
        
    }
    echo $usernameErr . '</br>' . $passwordErr;
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <h2>Login</h2>
    <div>
        <label for="Username">Username</label>
        <input type ="text" name="Username" id="username">
    </div>
    <div>
        <label for="Password">Password</label>
        <input type="password" name="Password" id="password">
    </div>
    <div><br>
        <input type="submit" name="login" value="Login">
    </div>
    </form>
    <a href="register.php">Register</a>
</body>