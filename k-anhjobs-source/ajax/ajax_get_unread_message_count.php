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
$count = 0;

if ($is_ctv) {
    // CTV: count unread candidate messages addressed to this CTV (id_send_type = 0, id_receive = mem)
    $res = $d->rawQueryOne("select count(*) as num from #_message where id_send_type = 0 and id_receive = ? and trangthai = 1", array($mem));
    $count = (int)($res['num'] ?? 0);
} else {
    // Candidate: find their room first
    $room = $d->rawQueryOne("select id from #_room where id_member = ?", array($mem));
    if ($room && isset($room['id'])) {
        // Count unread messages from CTVs and Admin in their room (type = 2, trangthai = 1)
        $res = $d->rawQueryOne("select count(*) as num from #_message where id_room = ? and type = 2 and trangthai = 1", array($room['id']));
        $count = (int)($res['num'] ?? 0);
    }
}

echo json_encode([
    'status' => 'success',
    'count' => $count
]);
exit;
