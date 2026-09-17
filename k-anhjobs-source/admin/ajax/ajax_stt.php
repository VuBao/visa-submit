<?php
	include "ajax_config.php";

	if(isset($_POST['id']))
	{
		$table = (isset($_POST['table'])) ? htmlspecialchars($_POST['table']) : '';
		$id = (isset($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
		$value = (isset($_POST['value'])) ? htmlspecialchars($_POST['value']) : 0;
		if(in_array($table, array('user','permission','permission_group'), true) && !$func->is_super_admin())
		{
			http_response_code(403);
			die();
		}

		$data['stt'] = $value;
		
		$d->where('id',$id);
		$d->update($table,$data);
		$cache->DeleteCache();
	}
?>
