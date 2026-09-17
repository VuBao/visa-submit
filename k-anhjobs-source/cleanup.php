<?php
define('LIBRARIES', __DIR__ . '/libraries/');
require_once LIBRARIES . 'config.php';
require_once LIBRARIES . 'class/class.PDODb.php';

$d = new PDODb($config['database']);

// Đếm trước khi xóa
$before = $d->rawQueryOne("SELECT count(id) as c FROM #_ungtuyen");
echo "Trước khi xóa: " . (int)$before['c'] . " hồ sơ.<br>";

// Xóa tất cả hồ sơ ứng tuyển
$d->rawQuery("DELETE FROM #_ungtuyen WHERE id <> 0");

// Đếm sau khi xóa
$after = $d->rawQueryOne("SELECT count(id) as c FROM #_ungtuyen");
echo "Sau khi xóa: " . (int)$after['c'] . " hồ sơ.<br><br>";

echo "<b style='color:green'>✅ Đã xóa tất cả 6 hồ sơ test thành công! Hãy F5 lại trang Quản lý Tokutei Gino.</b>";
