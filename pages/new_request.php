<link rel="stylesheet" href="../css/new_request.css">
<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Fetch Executive Officers
$executives = $pdo->query("SELECT id, full_name FROM users WHERE role_id = 2")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Locations
$locations = $pdo->query("SELECT id, location_name FROM locations")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Sender (Logged-in user) Details
$sender_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.full_name, u.work_location, u.contact_number, 
    CASE u.role_id
        WHEN 1 THEN 'User'
        WHEN 2 THEN 'Executive Officer'
        WHEN 3 THEN 'Duty Officer / Verifier'
        WHEN 4 THEN 'Admin'
        ELSE 'Unknown'
    END AS role_name
FROM users u WHERE u.id = ?");
$stmt->execute([$sender_id]);
$sender = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<h2>New Gate Pass Request</h2>

<form action="../process/new_request_process.php" method="POST" enctype="multipart/form-data" id="requestForm">

    <!-- Sender Details -->
    <div class="section-box">
        <h3>Sender Details</h3>
        <input type="hidden" name="sender_user_id" value="<?php echo $sender_id; ?>">

        <label>Sender Name:</label>
        <input type="text" name="sender_name" value="<?= htmlspecialchars($sender['full_name']) ?>" readonly required>

        <label>Working Location:</label>
        <input type="text" name="sender_work_location" value="<?= htmlspecialchars($sender['work_location']) ?>" readonly required>

        <label>Role:</label>
        <input type="text" name="sender_role" value="<?= htmlspecialchars($sender['role_name']) ?>" readonly required>

        <label>Contact Number:</label>
        <input type="text" name="sender_contact" value="<?= htmlspecialchars($sender['contact_number']) ?>" readonly required>
    </div>

    <!-- Receiver Details -->
    <div class="section-box">
        <h3>Receiver Details</h3>
        <label>Receiver User ID:</label>
        <input type="text" name="receiver_user_id" id="receiver_user_id" required>

        <label>Receiver Name:</label>
        <input type="text" name="receiver_name" id="receiver_name" readonly required>

        <label>Working Location:</label>
        <input type="text" name="receiver_work_location" id="receiver_work_location" readonly required>

        <label>Role:</label>
        <input type="text" name="receiver_role" id="receiver_role" readonly required>

        <label>Contact Number:</label>
        <input type="text" name="receiver_contact" id="receiver_contact" readonly required>
    </div>

    <!-- Item Details -->
    <div class="section-box">
        <h3>Item Details</h3>
        <table id="itemsTable" border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Serial No</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Returnable</th>
                    <th>Item Photos (Max 5)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button type="button" id="addItemBtn">Add Item</button>
    </div>

    <!-- Transport Details -->
    <div class="section-box">
        <h3>Transport Details</h3>
        <label>Transport Method:</label>
        <input type="radio" name="transport_method" value="person" checked> By Person
        <input type="radio" name="transport_method" value="vehicle"> By Vehicle

        <div id="by_person">
            <label>Person Name:</label>
            <input type="text" name="person_name" required>
            <label>Address:</label>
            <input type="text" name="person_address" required>
            <label>NIC:</label>
            <input type="text" name="person_nic" required>
            <label>Contact Number:</label>
            <input type="text" name="person_contact" required>
        </div>

        <div id="by_vehicle" style="display: none;">
            <label>Driver Name:</label>
            <input type="text" name="driver_name" required>
            <label>Vehicle No:</label>
            <input type="text" name="vehicle_no" required>
            <label>Contact Number:</label>
            <input type="text" name="vehicle_contact" required>
        </div>
    </div>

    <!-- Executive Approval -->
    <div class="section-box">
        <h3>Executive Approval</h3>
        <label>Select Executive:</label>
        <select name="executive_id" required>
            <option value="">-- Select Executive --</option>
            <?php foreach ($executives as $exec): ?>
                <option value="<?= $exec['id']; ?>"><?= htmlspecialchars($exec['full_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- In & Out Locations -->
    <div class="section-box">
        <h3>In Location</h3>
        <label>Select In Location:</label>
        <select name="in_location_id" required>
            <option value="">-- Select In Location --</option>
            <?php foreach ($locations as $loc): ?>
                <option value="<?= $loc['id']; ?>"><?= htmlspecialchars($loc['location_name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <h3>Out Location</h3>
        <label>Select Out Location:</label>
        <select name="out_location_id" required>
            <option value="">-- Select Out Location --</option>
            <?php foreach ($locations as $loc): ?>
                <option value="<?= $loc['id']; ?>"><?= htmlspecialchars($loc['location_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <input type="submit" value="Submit Request">
</form>

<!-- JavaScript -->
<script>
$(document).ready(function () {
    let itemIndex = 0;

    $('#addItemBtn').click(function () {
        const row = `
        <tr>
            <td><input type="text" name="serial_no[]" required></td>
            <td><input type="text" name="item_name[]" required></td>
            <td><input type="number" name="quantity[]" min="1" required></td>
            <td>
                <select name="is_returnable[]" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </td>
            <td>
                ${[0,1,2,3,4].map(i => `<input type="file" name="item_photos[${itemIndex}][]" accept="image/*">`).join('<br>')}
            </td>
            <td><button type="button" class="removeItemBtn">Remove</button></td>
        </tr>`;
        $('#itemsTable tbody').append(row);
        itemIndex++;
    });

    $(document).on('click', '.removeItemBtn', function () {
        $(this).closest('tr').remove();
    });

    $('input[name="transport_method"]').on('change', function () {
        if ($(this).val() === 'person') {
            $('#by_person').show();
            $('#by_vehicle').hide();
        } else {
            $('#by_person').hide();
            $('#by_vehicle').show();
        }
    });

    $('#receiver_user_id').on('blur', function () {
        $.post('../process/get_user_details.php', { user_id: $(this).val() }, function (data) {
            if (data.success) {
                $('#receiver_name').val(data.full_name);
                $('#receiver_work_location').val(data.work_location);
                $('#receiver_role').val(data.role);
                $('#receiver_contact').val(data.contact_number);
            }
        }, 'json');
    });

    $('#requestForm').on('submit', function (e) {
        if ($('#itemsTable tbody tr').length === 0) {
            alert("Please add at least one item.");
            e.preventDefault();
        }
    });
});

function toggleTransportValidation() {
    const isPerson = $('input[name="transport_method"]:checked').val() === 'person';

    // Toggle visibility
    $('#by_person').toggle(isPerson);
    $('#by_vehicle').toggle(!isPerson);

    // Toggle required attributes
    $('#by_person input').prop('required', isPerson);
    $('#by_vehicle input').prop('required', !isPerson);
}

// Initial call
toggleTransportValidation();

// Change event
$('input[name="transport_method"]').on('change', function () {
    toggleTransportValidation();
});

</script>

<?php include("../includes/footer.php"); ?>
