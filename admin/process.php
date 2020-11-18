<?php
require_once '../connect.php';
if (isset($_POST['updateInfo'])) {
    $id = trim(htmlspecialchars($_POST['id']));
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $address = trim(htmlspecialchars($_POST['address']));
    $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));

    // var_dump($id);
    // var_dump($name);
    // var_dump($email);
    // var_dump($address);
    // var_dump($mobileNumber);

    // Check if name is valid
    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
        header("location:update-user.php?errorName1=Name_is_invalid&email=$email&address=$address&mobileNumber=$mobileNumber");
        exit(0);
    }

    // Check if name already existed
    $sql1 = "SELECT user_name FROM users WHERE user_id <> :id AND user_name = :name";
    $statement1 = $con->prepare($sql1);
    $statement1->bindParam(":id", $id, PDO::PARAM_STR);
    $statement1->bindParam(":name", $name, PDO::PARAM_STR);
    $statement1->execute();
    $nameCount = $statement1->rowCount();
    if ($nameCount >= 1) {
        header("location:update-user.php?errorName2=name_already_existed&email=$email&address=$address&mobileNumber=$mobileNumber");
        exit(0);
    }

    // Check if Email is already existed
    $sql2 = "SELECT user_email FROM users WHERE user_id <> :id AND user_email = :email";
    $statement2 = $con->prepare($sql2);
    $statement2->bindParam(":id", $id, PDO::PARAM_STR);
    $statement2->bindParam(":email", $email, PDO::PARAM_STR);
    $statement2->execute();
    $emailCount = $statement2->rowCount();
    if ($emailCount >= 1) {
        header("location:update-user.php?errorEmail1=email_already_existed&name=$name&address=$address&mobileNumber=$mobileNumber");
        exit(0);
    }

    // Check if Email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location:update-user.php?errorEmail2=email_is_invalid&name=$name&address=$address&mobileNumber=$mobileNumber");
        exit(0);
    }

    // Check if Mobile Number is already existed
    $sql3 = "SELECT user_number FROM users WHERE user_id <> :id AND user_number = :number";
    $statement3 = $con->prepare($sql3);
    $statement3->bindParam(":id", $id, PDO::PARAM_STR);
    $statement3->bindParam(":number", $mobileNumber, PDO::PARAM_STR);
    $statement3->execute();
    $mobileCount = $statement3->rowCount();
    if ($mobileCount >= 1) {
        header("location:update-user.php?errorMobile=mobile_number_already_existed&name=$name&email=$email&address=$address");
        exit(0);
    }

    $sql4 = "UPDATE users SET user_name = :name, user_email = :email, user_address = :address, user_number = :number WHERE user_id = :id";
    $statement4 = $con->prepare($sql4);
    $statement4->bindParam(":name", $name, PDO::PARAM_STR);
    $statement4->bindParam(":email", $email, PDO::PARAM_STR);
    $statement4->bindParam(":address", $address, PDO::PARAM_STR);
    $statement4->bindParam(":number", $mobileNumber, PDO::PARAM_STR);
    $statement4->bindParam(":id", $id, PDO::PARAM_STR);

    if ($statement4->execute()) {
        header("location:update-user.php?success1=update_profile");
    }
}


if (isset($_POST['updateFood'])) {
    $id = trim(htmlspecialchars($_POST['id']));
    $name = trim(htmlspecialchars($_POST['name']));
    $description = trim(htmlspecialchars($_POST['description']));
    $price = trim(htmlspecialchars($_POST['price']));
    $category = trim(htmlspecialchars($_POST['category']));
    $foodImg = $_FILES['foodImg'];

    //get the food image name
    $foodName = "img/" . $foodImg['name'];



    // Check if the food name already existed
    $sql = "SELECT * FROM food WHERE food_id <> :id AND food_name = :name";
    $statement = $con->prepare($sql);
    $statement->bindParam(":id", $id, PDO::PARAM_INT);
    $statement->bindParam(":name", $name, PDO::PARAM_STR);
    $statement->execute();

    $foodExst = $statement->rowCount();
    // var_dump($foodExst);
    // exit(0);
    if ($foodExst >= 1) {
        header("location:update-food.php?errName1=Food_Name_is_already_taken&description=$description&price=$price&category=$category");
        exit(0);
    }

    $sql1 = "UPDATE food SET food_name = :name, food_description = :description, food_price = :price, food_category_name = :category, food_img = :foodImg WHERE food_id = :id";
    $statement1 = $con->prepare($sql1);
    $statement1->bindParam(":name", $name, PDO::PARAM_STR);
    $statement1->bindParam(":description", $description, PDO::PARAM_STR);
    $statement1->bindParam(":price", $price, PDO::PARAM_STR);
    $statement1->bindParam(":category", $category, PDO::PARAM_STR);
    $statement1->bindParam(":foodImg", $foodName, PDO::PARAM_STR);
    $statement1->bindParam(":id", $id, PDO::PARAM_INT);

    if ($statement1->execute()) {
        header("location:update-food.php?success1=update_food");
    }
}
