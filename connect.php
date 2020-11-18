<?php

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_NAME = "sadproject";
$DB_PASS = "";

try {
    $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed " . $e->getMessage());
}
