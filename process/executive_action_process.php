<?php
session_start();
include("../includes/db.php");

if ($_SESSION['role_id'] != 2) { // Executive Officer role check
    die("Access Denied.");
}

$request_id = $_POST['request_id'];
$action = $_POST['action'];
$comments = $_POST['comments'] ?? '';

// Validate action
$new_status = ($action == 'approved') ? 'pending_duty_officer' : 'rejected_by_executive';
$stmt = $pdo->prepare("UPDATE requests SET status = ?, updated_at = NOW() WHERE id = ? AND executive_id = ?");
$stmt->execute([$new_status, $request_id, $_SESSION['user_id']]);


// Determine new status
$new_status = ($action === 'approved') ? 'pending_duty_officer' : 'rejected';

// Update request
$stmt = $pdo->prepare("UPDATE requests SET status = ?, updated_at = NOW() WHERE id = ? AND executive_id = ?");
$stmt->execute([$new_status, $request_id, $_SESSION['user_id']]);

// Redirect
if ($action === 'approved') {
    header("Location: ../pages/executive_approved.php");
} else {
    header("Location: ../pages/executive_rejected.php");
}
exit();
?>
