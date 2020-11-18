<?php

session_start();
unset($_SESSION['adminname']);
unset($_SESSION['adminemail']);
unset($_SESSION['food_ID']);
session_destroy();
header("location:index.php");
