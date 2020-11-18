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
    <?php
    if (isset($_POST['update'])) {
        $name = trim(htmlspecialchars($_POST['name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $address = trim(htmlspecialchars($_POST['address']));
        $mobilenumber = trim(htmlspecialchars($_POST['mobilenumber']));


        // Check if name is valid
        if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
            header("location:profile.php?errorName1=Name_is_invalid");
            exit(0);
        }

        // Check if name already existed
        $sql1 = "SELECT user_name FROM users WHERE user_id <> :id AND user_name = :name";
        $statement1 = $con->prepare($sql1);
        $statement1->bindParam(":id", $_SESSION['id'], PDO::PARAM_STR);
        $statement1->bindParam(":name", $name, PDO::PARAM_STR);
        $statement1->execute();
        $nameCount = $statement1->rowCount();
        if ($nameCount >= 1) {
            header("location:profile.php?errorName2=name_already_existed");
            exit(0);
        }

        // Check if Email is already existed
        $sql2 = "SELECT user_email FROM users WHERE user_id <> :id AND user_email = :email";
        $statement2 = $con->prepare($sql2);
        $statement2->bindParam(":id", $_SESSION['id'], PDO::PARAM_STR);
        $statement2->bindParam(":email", $email, PDO::PARAM_STR);
        $statement2->execute();
        $emailCount = $statement2->rowCount();
        if ($emailCount >= 1) {
            header("location:profile.php?errorEmail1=email_already_existed");
            exit(0);
        }

        // Check if Email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location:profile.php?errorEmail2=email_is_invalid");
            exit(0);
        }

        // Check if Mobile Number is already existed
        $sql3 = "SELECT user_number FROM users WHERE user_id <> :id AND user_number = :number";
        $statement3 = $con->prepare($sql3);
        $statement3->bindParam(":id", $_SESSION['id'], PDO::PARAM_STR);
        $statement3->bindParam(":number", $mobilenumber, PDO::PARAM_STR);
        $statement3->execute();
        $mobileCount = $statement3->rowCount();
        if ($mobileCount >= 1) {
            header("location:profile.php?errorMobile=mobile_number_already_existed");
            exit(0);
        }

        if ($name === $_SESSION['name'] && $email === $_SESSION['email'] && $address === $_SESSION['address'] && $mobilenumber === $_SESSION['number']) {
            header("location:profile.php?error1=Nothing to update");
            exit(0);
        }

        $sql4 = "UPDATE users SET user_name = :name, user_email = :email, user_address = :address, user_number = :number WHERE user_id = :id";
        $statement4 = $con->prepare($sql4);
        $statement4->bindParam(":name", $name, PDO::PARAM_STR);
        $statement4->bindParam(":email", $email, PDO::PARAM_STR);
        $statement4->bindParam(":address", $address, PDO::PARAM_STR);
        $statement4->bindParam(":number", $mobilenumber, PDO::PARAM_STR);
        $statement4->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);

        if ($statement4->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['address'] = $address;
            $_SESSION['number'] = $mobilenumber;
            header("location:profile.php?success1=update_profile");
        } else {
            header("location:profile.php?error=sql_error");
        }
    }


    ?>

    <h2 class="text-center mt-3 mb-3">Profile</h2>
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
    <div class="container">
        <!-- Start of Form -->
        <form action="profile.php" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="">Name</label>
                    <input type="text" name="name" value="<?= $_SESSION['name']; ?>" class="form-control">
                    <?= (isset($_GET['errorName1'])) ? '<span style="color: rgb(226, 25, 25);">Name is invalid</span>' : "";  ?>
                    <?= (isset($_GET['errorName2'])) ? '<span style="color: rgb(226, 25, 25);">Name is already existed</span>' : "";  ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="">Email</label>
                    <input type="email" name="email" value="<?= $_SESSION['email']; ?>" class="form-control">
                    <?= (isset($_GET['errorEmail1'])) ? '<span style="color: rgb(226, 25, 25);">Email is already existed</span>' : "";  ?>
                    <?= (isset($_GET['errorEmail2'])) ? '<span style="color: rgb(226, 25, 25);">Email Format is invalid</span>' : "";  ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="">Address</label>
                    <input type="text" name="address" value="<?= $_SESSION['address']; ?>" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="">Phone Number</label>
                    <input type="tel" name="mobilenumber" class="form-control" value="<?= $_SESSION['number']; ?>">
                    <?= (isset($_GET['errorMobile'])) ? '<span style="color: rgb(226, 25, 25);">Mobile is already existed</span>' : "";  ?>
                </div>
            </div>

            <button type="submit" name="update" class="btn btn-info">Update Contacts and Information</button>
        </form>
        <form action="action.php" method="post">
            <h6 class="text-center mt-3 mb-3">
                <?php
                if (isset($_GET['successPass']) && isset($_GET['successPass']) == "password_updated") {
                ?>
                    <span class="text-success h3">Updated Successfully</span>
                <?php
                } else if (isset($_GET['error2'])) {
                ?>
                    <span class="text-info h3">Nothing to Update</span>
                <?php
                } else if (isset($_GET['errorPass'])) {
                ?>
                    <span class="text-danger h3">Confirm new password do not match</span>
                <?php
                } else if (isset($_GET['errorPass1'])) {
                ?>
                    <span class="text-danger h3">Current password is incorrect</span>
                <?php } ?>
            </h6>
            <h4 class="text-center mt-3 mb-3">Change Password</h4>

            <div class="form-group">
                <label for="">Current Password</label>
                <input type="password" name="currentpassword" class="form-control">
            </div>


            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="">New Password</label>
                    <input type="password" name="newpassword" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="">Confirm New Password</label>
                    <input type="password" name="confirmnewpassword" class="form-control">
                </div>
            </div>
            <input type="submit" name="updatePass" class="btn btn-primary" value="Update Password">

        </form>



        <form action="action.php" method="post" enctype="multipart/form-data">
            <h4 class="text-center mt-3 mb-3">Change Profile</h4>
            <h6 class="text-center mt-3 mb-3">
                <?php
                if (isset($_GET['successProfileImg']) && isset($_GET['successProfileImg']) == "profile_image_updated_successfully") {
                ?>
                    <span class="text-success h3">Profile Image Updated Successfully</span>
                <?php } ?>

            </h6>

            <div class="form-group col-md-6">
                <label for="">Profile Image</label>
                <input type="file" name="profileImg" class="form-control">
                <?= (isset($_GET['errorImg1'])) ? '<span style="color: rgb(226, 25, 25);">Image is not valid(Png,Jpeg,Jpg) Only</span>' : "";  ?>
                <?= (isset($_GET['errorImg2'])) ? '<span style="color: rgb(226, 25, 25);">Image only less than 2MB are valids</span>' : "";  ?>
            </div>
            <input type="submit" name="updateProfile" class="btn btn-primary" value="Update Profile Image">
        </form>

        <!-- End of Form -->

    </div>

    <!-- Start of Footer -->
    <footer class="page-footer font-small mdb-color darken-3 pt-4">


        <div class="footer-copyright text-center py-3">© 2020 | Chill Grill
        </div>


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