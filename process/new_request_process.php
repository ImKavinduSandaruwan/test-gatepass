<?php
session_start();
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Collect main request info
$created_by_user_id = $_SESSION['user_id'];
$sender_user_id = $_POST['sender_user_id'];
$sender_work_location = $_POST['sender_work_location'];
$sender_role = $_POST['sender_role'];
$sender_contact_number = $_POST['sender_contact'];

$receiver_user_id = $_POST['receiver_user_id'];
$receiver_name = $_POST['receiver_name'];
$receiver_work_location = $_POST['receiver_work_location'];
$receiver_role = $_POST['receiver_role'];
$receiver_contact_number = $_POST['receiver_contact'];

$transport_method = $_POST['transport_method'];
$person_name = $_POST['person_name'] ?? null;
$person_address = $_POST['person_address'] ?? null;
$person_nic = $_POST['person_nic'] ?? null;
$person_contact = $_POST['person_contact'] ?? null;
$driver_name = $_POST['driver_name'] ?? null;
$vehicle_no = $_POST['vehicle_no'] ?? null;
$vehicle_contact = $_POST['vehicle_contact'] ?? null;

$out_location_id = $_POST['out_location_id'];
$in_location_id = $_POST['in_location_id'];
$executive_id = $_POST['executive_id'];

$serial_nos = $_POST['serial_no'] ?? [];
$item_names = $_POST['item_name'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$is_returnables = $_POST['is_returnable'] ?? [];

$item_photos = $_FILES['item_photos'] ?? [];  // <-- key change here
$upload_dir = "../uploads/items/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$pdo->beginTransaction();

try {
    // Insert main request
    $stmt = $pdo->prepare("
        INSERT INTO requests 
        (user_id, executive_id, receiver_user_id, receiver_name, sender_work_location, sender_role, sender_contact_number,
         receiver_work_location, receiver_role, receiver_contact_number, transport_method, person_name, person_address, 
         person_nic, person_contact, driver_name, vehicle_no, vehicle_contact, out_location_id, in_location_id, created_by_user_id, status)
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $sender_user_id,
        $executive_id,
        $receiver_user_id,
        $receiver_name,
        $sender_work_location,
        $sender_role,
        $sender_contact_number,
        $receiver_work_location,
        $receiver_role,
        $receiver_contact_number,
        $transport_method,
        $person_name,
        $person_address,
        $person_nic,
        $person_contact,
        $driver_name,
        $vehicle_no,
        $vehicle_contact,
        $out_location_id,
        $in_location_id,
        $created_by_user_id
    ]);

    $request_id = $pdo->lastInsertId();

    // Insert each item and upload its multiple images
    foreach ($serial_nos as $i => $serial_no) {
        if (empty($item_names[$i]) || empty($quantities[$i])) continue;

        // Insert item with no photo first
        $stmt = $pdo->prepare("
            INSERT INTO items (request_id, serial_no, item_name, quantity, is_returnable, item_status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([
            $request_id,
            $serial_no,
            $item_names[$i],
            $quantities[$i],
            $is_returnables[$i] ?? 0
        ]);

        $item_id = $pdo->lastInsertId();

        // Upload up to 5 images for this item
        if (!empty($item_photos['name'][$i])) {
            for ($j = 0; $j < count($item_photos['name'][$i]); $j++) {
                if ($item_photos['error'][$i][$j] === UPLOAD_ERR_OK) {
                    $tmp_name = $item_photos['tmp_name'][$i][$j];
                    $original_name = basename($item_photos['name'][$i][$j]);
                    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                    $new_file_name = uniqid("item_{$item_id}_", true) . "." . $ext;
                    $target_path = $upload_dir . $new_file_name;

                    if (move_uploaded_file($tmp_name, $target_path)) {
                        // Insert image record
                        $stmt = $pdo->prepare("
                            INSERT INTO item_images (item_id, image_path)
                            VALUES (?, ?)
                        ");
                        $stmt->execute([$item_id, $new_file_name]);
                    } else {
                        throw new Exception("Failed to upload image for item $serial_no");
                    }
                }
            }
        }
    }

    $pdo->commit();
    header("Location: ../pages/my_requests.php?msg=request_created");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
