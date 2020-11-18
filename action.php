<?php
session_start();
require_once 'connect.php';

if (isset($_POST['fid'])) {
    $fid = $_POST['fid'];
    $cuid = $_SESSION['id'];
    $fname = $_POST['fname'];
    $fprice = $_POST['fprice'];
    $fimg = $_POST['fimg'];
    $fqty = 1;



    $sql = "SELECT * FROM cart WHERE cart_food_id = :foodid";
    $statement = $con->prepare($sql);
    $statement->bindParam(":foodid", $fid, PDO::PARAM_INT);
    $statement->execute();
    $f = $statement->fetch(PDO::FETCH_ASSOC);
    $foodid = $f['cart_food_id'] ?? false;
    if (!$foodid) {

        $sql1 = "INSERT INTO cart (cart_food_id,cart_food_name,cart_food_price,cart_food_quantity,cart_food_image,cart_user_id,total_amount)VALUES(:food_id,:food_name,:food_price,:food_quantity,:food_image,:cart_user_id,:food_total_amount)";
        $statement2 = $con->prepare($sql1);
        $statement2->bindParam(":food_id", $fid, PDO::PARAM_INT);
        $statement2->bindParam(":food_name", $fname, PDO::PARAM_STR);
        $statement2->bindParam(":food_price", $fprice, PDO::PARAM_STR);
        $statement2->bindParam(":food_quantity", $fqty, PDO::PARAM_INT);
        $statement2->bindParam(":food_image", $fimg, PDO::PARAM_STR);
        $statement2->bindParam(":cart_user_id", $cuid, PDO::PARAM_INT);
        $statement2->bindParam(":food_total_amount", $fprice, PDO::PARAM_INT);
        $statement2->execute();

        echo '
             <div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                 <strong>Food added to your cart!</strong>
             </div>
             ';
    } else {
        $sql1 = "SELECT cart_food_id,cart_food_name,cart_user_id FROM cart WHERE cart_food_id = :foodid AND cart_food_name = :name AND cart_user_id = :userid";
        $statement = $con->prepare($sql1);
        $statement->bindParam(":foodid", $foodid, PDO::PARAM_INT);
        $statement->bindParam(":name", $fname, PDO::PARAM_INT);
        $statement->bindParam(":userid", $cuid, PDO::PARAM_INT);

        $statement->execute();
        $cartRepeat = $statement->rowCount();
        if ($cartRepeat >= 1) {
            echo '
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Food already added to your cart!</strong>
        </div>
        ';
        } else {
            $sql1 = "INSERT INTO cart (cart_food_id,cart_food_name,cart_food_price,cart_food_quantity,cart_food_image,cart_user_id,total_amount)VALUES(:food_id,:food_name,:food_price,:food_quantity,:food_image,:cart_user_id,:food_total_amount)";
            $statement2 = $con->prepare($sql1);
            $statement2->bindParam(":food_id", $fid, PDO::PARAM_INT);
            $statement2->bindParam(":food_name", $fname, PDO::PARAM_STR);
            $statement2->bindParam(":food_price", $fprice, PDO::PARAM_STR);
            $statement2->bindParam(":food_quantity", $fqty, PDO::PARAM_INT);
            $statement2->bindParam(":food_image", $fimg, PDO::PARAM_STR);
            $statement2->bindParam(":cart_user_id", $cuid, PDO::PARAM_INT);
            $statement2->bindParam(":food_total_amount", $fprice, PDO::PARAM_INT);
            $statement2->execute();

            echo '
             <div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                 <strong>Food added to your cart!</strong>
             </div>
             ';
        }
    }
}

if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {

    $sql4 = "SELECT * FROM cart";
    $statement4 = $con->prepare($sql4);
    $statement4->execute();
    $total = 0;
    while ($cart = $statement4->fetch(PDO::FETCH_ASSOC)) {

        if ($cart['cart_user_id'] == $_SESSION['id']) {
            $cartid = $cart['cart_user_id'];
            $statement3 = $con->prepare("SELECT COUNT(cart_user_id) FROM cart WHERE cart_user_id = :cartid");
            $statement3->bindParam(":cartid", $cartid, PDO::PARAM_INT);
            $statement3->execute();
            $cartRows = $statement3->rowCount();

            $total += (int)$cartRows;
        }
    }
    echo $total;
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    $sql5 = "DELETE FROM cart WHERE cart_id = :id";
    $statement5 = $con->prepare($sql5);
    $statement5->bindParam(":id", $id, PDO::PARAM_INT);
    $statement5->execute();
    header("location:cart.php");
}

if (isset($_POST['cartqty'])) {
    $qty = $_POST['cartqty'];
    $fid = $_POST['cartfid'];
    $fprice = $_POST['cartfprice'];
    $totalprice = 0;
    $totalprice = (int)$qty * (int)$fprice;

    $sql6 = "UPDATE cart SET cart_food_quantity = :quantity, total_amount = :total WHERE cart_id = :id";
    $statement6 = $con->prepare($sql6);
    $statement6->bindParam(":quantity", $qty, PDO::PARAM_INT);
    $statement6->bindParam(":total", $totalprice, PDO::PARAM_INT);
    $statement6->bindParam(":id", $fid, PDO::PARAM_INT);
    $statement6->execute();
}

if (isset($_POST['action']) && isset($_POST['action']) == 'order') {
    $userId = $_POST['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobilenumber = $_POST['mobilenumber'];
    $orderedfood = $_POST['orderedfood'];
    $totalamount = $_POST['orderedtotalamount'];
    $paymentmode = $_POST['payment'];
    $orderFee = $_POST['orderFee'];
    $invoicenumber = rand(1000000, 1000000000) . '-' . rand(100, 999);

    // var_dump($orderFee);
    // exit(0);

    $food = str_replace("<br>", ", ", $orderedfood);

    $data = '';

    $statement = $con->prepare("INSERT INTO orders(o_user_name,o_user_food,o_user_email,o_user_phone,o_user_address,o_user_paymentmode,o_user_totalprice,o_invoice_number,o_delivery_fee,o_user_id)VALUES(:name,:foodorder,:email,:phone,:address,:paymentmode,:totalprice,:invoice,:deliveryFee,:userId)");
    $statement->bindParam(":name", $name, PDO::PARAM_STR);
    $statement->bindParam(":foodorder", $food, PDO::PARAM_STR);
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->bindParam(":phone", $mobilenumber, PDO::PARAM_STR);
    $statement->bindParam(":address", $address, PDO::PARAM_STR);
    $statement->bindParam(":paymentmode", $paymentmode, PDO::PARAM_STR);
    $statement->bindParam(":totalprice", $totalamount, PDO::PARAM_INT);
    $statement->bindParam(":invoice", $invoicenumber, PDO::PARAM_STR);
    $statement->bindParam(":deliveryFee", $orderFee, PDO::PARAM_STR);
    $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
    $statement->execute();

    $data .= '
        <div class="text-center">
            <h1 class="display-4 mt-2 text-danger">Ordered Placed Successfully</h1>
            <h4 class="lead text-center">Ordered Food:</h4>
            <h5 class="lead text-center">' . $orderedfood . '</h5>
            <h6 class="lead text-center">Name: ' . $name . '</h6>
            <h6 class="lead text-center">Email: ' . $email . '</h6>
            <h6 class="lead text-center">Address: ' . $address . '</h6>
            <h6 class="lead text-center">Mobile Number: ' . $mobilenumber . '</h6>
            <h6 class="lead text-center">Total Amount: ₱' .  number_format($totalamount, 2) . '</h6>
            <h6 class="lead text-center">Payment Mode: ' . $paymentmode . '</h6>
            <h6 class="lead text-center">Delivery Fee: ₱' . number_format($orderFee, 2) . '</h6>
        </div>
        
        <a href="order.php" class="btn btn-primary">Show Order</a>
    ';
    echo $data;
}

if (isset($_POST['updatePass'])) {
    $currentpassword = trim(htmlspecialchars($_POST['currentpassword'])) ?? false;
    $newpassword = trim(htmlspecialchars($_POST['newpassword']));
    $confirmnewpassword = trim(htmlspecialchars($_POST['confirmnewpassword']));

    if (empty($currentpassword) && empty($newpassword) && empty($confirmnewpassword)) {
        header("location:profile.php?error2=Nothing_to_update");
    }

    $sql1 = "SELECT * FROM users WHERE user_email = :email";
    $statement1 = $con->prepare($sql1);
    $statement1->bindParam(":email", $_SESSION['email'], PDO::PARAM_INT);
    $statement1->execute();

    while ($user = $statement1->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($currentpassword, $user['user_pass'])) {

            if ($newpassword == $confirmnewpassword) {
                $hashPass  = password_hash($newpassword, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET user_pass = :pass WHERE user_id = :id";
                $statement = $con->prepare($sql);
                $statement->bindParam(":pass", $hashPass, PDO::PARAM_STR);
                $statement->bindParam(":id", $_SESSION['id'], PDO::PARAM_STR);
                $statement->execute();
                header("location:profile.php?successPass=password_updated");
            } else {
                header("location:profile.php?errorPass=password_do_not_match");
            }
        } else {
            header("location:profile.php?errorPass1=password_incorrect");
        }
    }
}

if (isset($_POST['updateProfile'])) {
    $profileImg = $_FILES['profileImg'];

    $profileName = $profileImg['name'];
    $ext = $profileImg['type'];
    $extF = explode('/', $ext);
    $tmpname = $profileImg['tmp_name'];
    $dest = __DIR__ . "/upload/user_profile_img/" . $profileName;

    // var_dump($dest);
    // exit(0);

    $allowed = array('jpg', 'jpeg', 'png');
    $filesize = $profileImg['size'];
    if (!in_array($extF[1], $allowed)) {
        header('location:profile.php?errorImg1=image_is_not_valid');
        exit(0);
    }

    if ($filesize > 2000000) {
        header('location:profile.php?errorImg2=image_is_only_less_than_2MB');
        exit(0);
    }

    // Check if the food image already existed
    $sql1 = "SELECT * FROM users WHERE user_img = :userImg";
    $statement1 = $con->prepare($sql1);
    $statement1->bindParam(":userImg", $profileName, PDO::PARAM_STR);
    $statement1->execute();

    $foodImg = $statement1->rowCount();

    if ($foodImg >= 1) {
        header("location:profile.php?errProfileImg=Profile_Image_Name_already_taken");
        exit(0);
    }

    // Current Profile Img
    $currentProfile = $_SESSION['img'];

    // New Profile Img
    $newProfile = $profileName;

    // Path of the current profile
    $path = __DIR__ . "/upload/user_profile_img/" . $currentProfile;

    // var_dump($path);
    // exit(0);
    // Remove the old profile
    unlink($path);

    // Add the new Img
    move_uploaded_file($tmpname, $dest);

    // Set the new Value of the img
    $_SESSION['img'] = $newProfile;

    // Update the img
    $sql = "UPDATE users SET user_img = :img WHERE user_id = :id";
    $statement = $con->prepare($sql);
    $statement->bindParam(":img", $newProfile, PDO::PARAM_STR);
    $statement->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
    $statement->execute();



    header("location:profile.php?successProfileImg=profile_image_updated_successfully");
}
