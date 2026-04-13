<?php
$host = 'localhost';
$username = 'root';
$password = ''; // XAMPP default

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Auto-create database and tables if not exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS milk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE milk_db");
    
    // Tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'customer') DEFAULT 'customer',
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    
$pdo->exec("CREATE TABLE IF NOT EXISTS milk_plans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        milk_type ENUM('cow', 'buffalo') DEFAULT 'cow',
        default_quantity DECIMAL(4,2) DEFAULT 1.00,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    
$pdo->exec("CREATE TABLE IF NOT EXISTS daily_updates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        milk_type ENUM('cow', 'buffalo') DEFAULT 'cow',
        update_date DATE NOT NULL,
        quantity DECIMAL(4,2) DEFAULT NULL,
        status ENUM('skip', 'reduce', 'increase', 'normal') DEFAULT 'normal',
        notes TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_update (user_id, milk_type, update_date),
        INDEX idx_date (update_date)
    ) ENGINE=InnoDB");
    
    // Admin user
    $adminHash = password_hash('101723', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO users (name, email, password, role, status) VALUES 
        ('Ganesula Manoj Sai', 'ganesula.manojsai@gmail.com', '$adminHash', 'admin', 'approved') 
        ON DUPLICATE KEY UPDATE name='Ganesula Manoj Sai', password='$adminHash'");

    
} catch(PDOException $e) {
    die("DB Setup failed: " . $e->getMessage());
}
?>


