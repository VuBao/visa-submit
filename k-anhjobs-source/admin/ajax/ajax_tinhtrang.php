<?php
	include "ajax_config.php";

	if(isset($_POST['id']))
	{
		$id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
		$tinhtrang = (isset($_POST['tinhtrang'])) ? (int)$_POST['tinhtrang'] : 0;

		if($id > 0 && $tinhtrang >= 0)
		{
			$data['tinhtrang'] = $tinhtrang;
			if($tinhtrang == 5)
			{
				$row = $d->rawQueryOne("select working_date from #_ungtuyen where id = ?", array($id));
				if(empty($row['working_date']) || $row['working_date'] == 0)
				{
					$data['working_date'] = time();
				}
			}
			$d->where('id', $id);
			$d->update('ungtuyen', $data);
			echo 1;
		}
	}
?>
