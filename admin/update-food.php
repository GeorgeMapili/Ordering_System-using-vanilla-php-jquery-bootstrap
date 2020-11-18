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

                    <h1 class="text-center display-1 mt-5">UPDATE FOOD</h1>
                    <h6 class="text-center mt-3 mb-3">
                        <?php
                        if (isset($_GET['success1']) && isset($_GET['success1']) == "update_food") {
                        ?>
                            <span class="text-success h3">Updated Food Successfully</span>
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
                    $foodImg = "";
                    $description = "";
                    $price = "";
                    $category = "";
                    if (isset($_POST['update'])) {
                        $id =  trim(htmlspecialchars($_POST['id']));
                        $foodImg =  trim(htmlspecialchars($_POST['foodImg']));
                        $name =  trim(htmlspecialchars($_POST['name']));
                        $description =  trim(htmlspecialchars($_POST['description']));
                        $price =  trim(htmlspecialchars($_POST['price']));
                        $category =  trim(htmlspecialchars($_POST['category']));

                        // var_dump($id);
                        // var_dump($foodImg);
                        // var_dump($name);
                        // var_dump($description);
                        // var_dump($price);
                        // var_dump($category);
                        // // exit(0);
                        $_SESSION['food_ID'] = $id;
                    }
                    ?>
                    <form action="process.php" method="post" enctype="multipart/form-data">
                        <!-- <h2 class="text-center mt-3 mb-3">Profile</h2> -->
                        <input type="hidden" name="id" value="<?= $_SESSION['food_ID']; ?>">

                        <div class="form-group">
                            <label class="lead font-weight-bold">Food Name</label>
                            <?= (isset($_GET['name'])) ? '<input type="text" class="form-control" name="name" value="' . $_GET['name'] . '"' : '<input type="text" class="form-control" name="name" value="' . $name . '">'; ?>
                            <?= (isset($_GET['errName1'])) ? '<span style="color: rgb(226, 25, 25);">Food Name already taken</span>' : "";  ?>
                        </div>

                        <div class="form-group">
                            <label class="lead font-weight-bold">Food Description</label>
                            <?= (isset($_GET['description'])) ? '<textarea class="form-control" rows="5" name="description" required>' . $_GET['description'] . '</textarea>' : '<textarea class="form-control" rows="5" name="description" required>' . $description . '</textarea>';  ?>
                        </div>


                        <div class="form-group">
                            <label class="lead font-weight-bold">Food Price</label>
                            <?= (isset($_GET['price'])) ? '<input type="text" class="form-control" name="price" value="' . $_GET['price'] . '"' : '<input type="text" class="form-control" name="price" value="' . $price . '">'; ?>
                        </div>

                        <div class="form-group">
                            <label class="lead font-weight-bold">Food Category</label>
                            <?php
                            if (isset($_GET['category'])) {
                                $newCat = $_GET['category'];
                            ?>
                                <select class="form-control" name="category">
                                    <?php
                                    $sql = "SELECT * FROM category";
                                    $statement = $con->prepare($sql);
                                    $statement->execute();
                                    $catName = "";
                                    while ($category2 = $statement->fetch(PDO::FETCH_ASSOC)) :
                                    ?>
                                        <option value="<?= ($category2['category_name'] == $newCat) ? $newCat : $catName = ""; ?>" <?= ($category2['category_name'] == $newCat) ? $catName = "selected" : ""; ?>><?= ucwords($category2['category_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            <?php } else { ?>
                                <select class="form-control" name="category">
                                    <?php
                                    $sql = "SELECT * FROM category";
                                    $statement = $con->prepare($sql);
                                    $statement->execute();
                                    $catName = "";
                                    while ($category1 = $statement->fetch(PDO::FETCH_ASSOC)) :
                                    ?>
                                        <option value="<?= ($category1['category_name'] == $category) ? $category : $catName = ""; ?>" <?= ($category1['category_name'] == $category) ? $catName = "selected" : ""; ?>><?= ucwords($category1['category_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label class="lead font-weight-bold">Food Image</label>
                            <input type="file" class="form-control" name="foodImg" value="img/img1.jpg">
                            <?= (isset($_GET['errFoodImg'])) ? '<span style="color: rgb(226, 25, 25);">Food Image already taken</span>' : "";  ?>
                            <?= (isset($_GET['errorImg1'])) ? '<span style="color: rgb(226, 25, 25);">Image is not valid(Png,Jpeg,Jpg) Only</span>' : "";  ?>
                            <?= (isset($_GET['errorImg2'])) ? '<span style="color: rgb(226, 25, 25);">Image only less than 10MB are valids</span>' : "";  ?>
                        </div>

                        <input type="submit" value="Update" name="updateFood" class="mt-3 btn btn-info">


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