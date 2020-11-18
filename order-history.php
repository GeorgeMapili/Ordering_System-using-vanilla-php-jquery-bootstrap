<?php
session_start();
require_once 'connect.php';
if (!isset($_SESSION['id'])) {
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

</head>

<body>
    <!-- Start of Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="main.php">Chill Grill</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- <ul class="navbar-nav mr">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </ul> -->
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Food Categories</a>
                    <div class="dropdown-menu">
                        <?php
                        $sql4 = "SELECT * FROM category";
                        $statement4 = $con->prepare($sql4);
                        $statement4->execute();
                        while ($category4 = $statement4->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <a class="dropdown-item" href="food-category.php?categoryname=<?= $category4['category_name']; ?>"><?php echo ucwords($category4['category_name']); ?></a>
                        <?php endwhile; ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart <span id="cart-item" class="badge badge-primary"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order.php">Order</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <img src="upload/user_profile_img/<?= $_SESSION['img']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><?php echo $_SESSION['name']; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item disabled" href=""><?= $_SESSION['email']; ?></a>
                        <a class="dropdown-item" href="profile.php">My account</a>
                        <a class="dropdown-item" href="order-history.php">My Order History</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End of Navbar -->

    <h2 class="text-center mt-3 mb-3">My Order History</h2>

    <div class="container">
        <!-- Start of Cart table -->
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Invoice #</th>
                    <th scope="col">Order Name</th>
                    <th scope="col">Order Food</th>
                    <th scope="col">Food Total Price</th>
                    <th scope="col">Delivery Fee</th>
                    <th scope="col">Ordered On</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $status1 = "accepted";
                $status2 = "cancelled";
                $sql = "SELECT * FROM orders WHERE (o_status = :status1 OR o_status = :status2) AND o_user_id = :id";
                $statement = $con->prepare($sql);
                $statement->bindParam(":status1", $status1, PDO::PARAM_STR);
                $statement->bindParam(":status2", $status2, PDO::PARAM_STR);
                $statement->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
                $statement->execute();


                while ($order = $statement->fetch(PDO::FETCH_ASSOC)) :
                ?>
                    <tr>
                        <th scope="row"><?= $order['o_invoice_number']; ?></th>
                        <td><?= $order['o_user_name']; ?></td>
                        <td><?= $order['o_user_food']; ?></td>
                        <td>₱<?= number_format($order['o_user_totalprice'], 2); ?></td>
                        <td>₱<?= number_format($order['o_delivery_fee'], 2); ?></td>
                        <td><?= date("M d, Y, h:i A", strtotime($order['ordered_on'])); ?></td>
                        <!-- <td><a href="#" class="btn btn-danger" title="You cannot cancel once its delivering">Cancel</a></td> -->
                        <?php if ($order['o_status'] == "cancelled") { ?>
                            <td><input type="submit" name="cancel" class="btn btn-danger disabled" value="Cancelled"></td>
                        <?php } else if ($order['o_status'] == "accepted") { ?>
                            <td><input type="submit" name="cancel" class="btn btn-primary disabled" title="Delivering Food" value="Accepted"></td>
                        <?php } ?>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>


        <!-- End of Cart table -->

    </div>

    <!-- Start of Footer -->
    <footer class="page-footer font-small mdb-color darken-3 pt-4">


        <div class="footer-copyright text-center py-3">© 2020 | Chill Grill
        </div>


    </footer>
    <!-- End of Footer -->

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            $(".addItemBtn").click(function(e) {
                e.preventDefault();
                var $form = $(this).closest(".form-submit");
                var fid = $form.find(".fid").val();
                var cuid = $form.find(".cuid").val();
                var fname = $form.find(".fname").val();
                var fprice = $form.find(".fprice").val();
                var fimg = $form.find(".fimg").val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        fid: fid,
                        cuid: cuid,
                        fname: fname,
                        fprice: fprice,
                        fimg: fimg
                    },
                    success: function(response) {
                        $("#message").html(response);
                        window.scrollTo(0, 0);
                        load_cart_item_number();
                    }
                });
            });

            load_cart_item_number();

            function load_cart_item_number() {
                $.ajax({
                    url: 'action.php',
                    method: 'get',
                    data: {
                        cartItem: "cart_item"
                    },
                    success: function(response) {
                        $("#cart-item").html(response);
                    }
                });
            }
        });
    </script>

</body>

</html>