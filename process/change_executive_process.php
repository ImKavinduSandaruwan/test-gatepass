<?php
include("../includes/db.php");

$request_id = $_POST['request_id'];
$new_executive_id = $_POST['new_executive_id'];

if (!$request_id || !$new_executive_id) {
    die("Invalid input.");
}

$stmt = $pdo->prepare("UPDATE requests SET executive_id = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$new_executive_id, $request_id]);

header("Location: ../pages/view_request.php?id=$request_id&msg=executive_changed");
exit();
