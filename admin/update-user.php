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
            <div class="container">
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

                    <h1 class="text-center display-1 mt-5">UPDATE USERS</h1>
                    <h6 class="text-center mt-3 mb-3">
                        <?php
                        if (isset($_GET['success1']) && isset($_GET['success1']) == "update_profile") {
                        ?>
                            <span class="text-success h3">Updated Successfully</span>
                        <?php
                        } else if (isset($_GET['error1'])) {
                        ?>
                            <span class="text-info h3">Nothing to Update</span>
                        <?php
                        }
                        ?>
                    </h6>

                    <?php
                    $id = "";
                    $name = "";
                    $email = "";
                    $address = "";
                    $mobilenumber = "";
                    if (isset($_POST['update'])) {
                        $id = trim(htmlspecialchars($_POST['id']));
                        $name = trim(htmlspecialchars($_POST['name']));
                        $email = trim(htmlspecialchars($_POST['email']));
                        $address = trim(htmlspecialchars($_POST['address']));
                        $mobilenumber = trim(htmlspecialchars($_POST['mobilenumber']));
                    }
                    ?>
                    <form action="process.php" method="post">
                        <h2 class="text-center mt-3 mb-3">Profile</h2>

                        <input type="hidden" name="id" value="<?= $id; ?>">

                        <div class="form-group">
                            <label>Name</label>
                            <?= (isset($_GET['name'])) ? '<input type="text" class="form-control" name="name" value="' . $_GET['name'] . '"' : '<input type="text" class="form-control" name="name" value="' . $name . '">'; ?>
                            <?= (isset($_GET['errorName1'])) ? '<span style="color: rgb(226, 25, 25);">Name is invalid</span>' : "";  ?>
                            <?= (isset($_GET['errorName2'])) ? '<span style="color: rgb(226, 25, 25);">Name already taken</span>' : "";  ?>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword4">Email</label>
                            <?= (isset($_GET['email'])) ? '<input type="email" class="form-control" name="email" value="' . $_GET['email'] . '"' : '<input type="email" class="form-control" name="email" value="' . $email . '">'; ?>
                            <?= (isset($_GET['errorEmail1'])) ? '<span style="color: rgb(226, 25, 25);">Email is already existed</span>' : "";  ?>
                            <?= (isset($_GET['errorEmail2'])) ? '<span style="color: rgb(226, 25, 25);">Email Format is invalid</span>' : "";  ?>
                        </div>


                        <div class="form-group">
                            <label>Address</label>
                            <?= (isset($_GET['address'])) ? '<input type="text" class="form-control" name="address" value="' . $_GET['address'] . '"' : '<input type="text" class="form-control" name="address" value="' . $address . '">'; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword4">Mobile Number</label>
                            <?= (isset($_GET['mobileNumber'])) ? '<input type="tel" class="form-control" name="mobileNumber" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" value="' . $_GET['mobileNumber'] . '"' : '<input type="tel" class="form-control" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" name="mobileNumber" value="' . $mobilenumber . '">'; ?>
                            <?= (isset($_GET['errorMobile'])) ? '<span style="color: rgb(226, 25, 25);">Mobile is already existed</span>' : "";  ?>
                        </div>


                        <input type="submit" value="Update" name="updateInfo" class="mt-3 btn btn-info">

                    </form>

                </main>
            </div>

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