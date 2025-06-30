<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../includes/auth.php");
include("../includes/db.php");

// Only Duty Officer can access (role_id = 3)
if ($_SESSION['role_id'] != 3) {
    die("Access Denied.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Get and sanitize inputs
$request_id = $_POST['request_id'] ?? 0;
$action = $_POST['action'] ?? '';
$comments = trim($_POST['comments'] ?? '');

$dispatch_checker_name = $_POST['dispatch_checker_name'] ?? '';
$dispatch_checker_service_no = $_POST['dispatch_checker_service_no'] ?? '';
$dispatch_checker_nic = $_POST['dispatch_checker_nic'] ?? '';
$dispatch_checker_contact = $_POST['dispatch_checker_contact'] ?? '';

// Validate request_id
if (!is_numeric($request_id) || $request_id <= 0) {
    die("Invalid request ID.");
}

// Validate action
$valid_actions = ['verified', 'rejected_by_duty'];
if (!in_array($action, $valid_actions)) {
    die("Invalid action.");
}

// If rejected, comments are required
if ($action === 'rejected_by_duty' && empty($comments)) {
    die("Comments are required when rejecting.");
}

try {
    // Check if request exists and is pending duty officer
    $stmt = $pdo->prepare("SELECT * FROM requests WHERE id = ? AND status = 'pending_duty_officer'");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        die("Request not found or already processed.");
    }

    // Update request with new status, comments and dispatch checker details
    $updateStmt = $pdo->prepare("
        UPDATE requests
        SET 
            status = ?, 
            duty_officer_comments = ?, 
            dispatch_checker_name = ?, 
            dispatch_checker_service_no = ?, 
            dispatch_checker_nic = ?, 
            dispatch_checker_contact = ?, 
            updated_at = NOW()
        WHERE id = ?
    ");

    $updateStmt->execute([
        $action,
        $comments,
        $dispatch_checker_name,
        $dispatch_checker_service_no,
        $dispatch_checker_nic,
        $dispatch_checker_contact,
        $request_id
    ]);

    // Redirect after success
    if ($action === 'verified') {
        header("Location: ../pages/verify_verified.php");
        exit();
    } else {
        header("Location: ../pages/verify_pending.php?msg=Request rejected");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
