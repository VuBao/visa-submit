<?php
	include "ajax_config.php";

	if(isset($_POST['id'])) {
		$id_mem = $_SESSION[$login_member]['id'];
		
		if($id_mem) {
			$mem = $d->rawQueryOne("select * from #_member where id = ?", array($id_mem));
			if($mem['vongquay'] > 0) {
				$phanthuong = $d->rawQuery("select id, tile, ten$lang as ten, mota$lang as mota, photo from #_product where type = ? order by stt, id desc", array('vong-xoay-may-man'));
				$weights = array_column($phanthuong, 'tile');

				$totalWeight = array_sum($weights);

				$rand = random_int(0, $totalWeight - 1);

				$partialSum = 0;
				for ($i = 0; $i < count($weights); $i++) {
					$partialSum += $weights[$i];
					if ($rand < $partialSum) {
						$thuong = $phanthuong[$i];
						break;
					}
				}

				$data_user['vongquay'] = $mem['vongquay'] - 1;
				$d->where('id', $mem['id']);
				$d->update('member',$data_user);
				
				$transition['id_member'] = $mem['id'];
				$transition['thuong'] = $thuong['ten'];
				$transition['trangthai'] = 1;
				$transition['ngaytao'] = time();
				$d->insert('quaythuong', $transition);

				$data = [
					'status'	=> 'success',
					'message'	=> $thuong['mota'],
					'id' => $thuong['id']
				];
				echo json_encode($data);
				return;
			} else {
				$data = [
					'status' => 'error',
					'message'	=> bankhongcoluotquaynao,
				];
				echo json_encode($data);
				return;
			}
		} else {
			$data = [
				'status' => 'error',
				'message'	=> dangnhapdetieptuc,
			];
			echo json_encode($data);
			return;
		}
	}
?>
