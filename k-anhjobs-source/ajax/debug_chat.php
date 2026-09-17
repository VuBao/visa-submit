<?php
include "ajax_config.php";

header('Content-Type: text/plain; charset=utf-8');

echo "=== CHAT DEBUG ===\n\n";

// 1. id_send_type distribution
$dist = $d->rawQuery("select id_send_type, count(*) as num from #_message group by id_send_type");
echo "1. id_send_type distribution:\n";
foreach ($dist as $row) {
    echo "   id_send_type={$row['id_send_type']} => {$row['num']} messages\n";
}

// 2. Room 7 messages (from error log)
echo "\n2. Messages in room 7 (last 20):\n";
$msgs = $d->rawQuery("select id, id_send, type, id_send_type, LEFT(message,30) as msg from #_message where id_room = 7 order by id desc limit 0,20");
echo "ID | id_send | type | id_send_type | message\n";
echo "---|---------|------|--------------|--------\n";
foreach ($msgs as $m) {
    echo "{$m['id']} | {$m['id_send']} | {$m['type']} | {$m['id_send_type']} | {$m['msg']}\n";
}

// 3. CTV senders
echo "\n3. CTV senders (id_send_type=1) in room 7:\n";
$ctv = $d->rawQuery("select distinct id_send from #_message where id_room = 7 and id_send_type = 1");
print_r($ctv);

echo "\n4. Admin senders (id_send_type=2) in room 7:\n";
$adm = $d->rawQuery("select distinct id_send from #_message where id_room = 7 and id_send_type = 2");
print_r($adm);
?>
