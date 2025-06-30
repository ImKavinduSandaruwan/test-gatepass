<?php
include("../includes/db.php");

$user_id = $_POST['user_id'] ?? '';

$response = ['success' => false];

if ($user_id != '') {
    $stmt = $pdo->prepare("SELECT full_name, work_location, role_id, contact_number FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $response['success'] = true;
        $response['full_name'] = $user['full_name'];
        $response['work_location'] = $user['work_location'] ?? ''; 
        $response['role'] = $user['role_id'];
        $response['contact_number'] = $user['contact_number'] ?? '';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
