<?php
session_start();
require_once '../connect.php';

if (isset($_SESSION['adminname']) && isset($_SESSION['adminemail'])) {
    header("location:dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="registerBody">


    <?php

    if (isset($_POST['admin'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        if ($email === "admin@admin.com" && $password === "admin") {
            $_SESSION['adminname'] = "ADMIN";
            $_SESSION['adminemail'] = "admin@admin.com";
            header("location:dashboard.php");
        } else {
            header("location:index.php?error=incorrect_credentials");
        }
    }

    ?>

    <div class="form-container">
        <?php
        if (isset($_GET['error']) && $_GET['error'] == "incorrect_credentials") {
            echo '<span style="color:red;">Login Failed</span>';
        }
        ?>

        <h1>ADMIN</h1>
        <form action="index.php" method="POST">
            <h2>Login</h2>
            <div>
                <label for="">Email</label>
                <input type="text" name="email" placeholder="Email"><br>
            </div>
            <div>
                <label for="">Password</label>
                <input type="password" name="password" placeholder="Password"><br>
            </div>
            <div>
                <input type="submit" value="Login" name="admin">
            </div>
        </form>
    </div>

</body>

</html>