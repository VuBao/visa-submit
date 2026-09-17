<?php
	include "ajax_config.php";

	if(isset($_POST['id']))
	{
		$id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
		$thanhtoan = (isset($_POST['thanhtoan'])) ? (int)$_POST['thanhtoan'] : 1;

		if($id > 0 && $thanhtoan > 0)
		{
			$data['tinhtrang_gt'] = $thanhtoan;
			$d->where('id', $id);
			$d->update('ungtuyen', $data);
			echo 1;
		}
	}
?>
