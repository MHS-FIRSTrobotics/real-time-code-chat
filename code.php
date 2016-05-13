<?php
$id = 0;
$host = "value of Data Source";
$user = "value of User Id";
$pwd = "value of Password";
$db = "value of Database";
// Connect to database.
try {
    $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die();
}
$conn->query("SELECT * FROM snapedit2 WHERE id=$id");