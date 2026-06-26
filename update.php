<?php
require '../includes/functions.php';
requireCustomer();
require '../config/db.php';

$tomorrow = date('Y-m-d', strtotime('+1 day'));
$cutoffHour = 20; // 8PM
$isCutoff = (int)date('H') >= $cutoffHour;

$message = '';
if ($_POST && !$isCutoff) {
    $quantity = $_POST['quantity'] ?: null;
    $status = $_POST['status'];
    $notes = trim($_POST['notes']);

    $stmt = $pdo->prepare("INSERT INTO daily_updates (user_id, update_date, quantity, status, notes) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity=VALUES(quantity), status=VALUES(status), notes=VALUES(notes)");
    if ($stmt->execute([$_SESSION['user_id'], $tomorrow, $quantity, $status, $notes])) {
        $message = '<div class="alert alert-success">Updated successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Update failed.</div>';
    }
}

$stmt = $pdo->prepare("SELECT * FROM daily_updates WHERE user_id = ? AND update_date = ?");
$stmt->execute([$_SESSION['user_id'], $tomorrow]);
$update = $stmt->fetch();
?>
<?php include '../includes/header.php'; ?>
<h2>Update Tomorrow\'s Milk</h2>
<?php if ($isCutoff): ?>
<div class="alert alert-warning">Updates closed after 8PM. Opens tomorrow 12AM.</div>
<a href="dashboard.php" class="btn btn-secondary">Back</a>
<?php else: ?>
<?= $message ?>
<form id="updateForm" method="POST">
    <div class="mb-3">
        <label>Quantity (leave blank for default)</label>
        <input type="number" name="quantity" class="form-control" step="0.25" min="0" value="<?= $update['quantity'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
            <option value="normal" <?= ($update['status'] ?? '') == 'normal' ? 'selected' : '' ?>>Normal</option>
            <option value="skip" <?= ($update['status'] ?? '') == 'skip' ? 'selected' : '' ?>>Skip ❌</option>
            <option value="reduce" <?= ($update['status'] ?? '') == 'reduce' ? 'selected' : '' ?>>Reduce ➖</option>
            <option value="increase" <?= ($update['status'] ?? '') == 'increase' ? 'selected' : '' ?>>Increase ➕</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control"><?= $update['notes'] ?? '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="dashboard.php" class="btn btn-secondary">Back</a>
</form>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>

