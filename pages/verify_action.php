<link rel="stylesheet" href="../css/executive_action.css">

<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

if ($_SESSION['role_id'] != 3) { // Duty Officer
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

$request_id = $_GET['id'] ?? 0;

// Fetch request details
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
    WHERE r.id = ? AND r.status = 'pending_duty_officer'
");
$stmt->execute([$request_id]);
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

<h2>Verify Request - ID #<?php echo $request_id; ?></h2>

<h3>Sender Details</h3>
<p><strong>Name:</strong> <?php echo htmlspecialchars($request['sender_name']); ?></p>
<p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['sender_work_location']); ?></p>

<h3>Receiver Details</h3>
<p><strong>Name:</strong> <?php echo htmlspecialchars($request['receiver_name']); ?></p>
<p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['receiver_work_location']); ?></p>

<h3>Transport Details</h3>
<p><strong>Method:</strong> <?php echo ucfirst($request['transport_method']); ?></p>

<?php if ($request['transport_method'] === 'person'): ?>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($request['person_name']); ?></p>
    <p><strong>NIC:</strong> <?php echo htmlspecialchars($request['person_nic']); ?></p>
<?php else: ?>
    <p><strong>Driver:</strong> <?php echo htmlspecialchars($request['driver_name']); ?></p>
    <p><strong>Vehicle No:</strong> <?php echo htmlspecialchars($request['vehicle_no']); ?></p>
<?php endif; ?>

<h3>Item Details</h3>
<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Returnable</th>
            <th>Status</th>
            <th>Photos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['is_returnable'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo htmlspecialchars($item['item_status']); ?></td>
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
    </tbody>
</table>

<h3>Dispatch Checker Details</h3>
<form action="../process/verify_action_process.php" method="POST" id="verifyForm">
    <label for="dispatch_checker_name">Full Name:</label>
    <input type="text" name="dispatch_checker_name" id="dispatch_checker_name" required>

    <label for="dispatch_checker_service_no">Service Number:</label>
    <input type="text" name="dispatch_checker_service_no" id="dispatch_checker_service_no" required>

    <label for="dispatch_checker_nic">NIC No.:</label>
    <input type="text" name="dispatch_checker_nic" id="dispatch_checker_nic" required>

    <label for="dispatch_checker_contact">Contact Number:</label>
    <input type="text" name="dispatch_checker_contact" id="dispatch_checker_contact" required>


<h3>Action</h3>
<form action="../process/verify_action_process.php" method="POST" id="verifyForm">
    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

    <label>Action:</label>
    <select name="action" id="action" required>
        <option value="">-- Select --</option>
        <option value="verified">Verify</option>
        <option value="rejected_by_duty">Reject</option>
    </select>

    <label>Comments (required if rejected):</label>
    <textarea name="comments" id="comments" rows="4"></textarea>

    <input type="submit" value="Submit">
</form>

<p><a href="verify_pending.php">Back to Pending List</a></p>

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

<!-- Comment required if rejected -->
<script>
document.getElementById('verifyForm').addEventListener('submit', function (e) {
    const action = document.getElementById('action').value;
    const comments = document.getElementById('comments').value.trim();

    if (action === 'rejected_by_duty' && comments === '') {
        alert("Comments are required when rejecting.");
        e.preventDefault(); // stop form from submitting
    }
});
</script>

<?php include("../includes/footer.php"); ?>
