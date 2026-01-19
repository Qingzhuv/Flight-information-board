<?php
session_start();
$host = 'localhost';
$user = 'flight_system'; //数据库用户名
$pass = 'flight_system'; // 数据库密码
$dbname = 'flight_system'; //库

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("连接失败: " . $conn->connect_error);
$conn->set_charset("utf8mb4");
?>
