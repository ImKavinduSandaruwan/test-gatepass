<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Get request ID
$request_id = $_GET['id'] ?? 0;

// Fetch request details
$stmt = $pdo->prepare("
    SELECT r.*, 
        u1.full_name AS sender_name,
        u2.full_name AS executive_name
    FROM requests r
    JOIN users u1 ON r.user_id = u1.id
    JOIN users u2 ON r.executive_id = u2.id
    WHERE r.id = ?
");
$stmt->execute([$request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    echo "<p>Invalid Request.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch items
$stmt = $pdo->prepare("SELECT * FROM items WHERE request_id = ?");
$stmt->execute([$request_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all executives
$stmtExec = $pdo->prepare("SELECT id, full_name FROM users WHERE role_id = 2");
$stmtExec->execute();
$executives = $stmtExec->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link CSS -->
<link rel="stylesheet" href="../css/view_request.css">
<link rel="stylesheet" href="../css/badges.css">

<h2>View Gate Pass - ID #<?php echo htmlspecialchars($request_id); ?></h2>
<button class="btn" onclick="printGatePass()">Print Gate Pass</button>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'executive_changed'): ?>
    <p style="color: green;">Executive Officer changed successfully.</p>
<?php endif; ?>

<div class="card">
    <div class="card-header">Sender Details</div>
    <p><strong>User ID:</strong> <?php echo htmlspecialchars($request['user_id']); ?></p>
    <p><strong>Sender Name:</strong> <?php echo htmlspecialchars($request['sender_name']); ?></p>
    <p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['sender_work_location']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($request['sender_role']); ?></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['sender_contact_number']); ?></p>
</div>

<div class="card">
    <div class="card-header">Receiver Details</div>
    <p><strong>User ID:</strong> <?php echo htmlspecialchars($request['receiver_user_id']); ?></p>
    <p><strong>Receiver Name:</strong> <?php echo htmlspecialchars($request['receiver_name']); ?></p>
    <p><strong>Work Location:</strong> <?php echo htmlspecialchars($request['receiver_work_location']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($request['receiver_role']); ?></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['receiver_contact_number']); ?></p>
</div>

<div class="card">
    <div class="card-header">Transport Details</div>
    <p><strong>Transport Method:</strong> <?php echo ucfirst(htmlspecialchars($request['transport_method'])); ?></p>

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
</div>

<div class="card">
    <div class="card-header">Locations</div>
    <p><strong>Out Location:</strong> 
        <?php
        $stmtLoc = $pdo->prepare("SELECT location_name FROM locations WHERE id = ?");
        $stmtLoc->execute([$request['out_location_id']]);
        echo htmlspecialchars($stmtLoc->fetchColumn());
        ?>
    </p>
    <p><strong>In Location:</strong> 
        <?php
        $stmtLoc = $pdo->prepare("SELECT location_name FROM locations WHERE id = ?");
        $stmtLoc->execute([$request['in_location_id']]);
        echo htmlspecialchars($stmtLoc->fetchColumn());
        ?>
    </p>
</div>

<div class="card">
    <div class="card-header">Executive Officer</div>
    <p><strong>Current Executive Name:</strong> <?php echo htmlspecialchars($request['executive_name']); ?></p>

    <?php if (in_array($_SESSION['role_id'], [1, 2, 3, 4]) && $request['status'] == 'pending'): ?>
        <form action="../process/change_executive_process.php" method="POST" style="margin-top: 10px;">
            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

            <label for="new_executive_id">Change Executive Officer:</label>
            <select name="new_executive_id" id="new_executive_id" required>
                <option value="">-- Select Executive --</option>
                <?php foreach ($executives as $exec): ?>
                    <option value="<?php echo $exec['id']; ?>" 
                        <?php if ($exec['id'] == $request['executive_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($exec['full_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <input type="submit" value="Change Executive" class="btn">
        </form>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">Request Status</div>
    <p><strong>Status:</strong>
    <?php
    switch ($request['status']) {
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
            echo '<span class="status-badge">' . ucfirst($request['status']) . '</span>';
    }
    ?>
</p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($request['created_at']); ?></p>
    <p><strong>Updated At:</strong> <?php echo htmlspecialchars($request['updated_at']); ?></p>
</div>

<div class="card">
    <div class="card-header">Item Details</div>
    <table>
        <tr>
            <th>Serial No</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Returnable</th>
            <th>Item Status</th>
            <th>Item Photos</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['serial_no']); ?></td>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo $item['is_returnable'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo ucfirst(htmlspecialchars($item['item_status'])); ?></td>
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
</div>

<a href="my_requests.php" class="btn btn-outline">Back to My Requests</a>

<script>
function printGatePass() {
    window.print();
}

function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>

<!-- Image Modal -->
<div id="imageModal" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.9); justify-content:center; align-items:center;">
    <span onclick="closeModal()" style="position:absolute; top:20px; right:40px; font-size:40px; color:white; cursor:pointer;">&times;</span>
    <img id="modalImage" src="" style="max-width:90%; max-height:90%;">
</div>

<?php include("../includes/footer.php"); ?>
