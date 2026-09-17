<?php
	include "ajax_config.php";
	
	if(isset($_FILES["image"]))
	{
		$mem = $_SESSION[$login_member]['id'];
		if($mem) {
			$is_ctv = ($_SESSION[$login_member]['role'] == 1);
			if ($is_ctv && isset($_POST['id_candidate'])) {
				$id_candidate = (int)$_POST['id_candidate'];
				$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				if ($room['id']) {
					$data_room['ngaysua'] = time();
					$d->where('id', $room['id']);
					$d->update("room", $data_room);
				} else {
					$data_room['id_member'] = $id_candidate;
					$data_room['ngaysua'] = time();
					$d->insert("room", $data_room);
					$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				}
				$type = 2; // CTV message
				$id_send_type = 1; // CTV
				$id_receive = (int)$_POST['id_candidate'];
			} else {
				$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($mem));
				if ($room['id']) {
					$data_room['ngaysua'] = time();
					$d->where('id', $room['id']);
					$d->update("room", $data_room);
				} else {
					$data_room['id_member'] = $mem;
					$data_room['ngaysua'] = time();
					$d->insert("room", $data_room);
					$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($mem));
				}
				$type = 1; // Candidate message
				$id_send_type = 0; // Candidate
				$id_ctv_req = isset($_POST['id_ctv']) ? $_POST['id_ctv'] : 'admin';
				$id_receive = ($id_ctv_req == 'admin') ? 0 : (int)$id_ctv_req;
			}
			

			$file_name = $func->uploadName($_FILES["image"]["name"]);
			if($file = $func->uploadImage("image", 'jpg|png|gif|JPG|PNG|GIF', '../'.UPLOAD_FILE_L, $file_name))
			{
				$data_message['photo'] = $file;
			}

			$data_message['id_room'] = $room['id'];
			$data_message['id_send'] = $mem;
			$data_message['type'] = $type;
			$data_message['id_send_type'] = $id_send_type;
			$data_message['id_receive'] = $id_receive;
			$data_message['trangthai'] = 1;
			$data_message['ngaytao'] = time();

			if($d->insert("message", $data_message)) {
				$id_insert = $d->getLastInsertId();

				$response = [
					'type'  => '3782CNH',
					'mess' => $id_insert,
					'member' => $mem
				];
				echo json_encode($response);
				die();
			}
		}
	}

	if(isset($_POST['message'])) {
		$mem = $_SESSION[$login_member]['id'];
		if($mem) {
			$is_ctv = ($_SESSION[$login_member]['role'] == 1);
			if ($is_ctv && isset($_POST['id_candidate'])) {
				$id_candidate = (int)$_POST['id_candidate'];
				$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				if ($room['id']) {
					$data_room['ngaysua'] = time();
					$d->where('id', $room['id']);
					$d->update("room", $data_room);
				} else {
					$data_room['id_member'] = $id_candidate;
					$data_room['ngaysua'] = time();
					$d->insert("room", $data_room);
					$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				}
				$type = 2; // CTV message
				$id_send_type = 1; // CTV
				$id_receive = (int)$_POST['id_candidate'];
			} else {
				$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($mem));
				if ($room['id']) {
					$data_room['ngaysua'] = time();
					$d->where('id', $room['id']);
					$d->update("room", $data_room);
				} else {
					$data_room['id_member'] = $mem;
					$data_room['ngaysua'] = time();
					$d->insert("room", $data_room);
					$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($mem));
				}
				$type = 1; // Candidate message
				$id_send_type = 0; // Candidate
				$id_ctv_req = isset($_POST['id_ctv']) ? $_POST['id_ctv'] : 'admin';
				$id_receive = ($id_ctv_req == 'admin') ? 0 : (int)$id_ctv_req;
			}

			$data_message['id_room'] = $room['id'];
			$data_message['id_send'] = $mem;
			$data_message['message'] = htmlspecialchars($_POST['message']);
			$data_message['type'] = $type;
			$data_message['id_send_type'] = $id_send_type;
			$data_message['id_receive'] = $id_receive;
			$data_message['trangthai'] = 1;
			$data_message['ngaytao'] = time();

			if($d->insert("message", $data_message)) {
				$id_insert = $d->getLastInsertId();

				$response = [
					'type'  => '3782CNH',
					'mess' => $id_insert,
					'member' => $mem
				];
				echo json_encode($response);
				die();
			}
		}
	}
?>
