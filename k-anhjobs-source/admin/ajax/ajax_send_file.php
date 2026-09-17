<?php
	include "ajax_config.php";

	if(isset($_FILES["image"]))
	{
		$mem = $_POST['mem'];
		$send = $_SESSION[$login_admin]['id'];

		if($mem) {
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
			

			$file_name = $func->uploadName($_FILES["image"]["name"]);
			if($file = $func->uploadImage("image", 'jpg|png|gif|JPG|PNG|GIF', '../../'.UPLOAD_FILE_L, $file_name))
			{
				$data_message['photo'] = $file;
			}

			$data_message['id_room'] = $room['id'];
			$data_message['id_send'] = $send;
			$data_message['type'] = 2;
			$data_message['id_send_type'] = 2;
			$data_message['id_receive'] = (int)$mem;
			$data_message['trangthai'] = 1;
			$data_message['ngaytao'] = time();

			if($d->insert("message", $data_message)) {
				$id_insert = $d->getLastInsertId();

				$member = $d->rawQueryOne("select * from #_member where id = ?", array($mem));
				$response = [
					'type'  => '387NUC9',
					'mess' => $id_insert,
					'member' => $mem
				];
				$response['items_user'] = '<a class="items-user room-'.$member['id'].' active" href="index.php?com=chat&act=man&id='.$member['id'].'"><div class="avatar-user"><img src="../'. UPLOAD_FILE_L.$member['avatar'] .'" alt="'.$member['username'].'"></div><div class="text-user"><h2>'. ($member['ten'] ? $member['ten'] : $member['username']) .'</h2><h3> <span class="text-sp-1">'. ($data_message['type'] == 1 ? '' : 'Bạn: ') .''. ($data_message['message'] ? $data_message['message'] : 'đã gửi 1 ảnh') .' </span> - <span>'. $func->khoangcach($data_message['ngaytao']) .'</span> </h3></div><div class="read-user '. ($data_message['trangthai'] == 1 && $data_message['type'] == 1 ? 'active' : '') .'"></div></a>';
				echo json_encode($response);
				die();
			}
		}
	}

	if(isset($_POST['message'])) 
	{
		$mem = $_POST['mem'];
		$send = $_SESSION[$login_admin]['id'];

		if($mem) {
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

			$data_message['id_room'] = $room['id'];
			$data_message['id_send'] = $send;
			$data_message['message'] = htmlspecialchars($_POST['message']);
			$data_message['type'] = 2;
			$data_message['id_send_type'] = 2;
			$data_message['id_receive'] = (int)$mem;
			$data_message['trangthai'] = 1;
			$data_message['ngaytao'] = time();

			if($d->insert("message", $data_message)) {
				$id_insert = $d->getLastInsertId();

				$member = $d->rawQueryOne("select * from #_member where id = ?", array($mem));
				$response = [
					'type'  => '387NUC9',
					'mess' => $id_insert,
					'member' => $mem
				];
				$response['items_user'] = '<a class="items-user room-'.$member['id'].' active" href="index.php?com=chat&act=man&id='.$member['id'].'"><div class="avatar-user"><img src="../'. UPLOAD_FILE_L.$member['avatar'] .'" alt="'.$member['username'].'"></div><div class="text-user"><h2>'. ($member['ten'] ? $member['ten'] : $member['username']) .'</h2><h3> <span class="text-sp-1">'. ($data_message['type'] == 1 ? '' : 'Bạn: ') .''. ($data_message['message'] ? $data_message['message'] : 'đã gửi 1 ảnh') .' </span> - <span>'. $func->khoangcach($data_message['ngaytao']) .'</span> </h3></div><div class="read-user '. ($data_message['trangthai'] == 1 && $data_message['type'] == 1 ? 'active' : '') .'"></div></a>';
				echo json_encode($response);
				die();
			}
		}
	}
?>