<?php
$host = "localhost";
$dbname = "gatepass_db";  // this should be your database name
$username = "root";       // default username for XAMPP/WAMP
$password = "";           // default password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
