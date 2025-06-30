<?php
session_start();
include("../includes/db.php");

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute the query
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role_id'] = $user['role_id'];
    $_SESSION['full_name'] = $user['full_name'];

    header("Location: ../pages/dashboard.php");
    exit();
} else {
    // Login failed
    echo "Invalid username or password.<br><a href='../pages/login.php'>Try again</a>";
}
?>
