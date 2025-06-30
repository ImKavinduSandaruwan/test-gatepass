<link rel="stylesheet" href="../css/dashboard.css">
<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Count Total Requests
$stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_requests = $stmt->fetchColumn();

// Count Executive Approved
$stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$_SESSION['user_id']]);
$executive_approved = $stmt->fetchColumn();

// Count Duty Officer Verified
$stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE user_id = ? AND status = 'verified'");
$stmt->execute([$_SESSION['user_id']]);
$duty_officer_verified = $stmt->fetchColumn();

// Count Dispatched Items (Items Table → item_status = 'dispatched')
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM items 
    WHERE request_id IN (SELECT id FROM requests WHERE user_id = ?) 
    AND item_status = 'dispatched'
");
$stmt->execute([$_SESSION['user_id']]);
$dispatched_count = $stmt->fetchColumn();

// Count Received Items (Items Table → item_status = 'received')
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM items 
    WHERE request_id IN (SELECT id FROM requests WHERE user_id = ?) 
    AND item_status = 'received'
");
$stmt->execute([$_SESSION['user_id']]);
$received_count = $stmt->fetchColumn();
?>

<div class="modern-dashboard">
    <h1>Welcome back, <?php echo $_SESSION['full_name']; ?></h1>
    <p class="modern-subtitle">Here is an overview of your activity:</p>

    <div class="modern-cards">
        <!-- Total Requests -->
        <div class="modern-card">
            <h3>Total Requests</h3>
            <p class="modern-card-number"><?php echo $total_requests; ?></p>
        </div>

        <!-- Executive Approval -->
        <div class="modern-card">
            <h3>Executive Approved</h3>
            <p class="modern-card-number"><?php echo $executive_approved; ?></p>
        </div>

        <!-- Duty Officer Verified -->
        <div class="modern-card">
            <h3>Duty Officer Verified</h3>
            <p class="modern-card-number"><?php echo $duty_officer_verified; ?></p>
        </div>

        <!-- Dispatched -->
        <div class="modern-card">
            <h3>Dispatched Items</h3>
            <p class="modern-card-number"><?php echo $dispatched_count; ?></p>
        </div>

        <!-- Received -->
        <div class="modern-card">
            <h3>Received Items</h3>
            <p class="modern-card-number"><?php echo $received_count; ?></p>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
