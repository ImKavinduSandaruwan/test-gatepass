<link rel="stylesheet" href="../css/executive_action.css">

<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

if ($_SESSION['role_id'] != 2) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

$request_id = $_GET['id'] ?? 0;

// Fetch request
$stmt = $pdo->prepare("
    SELECT r.*, 
        u1.full_name AS sender_name,
        u2.full_name AS receiver_name,
        l1.location_name AS out_location,
        l2.location_name AS in_location
    FROM requests r
    JOIN users u1 ON r.user_id = u1.id
    LEFT JOIN users u2 ON r.receiver_user_id = u2.id
    LEFT JOIN locations l1 ON r.out_location_id = l1.id
    LEFT JOIN locations l2 ON r.in_location_id = l2.id
    WHERE r.id = ? AND r.executive_id = ? AND r.status = 'pending'
");
$stmt->execute([$request_id, $_SESSION['user_id']]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    echo "<p>Invalid request or already processed.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch items
$stmt = $pdo->prepare("SELECT * FROM items WHERE request_id = ?");
$stmt->execute([$request_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Approve / Reject Request - ID #<?php echo $request_id; ?></h2>

<!-- Full Details View -->

<h3>Sender Details</h3>
<p><strong>User ID:</strong> <?php echo htmlspecialchars($request['user_id']); ?></p>
<p><strong>Sender Name:</strong> <?php echo htmlspecialchars($request['sender_name']); ?></p>
<p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['sender_work_location']); ?></p>
<p><strong>Role:</strong> <?php echo htmlspecialchars($request['sender_role']); ?></p>
<p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['sender_contact_number']); ?></p>

<h3>Receiver Details</h3>
<p><strong>User ID:</strong> <?php echo htmlspecialchars($request['receiver_user_id']); ?></p>
<p><strong>Receiver Name:</strong> <?php echo htmlspecialchars($request['receiver_name']); ?></p>
<p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['receiver_work_location']); ?></p>
<p><strong>Role:</strong> <?php echo htmlspecialchars($request['receiver_role']); ?></p>
<p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['receiver_contact_number']); ?></p>

<h3>Transport Details</h3>
<p><strong>Transport Method:</strong> <?php echo ucfirst($request['transport_method']); ?></p>

<?php if ($request['transport_method'] == 'person'): ?>
    <p><strong>Person Name:</strong> <?php echo htmlspecialchars($request['person_name']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($request['person_address']); ?></p>
    <p><strong>NIC Number:</strong> <?php echo htmlspecialchars($request['person_nic']); ?></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['person_contact']); ?></p>
<?php else: ?>
    <p><strong>Driver Name:</strong> <?php echo htmlspecialchars($request['driver_name']); ?></p>
    <p><strong>Vehicle No:</strong> <?php echo htmlspecialchars($request['vehicle_no']); ?></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['vehicle_contact']); ?></p>
<?php endif; ?>

<h3>Locations</h3>
<p><strong>Out Location:</strong> <?php echo htmlspecialchars($request['out_location']); ?></p>
<p><strong>In Location:</strong> <?php echo htmlspecialchars($request['in_location']); ?></p>

<h3>Item Details</h3>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr style="background-color:#f0f0f0;">
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Returnable</th>
        <th>Item Status</th>
        <th>Item Photos</th>
    </tr>

    <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['is_returnable'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo ucfirst($item['item_status']); ?></td>
            <td>
                <?php
                $stmtImgs = $pdo->prepare("SELECT image_path FROM item_images WHERE item_id = ?");
                $stmtImgs->execute([$item['id']]);
                $images = $stmtImgs->fetchAll(PDO::FETCH_COLUMN);

                if (count($images) > 0):
                    foreach ($images as $imgPath):
                ?>
                    <button onclick="openModal('../uploads/items/<?php echo htmlspecialchars($imgPath); ?>')">View</button>
                <?php
                    endforeach;
                else:
                    echo "No photos";
                endif;
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Approve / Reject Form -->

<h3>Action</h3>

<form action="../process/executive_action_process.php" method="POST" id="approveRejectForm">
    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

    <label>Action:</label>
    <select name="action" id="action" required>
        <option value="">-- Select --</option>
        <option value="approved">Approve</option>
        <option value="rejected">Reject</option>
    </select>

    <label>Comments:</label>
    <textarea name="comments" id="comments" rows="4" cols="50"></textarea>

    <br>
    <input type="submit" value="Submit">
</form>

<p><a href="executive_pending.php">Back to Pending Requests</a></p>

<!-- JS to enforce comment required if rejected -->
<script>
document.getElementById('approveRejectForm').addEventListener('submit', function(e) {
    var action = document.getElementById('action').value;
    var comments = document.getElementById('comments').value.trim();

    if (action === 'rejected' && comments === '') {
        alert('Comments are required when rejecting a request.');
        e.preventDefault();
    }
});
</script>

<!-- Image Modal -->
<div id="imageModal" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.9); justify-content:center; align-items:center;">
    <span onclick="closeModal()" style="position:absolute; top:20px; right:40px; font-size:40px; color:white; cursor:pointer;">&times;</span>
    <img id="modalImage" src="" style="max-width:90%; max-height:90%;">
</div>

<script>
function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>

<?php include("../includes/footer.php"); ?>
