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

                <h1 class="text-center display-1 mt-5">ADD FOOD</h1>
                <p class="text-center mt-5"><?= (isset($_GET['success'])) ? "<span class='success'>Added Food Successfully</span>" : ""; ?></p>


                <?php

                if (isset($_POST['addfood'])) {
                    $name = trim(htmlspecialchars($_POST['name']));
                    $description = trim(htmlspecialchars($_POST['description']));
                    $price = trim(htmlspecialchars($_POST['price']));
                    $category = trim(htmlspecialchars($_POST['category']));
                    $foodImg = $_FILES['foodImg'];

                    //food image name
                    $foodname = "img/" . strtolower($foodImg['name']);

                    // Food Image Temporary name
                    $foodtemp = $foodImg['tmp_name'];

                    // Destination of the image
                    $dest = "../" . $foodname;

                    // Check if the foodname exist
                    $sql = "SELECT * FROM food WHERE food_name = :name";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->execute();

                    $foodName = $statement->fetch(PDO::FETCH_ASSOC);
                    if ($foodName >= 1) {
                        header("location:add-food.php?errFoodName=Food_name_already_taken&desc=$description&price=$price");
                        exit(0);
                    }

                    $ext = $foodImg['type'];
                    $extF = explode('/', $ext);
                    // Start
                    // Check if the Extension of image is valid
                    $allowed = array('jpg', 'jpeg', 'png');
                    // $filesize = $profileImg['size'];

                    if (!in_array(strtolower($extF[1]), $allowed)) {
                        header("location:add-food.php?errorImg1=image_is_not_valid&name=$name&desc=$description&price=$price");
                        exit(0);
                    }

                    // Check if the image size is valid

                    if ($foodImg['size'] > 10000000) {
                        header("location:add-food.php?errorImg2=image_is_only_less_than_10MB&name=$name&desc=$description&price=$price");
                        exit(0);
                    }

                    // End

                    // Check if the food image already existed
                    $sql1 = "SELECT * FROM food WHERE food_img = :foodimg";
                    $statement1 = $con->prepare($sql1);
                    $statement1->bindParam(":foodimg", $foodname, PDO::PARAM_STR);
                    $statement1->execute();

                    $foodImg = $statement1->rowCount();

                    if ($foodImg >= 1) {
                        header("location:add-food.php?errFoodImg=Food_Image_already_taken&name=$name&desc=$description&price=$price");
                        exit(0);
                    }

                    $sql = "INSERT INTO food(food_name,food_description,food_price,food_img,food_category_name)VALUES(:name,:desc,:price,:foodImg,:category)";
                    $statement = $con->prepare($sql);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":desc", $description, PDO::PARAM_STR);
                    $statement->bindParam(":price", $price, PDO::PARAM_STR);
                    $statement->bindParam(":foodImg", $foodname, PDO::PARAM_STR);
                    $statement->bindParam(":category", $category, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        move_uploaded_file($foodtemp, $dest);
                        header("location:add-food.php?success=Added_new_food");
                    }
                }

                ?>
                <!-- Start of Table -->
                <div class="container">
                    <form action="add-food.php" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Name</label>
                                    <!-- <input type="text" class="form-control" name="name" required> -->
                                    <?= (isset($_GET['name'])) ? '<input type="text" class="form-control" value="' . $_GET['name'] . '" name="name" required>' : '<input type="text" class="form-control" name="name" required>';  ?>
                                    <?= (isset($_GET['errFoodName'])) ? '<span style="color: rgb(226, 25, 25);">Food Name is already taken</span>' : ""; ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Description</label>
                                    <!-- <textarea class="form-control" rows="5" name="description" required></textarea> -->
                                    <?= (isset($_GET['desc'])) ? '<textarea class="form-control" rows="5" name="description" required>' . $_GET['desc'] . '</textarea>' : '<textarea class="form-control" rows="5" name="description" required></textarea>';  ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label>
                                    <!-- <input type="text" class="form-control" name="price" required> -->
                                    <?= (isset($_GET['price'])) ? '<input type="text" class="form-control" name="price" value="' . $_GET['price'] . '" required>' : '<input type="text" class="form-control" name="price" required>'; ?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" name="category">
                                        <?php
                                        $sql = "SELECT * FROM category";
                                        $statement = $con->prepare($sql);
                                        $statement->execute();

                                        while ($category = $statement->fetch(PDO::FETCH_ASSOC)) :
                                        ?>
                                            <option value="<?= $category['category_name']; ?>"><?= ucwords($category['category_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Food Image</label>
                                    <input type="file" class="form-control" name="foodImg" required>
                                    <?= (isset($_GET['errFoodImg'])) ? '<span style="color: rgb(226, 25, 25);">Food Image is already taken</span>' : ""; ?>
                                    <?= (isset($_GET['errorImg1'])) ? '<span style="color: rgb(226, 25, 25);">Image is not valid(Png,Jpeg,Jpg) Only</span>' : "";  ?>
                                    <?= (isset($_GET['errorImg2'])) ? '<span style="color: rgb(226, 25, 25);">Image only less than 10MB are valids</span>' : "";  ?>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Add" class="btn btn-primary" name="addfood">
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