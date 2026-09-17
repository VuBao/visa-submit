<?php
	include "ajax_config.php";
	
	$id = (isset($_POST['id']) && $_POST['id'] > 0) ? htmlspecialchars($_POST['id']) : 0;
	$iduser = $_SESSION[$login_member]['id'];

	if(!$id || !$iduser) {
		echo 0;
		die();
	} else {
		$check = $d->rawQueryOne("select * from #_uuthich where id_news = ? and id_member = ?", array($id, $iduser));
		if(!$check) {
			$data['id_member'] = $iduser;
			$data['id_news'] = $id;
			$data['ngaytao'] = time();
			$d->insert('uuthich', $data);
		} else {
			$d->rawQuery("delete from #_uuthich where id = ?", array($check['id']));
		}
		echo 1;
		die();
	}
?>
