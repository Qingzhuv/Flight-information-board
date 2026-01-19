<?php
/**
 * WESTCRAN 航班看板系统
 * 技术支持：https://westcran.tech
 * V1.0
 */

include 'config.php';
if (isset($_POST['login'])) {
    $u = $_POST['user'];
    $p = $_POST['pass'];
    // 使用预处理防止 SQL 注入（安全优化）
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    
    if ($user && password_verify($p, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: admin.php');
    } else { 
        $error = "用户名或密码错误"; 
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统登录 | WESTCRAN 航班看板</title>
    <style>
        :root {
            --primary-blue: #003399;
            --accent-red: #E60012;
            --bg-gradient: linear-gradient(135deg, #001f5c 0%, #003399 100%);
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: var(--bg-gradient);
            font-family: "PingFang SC", "Microsoft YaHei", sans-serif;
            overflow: hidden;
        }

        /* 登录卡片 */
        .login-card {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 360px;
            transform: translateY(-20px);
        }

        .login-card h3 {
            margin: 0 0 10px 0;
            color: var(--primary-blue);
            font-size: 24px;
            text-align: center;
            font-weight: 700;
        }

        .login-card p.subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* 输入框样式 */
        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
            transition: border-color 0.3s;
            outline: none;
        }

        input:focus {
            border-color: var(--primary-blue);
        }

        /* 按钮样式 */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--accent-red);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(230, 0, 18, 0.2);
        }

        .btn-login:hover {
            background: #cc0010;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(230, 0, 18, 0.3);
        }

        /* 错误提示 */
        .error-msg {
            color: var(--accent-red);
            background: #fff0f0;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #ffdada;
        }

        /* 品牌署名 */
        footer {
            position: absolute;
            bottom: 30px;
            text-align: center;
        }

        .brand-link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 1px;
            transition: color 0.3s;
        }

        .brand-link:hover {
            color: #ffffff;
        }

        .brand-link span {
            font-weight: bold;
            margin-right: 4px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3>系统管理登录</h3>
        <p class="subtitle">航班大屏信息调度系统</p>

        <?php if(isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="user" placeholder="用户名" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" name="pass" placeholder="密码" required>
            </div>
            <button type="submit" name="login" class="btn-login">确认登录</button>
        </form>
    </div>

    <footer>
        <a href="https://westcran.tech" target="_blank" class="brand-link">
            <span>WESTCRAN</span>西鹤软件 | 提供技术支持
        </a>
    </footer>

</body>
</html>
