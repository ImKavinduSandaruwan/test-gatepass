<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Only Duty Officer (role_id = 3)
if ($_SESSION['role_id'] != 3) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch verified requests with receiver name
$stmt = $pdo->prepare("
    SELECT r.*, u.full_name AS receiver_name 
    FROM requests r 
    LEFT JOIN users u ON r.receiver_user_id = u.id 
    WHERE r.status = 'verified' 
    ORDER BY r.updated_at DESC
");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- CSS Links -->
<link rel="stylesheet" href="../css/verify.css">
<link rel="stylesheet" href="../css/badges.css">

<div class="create-user-container">
    <h2 class="page-title">Verified Requests (Dispatch Pending)</h2>

    <?php if (count($requests) == 0): ?>
        <p>No verified requests available.</p>
    <?php else: ?>
        <table class="requests-table" border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr style="background-color: #f0f0f0;">
                <th>ID</th>
                <th>Receiver Name</th>
                <th>Status</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>

            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo $req['id']; ?></td>
                    <td><?php echo htmlspecialchars($req['receiver_name']); ?></td>
                    <td>
                        <span class="status-badge badge-verified">Verified</span>
                    </td>
                    <td><?php echo $req['updated_at']; ?></td>
                    <td>
                        <form action="../process/dispatch_process.php" method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                            <input type="submit" value="Dispatch" onclick="return confirm('Mark this request as dispatched?');" class="btn-dispatch">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
