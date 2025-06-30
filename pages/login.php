<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SLT Gate Pass System</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" href="../favicon.png" type="image/png">
</head>
<body class="alt-login-body">

<div class="alt-login-container">
    <div class="alt-login-card">
        <img src="../images/slt_logo.png" alt="SLT Mobitel Logo" class="login-logo">

        <h2>Sign In</h2>
        <p class="alt-login-subtitle">Please enter your credentials to access your account</p>

        <form action="../process/login_process.php" method="POST">
            <label>User ID</label>
            <input type="text" name="username" placeholder="Your User ID" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Your Password" required>

            <div style="text-align: right; margin-bottom: 10px;">
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <input type="submit" value="Sign In" class="alt-login-button">
        </form>

        <p class="alt-login-footer">
            Don’t have an account? <a href="#" class="contact-link">Contact administrator</a>
        </p>
    </div>
</div>

</body>
</html>
