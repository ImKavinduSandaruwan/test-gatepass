<!DOCTYPE html>
<html>
<head>
    <title>Gate Pass Management System</title>
    <link rel="stylesheet" href="../css/header.css"> <!-- Link to header.css -->
    <link rel="icon" href="../favicon.png" type="image/png">
</head>
<body>

<nav class="beautiful-navbar">
    <div class="nav-left">
        <img src="../images/slt_logo.png" alt="SLT Logo" class="nav-logo">
        <!-- <span class="nav-title">SLT Gate Pass System</span> -->
    </div>

    <div class="nav-right">
        <a href="../pages/dashboard.php">Dashboard</a>

        <?php if ($_SESSION['role_id'] == 1): ?>
            <a href="../pages/new_request.php">New Request</a>
            <a href="../pages/my_requests.php">My Requests</a>
            <a href="../pages/returnable-items.php">Returnable Items</a>
            <a href="../pages/receiver_request.php">Receive</a>

        <?php elseif ($_SESSION['role_id'] == 2): ?>
            <a href="../pages/new_request.php">New Request</a>
            <a href="../pages/my_requests.php">My Requests</a>
            <a href="../pages/executive_pending.php">Pending Requests</a>
            <a href="../pages/executive_approved.php">Approved Requests</a>
            <a href="../pages/executive_rejected.php">Rejected Requests</a>
            <!--<a href="../pages/returnable-items.php">Returnable Items</a> -->
            <a href="../pages/executive_history.php">History</a>
            <a href="../pages/receiver_request.php">Receive</a>

        <?php elseif ($_SESSION['role_id'] == 3): ?>
            <a href="../pages/new_request.php">New Request</a>
            <a href="../pages/my_requests.php">My Requests</a>
            <a href="../pages/verify_pending.php">Pending Requests</a>
            <a href="../pages/verify_verified.php">Verified Requests</a>
            <a href="../pages/verify_rejected.php">Rejected Requests</a>
            <!--<a href="../pages/returnable-items.php">Returnable Items</a> -->
            <a href="../pages/duty_history.php">History</a>
            <a href="../pages/receiver_request.php">Receive</a>

        <?php elseif ($_SESSION['role_id'] == 4): ?>
            <a href="../pages/new_request.php">New Request</a>
            <a href="../pages/my_requests.php">My Requests</a>
            <a href="../pages/create_user.php">Add User</a>
            <a href="../pages/admin.php">Admin Panel</a>
            <a href="../pages/admin_history.php">History</a>

        <?php endif; ?>

        <a href="../logout.php" class="nav-logout">Logout</a>
    </div>
</nav>

<div class="content">
