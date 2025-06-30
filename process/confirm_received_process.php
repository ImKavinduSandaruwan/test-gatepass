<?php
session_start();
include("../includes/db.php");

// Check login
if (!isset($_SESSION['user_id'])) {
    die("Access Denied.");
}

// Only POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request_id = $_POST['request_id'] ?? 0;
    $receiver_id = $_SESSION['user_id'];

    // Update only if this user is the receiver
    $stmt = $pdo->prepare("UPDATE requests SET status = 'received', updated_at = NOW() WHERE id = ? AND receiver_user_id = ?");
    $stmt->execute([$request_id, $receiver_id]);

    // Redirect back to receiver requests page
    header("Location: ../pages/receiver_requests.php"); // Make sure this matches your filename
    exit();
} else {
    echo "Invalid request method.";
}
