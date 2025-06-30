<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Fetch requests created by logged user with item count and receiver contact
$stmt = $pdo->prepare("
    SELECT r.*, 
        u2.full_name AS receiver_name,
        l1.location_name AS out_location,
        l2.location_name AS in_location,
        (SELECT COUNT(*) FROM items i WHERE i.request_id = r.id) AS items_count
    FROM requests r
    LEFT JOIN users u2 ON r.receiver_user_id = u2.id
    LEFT JOIN locations l1 ON r.out_location_id = l1.id
    LEFT JOIN locations l2 ON r.in_location_id = l2.id
    WHERE r.created_by_user_id = ?
    ORDER BY r.id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/my_request.css">
<link rel="stylesheet" href="../css/badges.css">

<h2>My Gate Pass Requests</h2>

<?php foreach ($requests as $req): ?>
    <div class="request-card">
        <div class="request-header">Request #<?php echo $req['id']; ?></div>
        <div class="request-content">
            <div class="left-column">
                <p><strong>Sender Name:</strong> <?php echo $_SESSION['full_name']; ?></p>
                <p><strong>Receiver Contact:</strong> <?php echo htmlspecialchars($req['receiver_contact_number'] ?? 'N/A'); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-badge <?php echo strtolower($req['status']); ?>">
                        <?php
                        switch ($req['status']) {
                            case 'pending':
                                echo '<span class="status-badge badge-pending">Pending</span>';
                                break;
                            case 'executive_approved':
                                echo '<span class="status-badge badge-executive">Executive Approved</span>';
                                break;
                            case 'verified':
                                echo '<span class="status-badge badge-verified">Fully Approved</span>';
                                break;
                            case 'dispatched':
                                echo '<span class="status-badge badge-dispatched">Dispatched</span>';
                                break;
                            case 'received':
                                echo '<span class="status-badge badge-received">Received</span>';
                                break;
                            case 'rejected':
                                echo '<span class="status-badge badge-rejected">Rejected</span>';
                                break;
                            default:
                                echo '<span class="status-badge">' . ucfirst($req['status']) . '</span>';
                        }
                        ?></p>
                <p><strong>Created At:</strong> <?php echo $req['created_at']; ?></p>
            </div>
            <div class="right-column">
                <p><strong>Receiver Name:</strong> <?php echo htmlspecialchars($req['receiver_name']); ?></p>
                <p><strong>Transport Method:</strong> <?php echo ucfirst($req['transport_method']); ?></p>
                <p><strong>Out Location:</strong> <?php echo htmlspecialchars($req['out_location']); ?></p>
                <p><strong>In Location:</strong> <?php echo htmlspecialchars($req['in_location']); ?></p>
                <p><strong>Items Count:</strong> <?php echo $req['items_count']; ?></p>
            </div>
        </div>
        <div class="request-action">
            <a href="view_request.php?id=<?php echo $req['id']; ?>" class="view-button">View</a>
        </div>
    </div>
<?php endforeach; ?>

<?php if (count($requests) == 0): ?>
    <p style="text-align: center;">No requests found.</p>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>
