<?php
include "ajax_config.php";

$mem = isset($_SESSION[$login_member]['id']) ? $_SESSION[$login_member]['id'] : 0;
if (!$mem) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Not logged in'
    ]);
    exit;
}

$is_ctv = (isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1);
if ($is_ctv && isset($_POST['id_candidate'])) {
    $id_candidate = (int)$_POST['id_candidate'];
    $room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
} else {
    $room = $d->rawQueryOne("select * from #_room where id_member = ?", array($mem));
}

if ($room && isset($room['id'])) {
    $id_ctv = isset($_POST['id_ctv']) ? $_POST['id_ctv'] : '';
    
    if ($is_ctv) {
        // CTV: get unread candidate messages addressed to this CTV (id_send_type=0, id_receive=mem) in this room
        $new_messages = $d->rawQuery("select * from #_message where id_room = ? and id_send_type = 0 and id_receive = ? and trangthai = 1 order by id asc", array($room['id'], $mem));
    } else if ($id_ctv == 'admin') {
        // Candidate chatting with Admin: get Admin messages (id_send_type=2)
        $new_messages = $d->rawQuery("select * from #_message where id_room = ? and id_send_type = 2 and trangthai = 1 order by id asc", array($room['id']));
    } else if ($id_ctv) {
        // Candidate chatting with specific CTV: get that CTV's messages (id_send_type=1)
        $new_messages = $d->rawQuery("select * from #_message where id_room = ? and id_send_type = 1 and id_send = ? and trangthai = 1 order by id asc", array($room['id'], $id_ctv));
    } else {
        // Default fallback: get all type=2 unread
        $new_messages = $d->rawQuery("select * from #_message where id_room = ? and type = 2 and trangthai = 1 order by id asc", array($room['id']));
    }
    
    if (count($new_messages) > 0) {
        // Mark as read
        if ($is_ctv) {
            $d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 0 and id_receive = ? and trangthai = 1", array($room['id'], $mem));
        } else if ($id_ctv == 'admin') {
            $d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 2 and trangthai = 1", array($room['id']));
        } else if ($id_ctv) {
            $d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 1 and id_send = ? and trangthai = 1", array($room['id'], $id_ctv));
        } else {
            $d->rawQuery("update #_message set trangthai = 2 where id_room = ? and type = 2 and trangthai = 1", array($room['id']));
        }
        
        $html = '';
        foreach ($new_messages as $message) {
            $html .= '<div class="message-left message">';
            $html .= '    <div class="mw-75" title="đã gửi lúc ' . date("h:i d/m/Y", $message['ngaytao']) . '">';
            if ($message['message']) {
                $html .= nl2br($message['message']);
            } else {
                $html .= '<img src="' . UPLOAD_FILE_L . $message['photo'] . '" alt="">';
            }
            $html .= '    </div>';
            $html .= '</div>';
        }
        
        echo json_encode([
            'status' => 'success',
            'html' => $html,
            'count' => count($new_messages)
        ]);
        exit;
    }
}

echo json_encode([
    'status' => 'empty',
    'html' => '',
    'count' => 0
]);
exit;
