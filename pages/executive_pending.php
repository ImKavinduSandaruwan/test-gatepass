<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Allow only Executive role
if ($_SESSION['role_id'] != 2) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch pending requests assigned to this executive
$stmt = $pdo->prepare("
    SELECT r.id, r.receiver_name, r.receiver_service_number, r.status, r.created_at,
        l1.location_name AS out_location,
        l2.location_name AS in_location
    FROM requests r
    JOIN locations l1 ON r.out_location_id = l1.id
    JOIN locations l2 ON r.in_location_id = l2.id
    WHERE r.executive_id = ? AND r.status = 'pending'
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link the external CSS -->
<link rel="stylesheet" href="../css/executive_style.css">

<h2 class="page-title">Executive - Pending Requests</h2>

<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Receiver Name</th>
                <th>Out Location</th>
                <th>In Location</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo $req['id']; ?></td>
                    <td><?php echo htmlspecialchars($req['receiver_name']); ?></td>
                    <td><?php echo htmlspecialchars($req['out_location']); ?></td>
                    <td><?php echo htmlspecialchars($req['in_location']); ?></td>
                    <td><?php echo $req['created_at']; ?></td>
                    <td>
                        <a class="action-button" href="executive_action.php?id=<?php echo $req['id']; ?>">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
