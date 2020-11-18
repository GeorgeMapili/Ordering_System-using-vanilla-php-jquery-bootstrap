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

                <h1 class="text-center display-1 mt-5">ALL CATEGORY</h1>

                <?php
                if (isset($_GET['success2']) && isset($_GET['success2']) == "deleted_category_successfully") {
                ?>
                    <span class="text-danger h3">Deleted Category Successfully</span>
                <?php } ?>

                <!-- Start of Cart table -->
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Category ID</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Category Total Food</th>
                            <th scope="col text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (isset($_POST['delete1'])) {
                            $id = $_POST['id'];

                            $sql2 = "DELETE FROM category WHERE category_id = :id";
                            $statement2 = $con->prepare($sql2);
                            $statement2->bindParam(":id", $id, PDO::PARAM_INT);
                            $statement2->execute();
                            header("location:view-category.php?success2=deleted_category_successfully");
                        }

                        ?>

                        <?php
                        $sql = "SELECT * FROM category";
                        $statement = $con->prepare($sql);
                        $statement->execute();

                        while ($category = $statement->fetch(PDO::FETCH_ASSOC)) :
                            $categoryName = $category['category_name'];
                        ?>
                            <tr>
                                <th scope="row"><?= $category['category_id']; ?></th>
                                <td><?= ucwords($category['category_name']); ?></td>
                                <?php
                                $sql1 = "SELECT food_category_name FROM food WHERE food_category_name = :categoryName";
                                $statement1 = $con->prepare($sql1);
                                $statement1->bindParam(":categoryName", $categoryName, PDO::PARAM_STR);
                                $statement1->execute();

                                $categoryRow = $statement1->rowCount();
                                ?>
                                <td><?= $categoryRow ?></td>
                                <td>
                                    <?php
                                    if ($category['category_total_food'] >= 1) {
                                    ?>
                                        <input type="submit" value="Delete" class="btn btn-danger disabled" title="You can't delete a category unless the category total food is empty">
                                    <?php
                                    } else { ?>
                                        <form action="view-category.php" method="post">
                                            <input type="hidden" name="id" value="<?= $category['category_id']; ?>">
                                            <input type="submit" onclick="return confirm('Are you sure to delete?')" name="delete1" value="Delete" class="btn btn-danger ">
                                        </form>
                                    <?php
                                    }
                                    ?>

                                </td>
                            </tr>

                        <?php endwhile; ?>




                    </tbody>
                </table>
                <tr>
                    <a href="add-category.php" class="btn btn-primary">Add Category</a>
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