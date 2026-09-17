<?php  
	if(!defined('SOURCES')) die("Error");

	@$id = htmlspecialchars($_GET['id']);
	@$idl = htmlspecialchars($_GET['idl']);
	@$idc = htmlspecialchars($_GET['idc']);
	@$idi = htmlspecialchars($_GET['idi']);
	@$ids = htmlspecialchars($_GET['ids']);

	if($id!='')
	{
		/* Lấy bài viết detail */
		$row_detail = $d->rawQueryOne("select * from #_news where id = ? and type = ? and hienthi > 0 limit 0,1",array($id,$type));

		/* Cập nhật lượt xem */
		$data_luotxem['luotxem'] = $row_detail['luotxem'] + 1;
		$d->where('id',$row_detail['id']);
		$d->update('news',$data_luotxem);

		if(isset($_POST['ungtuyen'])) {
			
			$iduser = $_SESSION[$login_member]['id'];
			$id_gioithieu = $_POST['id_gioithieu'];
			if($id_gioithieu) {
				$m_gt = $d->rawQueryOne("select * from #_member where username = ?", array($id_gioithieu));
				if(!$m_gt['id']) {
					$func->transfer(magioithieukhongchinhxac, $row_detail[$sluglang], false);
				} else {
					$data['id_member_gt'] = $m_gt['id'];
					$data['tennguoigioithieu'] = $m_gt['ten'];
				}
			}
			
			if ($iduser) {
				$member = $d->rawQueryOne('select * from #_member where id = ? and hienthi > 0', array($iduser));
				if(!$member['cv']) {
					$func->transfer(vuilongboxungcv, $config_base.'account/cap-nhat-ho-so', false);
				}else {
					$data['id_member'] = $member['id'];
					$data['ten'] = $member['ten'];
					$data['gioitinh'] = $member['gioitinh'];
					$data['ngaysinh'] = $member['ngaysinh'];
					$data['sodienthoai'] = $member['dienthoai'];
					$data['email'] = $member['email'];
					$data['diachi'] = $member['diachi'];
					$data['link_facebook'] = $member['link_facebook'];
					$data['id_news'] = $row_detail['id'];
					$data['tinhtrang'] = 1;
					$data['ngaytao'] = time();

					$d->insert('ungtuyen', $data);

					/* Gán giá trị gửi email */
					$strThongtin = '';
					$emailer->setEmail('tennguoigui',$member['ten']);
					$emailer->setEmail('emailnguoigui',$member['email']);
					$emailer->setEmail('dienthoainguoigui',$member['dienthoai']);
					$emailer->setEmail('diachinguoigui',$member['diachi']);
					$emailer->setEmail('tieudelienhe', "Ứng tuyển thành công vị trí ".$row_detail['ten'.$lang]);
					$emailer->setEmail('noidunglienhe', "Đơn ứng tuyển của bạn đã được ".$emailer->getEmail('company')." ghi nhận. ".$emailer->getEmail('company')." sẽ liên hệ với bạn trong thời gian nhanh nhất nếu hồ sơ của bạn phù hợp với yêu cầu tuyển dụng.");
					if($emailer->getEmail('tennguoigui'))
					{
						$strThongtin .= '<span style="text-transform:capitalize">'.$emailer->getEmail('tennguoigui').'</span><br>';
					}
					if($emailer->getEmail('emailnguoigui'))
					{
						$strThongtin .= '<a href="mailto:'.$emailer->getEmail('emailnguoigui').'" target="_blank">'.$emailer->getEmail('emailnguoigui').'</a><br>';
					}
					if($emailer->getEmail('diachinguoigui'))
					{
						$strThongtin .= ''.$emailer->getEmail('diachinguoigui').'<br>';
					}
					if($emailer->getEmail('dienthoainguoigui'))
					{
						$strThongtin .= 'Tel: '.$emailer->getEmail('dienthoainguoigui').'';
					}
					$emailer->setEmail('thongtin',$strThongtin);

					/* Nội dung gửi email cho admin */
					$contentAdmin = '
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
																	<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Kính chào</h1>
																	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">Bạn nhận được đơn ứng tuyển từ khách hàng <span style="text-transform:capitalize">'.$emailer->getEmail('tennguoigui').'</span> tại website '.$emailer->getEmail('company:website').'.</p>
																	<h3 style="font-size:13px;font-weight:bold;color:'.$emailer->getEmail('color').';text-transform:uppercase;margin:20px 0 0 0;padding: 0 0 5px;border-bottom:1px solid #ddd">Thông tin ứng tuyển <span style="font-size:12px;color:#777;text-transform:none;font-weight:normal">(Ngày '.date('d',$emailer->getEmail('datesend')).' tháng '.date('m',$emailer->getEmail('datesend')).' năm '.date('Y H:i:s',$emailer->getEmail('datesend')).')</span></h3>
																</td>
															</tr>
														<tr>
														<td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
														<table border="0" cellpadding="0" cellspacing="0" width="100%">
															<tbody>
																<tr>
																	<td style="padding:3px 0px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">'.$emailer->getEmail('thongtin').'</td>
																</tr>
																<tr>
																	<td colspan="2" style="border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444" valign="top">&nbsp;
																	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;margin-top:0"><strong>Nôi dung: </strong> '.$emailer->getEmail('tieudelienhe').'<br>
																	</td>
																</tr>
															</tbody>
														</table>
														</td>
													</tr>
													<tr>
														<td>
														<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><i></i></p>
														</td>
													</tr>
													<tr>
														<td>&nbsp;
															<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;border:1px '.$emailer->getEmail('color').' dashed;padding:10px;list-style-type:none">Bạn cần được hỗ trợ ngay? Chỉ cần gửi mail về <a href="mailto:'.$emailer->getEmail('company:email').'" style="color:'.$emailer->getEmail('color').';text-decoration:none" target="_blank"> <strong>'.$emailer->getEmail('company:email').'</strong> </a>, hoặc gọi về hotline <strong style="color:'.$emailer->getEmail('color').'">'.$emailer->getEmail('company:hotline').'</strong> '.$emailer->getEmail('company:worktime').'. '.$emailer->getEmail('company:website').' luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.</p>
														</td>
													</tr>
													<tr>
														<td>&nbsp;
														<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">Một lần nữa '.$emailer->getEmail('company:website').' cảm ơn bạn.</p>
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
																	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">Thông tin ứng tuyển của bạn đã được tiếp nhận. '.$emailer->getEmail('company:website').' sẽ phản hồi trong thời gian sớm nhất.</p>
																	<h3 style="font-size:13px;font-weight:bold;color:'.$emailer->getEmail('color').';text-transform:uppercase;margin:20px 0 0 0;padding: 0 0 5px;border-bottom:1px solid #ddd">Thông tin ứng tuyển <span style="font-size:12px;color:#777;text-transform:none;font-weight:normal">(Ngày '.date('d',$emailer->getEmail('datesend')).' tháng '.date('m',$emailer->getEmail('datesend')).' năm '.date('Y H:i:s',$emailer->getEmail('datesend')).')</span></h3>
																</td>
															</tr>
														<tr>
														<td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
														<table border="0" cellpadding="0" cellspacing="0" width="100%">
															<tbody>
																<tr>
																	<td style="padding:3px 0px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">'.$emailer->getEmail('thongtin').'</td>
																</tr>
																<tr>
																	<td colspan="2" style="border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444" valign="top">&nbsp;
																	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;margin-top:0"><strong>Nội dung: </strong> '.$emailer->getEmail('tieudelienhe').'<br>
																	</td>
																</tr>
															</tbody>
														</table>
														</td>
													</tr>
													<tr>
														<td>
														<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><i>'.$emailer->getEmail('noidunglienhe').'</i></p>
														
														</td>
													</tr>
													<tr>
														<td>&nbsp;
															<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;border:1px '.$emailer->getEmail('color').' dashed;padding:10px;list-style-type:none">Bạn cần được hỗ trợ ngay? Chỉ cần gửi mail về <a href="mailto:'.$emailer->getEmail('company:email').'" style="color:'.$emailer->getEmail('color').';text-decoration:none" target="_blank"> <strong>'.$emailer->getEmail('company:email').'</strong> </a>, hoặc gọi về hotline <strong style="color:'.$emailer->getEmail('color').'">'.$emailer->getEmail('company:hotline').'</strong> '.$emailer->getEmail('company:worktime').'. '.$emailer->getEmail('company:website').' luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.</p>
														</td>
													</tr>
													<tr>
														<td>&nbsp;
														<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">Một lần nữa '.$emailer->getEmail('company:website').' cảm ơn bạn đã dành sự quan tâm.</p>
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

					/* Send email admin */
					$arrayEmail = null;
					$subject = "Ứng tuyển từ ".$setting['ten'.$lang];
					$message = $contentAdmin;
					$file = 'file';

					if($emailer->sendEmail("admin", $arrayEmail, $subject, $message, $file))
					{
						/* Send email customer */
						$arrayEmail = array(
							"dataEmail" => array(
								"name" => $emailer->getEmail('tennguoigui'),
								"email" => $emailer->getEmail('emailnguoigui')
							)
						);
						$subject = "Thư ứng tuyển từ ".$setting['ten'.$lang];
						$message = $contentCustomer;
						$file = 'file';

						if($emailer->sendEmail("customer", $arrayEmail, $subject, $message, $file)) $func->transfer(ungtuyenthanhcong, $config_base.'account/danh-sach-ung-tuyen');
					}
					$func->transfer(ungtuyenthanhcong, $config_base.'account/danh-sach-ung-tuyen');
				}
			} else {
				$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
			}
		}

		/* Lấy cấp 1 */
		$news_list = $d->rawQueryOne("select * from #_news_list where id = ? and type = ? and hienthi > 0 limit 0,1",array($row_detail['id_list'],$type));

		/* Lấy cấp 2 */
		$news_cat = $d->rawQueryOne("select * from #_news_cat where id = ? and type = ? and hienthi > 0 limit 0,1",array($row_detail['id_cat'],$type));

		/* Lấy cấp 3 */
		$news_item = $d->rawQueryOne("select * from #_news_item where id = ? and type = ? and hienthi > 0 limit 0,1",array($row_detail['id_item'],$type));

		/* Lấy cấp 4 */
		$news_sub = $d->rawQueryOne("select * from #_news_sub where id = ? and type = ? and hienthi > 0 limit 0,1",array($row_detail['id_sub'],$type));	
		
		/* Lấy hình ảnh con */
		$hinhanhtt = $d->rawQuery("select photo from #_gallery where id_photo = ? and com='news' and type = ? and kind='man' and val = ? and hienthi > 0 order by stt,id desc",array($row_detail['id'],$type,$type));

		/* Lấy bài viết cùng loại */
		$where = "";
		$where = "id <> ? and id_list = ? and type = ? and hienthi > 0 and trangthai = 1 ";
		$params = array($id,$row_detail['id_list'],$type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* SEO */
		$seoDB = $seo->getSeoDB($row_detail['id'],'news','man',$row_detail['type']);
		$seo->setSeo('h1',$row_detail['ten']);
		if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
		else $seo->setSeo('title',$row_detail['ten']);
		if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
		if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($row_detail['options']) && $row_detail['options'] != '') ? json_decode($row_detail['options'],true) : null;
		if(!empty($row_detail['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $row_detail['photo']))
			{
				$img_json_bar = $func->getImgSize($row_detail['photo'],UPLOAD_NEWS_L.$row_detail['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'news',$row_detail['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_NEWS_L.$row_detail['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcr->setBreadCrumbs($news_list[$sluglang],$news_list['ten']);
		$breadcr->setBreadCrumbs($news_cat[$sluglang],$news_cat['ten']);
		$breadcr->setBreadCrumbs($news_item[$sluglang],$news_item['ten']);
		$breadcr->setBreadCrumbs($news_sub[$sluglang],$news_sub['ten']);
		$breadcr->setBreadCrumbs($row_detail[$sluglang],$row_detail['ten']);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
	else if($idl!='')
	{
		/* Lấy cấp 1 detail */
		$news_list = $d->rawQueryOne("select * from #_news_list where id = ? and type = ? limit 0,1",array($idl,$type));

		/* SEO */
		$title_cat = $news_list['ten'];
		$seoDB = $seo->getSeoDB($news_list['id'],'news','man_list',$news_list['type']);
		$seo->setSeo('h1',$news_list['ten']);
		if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
		else $seo->setSeo('title',$news_list['ten']);
		if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
		if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($news_list['options']) && $news_list['options'] != '') ? json_decode($news_list['options'],true) : null;
		if(!empty($news_list['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $news_list['photo']))
			{
				$img_json_bar = $func->getImgSize($news_list['photo'],UPLOAD_NEWS_L.$news_list['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'news_list',$news_list['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_NEWS_L.$news_list['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}
		$vitri = $d->rawQuery("select ten$lang as ten, id from #_search where hienthi > 0 and type = ? order by id desc", array('tinh-thanh'));

		/* Lấy bài viết */
		$where = "";
		$where = "id_list = ? and type = ? and hienthi > 0 and trangthai = 1";

		
		if(isset($_GET['vitri']) && $_GET['vitri']) {
			$where .= " and `id_tinh-thanh` = '".$_GET['vitri']."'";
		}
		$params = array($idl,$type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcr->setBreadCrumbs($news_list[$sluglang],$news_list['ten']);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
	else if($idc!='')
	{
		/* Lấy cấp 2 detail */
		$news_cat = $d->rawQueryOne("select * from #_news_cat where id = ? and type = ? limit 0,1",array($idc,$type));

		/* Lấy cấp 1 */
		$news_list = $d->rawQueryOne("select * from #_news_list where id = ? and type = ? limit 0,1",array($news_cat['id_list'],$type));
		
		/* Lấy bài viết */
		$where = "";
		$where = "id_cat = ? and type = ? and hienthi > 0";
		$params = array($idc,$type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* SEO */
		$title_cat = $news_cat['ten'];
		$seoDB = $seo->getSeoDB($news_cat['id'],'news','man_cat',$news_cat['type']);
		$seo->setSeo('h1',$news_cat['ten']);
		if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
		else $seo->setSeo('title',$news_cat['ten']);
		if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
		if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($news_cat['options']) && $news_cat['options'] != '') ? json_decode($news_cat['options'],true) : null;
		if(!empty($news_cat['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $news_cat['photo']))
			{
				$img_json_bar = $func->getImgSize($news_cat['photo'],UPLOAD_NEWS_L.$news_cat['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'news_cat',$news_cat['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_NEWS_L.$news_cat['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcr->setBreadCrumbs($news_list[$sluglang],$news_list['ten']);
		$breadcr->setBreadCrumbs($news_cat[$sluglang],$news_cat['ten']);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
	else if($idi!='')
	{
		/* Lấy cấp 3 detail */
		$news_item = $d->rawQueryOne("select * from #_news_item where id = ? and type = ? limit 0,1",array($idi,$type));

		/* Lấy cấp 1 */
		$news_list = $d->rawQueryOne("select * from #_news_list where id = ? and type = ? limit 0,1",array($news_item['id_list'],$type));

		/* Lấy cấp 2 */
		$news_cat = $d->rawQueryOne("select * from #_news_cat where id_list = ? and id = ? and type = ? limit 0,1",array($news_item['id_list'],$news_item['id_cat'],$type));

		/* Lấy bài viết */
		$where = "";
		$where = "id_item = ? and type = ? and hienthi > 0";
		$params = array($idi,$type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* SEO */
		$title_cat = $news_item['ten'];
		$seoDB = $seo->getSeoDB($news_item['id'],'news','man_item',$news_item['type']);
		$seo->setSeo('h1',$news_item['ten']);
		if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
		else $seo->setSeo('title',$news_item['ten']);
		if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
		if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($news_item['options']) && $news_item['options'] != '') ? json_decode($news_item['options'],true) : null;
		if(!empty($news_item['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $news_item['photo']))
			{
				$img_json_bar = $func->getImgSize($news_item['photo'],UPLOAD_NEWS_L.$news_item['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'news_item',$news_item['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_NEWS_L.$news_item['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcr->setBreadCrumbs($news_list[$sluglang],$news_list['ten']);
		$breadcr->setBreadCrumbs($news_cat[$sluglang],$news_cat['ten']);
		$breadcr->setBreadCrumbs($news_item[$sluglang],$news_item['ten']);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
	else if($ids!='')
	{
		/* Lấy cấp 4 */
		$news_sub = $d->rawQueryOne("select * from #_news_sub where id = ? and type = ? limit 0,1",array($ids,$type));

		/* Lấy cấp 1 */
		$news_list = $d->rawQueryOne("select * from #_news_list where id = ? and type = ? limit 0,1",array($news_sub['id_list'],$type));

		/* Lấy cấp 2 */
		$news_cat = $d->rawQueryOne("select * from #_news_cat where id_list = ? and id = ? and type = ? limit 0,1",array($news_sub['id_list'],$news_sub['id_cat'],$type));

		/* Lấy cấp 3 */
		$news_item = $d->rawQueryOne("select * from #_news_item where id_list = ? and id_cat = ? and id = ? and type = ? limit 0,1",array($news_sub['id_list'],$news_sub['id_cat'],$news_sub['id_item'],$type));

		/* Lấy bài viết */
		$where = "";
		$where = "id_sub = ? and type = ? and hienthi > 0";
		$params = array($ids,$type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* SEO */
		$title_cat = $news_sub['ten'];
		$seoDB = $seo->getSeoDB($news_sub['id'],'news','man_sub',$news_sub['type']);
		$seo->setSeo('h1',$news_sub['ten']);
		if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
		else $seo->setSeo('title',$news_sub['ten']);
		if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
		if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($news_sub['options']) && $news_sub['options'] != '') ? json_decode($news_sub['options'],true) : null;
		if(!empty($news_sub['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $news_sub['photo']))
			{
				$img_json_bar = $func->getImgSize($news_sub['photo'],UPLOAD_NEWS_L.$news_sub['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'news_sub',$news_sub['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_NEWS_L.$news_sub['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcr->setBreadCrumbs($news_list[$sluglang],$news_list['ten']);
		$breadcr->setBreadCrumbs($news_cat[$sluglang],$news_cat['ten']);
		$breadcr->setBreadCrumbs($news_item[$sluglang],$news_item['ten']);
		$breadcr->setBreadCrumbs($news_sub[$sluglang],$news_sub['ten']);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
	else
	{
		/* SEO */
		$seopage = $d->rawQueryOne("select * from #_seopage where type = ? limit 0,1",array($type));
		$seo->setSeo('h1',$title_crumb);
		if(!empty($seopage['title'.$seolang])) $seo->setSeo('title',$seopage['title'.$seolang]);
		else $seo->setSeo('title',$title_crumb);
		if(!empty($seopage['keywords'.$seolang])) $seo->setSeo('keywords',$seopage['keywords'.$seolang]);
		if(!empty($seopage['description'.$seolang])) $seo->setSeo('description',$seopage['description'.$seolang]);
		$seo->setSeo('url',$func->getPageURL());
		$img_json_bar = (isset($seopage['options']) && $seopage['options'] != '') ? json_decode($seopage['options'],true) : null;
		if(!empty($seopage['photo']))
		{
			if($img_json_bar == null || ($img_json_bar['p'] != $seopage['photo']))
			{
				$img_json_bar = $func->getImgSize($seopage['photo'],UPLOAD_SEOPAGE_L.$seopage['photo']);
				$seo->updateSeoDB(json_encode($img_json_bar),'seopage',$seopage['id']);
			}
			$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_SEOPAGE_L.$seopage['photo']);
			$seo->setSeo('photo:width',$img_json_bar['w']);
			$seo->setSeo('photo:height',$img_json_bar['h']);
			$seo->setSeo('photo:type',$img_json_bar['m']);
		}

		

		$vitri = $d->rawQuery("select ten$lang as ten, id from #_search where hienthi > 0 and type = ? order by id desc", array('tinh-thanh'));

		/* Lấy tất cả bài viết */
		$where = "";
		$where = "type = ? and hienthi > 0 and trangthai = 1";

		if(isset($_GET['vitri']) && $_GET['vitri']) {
			$where .= " and `id_tinh-thanh` = '".$_GET['vitri']."'";
		}

		$params = array($type);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_news where $where order by stt,id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_news where $where order by stt,id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);

		/* breadCrumbs */
		if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
		$breadcrumbs = $breadcr->getBreadCrumbs();
	}
?>