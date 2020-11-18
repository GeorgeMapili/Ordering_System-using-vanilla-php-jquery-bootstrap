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

    <div class="container mt-2">
        <div id="message"></div>
        <!-- Start of Carousel -->
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/carousel1.jpg" class="d-block w-100" alt="..." height="500px">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/carousel4.jpg" class="d-block w-100" alt="..." height="500px">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/carousel3.jpg" class="d-block w-100" alt="..." height="500px">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <!-- End of Carousel -->
    </div>

    <div class="container-fluid">
        <?php
        $sql1 = "SELECT * FROM category";
        $statement2 = $con->prepare($sql1);
        $statement2->execute();
        while ($category = $statement2->fetch(PDO::FETCH_ASSOC)) :
        ?>

            <h1 class="mt-5 mb-5 col-lg-12 col-md-12 col-sm-12"><?= ucwords($category['category_name']); ?></h1>

            <!-- Start of Food -->
            <div class="card-group">
                <div class="row m-3">
                    <?php
                    $foodSearch = $category['category_name'];
                    $sql = "SELECT * FROM food WHERE food_category_name = :category_name";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":category_name", $foodSearch, PDO::PARAM_STR);
                    $statement->execute();

                    while ($food = $statement->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <div class="card col-sm-6 col-md-4 col-lg-3">
                            <img src="<?= $food['food_img']; ?>" class="card-img-top" alt="<?= $food['food_name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $food['food_name']; ?></h5>
                                <p class="card-text"><?= $food['food_description']; ?></p>
                                <h5 class="card-text text-center text-info p-2">₱ <?= number_format($food['food_price'], 2); ?></h5>
                                <form action="" class="form-submit">
                                    <input type="hidden" class="fid" value="<?= $food['food_id']; ?>">
                                    <input type="hidden" class="cuid" value="<?= $_SESSION['id']; ?>">
                                    <input type="hidden" class="fname" value="<?= $food['food_name']; ?>">
                                    <input type="hidden" class="fprice" value="<?= $food['food_price']; ?>">
                                    <input type="hidden" class="fimg" value="<?= $food['food_img']; ?>">
                                    <button class="btn btn-primary addItemBtn">Order Now</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>

                <?php endwhile; ?>

                </div>
            </div>
            <!-- End of Food -->
    </div>

    <!-- Start of Footer -->
    <div class="container">
        <footer>
            <p class="text-center text-info">&copy; 2020 | Chill Grill</p>
        </footer>
    </div>
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