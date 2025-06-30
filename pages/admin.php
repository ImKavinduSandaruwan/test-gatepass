<link rel="stylesheet" href="../css/admin.css">

<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

// Only Admin (role_id = 4)
if ($_SESSION['role_id'] != 4) {
    echo "<p>Access Denied.</p>";
    include("../includes/footer.php");
    exit();
}

// Fetch Locations
$locations = $pdo->query("SELECT * FROM locations")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Page - Manage Master Data</h2>

<!-- Manage Locations -->
<div class="card">
    <h2>Manage Locations</h2>
        <form action="../process/admin_process.php" method="POST">
            <input type="hidden" name="action" value="add_location">
            <input type="text" name="location_name" placeholder="New Location Name" required>
            <input type="submit" value="Add Location">
        </form>

        <table border="1" cellpadding="10" cellspacing="0" width="50%">
            <tr style="background-color:#f0f0f0;">
                <th>ID</th>
                <th>Location Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($locations as $loc): ?>
                <tr>
                    <td><?php echo $loc['id']; ?></td>
                    <td><?php echo htmlspecialchars($loc['location_name']); ?></td>
                    <td>
                        <form action="../process/admin_process.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_location">
                            <input type="hidden" name="location_id" value="<?php echo $loc['id']; ?>">
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
</div>

<!-- Manage Categories -->
<div class="card">
    <h2>Manage Categories</h2>
        <form action="../process/admin_process.php" method="POST">
            <input type="hidden" name="action" value="add_category">
            <input type="text" name="category_name" placeholder="New Category Name" required>
            <input type="submit" value="Add Category">
        </form>

        <table border="1" cellpadding="10" cellspacing="0" width="50%">
            <tr style="background-color:#f0f0f0;">
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                    <td>
                        <form action="../process/admin_process.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_category">
                            <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
</div>

<?php include("../includes/footer.php"); ?>
