<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Fetch returnable items → from verified requests only
$stmt = $pdo->prepare("
    SELECT i.*, r.receiver_name, r.id AS request_id
    FROM items i
    JOIN requests r ON i.request_id = r.id
    WHERE i.is_returnable = 1
    ORDER BY r.updated_at DESC
");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="../css/returnable-items.css">

<div class="container">
    <h2 class="page-title">Item Tracker - Returnable Items</h2>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Receiver Name</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Item Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item['request_id']; ?></td>
                    <td><?php echo htmlspecialchars($item['receiver_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo ucfirst($item['item_status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
