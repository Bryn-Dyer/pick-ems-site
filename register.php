<?php
include 'header.php';
$username = $password = $confirmPassword =  '';
$usernameErr = $passwordErr = $confirmErr = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        $usernameErr = 'Username is required';
    } else {
        $stmt = mysqli_prepare($link, "SELECT Name FROM users WHERE Name = ?");
        mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0) {
            $usernameErr = "User already exists";
        } else {  
            $username = $_POST['username'];
            }
        mysqli_stmt_close($stmt);
    }
    if (empty($_POST['password'])) {
        $passwordErr = 'Password is required';
    } else {
        $password = $_POST["password"];
    }
    if (empty($_POST['confirm_password'])) {
        $confirmErr = 'Password is required';
    } else {
        $confirmPassword = $_POST['confirm_password'];
    }
    if(empty($passwordErr) && ($password != $confirmPassword)) {
        $confirmErr = "Passwords do not match";
    } 
    if (empty($usernameErr) && empty($passwordErr) && empty($confirmErr)) {
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($link, "INSERT INTO users (Name, hash) VALUES (?,?)");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt)) {
            echo "User Registered";
        } else {
            echo 'Error: ' . mysqli_stmt_error($stmt);
        }
    } else {
        echo $usernameErr . '</br>' . $passwordErr;
    }
}
?>

<!DOCTYPE html>
<html lang="english">
<body>
    <form action ="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div>
        <label>Username</label><br>
        <input type="text" name="username"><br>
    </div>
    <div>
        <label>Password</label><br>
        <input type="Password" name="password"><br>
    </div>
    <div>
        <label>Confirm Password</label><br>
        <input type="Password" name="confirm_password"><br>
    </div>
    <div><br>
        <input type="submit" name="submit" value="Register">
    </div>
    </form>
</body>
</html>