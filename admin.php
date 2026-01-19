<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}
?>
<!DOCTYPE html>
<!-- 
/**
 * Project: WESTCRAN Flight Status Board
 * Author: WESTCRAN 西鹤软件 (https://westcran.tech)
 * License: GNU GPL v3.0
 * * [法律声明]
 * 1. 任何基于本项目开发的衍生版本必须保留此署名。
 * 2. 禁止将本项目用于任何形式的非法用途，包括但不限于航空诈骗、虚假信息发布。
 * 3. 开发者不对使用者因违反法律导致的任何后果承担责任。
 */
 -->

<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>WESTCRAN航班看板 | 管理后台</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/jquery.min.js"></script>
    <style>
        :root { --csair-blue: #003399; }
        body { background-color: #f0f2f5; font-size: 14px; }
        .nav-custom { background: var(--csair-blue); color: white; padding: 10px 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-csair { background-color: var(--csair-blue); color: white; border: none; padding: 10px; }
        .btn-csair:hover { background-color: #002266; color: white; }
        
        /* 移动端卡片式表单 */
        .card { border: none; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .form-label { font-weight: bold; color: #555; margin-bottom: 5px; }
        .form-control, .form-select { 
            padding: 12px; 
            border-radius: 8px; 
            border: 1px solid #ddd;
            font-size: 16px;
        }

        input[type="time"], input[type="date"] {
            display: block;
            width: 100%;
            -webkit-appearance: none;
            min-height: 45px;
        }

        @media (max-width: 768px) {
            .desktop-only { display: none; }
            .mobile-card {
                background: white;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 10px;
                border-left: 4px solid var(--csair-blue);
            }
            .mobile-card .flight-info { display: flex; justify-content: space-between; align-items: center; }
            .mobile-card .flight-no { font-size: 18px; font-weight: bold; color: var(--csair-blue); }
            .mobile-card .flight-dest { font-size: 16px; font-weight: 500; }
            .mobile-card .details { color: #888; margin-top: 5px; font-size: 13px; }
            .container { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="nav-custom d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <h5 class="mb-0">调度后台</h5>
        <a href="https://westcran.tech" target="_blank" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 11px; border: 1px solid rgba(255,255,255,0.3); padding: 1px 8px; border-radius: 20px; margin-left: 10px;">
            由 WESTCRAN西鹤软件 提供技术支持
        </a>
    </div>
    <div>
        <a href="index.php" class="btn btn-sm btn-outline-light me-1">预览</a>
        <a href="logout.php" class="btn btn-sm btn-danger">退出</a>
    </div>
</div>

<div class="container">
    <div class="card p-3 p-md-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> 发布新航班</h6>
        <form id="addForm" class="row g-3">
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small">日期 DATE</label>
                <input type="date" name="f_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small">航班 FLIGHT</label>
                <input type="text" name="f_no" class="form-control" placeholder="如 CZ3101" required>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label text-muted small">目的地 DESTINATION</label>
                <input type="text" name="f_dest" class="form-control" placeholder="城市名称" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small">计划时间 TIME</label>
                <input type="time" name="f_time" class="form-control" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small">状态 STATUS</label>
                <select name="f_status" class="form-select">
                    <option value="准点">准点</option>
                    <option value="登机中">登机中</option>
                    <option value="延误">延误</option>
                    <option value="取消">取消</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label text-muted small">登机口</label>
                <input type="text" name="f_gate" class="form-control" placeholder="A10">
            </div>
            <div class="col-6 col-md-1 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="submit" class="btn btn-csair fw-bold">发布</button>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <h6 class="fw-bold mb-3">当前航班数据</h6>
        <div class="table-responsive desktop-only">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>日期</th><th>航班号</th><th>目的地</th><th>时间</th><th>状态</th><th>登机口</th><th>操作</th>
                    </tr>
                </thead>
                <tbody id="admin-list-desktop"></tbody>
            </table>
        </div>
        <div id="admin-list-mobile" class="d-md-none">
            </div>
    </div>
</div>

<script>
    function load() {
        $.getJSON('api.php?action=list', function(data) {
            let desktopHtml = '';
            let mobileHtml = '';
            
            if(data.length === 0) {
                desktopHtml = '<tr><td colspan="7" class="text-center text-muted">暂无数据</td></tr>';
                mobileHtml = '<div class="text-center text-muted p-4">暂无数据</div>';
            } else {
                data.forEach(f => {
                    const statusColor = f.f_status === '登机中' ? 'bg-success' : 'bg-secondary';
                    const timeStr = f.f_time ? f.f_time.substring(0, 5) : '--:--';
                    
                    // 电脑端行
                    desktopHtml += `<tr>
                        <td>${f.f_date}</td>
                        <td class="fw-bold text-primary">${f.f_no}</td>
                        <td>${f.f_dest}</td>
                        <td>${timeStr}</td>
                        <td><span class="badge ${statusColor}">${f.f_status}</span></td>
                        <td class="fw-bold">${f.f_gate || '--'}</td>
                        <td><button onclick="del(${f.id})" class="btn btn-sm btn-outline-danger">删除</button></td>
                    </tr>`;

                    // 手机端卡片
                    mobileHtml += `
                    <div class="mobile-card">
                        <div class="flight-info">
                            <div><span class="flight-no">${f.f_no}</span> <span class="ms-2 flight-dest">${f.f_dest}</span></div>
                            <span class="badge ${statusColor}">${f.f_status}</span>
                        </div>
                        <div class="details d-flex justify-content-between">
                            <span>时间: ${timeStr} | 登机口: ${f.f_gate || '--'}</span>
                            <a href="javascript:void(0)" onclick="del(${f.id})" class="text-danger text-decoration-none">删除</a>
                        </div>
                        <div class="text-muted small mt-1" style="font-size:11px;">日期: ${f.f_date}</div>
                    </div>`;
                });
            }
            $('#admin-list-desktop').html(desktopHtml);
            $('#admin-list-mobile').html(mobileHtml);
        });
    }

    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        $.post('api.php?action=add', $(this).serialize(), function(res) {
            if(res.status === 'success') {
                load();
                $('#addForm')[0].reset();
                alert('发布成功！');
            } else {
                alert('失败: ' + res.msg);
            }
        }, 'json');
    });

    function del(id) {
        if(confirm('确定要删除吗？')) {
            $.get('api.php?action=delete&id=' + id, function() { load(); });
        }
    }

    $(document).ready(load);

</script>
</body>
</html>
