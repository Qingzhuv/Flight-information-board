<?php
include 'config.php';
header('Content-Type: application/json');


$action = $_REQUEST['action'] ?? '';


if ($action == 'list') {
    $res = $conn->query("SELECT * FROM flights ORDER BY f_date DESC, f_time ASC");
    if (!$res) {
        die(json_encode(['status' => 'error', 'msg' => '数据库查询失败']));
    }
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    exit;
}


if ($action == 'add') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'msg' => '未登录或登录已失效']));
    }

    $date   = $_POST['f_date'] ?? '';
    $no     = $_POST['f_no'] ?? '';
    $dest   = $_POST['f_dest'] ?? '';
    $time   = $_POST['f_time'] ?? '';
    $status = $_POST['f_status'] ?? '准点';
    $gate   = $_POST['f_gate'] ?? '';

    if (empty($date) || empty($no)) {
        die(json_encode(['status' => 'error', 'msg' => '日期和航班号不能为空']));
    }

    $stmt = $conn->prepare("INSERT INTO flights (f_date, f_no, f_dest, f_time, f_status, f_gate) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die(json_encode(['status' => 'error', 'msg' => 'SQL准备失败: ' . $conn->error]));
    }

    $stmt->bind_param("ssssss", $date, $no, $dest, $time, $status, $gate);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'rows' => $stmt->affected_rows]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => '数据写入失败: ' . $stmt->error]);
    }
    exit;
}


if ($action == 'delete') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'msg' => '无权进行此操作']));
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        die(json_encode(['status' => 'error', 'msg' => '无效的航班ID']));
    }


    $stmt = $conn->prepare("DELETE FROM flights WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'msg' => '已成功删除']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => '未找到该航班，可能已被删除']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => '数据库删除操作失败: ' . $stmt->error]);
    }
    exit;
}

echo json_encode(['status' => 'error', 'msg' => '无效的 Action 指令']);
