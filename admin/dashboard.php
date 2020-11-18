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

                <h1 class="text-center display-1 mt-5">DASHBOARD</h1>

                <div class="row mt-5">
                    <?php

                    $sql = "SELECT * FROM users";
                    $statement = $con->prepare($sql);
                    $statement->execute();

                    $users = $statement->rowCount();

                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">All User</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $users; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $sql1 = "SELECT * FROM food";
                    $statement1 = $con->prepare($sql1);
                    $statement1->execute();

                    $food = $statement1->rowCount();
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">All Food</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $food; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php

                    $sql2 = "SELECT * FROM category";
                    $statement2 = $con->prepare($sql2);
                    $statement2->execute();

                    $category = $statement2->rowCount();
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">All Category</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $category; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $statusAccepted = "accepted";
                    $sql3 = "SELECT * FROM orders WHERE o_status = :status";
                    $statement3 = $con->prepare($sql3);
                    $statement3->bindParam(":status", $statusAccepted, PDO::PARAM_STR);
                    $statement3->execute();

                    $accepted = $statement3->rowCount();

                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">Accepted Order</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $accepted; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $statusPending = "pending";
                    $sql4 = "SELECT * FROM orders WHERE o_status = :status";
                    $statement4 = $con->prepare($sql4);
                    $statement4->bindParam(":status", $statusPending, PDO::PARAM_STR);
                    $statement4->execute();

                    $pending = $statement4->rowCount();
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">Pending Order</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $pending; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $statusCancelled = "cancelled";
                    $sql5 = "SELECT * FROM orders WHERE o_status = :status";
                    $statement5 = $con->prepare($sql5);
                    $statement5->bindParam(":status", $statusCancelled, PDO::PARAM_STR);
                    $statement5->execute();

                    $cancelled = $statement5->rowCount();
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">Cancelled Order</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $cancelled; ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $profitStatus = "accepted";
                    $sql6 = "SELECT o_user_totalprice FROM orders WHERE o_status = :status";
                    $statement6 = $con->prepare($sql6);
                    $statement6->bindParam(":status", $profitStatus, PDO::PARAM_STR);
                    $statement6->execute();

                    $profits = 0;
                    while ($order = $statement6->fetch(PDO::FETCH_ASSOC)) {
                        $profits += $order['o_user_totalprice'];
                    }
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">Profit of Orders</div>
                            <div class="card-body">
                                <h5 class="card-title">₱ <?= number_format($profits, 2); ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php
                    $profitStatus = "accepted";
                    $sql7 = "SELECT o_delivery_fee FROM orders WHERE o_status = :status";
                    $statement7 = $con->prepare($sql7);
                    $statement7->bindParam(":status", $profitStatus, PDO::PARAM_STR);
                    $statement7->execute();

                    $deliveryFee = 0;
                    while ($order = $statement7->fetch(PDO::FETCH_ASSOC)) {
                        $deliveryFee += $order['o_delivery_fee'];
                    }
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header h2">Delivery Fee Profit</div>
                            <div class="card-body">
                                <h5 class="card-title">₱ <?= number_format($deliveryFee, 2); ?></h5>
                            </div>
                        </div>
                    </div>

                </div>

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