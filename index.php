<?php
// require_once "autoloader/autoloader.php";
session_start();
require_once 'connect.php';

if (isset($_SESSION['id'])) {
    header("location:main.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body class="registerBody">

    <?php
    if (isset($_POST['submit'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        $sql = "SELECT * FROM users WHERE user_email = :email";
        $statement = $con->prepare($sql);
        $statement->bindParam(":email", $email, PDO::PARAM_STR);
        $statement->execute();

        while ($user = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['user_pass'])) {
                $_SESSION['id'] = $user['user_id'];
                $_SESSION['name'] = $user['user_name'];
                $_SESSION['email'] = $user['user_email'];
                $_SESSION['address'] = $user['user_address'];
                $_SESSION['number'] = $user['user_number'];
                $_SESSION['img'] = $user['user_img'];
                header("location:main.php");
                exit(0);
            } else {
                header("location:index.php?errorPass=incorrect_password");

                exit(0);
            }
        }
        $userCount = $statement->rowCount();
        if ($userCount == 0) {
            header("location:index.php?errorEmail=incorrect_email");
            exit(0);
        }
    }
    ?>

    <div class="form-container">
        <?php
        if (isset($_GET['success']) && $_GET['success'] == "register_succcess") {
            echo '<span style="color:green;">Register Success</span>';
        }
        ?>
        <?php
        if (isset($_GET['errorPass']) && $_GET['errorPass'] == "incorrect_password") {
            echo '<span style="color:red;">Incorrect Password</span>';
        }
        ?>
        <?php
        if (isset($_GET['errorEmail']) && $_GET['errorEmail'] == "incorrect_email") {
            echo '<span style="color:red;">Incorrect Email</span>';
        }
        ?>
        <h1>Welcome to Grill Chill</h1>
        <form action="index.php" method="POST">

            <h2>Login Now</h2>

            <div>
                <label for="">Email</label>
                <input type="text" name="email" placeholder="Juan@gmail.com" required><br>
            </div>
            <div>
                <label for="">Password</label>
                <input type="password" name="password" placeholder="*******" required><br>
            </div>
            <div>
                <input type="submit" value="Login" name="submit">
            </div>
            <div class="classReg">
                <a href="register.php">No account yet ?</a>
            </div>
        </form>
    </div>
    <div>Hello Friends</div>
</body>

</html>