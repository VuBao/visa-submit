<?php
	if(!defined('SOURCES')) die("Error");

	

	/* Cấu hình đường dẫn trả về */
	$strUrl = "";

	$strUrl .= (isset($_REQUEST['keyword'])) ? "&keyword=".htmlspecialchars($_REQUEST['keyword']) : "";

	switch($act)
	{
		case "man":
			get_items();
			$template = "congtacvien/man/items";
			break;

        case "cap-nhat":
			
            duyet();
            break;

		case "delete":
			delete_item();
			break;

		default:
			$template = "404";
	}

	/* Get  */
	function get_items()
	{
		global $d, $func, $strUrl, $curPage, $items, $paging;
		
		$where = "";

		if(isset($_REQUEST['keyword']))
        {
            $keyword = htmlspecialchars($_REQUEST['keyword']);
            $where .= " and (username LIKE '%$keyword%' or ten LIKE '%$keyword%' or email LIKE '%$keyword%' or mactv LIKE '%$keyword%')";
        }

		$per_page = 10;
        $startpoint = ($curPage * $per_page) - $per_page;
        $limit = " limit ".$startpoint.",".$per_page;
        $sql = "select * from #_member where id <> 0 $where and role = 1 order by stt,id desc $limit";
        $items = $d->rawQuery($sql);
        $sqlNum = "select count(*) as 'num' from #_member where id <> 0 $where and role = 1 order by stt,id desc";
        $count = $d->rawQueryOne($sqlNum);
        $total = $count['num'];
        $url = "index.php?com=congtacvien&act=man".$strUrl;
        $paging = $func->pagination($total,$per_page,$curPage,$url);
	}

	

	function generate_mactv_code($d) {
		$maxRow = $d->rawQueryOne("select mactv from #_member where role = 1 and mactv LIKE 'K%' order by CAST(SUBSTRING(mactv, 2) AS UNSIGNED) desc limit 0,1");
		$maxNum = 0;
		if(!empty($maxRow['mactv'])) {
			$numPart = preg_replace('/[^0-9]/', '', $maxRow['mactv']);
			$maxNum = (int)$numPart;
		}
		$maxNum++;
		return sprintf("K%03d", $maxNum);
	}

	function duyet()
    {
		global $d, $func, $strUrl, $curPage;
		
        $id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
		
        if($id) {
            $data['tinhtrang'] = 1;
            $data['hienthi'] = 1;
			$check_mem = $d->rawQueryOne("select mactv from #_member where id = ?", array($id));
			if(empty($check_mem['mactv'])) {
				$data['mactv'] = generate_mactv_code($d);
			}
            $d->where('id', $id);
            $d->update("member", $data);
			$func->transfer("Cập nhật thành công", "index.php?com=congtacvien&act=man&p=".$curPage);
        }
        elseif(isset($_GET['listid']))
        {
            $listid = explode(",", $_GET['listid']);
            for($i = 0; $i < count($listid); $i++)
            {
                $mid = htmlspecialchars($listid[$i]);
                $data = array();
                $data['tinhtrang'] = 1;
                $data['hienthi'] = 1;
				$check_mem = $d->rawQueryOne("select mactv from #_member where id = ?", array($mid));
				if(empty($check_mem['mactv'])) {
					$data['mactv'] = generate_mactv_code($d);
				}
                $d->where('id', $mid);
                $d->update("member", $data);
            }
            $func->transfer("Duyệt thành công", "index.php?com=congtacvien&act=man&p=".$curPage);
        }
        else {
            $func->transfer("Không nhận được dữ liệu", "index.php?com=congtacvien&act=man&p=".$curPage, false);
        }
    }

	/* Delete  */
	function delete_item()
	{
		global $d, $func, $curPage;

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

		if($id)
		{
			$row = $d->rawQueryOne("select id from #_member where id = ? limit 0,1",array($id));

			if(isset($row['id']) && $row['id'] > 0)
			{
				$d->rawQuery("delete from #_member where id = ?",array($id));
				$func->transfer("Xóa dữ liệu thành công", "index.php?com=congtacvien&act=man&p=".$curPage);
			}
			else $func->transfer("Xóa dữ liệu bị lỗi", "index.php?com=congtacvien&act=man&p=".$curPage, false);
		}
		elseif(isset($_GET['listid']))
		{
			$listid = explode(",",$_GET['listid']);
			
			for($i=0;$i<count($listid);$i++)
			{
				$id = htmlspecialchars($listid[$i]);
				$row = $d->rawQueryOne("select id from #_member where id = ? limit 0,1",array($id));

				if(isset($row['id']) && $row['id'] > 0)
				{
					$d->rawQuery("delete from #_member where id = ?",array($id));
				}
			}
			
			$func->transfer("Xóa dữ liệu thành công", "index.php?com=congtacvien&act=man&p=".$curPage);
		}
		else $func->transfer("Không nhận được dữ liệu", "index.php?com=congtacvien&act=man&p=".$curPage, false);
	}
?>