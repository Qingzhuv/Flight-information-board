<?php
/**
 * 航班信息系统 - 数据库初始化脚本
 * 由 WESTCRAN西鹤软件 提供技术支持
 */

$host = 'localhost';
$user = 'flight_system'; 
$pass = 'flight_system'; 
$db   = 'flight_system';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die("数据库连接失败");

$conn->query("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4");
$conn->select_db($db);

// 航班表
$conn->query("DROP TABLE IF EXISTS flights"); 
$conn->query("CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    f_date DATE NOT NULL,
    f_no VARCHAR(20) NOT NULL,
    f_dest VARCHAR(100) NOT NULL,
    f_time TIME NOT NULL,
    f_status ENUM('准点', '登机中', '延误', '取消') DEFAULT '准点',
    f_gate VARCHAR(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 管理员表
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
)");

$default_pass = password_hash('123456', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password) VALUES ('admin', '$default_pass')");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>系统初始化 - 西鹤软件</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: #fff; padding: 50px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; }
        .btn { display: inline-block; padding: 12px 30px; background: #003399; color: white; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 13px; color: #aaa; }
        .footer a { color: #003399; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="color: #003399; margin-bottom: 10px;">系统部署成功</h2>
        <p style="color: #666;">航班信息调度系统已初始化完成</p>
        <div style="background: #f9f9f9; padding: 15px; border-radius: 6px; margin: 20px 0; font-size: 14px; text-align: left;">
            🔑 账号：admin<br>
            🔒 密码：123456
        </div>
        <a href="login.php" class="btn">进入系统后台</a>
        <div class="footer">
            由 <a href="https://westcran.tech" target="_blank">WESTCRAN西鹤软件</a> 提供技术支持
        </div>
    </div>
</body>
</html>
