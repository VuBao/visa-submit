<?php
include "ajax/ajax_config.php";

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Chat Debug</h2>";

// 1. Check id_send_type distribution
$dist = $d->rawQuery("select id_send_type, count(*) as num from #_message group by id_send_type");
echo "<h3>1. id_send_type distribution:</h3><pre>";
print_r($dist);
echo "</pre>";

// 2. Check room for candidate id=872 (the one from error)
$room = $d->rawQueryOne("select * from #_room where id_member = 872");
echo "<h3>2. Room for member 872:</h3><pre>";
print_r($room);
echo "</pre>";

if ($room && $room['id']) {
    // 3. Show all messages in this room with their id_send_type
    $msgs = $d->rawQuery("select id, id_room, id_send, type, id_send_type, trangthai, LEFT(message, 50) as msg_preview, ngaytao from #_message where id_room = ? order by id desc limit 0,30", array($room['id']));
    echo "<h3>3. Messages in room " . $room['id'] . ":</h3>";
    echo "<table border='1' cellpadding='5'><tr><th>id</th><th>id_send</th><th>type</th><th>id_send_type</th><th>trangthai</th><th>message</th><th>time</th></tr>";
    foreach ($msgs as $m) {
        $color = '';
        if ($m['id_send_type'] == 0) $color = 'background:#ffe0e0'; // candidate = red
        if ($m['id_send_type'] == 1) $color = 'background:#e0ffe0'; // ctv = green
        if ($m['id_send_type'] == 2) $color = 'background:#e0e0ff'; // admin = blue
        echo "<tr style='$color'>";
        echo "<td>{$m['id']}</td><td>{$m['id_send']}</td><td>{$m['type']}</td><td>{$m['id_send_type']}</td><td>{$m['trangthai']}</td><td>{$m['msg_preview']}</td><td>" . date('d/m H:i', $m['ngaytao']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // 4. Check CTV senders
    $ctv_senders = $d->rawQuery("select distinct id_send from #_message where id_room = ? and id_send_type = 1", array($room['id']));
    echo "<h3>4. CTV senders (id_send_type=1):</h3><pre>";
    print_r($ctv_senders);
    echo "</pre>";

    // 5. Check Admin senders
    $admin_senders = $d->rawQuery("select distinct id_send from #_message where id_room = ? and id_send_type = 2", array($room['id']));
    echo "<h3>5. Admin senders (id_send_type=2):</h3><pre>";
    print_r($admin_senders);
    echo "</pre>";
}

// 6. Check table structure
$cols = $d->rawQuery("SHOW COLUMNS FROM #_message LIKE 'id_send_type'");
echo "<h3>6. Column definition:</h3><pre>";
print_r($cols);
echo "</pre>";
?>
