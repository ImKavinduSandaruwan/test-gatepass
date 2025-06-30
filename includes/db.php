<?php
$host = "gatepass-mysql.mysql.database.azure.com";
$dbname = "gatepass_db";  // this should be your database name
$username = "gatepassadmin@gatepass-mysql";       // default username for XAMPP/WAMP
$password = "Shwh621$21";           // default password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
