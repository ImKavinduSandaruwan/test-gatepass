<?php
session_start();
include("../includes/db.php");

// Only Admin (role_id = 4)
if ($_SESSION['role_id'] != 4) {
    die("Access Denied.");
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_location':
        $location_name = $_POST['location_name'];
        $stmt = $pdo->prepare("INSERT INTO locations (location_name) VALUES (?)");
        $stmt->execute([$location_name]);
        break;

    case 'delete_location':
        $location_id = $_POST['location_id'];
        $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
        $stmt->execute([$location_id]);
        break;

    case 'add_category':
        $category_name = $_POST['category_name'];
        $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$category_name]);
        break;

    case 'delete_category':
        $category_id = $_POST['category_id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$category_id]);
        break;

    default:
        // Invalid action
        break;
}

header("Location: ../pages/admin.php");
exit();
?>
