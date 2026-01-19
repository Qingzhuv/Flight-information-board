<?php
$conn = new mysqli('localhost', 'flight_system', 'flight_system');
$conn->query("CREATE DATABASE IF NOT EXISTS flight_system CHARACTER SET utf8mb4");
$conn->select_db('flight_system');

// 航班表
$conn->query("CREATE TABLE IF NOT EXISTS flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    f_date DATE, f_no VARCHAR(20), f_dest VARCHAR(100), 
    f_time TIME, f_status VARCHAR(20), f_gate VARCHAR(10)
)");

// 用户表
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
)");

// 创建默认用户 admin / 123456
$pass = password_hash('123456', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password) VALUES ('admin', '$pass')");

echo "系统初始化完成！默认账号：admin 密码：123456";
?>
