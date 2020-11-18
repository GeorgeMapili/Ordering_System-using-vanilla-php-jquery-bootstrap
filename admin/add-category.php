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

                <h1 class="text-center display-1 mt-5">ADD CATEGORY</h1>


                <?php

                if (isset($_POST['add'])) {
                    $name = trim(htmlspecialchars($_POST['name']));


                    // Check if name is valid
                    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                        header("location:add-user.php?errorName1=Category_name_is_invalid");
                        exit(0);
                    }

                    // Check if name already existed
                    $sql1 = "SELECT * FROM category WHERE category_name = :name";
                    $statement1 = $con->prepare($sql1);
                    $statement1->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement1->execute();
                    $nameCount = $statement1->rowCount();
                    if ($nameCount >= 1) {
                        header("location:add-category.php?errorName2=category_name_already_existed");
                        exit(0);
                    }

                    $sql = "INSERT INTO category (category_name)VALUES(:name)";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        header("location:add-category.php?success1=added_category_successfully");
                    }
                }

                ?>
                <!-- Start of Table -->
                <div class="container">
                    <form action="add-category.php" method="post">
                        <?php
                        if (isset($_GET['success1']) && isset($_GET['success1']) == "added_category_successfully") {
                        ?>
                            <span class="text-success h3">Added Category Successfully</span>
                        <?php } ?>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <!-- <input type="text" class="form-control" name="name" required> -->
                                    <input type="text" class="form-control" name="name" required><br>
                                    <?= (isset($_GET['errorName2'])) ? '<span style="color: rgb(226, 25, 25);">Food Name Category is already existed</span>' : "";  ?>
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