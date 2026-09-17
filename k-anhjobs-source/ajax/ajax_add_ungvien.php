<?php
include "ajax_config.php";

$iduser = $_SESSION[$login_member]['id'];
$is_ctv = ($_SESSION[$login_member]['role'] == 1);

if (!$iduser || !$is_ctv) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền thực hiện thao tác này.']);
    exit;
}

$ten = isset($_POST['ten']) ? trim($_POST['ten']) : '';
if ($ten === '') {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập họ tên ứng viên.']);
    exit;
}

$tinhtrang = isset($_POST['tinhtrang']) ? (int)$_POST['tinhtrang'] : 1;
if (!in_array($tinhtrang, [1, 2, 3, 4, 5])) $tinhtrang = 1;

// Get CTV (referrer) info from logged-in session
$ctv = $d->rawQueryOne("select ten, username, mactv from #_member where id = ?", array($iduser));
if (!$ctv) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin CTV.']);
    exit;
}
$ten_gt = ($ctv['mactv'] ? '[' . $ctv['mactv'] . '] ' : '') . ($ctv['ten'] ? $ctv['ten'] : $ctv['username']);

$data = array();
$data['id_member_gt'] = $iduser;
$data['tennguoigioithieu'] = htmlspecialchars($ten_gt);
$data['ten'] = htmlspecialchars($ten);
$data['congty'] = htmlspecialchars(trim($_POST['congty'] ?? ''));
$data['ten_news'] = htmlspecialchars(trim($_POST['ten_news'] ?? ''));
$data['tinhtrang'] = $tinhtrang;
$data['sodienthoai'] = htmlspecialchars(trim($_POST['sodienthoai'] ?? ''));
$data['link_facebook'] = htmlspecialchars(trim($_POST['link_facebook'] ?? ''));
$data['ghichu'] = htmlspecialchars(trim($_POST['ghichu'] ?? ''));
$data['tinhtrang_gt'] = 1;
$data['ngaytao'] = time();
if ($tinhtrang == 5) {
    $data['working_date'] = time();
}

if ($d->insert('ungtuyen', $data)) {
    echo json_encode(['status' => 'success', 'message' => 'Thêm ứng viên thành công!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu thông tin ứng viên.']);
}
exit;
