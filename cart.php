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

    <h2 class="text-center mt-3 mb-3">You just added to your cart an:</h2>

    <div class="container">
        <!-- Start of Cart table -->
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Food ID</th>
                    <th scope="col">Food Image</th>
                    <th scope="col">Food Name</th>
                    <th scope="col">Food Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM cart";
                $statement = $con->prepare($sql);
                $statement->execute();
                $grand_total = 0;
                while ($cart = $statement->fetch(PDO::FETCH_ASSOC)) :
                    if ($cart['cart_user_id'] == $_SESSION['id']) :
                ?>
                        <tr>
                            <input type="hidden" class="fid" value="<?= $cart['cart_id']; ?>">
                            <th scope="row"><?= $cart['cart_food_id']; ?></th>
                            <td><img src="<?= $cart['cart_food_image'] ?>" width="100" alt="" class=" img-fluid img-thumbnail"></td>
                            <td><?= $cart['cart_food_name']; ?></td>
                            <input type="hidden" class="fprice" value="<?= $cart['cart_food_price']; ?>">
                            <td>₱ <?= number_format($cart['cart_food_price'], 2); ?></td>
                            <td><input type="number" class="itemQty w-25" value="<?= $cart['cart_food_quantity']; ?>" min="1"></td>
                            <td>₱ <?= number_format($cart['total_amount'], 2); ?></td>
                            <td><a href="action.php?remove=<?= $cart['cart_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a></td>
                        </tr>
                        <?php
                        $grand_total += $cart['total_amount'];
                        ?>
                    <?php endif; ?>
                <?php endwhile; ?>
                <tr class="bg-dark text-white">
                    <td colspan="3"><a href="main.php" class="btn btn-primary">Add More Food</a></td>
                    <td colspan="2"><span class=" text-center">Total:</span></td>
                    <td><span class="text-info text-center">₱ <?= number_format($grand_total, 2); ?></span></td>
                    <form action="checkout.php" method="POST">
                        <?php
                        if ($grand_total > 1) {
                        ?>

                            <td><input type="submit" name="checkout" value="Checkout" class="btn btn-info"></td>
                        <?php
                        } else {
                        ?>
                            <td>
                                <buttton type="submit" name="checkout" class="btn btn-info disabled">Checkout</buttton>
                            </td>
                        <?php } ?>
                    </form>
                </tr>



            </tbody>
        </table>


        <!-- End of Cart table -->

    </div>

    <!-- Start of Footer -->
    <footer class=" page-footer font-small mdb-color darken-3 pt-4">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2020 | Chill Grill
        </div>
        <!-- Copyright -->

    </footer>
    <!-- End of Footer -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script> -->

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {

            $(".itemQty").on('change', function() {
                var $el = $(this).closest('tr');

                var cartqty = $el.find('.itemQty').val();
                var cartfid = $el.find('.fid').val();
                var cartfprice = $el.find(".fprice").val();
                location.reload(true);
                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    cache: false,
                    data: {
                        cartqty: cartqty,
                        cartfid: cartfid,
                        cartfprice: cartfprice
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });



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