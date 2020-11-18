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

                <h1 class="text-center display-1 mt-5">ALL USERS</h1>

                <?php
                if (isset($_POST['delete'])) {
                    $id = $_POST['id'];

                    $sql1 = "SELECT * FROM users WHERE user_id = :id";
                    $statement1 = $con->prepare($sql1);
                    $statement1->bindParam(":id", $id, PDO::PARAM_INT);
                    $statement1->execute();

                    $user = $statement1->fetch(PDO::FETCH_ASSOC);

                    $userProfileImg = $user['user_img'];

                    $path = "../upload/user_profile_img/" . $userProfileImg;

                    unlink($path);

                    $sql = "DELETE FROM users WHERE user_id = :id";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
                    $statement->execute();
                }

                ?>

                <!-- Start of Cart table -->
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">User Profile</th>
                            <th scope="col">User Name</th>
                            <th scope="col">User Email</th>
                            <th scope="col">User Address</th>
                            <th scope="col">User Phone</th>
                            <th scope="col text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users";
                        $statement = $con->prepare($sql);
                        $statement->execute();

                        while ($users = $statement->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?= $users['user_id']; ?></th>
                                <td><img src="<?= "../upload/user_profile_img/" . $users['user_img'];  ?>" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                                <td><?= $users['user_name']; ?></td>
                                <td><?= $users['user_email']; ?></td>
                                <td><?= $users['user_address']; ?></td>
                                <td><?= $users['user_number']; ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="view-user.php" method="post">
                                                <input type="hidden" value="<?= $users['user_id']; ?>" class="btn btn-danger" name="id">
                                                <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')" name="delete">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="update-user.php" method="post">
                                                <input type="hidden" name="id" value="<?= $users['user_id']; ?>">
                                                <input type="hidden" name="name" value="<?= $users['user_name']; ?>">
                                                <input type="hidden" name="email" value="<?= $users['user_email']; ?>">
                                                <input type="hidden" name="address" value="<?= $users['user_address']; ?>">
                                                <input type="hidden" name="mobilenumber" value="<?= $users['user_number']; ?>">
                                                <input type="submit" value="Update" class="btn btn-info" name="update" onclick="return confirm('Are you sure to update?')">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                        ?>


                    </tbody>
                </table>
                <tr>
                    <a href="add-user.php" class="btn btn-primary">Add User</a>
                </tr>


                <!-- End of Cart table -->
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