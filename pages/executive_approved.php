<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

if ($_SESSION['role_id'] != 2) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch approved requests
$stmt = $pdo->prepare("SELECT * FROM requests WHERE executive_id = ? AND status = 'approved' ORDER BY updated_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link the same external CSS -->
<link rel="stylesheet" href="../css/executive_style.css">

<h2 class="page-title">Executive - Approved Requests</h2>

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
                    <td><?php echo $req['updated_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
