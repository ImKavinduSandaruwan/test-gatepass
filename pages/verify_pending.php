<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Only allow Duty Officer (role_id = 3)
if ($_SESSION['role_id'] != 3) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch requests approved by executive, pending duty officer verification
$stmt = $pdo->prepare("
    SELECT r.*, u.full_name AS receiver_name
    FROM requests r
    LEFT JOIN users u ON r.receiver_user_id = u.id
    WHERE r.status = 'pending_duty_officer'
    ORDER BY r.updated_at DESC
");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link to CSS -->
<link rel="stylesheet" href="../css/verify.css">

<h2 class="page-title">Verify - Pending Requests</h2>

<?php if (count($requests) > 0): ?>
<table class="requests-table">
    <tr>
        <th>ID</th>
        <th>Receiver Name</th>
        <th>Status</th>
        <th>Updated At</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($requests as $req): ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo htmlspecialchars($req['receiver_name']); ?></td>
            <td><span class="status status-<?php echo $req['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $req['status'])); ?></span></td>
            <td><?php echo $req['updated_at']; ?></td>
            <td>
                <a href="verify_action.php?id=<?php echo $req['id']; ?>" class="btn-action">Verify / Reject</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>No pending requests for verification.</p>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>
