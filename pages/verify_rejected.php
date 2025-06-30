<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

if ($_SESSION['role_id'] != 3) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch rejected requests (rejected during verification)
$stmt = $pdo->prepare("SELECT * FROM requests WHERE status = 'rejected' ORDER BY updated_at DESC");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link to CSS -->
<link rel="stylesheet" href="../css/verify.css">

<h2 class="page-title">Verify - Rejected Requests</h2>

<table class="requests-table">
    <tr>
        <th>ID</th>
        <th>Receiver Name</th>
        <th>Status</th>
        <th>Updated At</th>
    </tr>

    <?php foreach ($requests as $req): ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo htmlspecialchars($req['receiver_name']); ?></td>
            <td>
                <span class="status status-<?php echo $req['status']; ?>">
                    <?php echo ucfirst($req['status']); ?>
                </span>
            </td>
            <td><?php echo $req['updated_at']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include("../includes/footer.php"); ?>
