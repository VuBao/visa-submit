<?php
include "ajax_config.php";

$iduser = $_SESSION[$login_member]['id'];
$is_ctv = ($_SESSION[$login_member]['role'] == 1);

if (!$iduser || !$is_ctv) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền thực hiện thao tác này.']);
    exit;
}

$id_candidate = (isset($_POST['id_candidate']) && $_POST['id_candidate'] > 0) ? (int)$_POST['id_candidate'] : 0;
$id_news = (isset($_POST['id_news']) && $_POST['id_news'] > 0) ? (int)$_POST['id_news'] : 0;

if (!$id_candidate || !$id_news) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
    exit;
}

// Check if candidate exists
$candidate = $d->rawQueryOne("select * from #_member where id = ? and hienthi > 0 limit 0,1", array($id_candidate));
if (!$candidate) {
    echo json_encode(['status' => 'error', 'message' => 'Ứng viên không tồn tại.']);
    exit;
}

// Check if job exists
$job = $d->rawQueryOne("select * from #_news where id = ? and type = ? and hienthi > 0 limit 0,1", array($id_news, 'tin-tuyen-dung'));
if (!$job) {
    echo json_encode(['status' => 'error', 'message' => 'Công việc không tồn tại hoặc đã ẩn.']);
    exit;
}

// Check if already referred / applied for this job
$check = $d->rawQueryOne("select id from #_ungtuyen where id_member = ? and id_news = ? limit 0,1", array($id_candidate, $id_news));
if ($check) {
    echo json_encode(['status' => 'error', 'message' => 'Ứng viên này đã ứng tuyển hoặc được giới thiệu cho công việc này trước đó.']);
    exit;
}

// Get CTV info
$ctv = $d->rawQueryOne("select ten, username from #_member where id = ?", array($iduser));
$ten_ctv = $ctv['ten'] ? $ctv['ten'] : $ctv['username'];

// Save to #_ungtuyen
$data = [];
$data['id_member'] = $candidate['id'];
$data['ten'] = $candidate['ten'];
$data['gioitinh'] = $candidate['gioitinh'];
$data['ngaysinh'] = $candidate['ngaysinh'];
$data['sodienthoai'] = $candidate['dienthoai'];
$data['email'] = $candidate['email'];
$data['diachi'] = $candidate['diachi'];
$data['link_facebook'] = $candidate['link_facebook'];
$data['id_news'] = $id_news;
$data['ten_news'] = $job['tenvi'];
$data['id_member_gt'] = $iduser; // The CTV who introduced
$data['tennguoigioithieu'] = $ten_ctv;
$data['cv'] = $candidate['cv']; // Copy candidate CV
$data['tinhtrang'] = 1; // Default status: Applied/Ứng tuyển
$data['tinhtrang_gt'] = 1; // Referral payment status: Unpaid/Chưa thanh toán
$data['ngaytao'] = time();

if ($d->insert('ungtuyen', $data)) {
    // Tự động nhắn tin cho Admin dưới danh nghĩa Ứng viên
    $room = $d->rawQueryOne("select * from #_room where id_member = ?", array($candidate['id']));
    if ($room['id']) {
        $data_room['ngaysua'] = time();
        $d->where('id', $room['id']);
        $d->update("room", $data_room);
    } else {
        $data_room['id_member'] = $candidate['id'];
        $data_room['ngaysua'] = time();
        $d->insert("room", $data_room);
        $room = $d->rawQueryOne("select * from #_room where id_member = ?", array($candidate['id']));
    }
    
    $data_message['id_room'] = $room['id'];
    $data_message['id_send'] = $candidate['id'];
    $data_message['message'] = "Chào Admin, tôi là " . ($candidate['ten'] ? $candidate['ten'] : $candidate['username']) . ". Tôi được CTV " . $ten_ctv . " giới thiệu ứng tuyển vào vị trí: " . $job['tenvi'] . ". Nhờ Admin xem xét và duyệt giúp tôi nhé!";
    $data_message['type'] = 1;
    $data_message['id_send_type'] = 0; // Sent on behalf of candidate
    $data_message['id_receive'] = 0; // Sent to Admin
    $data_message['trangthai'] = 1;
    $data_message['ngaytao'] = time();
    $d->insert("message", $data_message);

    echo json_encode(['status' => 'success', 'message' => 'Đã chốt ứng viên thành công! Thông tin giới thiệu đã được gửi tới hệ thống Admin duyệt.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu thông tin giới thiệu vào hệ thống.']);
}
exit;
