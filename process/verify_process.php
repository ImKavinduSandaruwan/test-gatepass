<?php
session_start();
include("../includes/db.php");

if ($_SESSION['role_id'] != 3) {
    die("Access Denied.");
}

$request_id = $_POST['request_id'];
$action = $_POST['action'];
$comments = $_POST['comments'] ?? '';

// Validate action
if (!in_array($action, ['verified', 'rejected'])) {
    die("Invalid action.");
}

// Update request status
$stmt = $pdo->prepare("UPDATE requests SET status = ?, updated_at = NOW() WHERE id = ? AND status = 'approved'");
$stmt->execute([$action, $request_id]);

// Optional: log comments if needed

// Redirect
if ($action == 'verified') {
    header("Location: ../pages/verify_verified.php");
} else {
    header("Location: ../pages/verify_rejected.php");
}
exit();
?>
