<?php
	if(!defined('SOURCES')) die("Error");

	/* Cấu hình đường dẫn trả về */
	$strUrl = "";

	switch($act)
	{
		case "man":
			get_items();
			$template = "chat/man/items";
			break;

		default:
			$template = "404";
	}

	/* Get  */
	function get_items()
	{
		global $d, $func, $strUrl, $room, $room_active, $member_active, $message;
		
		$room = $d->rawQuery("select * from #_room where id <> 0 order by ngaysua desc");

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
		if($id) {
			$room_active = $d->rawQueryOne("select * from #_room where id_member = ?", array($id));
			$member_active = $d->rawQueryOne("select * from #_member where id = ?", array($id));
			$message = $d->rawQuery("select * from #_message where id_room = ? and ((id_send_type = 0 and id_receive = 0) OR id_send_type = 2) order by id desc", array($room_active['id']));
			$d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 0 and id_receive = 0 and trangthai = 1", array($room_active['id']));
		}
	}
?>