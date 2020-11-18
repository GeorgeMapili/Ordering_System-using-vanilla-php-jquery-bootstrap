<?php
session_start();
require_once "../connect.php";

if (!$_SESSION['adminname'] && !$_SESSION['adminemail']) {
    header("location:index.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Chill Grill | Main</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <!-- Start of Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">Chill Grill</a>
        <ul class="navbar-nav ml-auto">
            <img src="../upload/user_profile_img/q.jpg" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= $_SESSION['adminname']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item disabled" href=""><?= $_SESSION['adminemail']; ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="adminlogout.php">Logout</a>
                </div>
            </li>
        </ul>
        </div>
    </nav>
    <!-- Start of Dashboard -->

    <!-- Start of Dashboard -->

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-light d-none d-md-block sidebar">
                <div class="left-sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link active">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="view-user.php" class="nav-link">View All User</a>
                        </li>
                        <li class="nav-item">
                            <a href="view-food.php" class="nav-link">View All Food</a>
                        </li>
                        <li class="nav-item">
                            <a href="view-category.php" class="nav-link">View All Category</a>
                        </li>
                        <li class="nav-item">
                            <a href="pending-order.php" class="nav-link">View Pending Order</a>
                        </li>
                        <li class="nav-item">
                            <a href="cancelled-order.php" class="nav-link">View Cancelled Order</a>
                        </li>
                        <li class="nav-item">
                            <a href="accepted-order.php" class="nav-link">View Accepted Order</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

                <h1 class="text-center display-1 mt-5">ADD USERS</h1>
                <p class="text-center mt-5"><?= (isset($_GET['success1'])) ? "<span class='success'>Added User Successfully</span>" : ""; ?></p>


                <?php

                if (isset($_POST['add'])) {
                    $name = trim(htmlspecialchars($_POST['name']));
                    $email = trim(htmlspecialchars($_POST['email']));
                    $address = trim(htmlspecialchars($_POST['address']));
                    $mobileNumber = trim(htmlspecialchars($_POST['mobilenumber']));
                    $profileImg = $_FILES['profileImg'];
                    $password = trim(htmlspecialchars($_POST['password']));
                    $confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));

                    // image name
                    $imgName = $profileImg['name'];

                    // Check if name is valid
                    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                        header("location:add-user.php?errorName1=Name_is_invalid&email=$email&address=$address&mobileNumber=$mobileNumber");
                        exit(0);
                    }

                    // Check if name already existed
                    $sql1 = "SELECT * FROM users WHERE user_name = :name";
                    $statement1 = $con->prepare($sql1);
                    $statement1->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement1->execute();
                    $nameCount = $statement1->rowCount();
                    if ($nameCount >= 1) {
                        header("location:add-user.php?errorName2=name_already_existed&email=$email&address=$address&mobileNumber=$mobileNumber");
                        exit(0);
                    }

                    // Check if Email is already existed
                    $sql2 = "SELECT * FROM users WHERE user_email = :email";
                    $statement2 = $con->prepare($sql2);
                    $statement2->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement2->execute();
                    $emailCount = $statement2->rowCount();
                    if ($emailCount >= 1) {
                        header("location:add-user.php?errorEmail1=email_already_existed&name=$name&address=$address&mobileNumber=$mobileNumber");
                        exit(0);
                    }

                    // Check if Email is valid
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        header("location:add-user.php?errorEmail2=email_is_invalid&name=$name&address=$address&mobileNumber=$mobileNumber");
                        exit(0);
                    }

                    // Check if Mobile Number is already existed
                    $sql3 = "SELECT * FROM users WHERE user_number = :number";
                    $statement3 = $con->prepare($sql3);
                    $statement3->bindParam(":number", $mobileNumber, PDO::PARAM_STR);
                    $statement3->execute();
                    $mobileCount = $statement3->rowCount();
                    if ($mobileCount >= 1) {
                        header("location:add-user.php?errorMobile=mobile_number_already_existed&name=$name&email=$email&address=$address");
                        exit(0);
                    }

                    // Check if Password Match
                    if ($password !== $confirmPassword) {
                        header("location:add-user.php?errorConfirmPassword=password_did_not_match&name=$name&email=$email&address=$address&mobileNumber=$mobileNumber");
                        exit(0);
                    }

                    // Password Hash
                    $HashPass = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (user_name,user_email,user_pass,user_address,user_number,user_img)VALUES(:name,:email,:password,:address,:mobileNumber,:profileImg)";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement->bindParam(":address", $address, PDO::PARAM_STR);
                    $statement->bindParam(":mobileNumber", $mobileNumber, PDO::PARAM_STR);
                    $statement->bindParam(":password", $HashPass, PDO::PARAM_STR);
                    $statement->bindParam(":profileImg", $imgName, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        header("location:add-user.php?success1=register_succcess");
                    }
                }

                ?>
                <!-- Start of Table -->
                <div class="container">
                    <form action="add-user.php" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Name</label>
                                    <!-- <input type="text" class="form-control" name="name" required> -->
                                    <?= (isset($_GET['name'])) ? '<input type="text" class="form-control" name="name" value="' . $_GET['name'] . '" required><br>' : '<input type="text" class="form-control" name="name" required><br>'; ?>
                                    <?= (isset($_GET['errorName1'])) ? '<span style="color: rgb(226, 25, 25);">Name is invalid</span>' : "";  ?>
                                    <?= (isset($_GET['errorName2'])) ? '<span style="color: rgb(226, 25, 25);">Name is already existed</span>' : "";  ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Email</label>
                                    <!-- <input type="email" class="form-control" name="email" required> -->
                                    <?= (isset($_GET['email'])) ? '<input type="email" name="email" value="' . $_GET['email'] . '" class="form-control" required><br>' : '<input type="email" name="email"  class="form-control" required><br>'; ?>
                                    <?= (isset($_GET['errorEmail1'])) ? '<span style="color: rgb(226, 25, 25);">Email is already existed</span>' : "";  ?>
                                    <?= (isset($_GET['errorEmail2'])) ? '<span style="color: rgb(226, 25, 25);">Email Format is invalid</span>' : "";  ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <!-- <input type="text" class="form-control" name="address" required> -->
                                    <?= (isset($_GET['address'])) ? '<input type="text" class="form-control" name="address" value="' . $_GET['address'] . '" required><br>' : '<input type="text" class="form-control" name="address" required><br>'; ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <!-- <input type="tel" class="form-control" name="mobilenumber" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required> -->
                                    <?= (isset($_GET['mobileNumber'])) ? '<input type="tel" class="form-control" name="mobilenumber" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" value="' . $_GET['mobileNumber'] . '" required><br>' : '<input type="tel" class="form-control" name="mobilenumber" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required><br>'; ?>
                                    <?= (isset($_GET['errorMobile'])) ? '<span style="color: rgb(226, 25, 25);">Mobile is already existed</span>' : "";  ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Image Profile</label>
                                    <input type="file" class="form-control" name="profileImg" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" minlength="8" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="confirmPassword" minlength="8" required>
                                    <?= (isset($_GET['errorConfirmPassword'])) ? '<span style="color: rgb(226, 25, 25);">Password did not match</span>' : "";  ?>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Add" class="btn btn-primary" name="add">
                </div>
                </form>
                <!-- End of Table -->
            </main>
        </div>
    </div>
    <!-- End of Dashboard -->

    <!-- Start of Footer -->
    <!-- <footer class="page-footer font-small mdb-color darken-3 pt-4">


        <div class="footer-copyright text-center py-3">© 2020 | Chill Grill
        </div>


    </footer> -->
    <!-- End of Footer -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>