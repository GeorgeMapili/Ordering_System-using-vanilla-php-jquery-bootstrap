<?php
session_start();
require_once 'connect.php';
if (isset($_SESSION['id'])) {
    header("location:main.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body class="registerBody">

    <?php
    if (isset($_POST['register'])) {
        $name = trim(htmlspecialchars($_POST['name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $address = trim(htmlspecialchars($_POST['address']));
        $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
        $password = trim(htmlspecialchars($_POST['password']));
        $confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));
        $profileImg = $_FILES['profileImg'];

        // image name
        $imgName = $profileImg['name'];

        // Check if name is valid
        if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
            header("location:register.php?errorName1=Name is invalid&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if name already existed
        $sql1 = "SELECT * FROM users WHERE user_name = :name";
        $statement1 = $con->prepare($sql1);
        $statement1->bindParam(":name", $name, PDO::PARAM_STR);
        $statement1->execute();
        $nameCount = $statement1->rowCount();
        if ($nameCount >= 1) {
            header("location:register.php?errorName2=name_already_existed&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if Email is already existed
        $sql2 = "SELECT * FROM users WHERE user_email = :email";
        $statement2 = $con->prepare($sql2);
        $statement2->bindParam(":email", $email, PDO::PARAM_STR);
        $statement2->execute();
        $emailCount = $statement2->rowCount();
        if ($emailCount >= 1) {
            header("location:register.php?errorEmail1=email_already_existed&name=$name&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if Email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location:register.php?errorEmail2=email_is_invalid&name=$name&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if Mobile Number is already existed
        $sql3 = "SELECT * FROM users WHERE user_number = :number";
        $statement3 = $con->prepare($sql3);
        $statement3->bindParam(":number", $mobileNumber, PDO::PARAM_STR);
        $statement3->execute();
        $mobileCount = $statement3->rowCount();
        if ($mobileCount >= 1) {
            header("location:register.php?errorMobile=mobile_number_already_existed&name=$name&email=$email&address=$address");
            exit(0);
        }

        // Check if Password Match
        if ($password !== $confirmPassword) {
            header("location:register.php?errorConfirmPassword=password_did_not_match&name=$name&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check Image
        $profileName = $profileImg['name'];
        $ext = $profileImg['type'];
        $extF = explode('/', $ext);
        $tmpname = $profileImg['tmp_name'];
        $dest = __DIR__ . "/upload/user_profile_img/" . $profileName;

        // Check if the Extension of image is valid
        $allowed = array('jpg', 'jpeg', 'png');
        // $filesize = $profileImg['size'];

        if (!in_array(strtolower($extF[1]), $allowed)) {
            header("location:register.php?errorImg1=image_is_not_valid&name=$name&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if the image size is valid

        if ($profileImg['size'] > 2000000) {
            header("location:register.php?errorImg2=image_is_only_less_than_2MB&name=$name&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        // Check if the user image already existed
        $sql4 = "SELECT * FROM users WHERE user_img = :userImg";
        $statement4 = $con->prepare($sql4);
        $statement4->bindParam(":userImg", $profileName, PDO::PARAM_STR);
        $statement4->execute();

        $userImg = $statement4->rowCount();

        if ($userImg >= 1) {
            header("location:register.php?errProfileImg=Profile_Image_Name_already_taken&name=$name&email=$email&address=$address&mobileNumber=$mobileNumber");
            exit(0);
        }

        move_uploaded_file($tmpname, $dest);

        // Password Hash
        $HashPass = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (user_name,user_email,user_pass,user_address,user_number,user_img)VALUES(:name,:email,:password,:address,:mobileNumber,:profileImg)";
        $statement = $con->prepare($sql);
        $statement->bindParam(":name", $name, PDO::PARAM_STR);
        $statement->bindParam(":email", $email, PDO::PARAM_STR);
        $statement->bindParam(":address", $address, PDO::PARAM_STR);
        $statement->bindParam(":mobileNumber", $mobileNumber, PDO::PARAM_STR);
        $statement->bindParam(":password", $HashPass, PDO::PARAM_STR);
        $statement->bindParam(":profileImg", $imgName, PDO::PARAM_STR);

        if ($statement->execute()) {
            header("location:index.php?success=register_succcess");
        }
    }
    ?>
    <div class="form-container">
        <h1>Welcome to Grill Chill</h1>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <h2>Create an account</h2>
            <div>
                <label for="">Name</label>
                <?= (isset($_GET['name'])) ? '<input type="text" name="name" value="' . $_GET['name'] . '" placeholder="Juan" required><br>' : '<input type="text" name="name" placeholder="Juan" required><br>'; ?>
                <!-- <input type="text" name="name" placeholder="Juan" required><br> -->
                <?= (isset($_GET['errorName1'])) ? '<span style="color: rgb(226, 25, 25);">Name is invalid</span>' : "";  ?>
                <?= (isset($_GET['errorName2'])) ? '<span style="color: rgb(226, 25, 25);">Name is already existed</span>' : "";  ?>
            </div>
            <div>
                <label for="">Email</label>
                <?= (isset($_GET['email'])) ? '<input type="email" name="email" value="' . $_GET['email'] . '" placeholder="Juan@gmail.com" required><br>' : '<input type="email" name="email" placeholder="Juan@gmail.com" required><br>'; ?>
                <!-- <input type="email" name="email" placeholder="Juan@gmail.com" required><br> -->
                <?= (isset($_GET['errorEmail1'])) ? '<span style="color: rgb(226, 25, 25);">Email is already existed</span>' : "";  ?>
                <?= (isset($_GET['errorEmail2'])) ? '<span style="color: rgb(226, 25, 25);">Email Format is invalid</span>' : "";  ?>
            </div>
            <div>
                <label for="">Address</label>
                <?= (isset($_GET['address'])) ? '<input type="text" name="address" value="' . $_GET['address'] . '" placeholder="123 Main St. Dumaguete City" required><br>' : '<input type="text" name="address" placeholder="123 Main St. Dumaguete City" required><br>'; ?>
                <!-- <input type="text" name="address" placeholder="123 Main St. Dumaguete City" required><br> -->
            </div>
            <div>
                <label for="">Mobile Number</label>
                <?= (isset($_GET['mobileNumber'])) ? '<input type="tel" name="mobileNumber" value="' . $_GET['mobileNumber'] . '" placeholder="+639876543210 or 09876543210" required><br>' : '<input type="tel" name="mobileNumber" placeholder="+639876543210 or 09876543210"  pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required><br>'; ?>
                <!-- <input type="tel" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required><br> -->
                <?= (isset($_GET['errorMobile'])) ? '<span style="color: rgb(226, 25, 25);">Mobile is already existed</span>' : "";  ?>
            </div>
            <div>
                <label for="">Password</label>
                <input type="password" name="password" placeholder="*******" minlength="8" required><br>
            </div>
            <div>
                <label for="">Confirm Password</label>
                <input type="password" name="confirmPassword" placeholder="*******" minlength="8" required><br>
                <?= (isset($_GET['errorConfirmPassword'])) ? '<span style="color: rgb(226, 25, 25);">Password did not match</span>' : "";  ?>
            </div>
            <div>
                <label for="">Profile Image</label>
                <input type="file" name="profileImg" required><br>
                <?= (isset($_GET['errorImg1'])) ? '<span style="color: rgb(226, 25, 25);">Image is not valid(Png,Jpeg,Jpg) Only</span>' : "";  ?>
                <?= (isset($_GET['errorImg2'])) ? '<span style="color: rgb(226, 25, 25);">Image only less than 2MB are valids</span>' : "";  ?>
                <?= (isset($_GET['errProfileImg'])) ? '<span style="color: rgb(226, 25, 25);">Profile Image Name is already taken</span>' : "";  ?>
            </div>
            <div>
                <input type="submit" value="Register" name="register">
            </div>
            <div class="classReg">
                <a href="index.php">Already have an accout?</a>
            </div>
        </form>
    </div>

</body>

</html>