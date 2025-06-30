<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

if ($_SESSION['role_id'] != 2) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

try {
    // Fetch rejected requests
    $stmt = $pdo->prepare("SELECT * FROM requests WHERE executive_id = ? AND status = 'rejected' ORDER BY updated_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    include("../includes/footer.php");
    exit();
}
?>

<!-- Link the same external CSS -->
<link rel="stylesheet" href="../css/executive_style.css">

<h2 class="page-title">Executive - Rejected Requests</h2>

<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Receiver Name</th>
                <th>Status</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo $req['id']; ?></td>
                    <td><?php echo htmlspecialchars($req['receiver_name']); ?></td>
                    <td><?php echo ucfirst($req['status']); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", strtotime($req['updated_at'])); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($requests) === 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 20px; color: #777;">No rejected requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
