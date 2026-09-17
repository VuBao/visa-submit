<?php
	if(!defined('SOURCES')) die("Error");


	/* Cấu hình đường dẫn trả về */
	$strUrl = "";
	$strUrl .= (isset($_REQUEST['tinhtrang'])) ? "&tinhtrang=".htmlspecialchars($_REQUEST['tinhtrang']) : "";
	
	$strUrl .= (isset($_REQUEST['ngaydat'])) ? "&ngaydat=".htmlspecialchars($_REQUEST['ngaydat']) : "";
	

	$strUrl .= (isset($_REQUEST['keyword'])) ? "&keyword=".htmlspecialchars($_REQUEST['keyword']) : "";

	switch($act)
	{
		case "man":
			get_items();
			$template = "ungtuyen/man/items";
			break;
		case "add":
			$template = "ungtuyen/man/item_add";
			break;
		case "edit":
			get_item();
			$template = "ungtuyen/man/item_add";
			break;

		case "save":
			save_item();
			break;

		case "delete":
			delete_item();
			break;

		default:
			$template = "404";
	}

	/* Auto-check schema & auto-generate mactv for CTVs */
	function ensure_mactv($d)
	{
		$checkCol = $d->rawQuery("SHOW COLUMNS FROM #_member LIKE 'mactv'");
		if(empty($checkCol)) {
			$d->rawQuery("ALTER TABLE #_member ADD COLUMN `mactv` VARCHAR(50) DEFAULT NULL AFTER `username`");
		}

		$checkWorkingCol = $d->rawQuery("SHOW COLUMNS FROM #_ungtuyen LIKE 'working_date'");
		if(empty($checkWorkingCol)) {
			$d->rawQuery("ALTER TABLE #_ungtuyen ADD COLUMN `working_date` INT(11) NOT NULL DEFAULT 0 AFTER `ngaytao`");
		}

		$ctvs = $d->rawQuery("select id, mactv from #_member where role = 1 and (mactv is null or mactv = '') order by id asc");
		if(!empty($ctvs)) {
			$maxRow = $d->rawQueryOne("select mactv from #_member where role = 1 and mactv LIKE 'K%' order by CAST(SUBSTRING(mactv, 2) AS UNSIGNED) desc limit 0,1");
			$maxNum = 0;
			if(!empty($maxRow['mactv'])) {
				$numPart = preg_replace('/[^0-9]/', '', $maxRow['mactv']);
				$maxNum = (int)$numPart;
			}

			foreach($ctvs as $ctv) {
				$maxNum++;
				$newCode = sprintf("K%03d", $maxNum);
				$d->rawQuery("update #_member set mactv = ? where id = ?", array($newCode, $ctv['id']));
			}
		}
	}

	/* Get ungtuyen */
	function get_items()
	{
		global $d, $func, $strUrl, $curPage, $items, $paging, $startpoint, $count_phongvan, $count_naitei, $count_visa, $count_lamviec, $thang_selected, $nam_selected;
		
		ensure_mactv($d);

		$where = "";

		$tinhtrang = (isset($_REQUEST['tinhtrang'])) ? htmlspecialchars($_REQUEST['tinhtrang']) : 0;
		if($tinhtrang) $where .= " and tinhtrang=$tinhtrang";

		if(isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '')
		{
			$keyword = htmlspecialchars($_REQUEST['keyword']);
			$members = $d->rawQuery("select id from #_member where mactv LIKE ? or ten LIKE ? or username LIKE ? or email LIKE ?", array("%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"));
			$idmems = array();
			if(!empty($members)) {
				foreach($members as $m) $idmems[] = $m['id'];
			}
			
			if(!empty($idmems)) {
				$id_str = implode(",", $idmems);
				$where .= " and (id_member_gt IN ($id_str) or id_member IN ($id_str) or ten LIKE '%$keyword%' or congty LIKE '%$keyword%' or ten_news LIKE '%$keyword%' or ghichu LIKE '%$keyword%')";
			} else {
				$where .= " and (ten LIKE '%$keyword%' or congty LIKE '%$keyword%' or ten_news LIKE '%$keyword%' or ghichu LIKE '%$keyword%' or sodienthoai LIKE '%$keyword%')";
			}
		}

		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_ungtuyen where id<>0 $where order by id asc $limit";
		$items = $d->rawQuery($sql);
		$sqlNum = "select count(*) as 'num' from #_ungtuyen where id<>0 $where order by id asc";
		$count = $d->rawQueryOne($sqlNum);
		$total = $count['num'];
		$url = "index.php?com=ungtuyen&act=man".$strUrl;
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* Thống kê BẢNG THÀNH TÍCH theo 4 tình trạng */
		$thang_selected = (isset($_REQUEST['thang'])) ? (int)$_REQUEST['thang'] : (int)date('m');
		$nam_selected = (isset($_REQUEST['nam'])) ? (int)$_REQUEST['nam'] : (int)date('Y');

		$where_date_stat = "";
		if($thang_selected > 0 && $nam_selected > 0) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $thang_selected, $nam_selected);
			$time_start = strtotime(sprintf("%04d-%02d-01 00:00:00", $nam_selected, $thang_selected));
			$time_end = strtotime(sprintf("%04d-%02d-%02d 23:59:59", $nam_selected, $thang_selected, $days_in_month));
			$where_date_stat = " and (ngaytao >= $time_start and ngaytao <= $time_end)";
		} elseif($nam_selected > 0) {
			$time_start = strtotime(sprintf("%04d-01-01 00:00:00", $nam_selected));
			$time_end = strtotime(sprintf("%04d-12-31 23:59:59", $nam_selected));
			$where_date_stat = " and (ngaytao >= $time_start and ngaytao <= $time_end)";
		}

		$row_pv = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 2 $where_date_stat");
		$count_phongvan = (int)$row_pv['c'];

		$row_naitei = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 3 $where_date_stat");
		$count_naitei = (int)$row_naitei['c'];

		$row_visa = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 4 $where_date_stat");
		$count_visa = (int)$row_visa['c'];

		$row_lv = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 5 $where_date_stat");
		$count_lamviec = (int)$row_lv['c'];
	}

	/* Edit ungtuyen */
	function get_item()
	{
		global $d, $func, $curPage, $item, $chitietdonhang, $func;

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

		if(!$id) $func->transfer("Không nhận được dữ liệu", "index.php?com=ungtuyen&act=man&p=".$curPage, false);
		
		$item = $d->rawQueryOne("select * from #_ungtuyen where id = ? limit 0,1",array($id));

		if(!$item['id']) $func->transfer("Dữ liệu không có thực", "index.php?com=ungtuyen&act=man&p=".$curPage, false);

	}

	/* Save ungtuyen */
	function save_item()
	{
		global $d, $func, $curPage, $emailer,$setting, $config_base;

		if(empty($_POST)) $func->transfer("Không nhận được dữ liệu", "index.php?com=ungtuyen&act=man&p=".$curPage, false);

		$id = (isset($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;

		$ungtuyen = $d->rawQueryOne('select * from #_ungtuyen where id = ?', array($id));

		/* Post dữ liệu */
		$data = (isset($_POST['data'])) ? $_POST['data'] : null;
		if($data)
		{
			foreach($data as $column => $value)
			{
				$data[$column] = htmlspecialchars($value);
			}
		}

		if(isset($data['tinhtrang']) && $data['tinhtrang'] == 5) {
			if(empty($ungtuyen['working_date']) || $ungtuyen['working_date'] == 0) {
				$data['working_date'] = time();
			}
		}

		if(!empty($data['id_member_gt'])) {
			$gt_mem = $d->rawQueryOne("select ten, username, mactv from #_member where id = ?", array($data['id_member_gt']));
			if(!empty($gt_mem)) {
				$data['tennguoigioithieu'] = ($gt_mem['mactv'] ? '['.$gt_mem['mactv'].'] ' : '') . ($gt_mem['ten'] ? $gt_mem['ten'] : $gt_mem['username']);
			}
		}

		$data['ngaysinh'] = (isset($data['ngaysinh'])) ? strtotime($data['ngaysinh']) : 0;
		$data['ngaylayvisa'] = (isset($data['ngaylayvisa'])) ? strtotime($data['ngaylayvisa']) : 0;
		if(isset($data['ngaytao']) && $data['ngaytao'] != '') {
			$data['ngaytao'] = strtotime($data['ngaytao']);
		} else if(!$id || empty($ungtuyen['ngaytao'])) {
			$data['ngaytao'] = time();
		}

		if(!$data['tennguoigioithieu']) {
			unset($data['tennguoigioithieu']);
		}
		
		if($id) {
			if($ungtuyen['email']) {
				if($ungtuyen['tinhtrang'] != $data['tinhtrang'] && $data['tinhtrang'] > 1) {
					/* Gán giá trị gửi email */
					if($data['tinhtrang'] == 2) {
						$noidung = "Đơn ứng tuyển mã #".$ungtuyen['id']." của bạn đã được chuyển sang trạng thái trước phỏng vấn ".($data['ghichu'] ? '('.$data['ghichu'].')': '').".";
					} else if($data['tinhtrang'] == 3) {
						$noidung = "Đơn ứng tuyển mã #".$ungtuyen['id']." của bạn đã được chuyển sang trạng thái đậu naitei ".($data['ghichu'] ? '('.$data['ghichu'].')': '').".";
					} else if($data['tinhtrang'] == 4) {
						$noidung = "Đơn ứng tuyển mã #".$ungtuyen['id']." của bạn đã được chuyển sang trạng thái đăng ký xin visa ".($data['ghichu'] ? '('.$data['ghichu'].')': '').".";
					} else if($data['tinhtrang'] == 5) {
						$noidung = "Đơn ứng tuyển mã #".$ungtuyen['id']." của bạn đã được chuyển sang trạng thái đang làm việc ".($data['ghichu'] ? '('.$data['ghichu'].')': '').".";
					}
		
					$emailer->setEmail('title-newsletter',"Cập nhật trạng thái ứng tuyển");
					$emailer->setEmail('content-newsletter', $noidung);
		
					/* Nội dung gửi email cho khách hàng */
					$contentCustomer = '
					<table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0" style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px" width="100%">
						<tbody>
							<tr>
								<td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px" width="600">
										<tbody>
											<tr>
												<td align="center" id="m_-6357629121201466163headerImage" valign="bottom">
													<table cellpadding="0" cellspacing="0" style="border-bottom:3px solid '.$emailer->getEmail('color').';padding-bottom:10px;background-color:#fff" width="100%">
														<tbody>
															<tr>
																<td bgcolor="#FFFFFF" style="padding:0" valign="top" width="100%">
																	<div style="color:#fff;background-color:f2f2f2;font-size:11px">&nbsp;</div>
																	<table style="width:100%;">
																		<tbody>
																			<tr>
																				<td>
																					<a href="'.$emailer->getEmail('home').'" style="border:medium none;text-decoration:none;color:#007ed3;margin:0px 0px 0px 20px" target="_blank">'.$emailer->getEmail('logo').'</a>
																				</td>
																				<td style="padding:15px 20px 0 0;text-align:right">'.$emailer->getEmail('social').'</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr style="background:#fff">
												<td align="left" height="auto" style="padding:15px" width="600">
													<table>
														<tbody>
															<tr>
																<td>
																	<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Chào bạn!</h1>
																	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">Thông tin ứng tuyển của bạn đã được cập nhật. '.$emailer->getEmail('company:website').' xin gửi nội dung cập nhất mới nhất như sau.</p>
																	<h3 style="font-size:13px;font-weight:bold;color:'.$emailer->getEmail('color').';text-transform:uppercase;margin:20px 0 0 0;padding: 0 0 5px;border-bottom:1px solid #ddd">'.$emailer->getEmail('title-newsletter').'</h3>
																</td>
															</tr>
														<tr>
													</tr>
													<tr>
														<td>
														<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">'.$emailer->getEmail('content-newsletter').'</p>
														</td>
													</tr>
													<tr>
														<td>
															<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;border:1px '.$emailer->getEmail('color').' dashed;padding:10px;margin-top:15px;list-style-type:none">Bạn cần được hỗ trợ ngay? Chỉ cần gửi mail về <a href="mailto:'.$emailer->getEmail('company:email').'" style="color:'.$emailer->getEmail('color').';text-decoration:none" target="_blank"> <strong>'.$emailer->getEmail('company:email').'</strong> </a>, hoặc gọi về hotline <strong style="color:'.$emailer->getEmail('color').'">'.$emailer->getEmail('company:hotline').'</strong> '.$emailer->getEmail('company:worktime').'. '.$emailer->getEmail('company:website').' luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.</p>
														</td>
													</tr>
													<tr>
														<td>&nbsp;
														<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">Một lần nữa '.$emailer->getEmail('company:website').' cảm ơn quý khách.</p>
														<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;text-align:right"><strong><a href="'.$emailer->getEmail('home').'" style="color:'.$emailer->getEmail('color').';text-decoration:none;font-size:14px" target="_blank">'.$emailer->getEmail('company').'</a> </strong></p>
														</td>
													</tr>
												</tbody>
											</table>
											</td>
										</tr>
									</tbody>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center">
								<table width="600">
									<tbody>
										<tr>
											<td>
											<p align="left" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:18px;color:#4b8da5;padding:10px 0;margin:0px;font-weight:normal">Quý khách nhận được email này vì đã liên hệ tại '.$emailer->getEmail('company:website').'.<br>
											Để chắc chắn luôn nhận được email thông báo, phản hồi từ '.$emailer->getEmail('company:website').', quý khách vui lòng thêm địa chỉ <strong><a href="mailto:'.$emailer->getEmail('email').'" target="_blank">'.$emailer->getEmail('email').'</a></strong> vào số địa chỉ (Address Book, Contacts) của hộp email.<br>
											<b>Địa chỉ:</b> '.$emailer->getEmail('company:address').'</p>
											</td>
										</tr>
									</tbody>
								</table>
								</td>
							</tr>
						</tbody>
					</table>';
					
				
					/* Send email customer */
					$arrayEmail = array(
						"dataEmail" => array(
							"name" => $ungtuyen['ten'],
							"email" => $ungtuyen['email']
						)
					);
					$subject = "Cập nhật trạng thái ứng tuyển từ ".$setting['tenvi'];
					$message = $contentCustomer;
					$file = 'file';
					
		
					$emailer->sendEmail("customer", $arrayEmail, $subject, $message, $file);
				}
			}
			if(isset($_FILES['file']))
			{
				$file_name = $func->uploadName($_FILES['file']["name"]);
				if($photo = $func->uploadImage("file", "doc|docx|pdf|rar|zip|ppt|pptx|DOC|DOCX|PDF|RAR|ZIP|PPT|PPTX|xls|xlsx|jpg|gif|png|jpeg|gif|JPG|PNG|JPEG|Png|GIF", '../'.UPLOAD_FILE_L,$file_name))
				{
					$data['cv'] = $photo;
				}
			}
			$d->where('id', $id);
			if($d->update('ungtuyen',$data)) $func->transfer("Cập nhật dữ liệu thành công", "index.php?com=ungtuyen&act=man&p=".$curPage);
			else $func->transfer("Cập nhật dữ liệu bị lỗi", "index.php?com=ungtuyen&act=man&p=".$curPage, false);
		} else {
			if(isset($_FILES['file']))
			{
				$file_name = $func->uploadName($_FILES['file']["name"]);
				if($photo = $func->uploadImage("file", "doc|docx|pdf|rar|zip|ppt|pptx|DOC|DOCX|PDF|RAR|ZIP|PPT|PPTX|xls|xlsx|jpg|gif|png|jpeg|gif|JPG|PNG|JPEG|Png|GIF", '../'.UPLOAD_FILE_L,$file_name))
				{
					$data['cv'] = $photo;
				}
			}
			if($d->insert('ungtuyen',$data)) {
				$func->transfer("Thêm thành công", "index.php?com=ungtuyen&act=man&p=".$curPage);
			}
		}
	}

	/* Delete ungtuyen */
	function delete_item()
	{
		global $d, $func, $curPage;

		$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

		if($id)
		{
			$row = $d->rawQueryOne("select id from #_ungtuyen where id = ? limit 0,1",array($id));

			if(isset($row['id']) && $row['id'] > 0)
			{
				$d->rawQuery("delete from #_ungtuyen where id = ?",array($id));
				$func->transfer("Xóa dữ liệu thành công", "index.php?com=ungtuyen&act=man&p=".$curPage);
			}
			else $func->transfer("Xóa dữ liệu bị lỗi", "index.php?com=ungtuyen&act=man&p=".$curPage, false);
		}
		elseif(isset($_GET['listid']))
		{
			$listid = explode(",",$_GET['listid']);
			
			for($i=0;$i<count($listid);$i++)
			{
				$id = htmlspecialchars($listid[$i]);
				$row = $d->rawQueryOne("select id from #_ungtuyen where id = ? limit 0,1",array($id));

				if(isset($row['id']) && $row['id'] > 0)
				{
					$d->rawQuery("delete from #_ungtuyen where id = ?",array($id));
				}
			}
			
			$func->transfer("Xóa dữ liệu thành công", "index.php?com=ungtuyen&act=man&p=".$curPage);
		}
		else $func->transfer("Không nhận được dữ liệu", "index.php?com=ungtuyen&act=man&p=".$curPage, false);
	}
?>