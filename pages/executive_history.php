<link rel="stylesheet" href="../css/executive_action.css">

<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Allow only Executive role (role_id = 2)
if ($_SESSION['role_id'] != 2) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

$search = $_GET['search'] ?? '';
$executive_id = $_SESSION['user_id'];

// SQL query
$sql = "
    SELECT r.*, u.full_name AS sender_name
    FROM requests r
    JOIN users u ON r.user_id = u.id
    WHERE r.executive_id = :executive_id
";

if (!empty($search)) {
    $sql .= " AND (
        r.id LIKE :search OR
        r.receiver_name LIKE :search OR
        r.created_at LIKE :search
    )";
}

$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);

$params = ['executive_id' => $executive_id];
if (!empty($search)) {
    $params['search'] = "%$search%";
}

$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Executive Request History</h2>

    <form method="get" class="search-form">
        <input type="text" name="search" placeholder="Search by request_id, receiver, or date" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (count($requests) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Receiver</th>
                    <th>Sender</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['receiver_name']) ?></td>
                        <td><?= htmlspecialchars($row['sender_name']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td><?= ucwords(str_replace("_", " ", $row['status'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No request history found.</p>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
