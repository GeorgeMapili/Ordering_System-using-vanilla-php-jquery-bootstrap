<?php
// Local machine connection
// $DB_HOST = "localhost";
// $DB_USER = "root";
// $DB_NAME = "sadproject";
// $DB_PASS = "";

// Remove Db connection
$DB_HOST = "remotemysql.com";
$DB_USER = "PgpKEP3m0J";
$DB_NAME = "PgpKEP3m0J";
$DB_PASS = "Ez9klv2E4I";

try {
    $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed " . $e->getMessage());
}
