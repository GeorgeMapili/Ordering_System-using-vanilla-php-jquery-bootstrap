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


                <?php

                if (isset($_POST['delete'])) {
                    $id = $_POST['id'];

                    $sql = "DELETE FROM food WHERE food_id = :id";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
                    $statement->execute();
                }

                ?>

                <h1 class="text-center display-1 mt-5">ALL FOOD</h1>

                <!-- Start of Cart table -->
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Food ID</th>
                            <th scope="col">Food Image</th>
                            <th scope="col">Food Name</th>
                            <th scope="col">Food Desciption</th>
                            <th scope="col">Food Price</th>
                            <th scope="col">Food Category</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM food";
                        $statement = $con->prepare($sql);
                        $statement->execute();

                        while ($food = $statement->fetch(PDO::FETCH_ASSOC)) :
                            // var_dump($food['food_img']);
                            // exit(0);
                        ?>
                            <tr>
                                <th scope="row"><?= $food['food_id']; ?></th>
                                <td><img src="../<?= $food['food_img']; ?>" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                                <td><?= $food['food_name']; ?></td>
                                <td><?= $food['food_description']; ?></td>
                                <td>₱ <?= number_format($food['food_price'], 2); ?></td>
                                <td><?= ucwords($food['food_category_name']); ?></td>
                                <td>
                                    <!-- <a href="#" class="btn btn-danger">Delete</a> -->
                                    <div class="row">
                                        <div class="col">
                                            <form action="view-food.php" method="post">
                                                <input type="hidden" name="id" value="<?= $food['food_id']; ?>">
                                                <input type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')" value="Delete">
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="update-food.php" method="post">
                                                <input type="hidden" name="id" value="<?= $food['food_id']; ?>">
                                                <input type="hidden" name="foodImg" value="<?= $food['food_img']; ?>">
                                                <input type="hidden" name="name" value="<?= $food['food_name']; ?>">
                                                <input type="hidden" name="description" value="<?= $food['food_description']; ?>">
                                                <input type="hidden" name="price" value="<?= $food['food_price']; ?>">
                                                <input type="hidden" name="category" value="<?= $food['food_category_name']; ?>">
                                                <input type="submit" name="update" class="btn btn-info" onclick="return confirm('Are you sure to update?')" value="Update">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>



                    </tbody>
                </table>
                <tr>
                    <a href="add-food.php" class="btn btn-primary mt-3">Add Food</a>
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