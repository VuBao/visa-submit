<?php
	if(!defined('SOURCES')) die("Error");

	

	/* Cấu hình đường dẫn trả về */
	$strUrl = "";
	$strUrl .= (isset($_REQUEST['trangthai'])) ? "&trangthai=".htmlspecialchars($_REQUEST['trangthai']) : "";
	
	$strUrl .= (isset($_REQUEST['ngaydat'])) ? "&ngaydat=".htmlspecialchars($_REQUEST['ngaydat']) : "";

	$strUrl .= (isset($_REQUEST['keyword'])) ? "&keyword=".htmlspecialchars($_REQUEST['keyword']) : "";

	switch($act)
	{
		case "man":
			get_items();
			$template = "quanliquaythuong/man/items";
			break;

		case "duyet":
			duyet();
			break;

		case "delete":
			delete_item();
			break;

		default:
			$template = "404";
	}

	/* Get ungtuyen */
	function get_items()
	{
		global $d, $func, $strUrl, $curPage, $items, $paging;
		
		$where = "";

		$tinhtrang = (isset($_REQUEST['trangthai'])) ? htmlspecialchars($_REQUEST['trangthai']) : 0;
		$ngaydat = (isset($_REQUEST['ngaydat'])) ? htmlspecialchars($_REQUEST['ngaydat']) : 0;
		
		if($tinhtrang) $where .= " and trangthai=$tinhtrang";
		
		if($ngaydat)
		{
			$ngaydat = explode("-",$ngaydat);
			$ngaytu = trim($ngaydat[0]);
			$ngayden = trim($ngaydat[1]);
			$ngaytu = strtotime(str_replace("/","-",$ngaytu));
			$ngayden = strtotime(str_replace("/","-",$ngayden));
			$where .= " and ngaytao<=$ngayden and ngaytao>=$ngaytu";
		}
		
	
		if(isset($_REQUEST['keyword']))
		{
			$keyword = htmlspecialchars($_REQUEST['keyword']);
			$member = $d->rawQueryOne("select id from #_member where username = ?", array($keyword));
			if($member['id']) {
				$idmem = $member['id'];
				$where .= " and id_member=$idmem";
			} 
		}

		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_quaythuong where id<>0 $where order by ngaytao desc $limit";
		$items = $d->rawQuery($sql);
		$sqlNum = "select count(*) as 'num' from #_quaythuong where id<>0 $where order by ngaytao desc";
		$count = $d->rawQueryOne($sqlNum);
		$total = $count['num'];
		$url = "index.php?com=quanliquaythuong&act=man".$strUrl;
		$paging = $func->pagination($total,$per_page,$curPage,$url);

	}

	function duyet()
	{
		global $d, $func, $curPage;

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

		if($id)
		{
			$row = $d->rawQueryOne("select id from #_quaythuong where id = ? limit 0,1",array($id));

			if(isset($row['id']) && $row['id'] > 0)
			{
				$data['trangthai'] = 2;
				$d->where('id', $row['id']);
				$d->update('quaythuong', $data);
				$func->transfer("Cập nhật mã quay thưởng thành công", "index.php?com=quanliquaythuong&act=man&p=".$curPage);
			}
			else $func->transfer("Cập nhật lỗi", "index.php?com=quanliquaythuong&act=man&p=".$curPage, false);
		}
		elseif(isset($_GET['listid']))
		{
			$listid = explode(",",$_GET['listid']);
			
			for($i=0;$i<count($listid);$i++)
			{
				$id = htmlspecialchars($listid[$i]);
				$row = $d->rawQueryOne("select id from #_quaythuong where id = ? limit 0,1",array($id));

				if(isset($row['id']) && $row['id'] > 0)
				{
					$data['trangthai'] = 2;
					$d->where('id', $row['id']);
					$d->update('quaythuong', $data);
				}
			}
			
			$func->transfer("Cập nhật mã quay thưởng thành công", "index.php?com=quanliquaythuong&act=man&p=".$curPage);
		}
		else $func->transfer("Cập nhật lỗi", "index.php?com=quanliquaythuong&act=man&p=".$curPage, false);
	}
	/* Delete ungtuyen */
	function delete_item()
	{
		global $d, $func, $curPage;

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

		if($id)
		{
			$row = $d->rawQueryOne("select id from #_quaythuong where id = ? limit 0,1",array($id));

			if(isset($row['id']) && $row['id'] > 0)
			{
				$d->rawQuery("delete from #_quaythuong where id = ?",array($id));
				$func->transfer("Xóa dữ liệu thành công", "index.php?com=quanliquaythuong&act=man&p=".$curPage);
			}
			else $func->transfer("Xóa dữ liệu bị lỗi", "index.php?com=quanliquaythuong&act=man&p=".$curPage, false);
		}
		elseif(isset($_GET['listid']))
		{
			$listid = explode(",",$_GET['listid']);
			
			for($i=0;$i<count($listid);$i++)
			{
				$id = htmlspecialchars($listid[$i]);
				$row = $d->rawQueryOne("select id from #_quaythuong where id = ? limit 0,1",array($id));

				if(isset($row['id']) && $row['id'] > 0)
				{
					$d->rawQuery("delete from #_quaythuong where id = ?",array($id));
				}
			}
			
			$func->transfer("Xóa dữ liệu thành công", "index.php?com=quanliquaythuong&act=man&p=".$curPage);
		}
		else $func->transfer("Không nhận được dữ liệu", "index.php?com=quanliquaythuong&act=man&p=".$curPage, false);
	}
?>