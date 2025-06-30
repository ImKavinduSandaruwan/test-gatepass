<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

$message = "";

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);
    $message = "User deleted successfully!";
}

// Handle Update
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $work_location = $_POST['work_location'];
    $contact_number = $_POST['contact_number'];

    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, role_id = ?, work_location = ?, contact_number = ? WHERE id = ?");
    $stmt->execute([$full_name, $email, $role_id, $work_location, $contact_number, $user_id]);

    $message = "User updated successfully!";
}

// Handle Create
if (isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $work_location = $_POST['work_location'];
    $contact_number = $_POST['contact_number'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, role_id, work_location, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $full_name, $email, $role_id, $work_location, $contact_number]);

    $message = "User created successfully!";
}

// Handle Search
$search = $_GET['search'] ?? '';
if ($search != '') {
    $search_term = "%$search%";
    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE full_name LIKE ? 
           OR email LIKE ? 
           OR work_location LIKE ? 
           OR role_id IN (
                CASE 
                    WHEN ? LIKE '%user%' THEN 1
                    WHEN ? LIKE '%executive%' THEN 2
                    WHEN ? LIKE '%duty%' THEN 3
                    WHEN ? LIKE '%admin%' THEN 4
                    ELSE -1
                END
            )
        ORDER BY id ASC
    ");
    $stmt->execute([$search_term, $search_term, $search_term, $search, $search, $search, $search]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id ASC");
    $stmt->execute();
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If editing
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="../css/create_user.css">

<div class="create-user-container">

    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <div class="card">
        <h2>Search Users</h2>
        <form method="GET" action="create_user.php" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Search by Name, Email, Branch, Role" value="<?php echo htmlspecialchars($search); ?>" style="width: 341px; padding: 12px;">
            <input type="submit" value="Search" style="padding: 12px 16px;">
            <?php if ($search != ''): ?>
                <a href="create_user.php" style="margin-left: 10px;">Clear Search</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 30px;">Create New User</h2>

            <form action="" method="POST" class="create-user-form">
                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <label>Full Name:</label>
                <input type="text" name="full_name" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Role:</label>
                <select name="role_id" required>
                    <option value="1">User</option>
                    <option value="2">Executive Officer</option>
                    <option value="3">Duty Officer / Verifier</option>
                    <option value="4">Admin</option>
                </select>

                <label>Branch (Work Location):</label>
                <input type="text" name="work_location" required>

                <label>Contact Number:</label>
                <input type="text" name="contact_number" required>

                <input type="submit" name="create_user" value="Create User">
            </form>
        </div>
    </div>

    <br><br>

    <div class="create-user-container2">
        <div class="card">
            <h2>Existing Users</h2>

            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tr style="background-color:#f0f0f0;">
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td>
                            <?php
                            switch ($user['role_id']) {
                                case 1: echo "User"; break;
                                case 2: echo "Executive Officer"; break;
                                case 3: echo "Duty Officer / Verifier"; break;
                                case 4: echo "Admin"; break;
                                default: echo "Unknown"; break;
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['work_location']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a href="create_user.php?edit_id=<?php echo $user['id']; ?>">Update</a> |
                            <a href="create_user.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="create-user-container2">
            <?php if ($edit_user): ?>
                <h2 style="margin-top: 30px;">Update User - ID #<?php echo $edit_user['id']; ?></h2>

                <form action="" method="POST" class="create-user-form">
                    <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">

                    <label>Full Name:</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($edit_user['full_name']); ?>" required>

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>

                    <label>Role:</label>
                    <select name="role_id" required>
                        <option value="1" <?php if ($edit_user['role_id'] == 1) echo 'selected'; ?>>User</option>
                        <option value="2" <?php if ($edit_user['role_id'] == 2) echo 'selected'; ?>>Executive Officer</option>
                        <option value="3" <?php if ($edit_user['role_id'] == 3) echo 'selected'; ?>>Duty Officer / Verifier</option>
                        <option value="4" <?php if ($edit_user['role_id'] == 4) echo 'selected'; ?>>Admin</option>
                    </select>

                    <label>Branch (Work Location):</label>
                    <input type="text" name="work_location" value="<?php echo htmlspecialchars($edit_user['work_location']); ?>" required>

                    <label>Contact Number:</label>
                    <input type="text" name="contact_number" value="<?php echo htmlspecialchars($edit_user['contact_number']); ?>" required>

                    <input type="submit" name="update_user" value="Update User">
                </form>

                <p><a href="create_user.php">Cancel Edit</a></p>
            </div>

    <?php else: ?>
        
    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
