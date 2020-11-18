<?php

session_start();
unset($_SESSION['adminname']);
unset($_SESSION['adminemail']);
session_destroy();
header("location:index.php");
