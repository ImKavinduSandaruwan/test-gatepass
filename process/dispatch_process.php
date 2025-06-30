<?php
session_start();
include("../includes/db.php");

// Only Duty Officer (role_id = 3)
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    die("Access Denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'] ?? 0;

    // Update status to 'dispatched'
    $stmt = $pdo->prepare("UPDATE requests SET status = 'dispatched', updated_at = NOW() WHERE id = ?");
    $stmt->execute([$request_id]);

    header("Location: ../pages/verify_verified.php"); // Update path as needed
    exit();
}
