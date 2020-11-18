<?php
session_start();
require_once 'connect.php';
if (!isset($_SESSION['id'])) {
	header("location:index.php");
}

if (isset($_POST['checkout'])) {



	$grand_total = 0;
	$allItems = '';
	$items = array();
	$cuid = $_SESSION['id'];

	$sql = "SELECT CONCAT(cart_food_name, '(Qty: ',cart_food_quantity,')','(Price: ₱ ',total_amount,'.00)') AS ItemQty, total_amount FROM cart WHERE cart_user_id = :id";
	$statement = $con->prepare($sql);
	$statement->bindParam(":id", $cuid, PDO::PARAM_INT);
	$statement->execute();

	while ($checkout = $statement->fetch(PDO::FETCH_ASSOC)) {
		$grand_total += $checkout['total_amount'];
		$items[] = $checkout['ItemQty'];
	}
	$allItems = implode("<br> ", $items);

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
							<span><?= $_SESSION['name']; ?></span>
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

		<div class="container">
			<div class="row justify-content-center bg-light">
				<div class="col-lg-6 px-4 pb-4" id="order">
					<h4 class="text-center text-info p-2">Complete your order!</h4>
					<div class="jumpbotron p-3 mb-2 text-center">
						<h6 class="lead "><strong>Ordered Food:</strong> </h6>
						<h6 class="lead "><?= $allItems; ?></h6>
						<h5 class="lead"><b>Food Total Amount:</b> ₱ <?= number_format($grand_total, 2); ?></h5>
					</div>
					<form action="checkout.php" method="post" id="placeOrder">
						<input type="hidden" name="orderedfood" value="<?= $allItems; ?>">
						<input type="hidden" name="orderedtotalamount" value="<?= $grand_total; ?>">
						<input type="hidden" name="userId" value="<?= $_SESSION['id']; ?>">
						<h3 class="lead text-center">Contact information</h3>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="">Name</label>
								<input type="text" name="name" value="<?= $_SESSION['name']; ?>" class="form-control" readonly>
							</div>
							<div class="form-group col-md-6">
								<label for="">Email</label>
								<input type="email" name="email" value="<?= $_SESSION['email']; ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="">Address</label>
								<input type="text" name="address" value="<?= $_SESSION['address']; ?>" class="form-control" readonly>
							</div>
							<div class="form-group col-md-6">
								<label for="">Phone Number</label>
								<input type="tel" name="mobilenumber" class="form-control" value="<?= $_SESSION['number']; ?>" readonly>
							</div>
						</div>
						<h6 class="text-center lead">Payment Mode</h6>
						<div class="form-group">
							<select name="payment" class="form-control" required>
								<option value="" selected disabled>Select</option>
								<option value="cod">Cash on delivery</option>
							</select>
						</div>
						<h6 class="text-center lead">City / Municipality Delivery Fee</h6>
						<div class="form-group">
							<select name="orderFee" class="form-control" required>
								<option value="" selected disabled>Select</option>
								<?php
								$sql1 = "SELECT * FROM areas";
								$statement1 = $con->prepare($sql1);
								$statement1->execute();

								while ($areas = $statement1->fetch(PDO::FETCH_ASSOC)) :
								?>
									<option value="<?= ($areas['area_fee'] != "Free") ? $areas['area_fee'] : "0"; ?>"><?= $areas['area_name']; ?> - <?= ($areas['area_fee'] != "Free") ? " ₱" . number_format($areas['area_fee'], 2) : "Free"; ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<input type="submit" name="placeorder" class="btn btn-primary" value="Place Order">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Start of Footer -->
		<!-- <div class="container">
        <footer>
            <p class="text-center text-info">&copy; 2020 | Chill Grill</p>
        </footer>
    </div> -->
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
				$("#placeOrder").submit(function(e) {
					e.preventDefault();

					$.ajax({
						url: 'action.php',
						method: 'post',
						data: $('form').serialize() + "&action=order",
						success: function(response) {
							$("#order").html(response);
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

<?php } else {
	header("location:main.php");
} ?>