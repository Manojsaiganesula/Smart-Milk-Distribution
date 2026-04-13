<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../index.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../customer/dashboard.php');
        exit;
    }
}

function requireCustomer() {
    requireLogin();
    if ($_SESSION['role'] !== 'customer') {
        header('Location: ../admin/dashboard.php');
        exit;
    }
}

function getUser($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function login($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status IN ('approved', 'pending')");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function registerCustomer($pdo, $name, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Check if email exists first
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        return false;
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'customer', 'pending')");
    $stmt->execute([$name, $email, $hash]);
    $userId = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO milk_plans (user_id, default_quantity) VALUES (?, 1.00)")->execute([$userId]);
    return true;
}

function getTotalMilk($pdo) {
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(CASE 
            WHEN du.quantity IS NOT NULL THEN du.quantity 
            ELSE mp.default_quantity 
        END), 0) as total
        FROM users u 
        LEFT JOIN milk_plans mp ON u.id = mp.user_id
        LEFT JOIN daily_updates du ON u.id = du.user_id AND du.update_date = ?
        WHERE u.status = 'approved' AND u.role = 'customer'
    ");
    $stmt->execute([$tomorrow]);
    return $stmt->fetchColumn();
}
?>

