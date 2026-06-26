<?php
require '../includes/functions.php';
requireAdmin();
require '../config/db.php';

if ($_POST) {
    $userId = $_POST['user_id'];
    $status = $_POST['status'];
    $pdo->prepare("UPDATE users SET status = ? WHERE id = ?")->execute([$status, $userId]);
    
    if ($status === 'approved') {
        // Ensure milk plan exists
        $pdo->prepare("INSERT IGNORE INTO milk_plans (user_id, default_quantity) VALUES (?, 1.00)")->execute([$userId]);
    }
}
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Customers</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$stmt = $pdo->query("SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC");
while ($user = $stmt->fetch()): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <span class="badge <?= $user['status'] == 'approved' ? 'bg-success' : 'bg-warning' ?>">
                    <?= ucfirst($user['status']) ?>
                </span>
            </td>
            <td>
                <?php if ($user['status'] !== 'approved'): ?>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Approve</button>
                    <button type="submit" name="status" value="rejected" class="btn btn-sm btn-danger">Reject</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
<?php endwhile; ?>
    </tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
<?php include '../includes/footer.php'; ?>

