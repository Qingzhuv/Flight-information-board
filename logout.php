<?php
/**
 * Project: WESTCRAN Flight Status Board
 * Author: WESTCRAN 西鹤软件 (https://westcran.tech)
 * License: GNU GPL v3.0
 * * [法律声明]
 * 1. 任何基于本项目开发的衍生版本必须保留此署名。
 * 2. 禁止将本项目用于任何形式的非法用途，包括但不限于航空诈骗、虚假信息发布。
 * 3. 开发者不对使用者因违反法律导致的任何后果承担责任。
 */

session_start();

// 2. 清空 $_SESSION 数组中的所有数据
$_SESSION = array();

// 3. 如果使用的是基于 Cookie 的 Session，则销毁会话 Cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 4. 彻底销毁服务器端的 Session 会话文件
session_destroy();

// 5. 任务完成，重定向到登录页面
header("Location: login.php");
exit;
?>
