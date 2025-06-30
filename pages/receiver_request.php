<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Only show requests for this logged-in user
$receiver_id = $_SESSION['user_id'];

// Fetch requests where this user is the receiver
$stmt = $pdo->prepare("
    SELECT r.*, 
           u.full_name AS sender_name,
           l1.location_name AS out_location,
           l2.location_name AS in_location
    FROM requests r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN locations l1 ON r.out_location_id = l1.id
    LEFT JOIN locations l2 ON r.in_location_id = l2.id
    WHERE r.receiver_user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$receiver_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../css/create_user.css"> 
<link rel="stylesheet" href="../css/badges.css">


<div class="create-user-container2">
    <h2>Requests Assigned to You</h2>

    <?php if (count($requests) == 0): ?>
        <p>No requests found where you are the receiver.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr style="background-color:#f0f0f0;">
                <th>ID</th>
                <th>Sender</th>
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo $req['id']; ?></td>
                    <td><?php echo htmlspecialchars($req['sender_name']); ?></td>
                    <td><?php echo htmlspecialchars($req['out_location']); ?></td>
                    <td><?php echo htmlspecialchars($req['in_location']); ?></td>
                    <td>
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
                        ?>
                    </td>
                    <td><?php echo date('Y-m-d', strtotime($req['created_at'])); ?></td>
                    <td>
                        <a href="view_request.php?id=<?php echo $req['id']; ?>" class="btn-action btn-edit">View</a>
                        
                        <?php if ($req['status'] === 'dispatched'): ?>
                            <form action="../process/confirm_received_process.php" method="POST" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <input type="submit" value="Confirm Received" onclick="return confirm('Are you sure you received this?');" style="margin-top: 8px;">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
