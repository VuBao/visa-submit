<?php
if (!defined('SOURCES')) die("Error");

$action = htmlspecialchars($match['params']['action']);
$background = $d->rawQueryOne("select photo from #_photo where type = ? and act = ? and hienthi > 0 limit 0,1",array('banner-auth','photo_static'));

switch ($action) {
	case 'dang-nhap':
		$title_crumb = dangnhap;
		$template = "account/dangnhap";
		$scope = 'https://www.googleapis.com/auth/userinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile';
		$google = 'https://accounts.google.com/o/oauth2/auth?response_type=code&client_id='.$optsetting['client_id_google'].'&redirect_uri='.$config_base.'account/login-google&scope='.$scope.'&approval_prompt=force&flowName=GeneralOAuthFlow';
		
        $facebook = 'https://www.facebook.com/v12.0/dialog/oauth?client_id='.$optsetting['client_id_facebook'].'&redirect_uri='.$config_base.'account/login-facebook&scope=email,public_profile,user_gender, user_link';
		if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) $func->transfer(bandaloginvaohethong, $config_base, false);
		if (isset($_POST['dangnhap'])) login();
		break;

	case 'login-facebook':
		login_facebook();
		break;

	case 'login-google':
		login_google();
		break;

	case 'dang-ky':
		$title_crumb = dangky;
		$template = "account/dangky";
		if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) $func->transfer(bandaloginvaohethong, $config_base, false);
		if (isset($_POST['dangky'])) signup();
		break;

	case 'dang-ky-ctv':
		$title_crumb = dangky;
		$template = "account/dangkyctv";
		if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) $func->transfer(bandaloginvaohethong, $config_base, false);
		if (isset($_POST['dangky'])) signupntd();
		break;

	case 'quen-mat-khau':
		$title_crumb = quenmatkhau;
		$template = "account/quenmatkhau";
		if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) $func->transfer(bandaloginvaohethong, $config_base, false);
		if (isset($_POST['quenmatkhau'])) doimatkhau_user();
		break;

	case 'kich-hoat':
		$title_crumb = kichhoat;
		$template = "account/kichhoat";
		if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) $func->transfer(bandaloginvaohethong, $config_base, false);
		if (isset($_POST['kichhoat'])) active_user();
		break;

	case 'thong-tin':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/thongtin";
		$title_crumb = capnhatthongtin;
		info_user();
		break;

	case 'cap-nhat-ho-so':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/capnhathoso";
		$title_crumb = capnhathoso;
		info_user();
		break;

	case 'dang-ky-nguyen-vong':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/dangkynguyenvong";
		$title_crumb = "Đăng ký nguyện vọng";
		info_user();
		break;

	case 'danh-sach-ung-tuyen':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/danhsachungtuyen";
		$title_crumb = (isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1) ? "Danh sách ứng viên của tôi" : danhsachungtuyen;
		danhsachungtuyen();
		break;
	
	case 'danh-sach-da-luu':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/danhsachdaluu";
		$title_crumb = danhsachdaluu;
		danhsachdaluu();
		break;

	case 'danh-sach-gioi-thieu':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/danhsachgioithieu";
		$title_crumb = danhsachgioithieu;
		danhsachgioithieu();
		break;

	case 'danh-sach-nguyen-vong':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		if ($_SESSION[$login_member]['role'] != 1) $func->transfer("Trang không tồn tại", $config_base, false);
		$template = "account/danhsachnguyenvong";
		$title_crumb = "Danh sách nguyện vọng ứng viên";
		danhsachnguyenvong_ctv();
		break;

	case 'ung-tuyen-ngay':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		ungtuyenngay();
		break;

	case 'chat':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		$template = "account/chat";
		$is_ctv = ($_SESSION[$login_member]['role'] == 1);
		if ($is_ctv) {
			$title_crumb = "Chat với ứng viên";
		} else {
			$title_crumb = "Chat với CTV";
		}
		chat();
		break;

	case 'dang-xuat':
		if (!isset($_SESSION[$login_member]['active']) || !$_SESSION[$login_member]['active']) $func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		logout();

	default:
		header('HTTP/1.0 404 Not Found', true, 404);
		include("404.php");
		exit();
}

/* SEO */
$seo->setSeo('title', $title_crumb);

/* breadCrumbs */
if (isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs('', $title_crumb);
$breadcrumbs = $breadcr->getBreadCrumbs();

function chat()
{
	global $d, $func, $config_base, $get_page, $login_member, $room, $message, $rooms, $ctv_senders, $id_ctv;
	$iduser = $_SESSION[$login_member]['id'];
	if($iduser) {
		$is_ctv = ($_SESSION[$login_member]['role'] == 1);
		if ($is_ctv) {
			$rooms = $d->rawQuery("select * from #_room order by ngaysua desc");
			$id_candidate = isset($_GET['id']) ? (int)$_GET['id'] : (count($rooms) ? (int)$rooms[0]['id_member'] : 0);
			if ($id_candidate) {
				$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				if(!$room['id'] && isset($_GET['id'])) {
					$data_room['id_member'] = $id_candidate;
					$data_room['ngaysua'] = time();
					$d->insert("room", $data_room);
					$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($id_candidate));
				}
				if (isset($room['id']) && $room['id']) {
					// CTV: load candidate messages addressed to this CTV (id_send_type=0, id_receive=iduser) + this CTV's own messages
					$message = $d->rawQuery("select * from #_message where id_room = ? and ((id_send_type = 0 and id_receive = ?) OR (id_send_type = 1 AND id_send = ?)) order by id desc limit 0,30", array($room['id'], $iduser, $iduser));
					// Only mark candidate messages addressed to this CTV as read
					$d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 0 and id_receive = ? and trangthai = 1", array($room['id'], $iduser));
				}
			}
		} else {
			$room = $d->rawQueryOne("select * from #_room where id_member = ?", array($iduser));
			if($room['id']) {
				$ctv_senders = $d->rawQuery("select distinct id_send from #_message where id_room = ? and id_send_type = 1", array($room['id']));
				$id_ctv = isset($_GET['id_ctv']) ? $_GET['id_ctv'] : (count($ctv_senders) ? $ctv_senders[0]['id_send'] : 'admin');
				$message = $d->rawQuery("select * from #_message where id_room = ? order by id desc limit 0,50", array($room['id']));
				// Mark as read only for the current chat partner
				if ($id_ctv == 'admin') {
					$d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 2 and trangthai = 1", array($room['id']));
				} else {
					$d->rawQuery("update #_message set trangthai = 2 where id_room = ? and id_send_type = 1 and id_send = ? and trangthai = 1", array($room['id'], (int)$id_ctv));
				}
			}
		}
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}
function login_facebook()
{
	global $d, $func, $config_base, $get_page, $per_page, $login_member, $optsetting;
	if (isset($_GET['code'])) {
		
		$secret = $optsetting['secret_facebook'];
		$client_id = $optsetting['client_id_facebook'];
		$redirect_url = $config_base.'account/login-facebook';
		$code = $_GET['code'];

		$url = "https://graph.facebook.com/v12.0/oauth/access_token?client_id=$client_id&redirect_uri=$redirect_url&client_secret=$secret&code=$code";
		$call = curl_init();
		curl_setopt($call, CURLOPT_URL, $url);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($call);
		$response = json_decode($response);
		$response = $response->access_token;
		curl_close($call);
		
		$url_get_info_user = 'https://graph.facebook.com/me?fields=id,name,link,gender,email,picture&access_token=' . $response;
		$call = curl_init();
		curl_setopt($call, CURLOPT_URL, $url_get_info_user);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		$user_info = curl_exec($call);
		curl_close($call);

		$user_info = json_decode($user_info);

		
		
		$check = $d->rawQueryOne("select * from #_member where username = ?", array($user_info->id));
		
		if($check['id']) {
			$id_user = $check['id'];
			$lastlogin = time();
			$login_session = md5($check['password'] . $lastlogin);
			$d->rawQuery("update #_member set login_session = ?, lastlogin = ? where id = ?", array($login_session, $lastlogin, $id_user));
			/* Lưu session login */
			$_SESSION[$login_member]['active'] = true;
			$_SESSION[$login_member]['id'] = $check['id'];
			$_SESSION[$login_member]['username'] = $check['username'];
			$_SESSION[$login_member]['dienthoai'] = $check['dienthoai'];
			$_SESSION[$login_member]['diachi'] = $check['diachi'];
			$_SESSION[$login_member]['email'] = $check['email'];
			$_SESSION[$login_member]['role'] = $check['role'];
			$_SESSION[$login_member]['login_session'] = $login_session;

			$avatar = "https://graph.facebook.com/".$user_info->id."/picture?type=large";
			$name = $user_info->id.'_'.time();
			$filename = 'upload/file/' . $name . '.jpg';
			if (file_exists('upload/file/'.$check['avatar'])) {
				unlink('upload/file/'.$check['avatar']);
			}

			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_HEADER, 0);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_URL, $avatar);
			// $datapic = curl_exec($ch);
			// curl_close($ch);

			// file_put_contents($filename, $datapic);
			// // Function to write image into file
			// file_put_contents($filename, $datapic);

			$datapic = file_get_contents($avatar);
			file_put_contents($filename, $datapic);

			$data_user['avatar'] = $name . '.jpg';
			$d->where('id', $check['id']);
			$d->update("member", $data_user);

			$func->transfer(dangnhapthanhcong, $config_base.'account/thong-tin');
		}else {
			$data_user['ten'] = $user_info->name;
			$data_user['username'] = $user_info->id;
			$data_user['password'] = md5($user_info->id);	
			$data_user['email'] = $user_info->email;
			$data_user['link_facebook'] = $user_info->link;
			$data_user['gioitinh'] = $user_info->gender;
			$data_user['dienthoai'] = (isset($_POST['dienthoai'])) ? htmlspecialchars($_POST['dienthoai']) : 0;
			$data_user['diachi'] = (isset($_POST['diachi'])) ? htmlspecialchars($_POST['diachi']) : '';
			$data_user['ngaysinh'] = (isset($_POST['ngaysinh'])) ? strtotime(str_replace("/", "-", $_POST['ngaysinh'])) : 0;
			$data_user['hienthi'] = 1;
			$data_user['ngaytao'] = time();

			$avatar = "https://graph.facebook.com/".$user_info->id."/picture?type=large";
			$name = $user_info->id.'_'.time();
			$filename = 'upload/file/' . $name . '.jpg';
			if (file_exists($filename)) {
				unlink($filename);
			}

			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_HEADER, 0);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_URL, $avatar);
			// $datapic = curl_exec($ch);
			// curl_close($ch);

			// file_put_contents($filename, $datapic);
			// // Function to write image into file
			// file_put_contents($filename, $datapic);
			$datapic = file_get_contents($avatar);
			file_put_contents($filename, $datapic);
		
			$data_user['avatar'] = $user_info->id . '.jpg';

			if($d->insert('member', $data_user)) {
				$row = $d->rawQueryOne("select * from #_member where username = ?", array($user_info->id));
				$id_user = $row['id'];
				$lastlogin = time();
				$login_session = md5($row['password'] . $lastlogin);
				$d->rawQuery("update #_member set login_session = ?, lastlogin = ? where id = ?", array($login_session, $lastlogin, $id_user));
	
				/* Lưu session login */
				$_SESSION[$login_member]['active'] = true;
				$_SESSION[$login_member]['id'] = $row['id'];
				$_SESSION[$login_member]['username'] = $row['username'];
				$_SESSION[$login_member]['dienthoai'] = $row['dienthoai'];
				$_SESSION[$login_member]['diachi'] = $row['diachi'];
				$_SESSION[$login_member]['email'] = $row['email'];
				$_SESSION[$login_member]['role'] = $row['role'];
				$_SESSION[$login_member]['login_session'] = $login_session;
				
				$func->transfer(dangnhapthanhcong, $config_base.'account/thong-tin');
			} else {
				$func->transfer(tendangnhaphoacmatkhaukhongdung, $config_base . "account/dang-nhap", false);
			}
		}
		
	}
}
function login_google()
{
	global $d, $func, $config_base, $get_page, $per_page, $login_member, $optsetting;
	
	if (isset($_GET['code'])) {
		$secret = $optsetting['secret_google'];
		$client_id = $optsetting['client_id_google'];
		$redirect_url = $config_base.'account/login-google';
		$code = $_GET['code'];

		$url = 'https://www.googleapis.com/oauth2/v4/token';
		$data = [
			'code'  => $code,
			'client_id' => $client_id,
			'client_secret' => $secret,
			
			'redirect_uri' => $redirect_url,
			'grant_type' => 'authorization_code'
		];
		$data_string = http_build_query($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$response = json_decode($response);
		$token = $response->access_token;
		curl_close($ch);

		$url_token = 'https://www.googleapis.com/oauth2/v3/userinfo?alt=json&access_token';
		$url_get_info_user = $url_token . "=$token";
		$call = curl_init();
		curl_setopt($call, CURLOPT_URL, $url_get_info_user);
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($call, CURLOPT_RETURNTRANSFER, 1);

		$user_info = curl_exec($call);
		curl_close($call);
		$user_info = json_decode($user_info);

		$check = $d->rawQueryOne("select * from #_member where username = ?", array($user_info->sub));		
		
		
		if($check['id']) {
			$id_user = $check['id'];
			$lastlogin = time();
			$login_session = md5($check['password'] . $lastlogin);
			$d->rawQuery("update #_member set login_session = ?, lastlogin = ? where id = ?", array($login_session, $lastlogin, $id_user));
			/* Lưu session login */
			$_SESSION[$login_member]['active'] = true;
			$_SESSION[$login_member]['id'] = $check['id'];
			$_SESSION[$login_member]['username'] = $check['username'];
			$_SESSION[$login_member]['dienthoai'] = $check['dienthoai'];
			$_SESSION[$login_member]['diachi'] = $check['diachi'];
			$_SESSION[$login_member]['email'] = $check['email'];
			$_SESSION[$login_member]['role'] = $check['role'];
			$_SESSION[$login_member]['login_session'] = $login_session;

			$func->transfer(dangnhapthanhcong, $config_base.'account/thong-tin');
		}else {
			$data_user['ten'] = $user_info->name;
			$data_user['username'] = $user_info->sub;
			$data_user['password'] = md5($user_info->sub);	
			$data_user['email'] = $user_info->email;
			$data_user['dienthoai'] = (isset($_POST['dienthoai'])) ? htmlspecialchars($_POST['dienthoai']) : 0;
			$data_user['diachi'] = (isset($_POST['diachi'])) ? htmlspecialchars($_POST['diachi']) : '';
			$data_user['gioitinh'] = (isset($_POST['gioitinh'])) ? htmlspecialchars($_POST['gioitinh']) : 0;
			$data_user['ngaysinh'] = (isset($_POST['ngaysinh'])) ? strtotime(str_replace("/", "-", $_POST['ngaysinh'])) : 0;
			$data_user['hienthi'] = 1;

			$avatar = $user_info->picture;
			$filename = 'upload/file/' . $user_info->sub . '.jpg';
			if (file_exists($filename)) {
				unlink($filename);
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $avatar);
			$datapic = curl_exec($ch);
			curl_close($ch);

			file_put_contents($filename, $datapic);
			// Function to write image into file
			file_put_contents($filename, $datapic);
		
			$data_user['avatar'] = $user_info->sub . '.jpg';
			$data_user['ngaytao'] = time();

			if($d->insert('member', $data_user)) {
				$row = $d->rawQueryOne("select * from #_member where username = ?", array($user_info->sub));
				$id_user = $row['id'];
				$lastlogin = time();
				$login_session = md5($row['password'] . $lastlogin);
				$d->rawQuery("update #_member set login_session = ?, lastlogin = ? where id = ?", array($login_session, $lastlogin, $id_user));
	
				/* Lưu session login */
				$_SESSION[$login_member]['active'] = true;
				$_SESSION[$login_member]['id'] = $row['id'];
				$_SESSION[$login_member]['username'] = $row['username'];
				$_SESSION[$login_member]['dienthoai'] = $row['dienthoai'];
				$_SESSION[$login_member]['diachi'] = $row['diachi'];
				$_SESSION[$login_member]['email'] = $row['email'];
				$_SESSION[$login_member]['role'] = $row['role'];
				$_SESSION[$login_member]['login_session'] = $login_session;
				
				$func->transfer(dangnhapthanhcong, $config_base.'account/thong-tin');
			} else {
				$func->transfer(tendangnhaphoacmatkhaukhongdung, $config_base . "account/dang-nhap", false);
			}
		}
	}
}



function ungtuyenngay()
{
	global $d, $func, $paging, $news, $config_base,$total, $get_page, $per_page, $login_member, $emailer,$setting, $lang;
	$iduser = $_SESSION[$login_member]['id'];
	if($iduser) {
		$id_news = $_GET['id_tuyendung'];
		$news = $d->rawQueryOne("select * from #_news where id = ?", array($id_news));
		
		if($id_news) {
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
				$data['id_news'] = $id_news;
				$data['tinhtrang'] = 1;
				$data['ngaytao'] = time();
				$d->insert('ungtuyen', $data);

				/* Gán giá trị gửi email */
				$strThongtin = '';
				$emailer->setEmail('tennguoigui',$member['ten']);
				$emailer->setEmail('emailnguoigui',$member['email']);
				$emailer->setEmail('dienthoainguoigui',$member['dienthoai']);
				$emailer->setEmail('diachinguoigui',$member['diachi']);
				$emailer->setEmail('tieudelienhe', "Ứng tuyển thành công vị trí ".$news['ten'.$lang]);
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

				$emailer->sendEmail("customer", $arrayEmail, $subject, $message, $file);

				/* Send email admin */
				$arrayEmail = null;
				$subject = "Ứng tuyển từ ".$setting['ten'.$lang];
				$message = $contentAdmin;
				$file = 'file';

				$emailer->sendEmail("admin", $arrayEmail, $subject, $message, $file);

				$func->transfer(ungtuyenthanhcong, $config_base.'account/danh-sach-ung-tuyen');
			}
		} else {
			$func->transfer(trangkhongtontai, $config_base, false);
		}
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}

function danhsachgioithieu()
{
	global $d, $func, $paging, $news, $config_base, $total, $get_page, $per_page, $login_member, $thang_selected, $nam_selected, $count_phongvan, $count_naitei, $count_visa, $count_lamviec;
	$iduser = $_SESSION[$login_member]['id'];

	if($iduser) {
		$thang_selected = (isset($_GET['thang'])) ? (int)$_GET['thang'] : (int)date('m');
		$nam_selected = (isset($_GET['nam'])) ? (int)$_GET['nam'] : (int)date('Y');

		$where_date = "";
		if($thang_selected > 0 && $nam_selected > 0) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $thang_selected, $nam_selected);
			$time_start = strtotime(sprintf("%04d-%02d-01 00:00:00", $nam_selected, $thang_selected));
			$time_end = strtotime(sprintf("%04d-%02d-%02d 23:59:59", $nam_selected, $thang_selected, $days_in_month));
			$where_date = " and (ngaytao >= $time_start and ngaytao <= $time_end)";
		} elseif($nam_selected > 0) {
			$time_start = strtotime(sprintf("%04d-01-01 00:00:00", $nam_selected));
			$time_end = strtotime(sprintf("%04d-12-31 23:59:59", $nam_selected));
			$where_date = " and (ngaytao >= $time_start and ngaytao <= $time_end)";
		}

		// Lấy danh sách ID ứng viên thuộc CTV này (trực tiếp thông qua #_ungtuyen.id_member_gt)
		$ctv_member_ids = array();
		$sub_members = array(); // query removed because id_member_gt doesn't exist in table_member
		if(!empty($sub_members)) {
			foreach($sub_members as $sm) $ctv_member_ids[] = (int)$sm['id'];
		}

		$where_ctv = "(id_member_gt = $iduser";
		if(!empty($ctv_member_ids)) {
			$where_ctv .= " or id_member IN (" . implode(",", $ctv_member_ids) . ")";
		}
		$where_ctv .= ")";

		// Thống kê thành tích theo 4 tình trạng (bao gồm cả mã legacy)
		$row_pv = $d->rawQueryOne("select count(id) as c from #_ungtuyen where $where_ctv and tinhtrang = 2 $where_date");
		$count_phongvan = (int)$row_pv['c'];

		$row_naitei = $d->rawQueryOne("select count(id) as c from #_ungtuyen where $where_ctv and tinhtrang = 3 $where_date");
		$count_naitei = (int)$row_naitei['c'];

		$row_visa = $d->rawQueryOne("select count(id) as c from #_ungtuyen where $where_ctv and tinhtrang = 4 $where_date");
		$count_visa = (int)$row_visa['c'];

		$row_lv = $d->rawQueryOne("select count(id) as c from #_ungtuyen where $where_ctv and tinhtrang = 5 $where_date");
		$count_lamviec = (int)$row_lv['c'];

		/* Lấy danh sách bài viết ứng tuyển */
		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select id_news, max(id) as max_id from #_ungtuyen where $where_ctv $where_date group by id_news order by max_id desc $limit";
		$news = $d->rawQuery($sql);
		$sqlNum = "select count(distinct id_news) as 'num' from #_ungtuyen where $where_ctv $where_date";
		$count = $d->rawQueryOne($sqlNum);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}

function danhsachnguyenvong_ctv()
{
	global $d, $func, $paging, $items, $config_base, $total, $get_page, $per_page, $login_member;
	$iduser = $_SESSION[$login_member]['id'];

	if($iduser) {
		$where = "role = 0 and trinhdotiengnhat is not null and trinhdotiengnhat != '' and duyetnguyenvong = 1 and id not in (select id_member from #_ungtuyen)";
		$params = array();

		if(isset($_REQUEST['keyword'])) {
			$keyword = htmlspecialchars($_REQUEST['keyword']);
			$where .= " and (ten LIKE ? or trinhdotiengnhat LIKE ? or tinhmongmuon LIKE ? or nganhnghemongmuon LIKE ?)";
			array_push($params, "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%");
		}

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_member where $where order by id desc $limit";
		$items = $d->rawQuery($sql, $params);
		$sqlNum = "select count(*) as 'num' from #_member where $where";
		$count = $d->rawQueryOne($sqlNum, $params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}

function danhsachdaluu()
{
	global $d, $func, $paging, $news, $config_base,$total, $get_page, $per_page, $login_member;
	$iduser = $_SESSION[$login_member]['id'];

	if($iduser) {
		/* Lấy tất cả bài viết */
		$where = "";
		$where = "id_member = ?";
		$params = array($iduser);

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_uuthich where $where order by id desc $limit";
		$news = $d->rawQuery($sql,$params);
		$sqlNum = "select count(*) as 'num' from #_uuthich where $where order by id desc";
		$count = $d->rawQueryOne($sqlNum,$params);
		$total = $count['num'];
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}
function danhsachungtuyen()
{
	global $d, $func, $paging, $news, $config_base, $total, $get_page, $per_page, $login_member, $ten_gioithieu;

	$iduser = $_SESSION[$login_member]['id'];

	if($iduser) {
		/* Lấy tất cả bài viết */
		$where = "";
		if (isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1) {
			$where = "(id_member = ? OR id_member_gt = ?)";
			$params = array($iduser, $iduser);

			$ctv = $d->rawQueryOne("select ten, username, mactv from #_member where id = ?", array($iduser));
			if ($ctv) {
				$ten_gioithieu = ($ctv['mactv'] ? '[' . $ctv['mactv'] . '] ' : '') . ($ctv['ten'] ? $ctv['ten'] : $ctv['username']);
			}
		} else {
			$where = "id_member = ?";
			$params = array($iduser);
		}
		
		$tinhtrang = (isset($_REQUEST['tinhtrang'])) ? htmlspecialchars($_REQUEST['tinhtrang']) : 0;
		if($tinhtrang) {
			$where .= " and tinhtrang = " . (int)$tinhtrang;
		}

		$curPage = $get_page;
		$per_page = 10;
		$startpoint = ($curPage * $per_page) - $per_page;
		$limit = " limit ".$startpoint.",".$per_page;
		$sql = "select * from #_ungtuyen where $where order by id desc $limit";
		$news = $d->rawQuery($sql, $params);
		$sqlNum = "select count(*) as 'num' from #_ungtuyen where $where";
		$count = $d->rawQueryOne($sqlNum, $params);
		$total = !empty($count['num']) ? $count['num'] : 0;
		$url = $func->getCurrentPageURL();
		$paging = $func->pagination($total,$per_page,$curPage,$url);
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}

function info_user()
{
	global $d, $func, $row_detail, $config_base, $login_member;

	$iduser = $_SESSION[$login_member]['id'];

	if ($iduser) {
		$row_detail = $d->rawQueryOne("select ten, username,link_facebook, role, quoctich, tucachcutru, thoihanvisa, kinhnghiemlamviec, gioithieubanthan,dienthoai, gioitinh,avatar, ngaysinh, email, diachi, soyeulilich, cv, thengoaitruoc, thengoaisau, trinhdotiengnhat, tinhmongmuon, nganhnghemongmuon, noidungmongmuon, mucluongmongmuon, thoigianchuyenviec, zalo_fb_line, zalo_fb_line_id, mongmuonkhac from #_member where id = ? limit 0,1", array($iduser));

		if (isset($_POST['submit-capnhat'])) {

			$data = array();

			$email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';
			/* Kiểm tra email thay đổi */
			$check = $d->rawQueryOne("select id from #_member where email = ? limit 0,1", array($email));
			if ($check['id']) {
				if($check['id'] != $iduser) {
					$_SESSION['popup_error'] = diachiemaildatontai;
					$func->redirect($config_base . "account/cap-nhat-ho-so");
					exit();
				}
			} 

			$dienthoai = (isset($_POST['dienthoai'])) ? htmlspecialchars($_POST['dienthoai']) : '';
			


			if(isset($_FILES["avatar"]))
			{
				$file_name = $func->uploadName($_FILES["avatar"]["name"]);
				if($file = $func->uploadImage("avatar", 'jpg|png|gif|jpeg|webp|JPG|PNG|GIF|JPEG|WEBP', UPLOAD_FILE_L, $file_name))
				{
					$data['avatar'] = $file;
					if($row_detail['avatar'] != null) {
						$func->delete_file(UPLOAD_FILE_L.$row_detail['avatar']);
					}
				}
			}

			if(isset($_FILES["file_soyeulilich"]))
			{
				$file_name = $func->uploadName($_FILES["file_soyeulilich"]["name"]);
				if($file = $func->uploadImage("file_soyeulilich", 'doc|docx|pdf|rar|zip|ppt|pptx|DOC|DOCX|PDF|RAR|ZIP|PPT|PPTX|xls|xlsx|jpg|gif|png|jpeg|webp|JPG|PNG|JPEG|Png|GIF|WEBP', UPLOAD_FILE_L, $file_name))
				{
					$data['soyeulilich'] = $file;
					if($row_detail['soyeulilich'] != null) {
						$func->delete_file(UPLOAD_FILE_L.$row_detail['soyeulilich']);
					}
				}
			}

			if(isset($_FILES["file_cv"]))
			{
				$file_name = $func->uploadName($_FILES["file_cv"]["name"]);
				if($file = $func->uploadImage("file_cv", 'doc|docx|pdf|rar|zip|ppt|pptx|DOC|DOCX|PDF|RAR|ZIP|PPT|PPTX|xls|xlsx|jpg|gif|png|jpeg|webp|JPG|PNG|JPEG|Png|GIF|WEBP', UPLOAD_FILE_L, $file_name))
				{
					$data['cv'] = $file;
					if($row_detail['cv'] != null) {
						$func->delete_file(UPLOAD_FILE_L.$row_detail['cv']);
					}
				}
			}

			if(isset($_FILES["file_thengoaitruoc"]))
			{
				$file_name = $func->uploadName($_FILES["file_thengoaitruoc"]["name"]);
				if($file = $func->uploadImage("file_thengoaitruoc", 'jpg|gif|png|jpeg|webp|JPG|PNG|JPEG|Png|GIF|WEBP', UPLOAD_FILE_L, $file_name))
				{
					$data['thengoaitruoc'] = $file;
					if($row_detail['thengoaitruoc'] != null) {
						$func->delete_file(UPLOAD_FILE_L.$row_detail['thengoaitruoc']);
					}
				}
			}

			if(isset($_FILES["file_thengoaisau"]))
			{
				$file_name = $func->uploadName($_FILES["file_thengoaisau"]["name"]);
				if($file = $func->uploadImage("file_thengoaisau", 'jpg|gif|png|jpeg|webp|JPG|PNG|JPEG|Png|GIF|WEBP', UPLOAD_FILE_L, $file_name))
				{
					$data['thengoaisau'] = $file;
					if($row_detail['thengoaisau'] != null) {
						$func->delete_file(UPLOAD_FILE_L.$row_detail['thengoaisau']);
					}
				}
			}

			$data['email'] = $email;
			if (isset($_POST['dienthoai'])) $data['dienthoai'] = htmlspecialchars($_POST['dienthoai']);
			if (isset($_POST['ten'])) $data['ten'] = htmlspecialchars($_POST['ten']);
			if (isset($_POST['link_facebook'])) $data['link_facebook'] = htmlspecialchars($_POST['link_facebook']);
			$data['gioitinh'] = (isset($_POST['gioitinh'])) ? htmlspecialchars($_POST['gioitinh']) : '';
			$data['quoctich'] = (isset($_POST['quoctich'])) ? htmlspecialchars($_POST['quoctich']) : '';
			$data['ngaysinh'] = (isset($_POST['ngaysinh'])) ? strtotime(str_replace("/", "-", htmlspecialchars($_POST['ngaysinh']))) : 0;
			$data['diachi'] = (isset($_POST['diachi'])) ? htmlspecialchars($_POST['diachi']) : '';
			if (isset($_POST['tucachcutru'])) $data['tucachcutru'] = htmlspecialchars($_POST['tucachcutru']);
			if (isset($_POST['thoihanvisa'])) $data['thoihanvisa'] = htmlspecialchars($_POST['thoihanvisa']);
			$data['kinhnghiemlamviec'] = (isset($_POST['kinhnghiemlamviec'])) ? htmlspecialchars($_POST['kinhnghiemlamviec']) : '';
			$data['gioithieubanthan'] = (isset($_POST['gioithieubanthan'])) ? htmlspecialchars($_POST['gioithieubanthan']) : '';

			$d->where('id', $iduser);
			if ($d->update('member', $data)) {
				$_SESSION['popup_success'] = capnhatthongtinthanhcong;
				$func->redirect($config_base . "account/thong-tin");
				exit();
			}
		}

		if (isset($_POST['submit-nguyenvong'])) {
			$data = array();
			$data['ten'] = (isset($_POST['ten'])) ? htmlspecialchars($_POST['ten']) : '';
			$data['tucachcutru'] = (isset($_POST['tucachcutru'])) ? htmlspecialchars($_POST['tucachcutru']) : '';
			$data['thoihanvisa'] = (isset($_POST['thoihanvisa'])) ? htmlspecialchars($_POST['thoihanvisa']) : '';
			$data['trinhdotiengnhat'] = (isset($_POST['trinhdotiengnhat'])) ? htmlspecialchars($_POST['trinhdotiengnhat']) : '';
			$data['tinhmongmuon'] = (isset($_POST['tinhmongmuon'])) ? htmlspecialchars($_POST['tinhmongmuon']) : '';
			$data['nganhnghemongmuon'] = (isset($_POST['nganhnghemongmuon'])) ? htmlspecialchars($_POST['nganhnghemongmuon']) : '';
			$data['noidungmongmuon'] = (isset($_POST['noidungmongmuon'])) ? htmlspecialchars($_POST['noidungmongmuon']) : '';
			$data['mucluongmongmuon'] = (isset($_POST['mucluongmongmuon'])) ? htmlspecialchars($_POST['mucluongmongmuon']) : '';
			$data['thoigianchuyenviec'] = (isset($_POST['thoigianchuyenviec'])) ? htmlspecialchars($_POST['thoigianchuyenviec']) : '';
			$data['zalo_fb_line'] = (isset($_POST['zalo_fb_line'])) ? htmlspecialchars($_POST['zalo_fb_line']) : '';
			$data['zalo_fb_line_id'] = (isset($_POST['zalo_fb_line_id'])) ? htmlspecialchars($_POST['zalo_fb_line_id']) : '';
			$data['mongmuonkhac'] = (isset($_POST['mongmuonkhac'])) ? htmlspecialchars($_POST['mongmuonkhac']) : '';
			$data['duyetnguyenvong'] = 0;
			$data['ngaydangkynguyenvong'] = time();

			$d->where('id', $iduser);
			if ($d->update('member', $data)) {
				$_SESSION['popup_success'] = "Cập nhật nguyện vọng ứng tuyển thành công";
				$func->redirect($config_base . "account/thong-tin");
				exit();
			}
		}
	} else {
		$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
	}
}

function active_user()
{
	global $d, $func, $row_detail, $config_base;

	$id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
	$maxacnhan = (isset($_POST['maxacnhan'])) ? htmlspecialchars($_POST['maxacnhan']) : '';

	/* Kiểm tra thông tin */
	$row_detail = $d->rawQueryOne("select hienthi, maxacnhan, id from #_member where id = ? limit 0,1", array($id));

	if (!$row_detail['id']) $func->transfer(taikhoanchuakichhoat, $config_base, false);
	else if ($row_detail['hienthi']) $func->transfer(taikhoandakichhoat, $config_base);
	else {
		if ($row_detail['maxacnhan'] == $maxacnhan) {
			$data['hienthi'] = 1;
			$data['maxacnhan'] = '';
			$d->where('id', $id);
			if ($d->update('member', $data)) $func->transfer(kickhoattaikhoanthanhcong, $config_base . "account/dang-nhap");
		} else {
			$func->transfer(makhongdung, $config_base . "account/kich-hoat?id=" . $id, false);
		}
	}
}

function login()
{
	global $d, $func, $login_member, $config_base;
	
	$login_type = (isset($_GET['type'])) ? htmlspecialchars($_GET['type']) : 'candidate';
	$email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';
	$password = (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '';
	$passwordMD5 = md5($password);
	$remember = (isset($_POST['remember-user'])) ? htmlspecialchars($_POST['remember-user']) : false;

	if (!$email) $func->transfer(chuanhaptaikhoan, 'account/dang-nhap?type='.$login_type, false);
	if (!$password) $func->transfer(chuanhapmatkhau, 'account/dang-nhap?type='.$login_type, false);

	$row = $d->rawQueryOne("select id, password, role, username, dienthoai, diachi, email, ten from #_member where email = ? and hienthi > 0 limit 0,1", array($email));

	if ($row['id']) {
		if ($row['password'] == $passwordMD5) {
			if ($login_type == 'ctv' && $row['role'] != 1) {
				$func->transfer("Tài khoản của bạn không phải là Cộng tác viên!", $config_base . "account/dang-nhap?type=ctv", false);
			}
			if ($login_type == 'candidate' && $row['role'] == 1) {
				$func->transfer("Tài khoản của bạn là Cộng tác viên, vui lòng chọn tab Cộng tác viên!", $config_base . "account/dang-nhap?type=candidate", false);
			}

			/* Tạo login session */
			$id_user = $row['id'];
			$lastlogin = time();
			$login_session = md5($row['password'] . $lastlogin);
			$d->rawQuery("update #_member set login_session = ?, lastlogin = ? where id = ?", array($login_session, $lastlogin, $id_user));

			/* Lưu session login */
			$_SESSION[$login_member]['active'] = true;
			$_SESSION[$login_member]['id'] = $row['id'];
			$_SESSION[$login_member]['username'] = $row['username'];
			$_SESSION[$login_member]['dienthoai'] = $row['dienthoai'];
			$_SESSION[$login_member]['diachi'] = $row['diachi'];
			$_SESSION[$login_member]['email'] = $row['email'];
			$_SESSION[$login_member]['role'] = $row['role'];
			$_SESSION[$login_member]['login_session'] = $login_session;

			/* Nhớ mật khẩu */
			setcookie('login_member_id', "", -1, '/');
			setcookie('login_member_session', "", -1, '/');
			if ($remember) {
				$time_expiry = time() + 3600 * 24;
				setcookie('login_member_id', $row['id'], $time_expiry, '/');
				setcookie('login_member_session', $login_session, $time_expiry, '/');
			}

			if ($row['role'] == 1) {
				$func->transfer(dangnhapthanhcong, $config_base.'account/danh-sach-gioi-thieu');
			} else {
				$func->transfer(dangnhapthanhcong, $config_base.'account/thong-tin');
			}
		} else {
			$func->transfer(tendangnhaphoacmatkhaukhongdung, $config_base . "account/dang-nhap?type=".$login_type, false);
		}
	} else {
		$func->transfer(tendangnhaphoacmatkhaukhongdung, $config_base . "account/dang-nhap?type=".$login_type, false);
	}
}
function signupntd() 
{
	global $d, $func, $config_base;

	$email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';
	$password = (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '';
	$passwordMD5 = md5($password);
	$repassword = (isset($_POST['repassword'])) ? htmlspecialchars($_POST['repassword']) : '';
	$maxacnhan = $func->digitalRandom(0, 3, 6);

	if ($password != $repassword) $func->transfer(matkhaukhongtrungnhau, $config_base . "account/dang-ky", false);

	/* Kiểm tra email đăng ký */
	$row = $d->rawQueryOne("select id from #_member where email = ? limit 0,1", array($email));
	if ($row['id']) $func->transfer(diachiemaildatontai, $config_base . "account/dang-ky-ctv", false);

	$data['ten'] = (isset($_POST['ten'])) ? htmlspecialchars($_POST['ten']) : '';
	$data['username'] = (isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : '';
	$data['password'] = md5($password);
	$data['email'] = $email;
	$data['dienthoai'] = (isset($_POST['dienthoai'])) ? htmlspecialchars($_POST['dienthoai']) : 0;
	$data['diachi'] = (isset($_POST['diachi'])) ? htmlspecialchars($_POST['diachi']) : '';
	$data['gioitinh'] = (isset($_POST['gioitinh'])) ? htmlspecialchars($_POST['gioitinh']) : 0;
	$data['ngaysinh'] = (isset($_POST['ngaysinh'])) ? strtotime(str_replace("/", "-", $_POST['ngaysinh'])) : 0;
	$data['maxacnhan'] = $maxacnhan;
	$data['role'] = 1;
	$data['hienthi'] = 0;
	$data['ngaytao'] = time();

	if ($d->insert('member', $data)) {
		// send_active_user($username);
		$func->transfer(dangkythanhcong, $config_base . "account/dang-nhap");
	} else {
		$func->transfer(dangkythatbai, $config_base, false);
	}
}
function signup()
{
	global $d, $func, $config_base;

	$email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';
	$password = (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '';
	$passwordMD5 = md5($password);
	$repassword = (isset($_POST['repassword'])) ? htmlspecialchars($_POST['repassword']) : '';
	$maxacnhan = $func->digitalRandom(0, 3, 6);

	if ($password != $repassword) $func->transfer(matkhaukhongtrungnhau, $config_base . "account/dang-ky", false);

	/* Kiểm tra email đăng ký */
	$row = $d->rawQueryOne("select id from #_member where email = ? limit 0,1", array($email));
	if ($row['id']) $func->transfer(diachiemaildatontai, $config_base . "account/dang-ky", false);

	$data['ten'] = (isset($_POST['ten'])) ? htmlspecialchars($_POST['ten']) : '';
	$data['username'] = (isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : '';
	$data['password'] = md5($password);
	$data['email'] = $email;
	$data['dienthoai'] = (isset($_POST['dienthoai'])) ? htmlspecialchars($_POST['dienthoai']) : 0;
	$data['diachi'] = (isset($_POST['diachi'])) ? htmlspecialchars($_POST['diachi']) : '';
	$data['gioitinh'] = (isset($_POST['gioitinh'])) ? htmlspecialchars($_POST['gioitinh']) : 0;
	$data['ngaysinh'] = (isset($_POST['ngaysinh'])) ? strtotime(str_replace("/", "-", $_POST['ngaysinh'])) : 0;
	$data['maxacnhan'] = $maxacnhan;
	$data['hienthi'] = 1;
	$data['ngaytao'] = time();

	if ($d->insert('member', $data)) {
		// send_active_user($username);
		$func->transfer(dangkythanhcong1, $config_base . "account/dang-nhap");
	} else {
		$func->transfer(dangkythatbai, $config_base, false);
	}
}

function send_active_user($username)
{
	global $d, $setting, $emailer, $func, $config_base, $lang;

	/* Lấy thông tin người dùng */
	$row = $d->rawQueryOne("select id, maxacnhan, username, password, ten, email, dienthoai, diachi from #_member where username = ? limit 0,1", array($username));

	/* Gán giá trị gửi email */
	$iduser = $row['id'];
	$maxacnhan = $row['maxacnhan'];
	$tendangnhap = $row['username'];
	$matkhau = $row['password'];
	$tennguoidung = $row['ten'];
	$emailnguoidung = $row['email'];
	$dienthoainguoidung = $row['dienthoai'];
	$diachinguoidung = $row['diachi'];
	$linkkichhoat = $config_base . "account/kich-hoat?id=" . $iduser;

	/* Thông tin đăng ký */
	$thongtindangky = '<td style="padding:3px 9px 9px 0px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top"><span style="text-transform:normal">Username: ' . $tendangnhap . '</span><br>Mật khẩu: *******' . substr($matkhau, -3) . '<br>Mã kích hoạt: ' . $maxacnhan . '</td><td style="padding:3px 0px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">';
	if ($tennguoidung) {
		$thongtindangky .= '<span style="text-transform:capitalize">' . $tennguoidung . '</span><br>';
	}
	if ($emailnguoidung) {
		$thongtindangky .= '<a href="mailto:' . $emailnguoidung . '" target="_blank">' . $emailnguoidung . '</a><br>';
	}
	if ($diachinguoidung) {
		$thongtindangky .= $diachinguoidung . '<br>';
	}
	if ($dienthoainguoidung) {
		$thongtindangky .= 'Tel: ' . $dienthoainguoidung . '</td>';
	}

	$contentMember = '
	<table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0" style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px" width="100%">
	<tbody>
	<tr>
	<td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px" width="600">
	<tbody>
	<tr>
	<td align="center" id="m_-6357629121201466163headerImage" valign="bottom">
	<table cellpadding="0" cellspacing="0" style="border-bottom:3px solid ' . $emailer->getEmail('color') . ';padding-bottom:10px;background-color:#fff" width="100%">
	<tbody>
	<tr>
	<td bgcolor="#FFFFFF" style="padding:0" valign="top" width="100%">
	<div style="color:#fff;background-color:f2f2f2;font-size:11px">&nbsp;</div>
	<div style="display:flex;justify-content:space-between;align-items:center;">
	<table style="width:100%;">
	<tbody>
	<tr>
	<td>
	<a href="' . $emailer->getEmail('home') . '" style="border:medium none;text-decoration:none;color:#007ed3;margin:0px 0px 0px 20px" target="_blank">' . $emailer->getEmail('logo') . '</a>
	</td>
	<td style="padding:15px 20px 0 0;text-align:right">' . $emailer->getEmail('social') . '</td>
	</tr>
	</tbody>
	</table>
	</div>
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
	<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Cảm ơn quý khách đã đăng ký tại ' . $emailer->getEmail('company:website') . '</h1>
	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">Thông tin tài khoản của quý khách đã được ' . $emailer->getEmail('company:website') . ' cập nhật. Quý khách vui lòng kích hoạt tài khoản bằng cách truy cập vào đường link phía dưới.</p>
	<h3 style="font-size:13px;font-weight:bold;color:' . $emailer->getEmail('color') . ';text-transform:uppercase;margin:20px 0 0 0;padding: 0 0 5px;border-bottom:1px solid #ddd">Thông tin tài khoản <span style="font-size:12px;color:#777;text-transform:none;font-weight:normal">(Ngày ' . date('d', $emailer->getEmail('datesend')) . ' tháng ' . date('m', $emailer->getEmail('datesend')) . ' năm ' . date('Y H:i:s', $emailer->getEmail('datesend')) . ')</span></h3>
	</td>
	</tr>
	<tr>
	<td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<thead>
	<tr>
	<th align="left" style="padding:6px 9px 0px 0px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%">Thông tin tài khoản</th>
	<th align="left" style="padding:6px 0px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%">Thông tin người dùng</th>
	</tr>
	</thead>
	<tbody>
	<tr>' . $thongtindangky . '</tr>
	</tbody>
	</table>
	</td>
	</tr>
	<tr>
	<td>
	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><i>Lưu ý: Quý khách vui lòng truy cập vào đường link phía dưới để hoàn tất quá trình đăng ký tài khoản.</i>
	<div style="margin:auto"><a href="' . $linkkichhoat . '" style="display:inline-block;text-decoration:none;background-color:' . $emailer->getEmail('color') . '!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:38%;margin-top:5px" target="_blank">Kích hoạt tài khoản</a></div>
	</p>
	</td>
	</tr>
	<tr>
	<td>&nbsp;
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;border:1px ' . $emailer->getEmail('color') . ' dashed;padding:10px;list-style-type:none">Bạn cần được hỗ trợ ngay? Chỉ cần gửi mail về <a href="mailto:' . $emailer->getEmail('company:email') . '" style="color:' . $emailer->getEmail('color') . ';text-decoration:none" target="_blank"> <strong>' . $emailer->getEmail('company:email') . '</strong> </a>, hoặc gọi về hotline <strong style="color:' . $emailer->getEmail('color') . '">' . $emailer->getEmail('company:hotline') . '</strong> ' . $emailer->getEmail('company:worktime') . '. ' . $emailer->getEmail('company:website') . ' luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.</p>
	</td>
	</tr>
	<tr>
	<td>&nbsp;
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">Một lần nữa ' . $emailer->getEmail('company:website') . ' cảm ơn quý khách.</p>
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;text-align:right"><strong><a href="' . $emailer->getEmail('home') . '" style="color:' . $emailer->getEmail('color') . ';text-decoration:none;font-size:14px" target="_blank">' . $emailer->getEmail('company') . '</a> </strong></p>
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
	<p align="left" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:18px;color:#4b8da5;padding:10px 0;margin:0px;font-weight:normal">Quý khách nhận được email này vì đã đăng ký tại ' . $emailer->getEmail('company:website') . '.<br>
	Để chắc chắn luôn nhận được email thông báo, phản hồi từ ' . $emailer->getEmail('company:website') . ', quý khách vui lòng thêm địa chỉ <strong><a href="mailto:' . $emailer->getEmail('email') . '" target="_blank">' . $emailer->getEmail('email') . '</a></strong> vào số địa chỉ (Address Book, Contacts) của hộp email.<br>
	<b>Địa chỉ:</b> ' . $emailer->getEmail('company:address') . '</p>
	</td>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	</tbody>
	</table>';

	/* Send email admin */
	$arrayEmail = array(
		"dataEmail" => array(
			"name" => $row['username'],
			"email" => $row['email']
		)
	);
	$subject = thukichhoat . $setting['ten' . $lang];
	$message = $contentMember;
	$file = '';

	if (!$emailer->sendEmail("customer", $arrayEmail, $subject, $message, $file)) $func->transfer("Có lỗi xảy ra trong quá trình kích hoạt tài khoản. Vui lòng liên hệ với chúng tôi.", $config_base . "lien-he", false);
}

function doimatkhau_user()
{
	global $d, $setting, $emailer, $func, $login_member, $config_base, $lang;

	$email = (isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : '';
	$newpass = substr(md5(rand(0, 999) * time()), 15, 6);
	$newpassMD5 = md5($newpass);

	if (!$email) $func->transfer(chuanhapemaildangkytaikhoan, $config_base . "account/quen-mat-khau", false);

	/* Kiểm tra username và email */
	$row = $d->rawQueryOne("select id from #_member where email = ? limit 0,1", array($email));
	if (!$row['id']) $func->transfer(emailkhongtontai, $config_base . "account/quen-mat-khau", false);

	/* Cập nhật mật khẩu mới */
	$data['password'] = $newpassMD5;
	$d->where('email', $email);
	$d->update('member', $data);

	/* Lấy thông tin người dùng */
	$row = $d->rawQueryOne("select id, username, password, ten, email, dienthoai, diachi from #_member where username = ? limit 0,1", array($username));

	/* Gán giá trị gửi email */
	$iduser = $row['id'];
	$tendangnhap = $row['username'];
	$matkhau = $row['password'];
	$tennguoidung = $row['ten'];
	$emailnguoidung = $row['email'];
	$dienthoainguoidung = $row['dienthoai'];
	$diachinguoidung = $row['diachi'];

	/* Thông tin đăng ký */
	$thongtindangky = '<td style="padding:3px 9px 9px 0px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top"><span style="text-transform:normal">Username: ' . $tendangnhap . '</span><br>Mật khẩu: *******' . substr($matkhau, -3) . '</td><td style="padding:3px 0px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">';
	if ($tennguoidung) {
		$thongtindangky .= '<span style="text-transform:capitalize">' . $tennguoidung . '</span><br>';
	}

	if ($emailnguoidung) {
		$thongtindangky .= '<a href="mailto:' . $emailnguoidung . '" target="_blank">' . $emailnguoidung . '</a><br>';
	}

	if ($diachinguoidung) {
		$thongtindangky .= $diachinguoidung . '<br>';
	}

	if ($dienthoainguoidung) {
		$thongtindangky .= 'Tel: ' . $dienthoainguoidung . '</td>';
	}

	$contentMember = '
	<table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0" style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px" width="100%">
	<tbody>
	<tr>
	<td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px" width="600">
	<tbody>
	<tr>
	<td align="center" id="m_-6357629121201466163headerImage" valign="bottom">
	<table cellpadding="0" cellspacing="0" style="border-bottom:3px solid ' . $emailer->getEmail('color') . ';padding-bottom:10px;background-color:#fff" width="100%">
	<tbody>
	<tr>
	<td bgcolor="#FFFFFF" style="padding:0" valign="top" width="100%">
	<div style="color:#fff;background-color:f2f2f2;font-size:11px">&nbsp;</div>
	<table style="width:100%;">
	<tbody>
	<tr>
	<td>
	<a href="' . $emailer->getEmail('home') . '" style="border:medium none;text-decoration:none;color:#007ed3;margin:0px 0px 0px 20px" target="_blank">' . $emailer->getEmail('logo') . '</a>
	</td>
	<td style="padding:15px 20px 0 0;text-align:right">' . $emailer->getEmail('social') . '</td>
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
	<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">'.kinhchaoquykhach.'</h1>
	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">'.vuilongnhapvaoduongdanphiaduoi.'</p>
	<h3 style="font-size:13px;font-weight:bold;color:' . $emailer->getEmail('color') . ';text-transform:uppercase;margin:20px 0 0 0;padding: 0 0 5px;border-bottom:1px solid #ddd">'.thongtintaikhoan.'<span style="font-size:12px;color:#777;text-transform:none;font-weight:normal">(Ngày ' . date('d', $emailer->getEmail('datesend')) . ' tháng ' . date('m', $emailer->getEmail('datesend')) . ' năm ' . date('Y H:i:s', $emailer->getEmail('datesend')) . ')</span></h3>
	</td>
	</tr>
	<tr>
	<td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<thead>
	<tr>
	<th align="left" style="padding:6px 9px 0px 0px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%">'.thongtintaikhoan.'</th>
	<th align="left" style="padding:6px 0px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%">'.thongtinnguoidung.'</th>
	</tr>
	</thead>
	<tbody>
	<tr>' . $thongtindangky . '</tr>
	</tbody>
	</table>
	</td>
	</tr>
	<tr>
	<td>
	<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><i>'.luuyquenmatkhau.'</i>
	<div style="margin:auto"><p style="display:inline-block;text-decoration:none;background-color:' . $emailer->getEmail('color') . '!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:33%;margin-top:5px" target="_blank">'.matkhaumoi.': ' . $newpass . '</p></div>
	</p>
	</td>
	</tr>
	<tr>
	<td>&nbsp;
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;border:1px ' . $emailer->getEmail('color') . ' dashed;padding:10px;list-style-type:none">'.bancanhotrongay.'? '.chicanguimailve.' <a href="mailto:' . $emailer->getEmail('company:email') . '" style="color:' . $emailer->getEmail('color') . ';text-decoration:none" target="_blank"> <strong>' . $emailer->getEmail('company:email') . '</strong> </a>, '.hoatgoivehotline.' <strong style="color:' . $emailer->getEmail('color') . '">' . $emailer->getEmail('company:hotline') . '</strong> ' . $emailer->getEmail('company:worktime') . '. ' . $emailer->getEmail('company:website') . ''.luonsansanhotrobatculucnao.' </p>
	</td>
	</tr>
	<tr>
	<td>&nbsp;
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">'.motlannua.' ' . $emailer->getEmail('company:website') . ' '.camonquykhach.'.</p>
	<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal;text-align:right"><strong><a href="' . $emailer->getEmail('home') . '" style="color:' . $emailer->getEmail('color') . ';text-decoration:none;font-size:14px" target="_blank">' . $emailer->getEmail('company') . '</a> </strong></p>
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
	<p align="left" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:18px;color:#4b8da5;padding:10px 0;margin:0px;font-weight:normal">Quý khách nhận được email này vì đã liên hệ tại ' . $emailer->getEmail('company:website') . '.<br>
	Để chắc chắn luôn nhận được email thông báo, phản hồi từ ' . $emailer->getEmail('company:website') . ', quý khách vui lòng thêm địa chỉ <strong><a href="mailto:' . $emailer->getEmail('email') . '" target="_blank">' . $emailer->getEmail('email') . '</a></strong> vào số địa chỉ (Address Book, Contacts) của hộp email.<br>
	<b>Địa chỉ:</b> ' . $emailer->getEmail('company:address') . '</p>
	</td>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	</tbody>
	</table>';

	/* Send email admin */
	$arrayEmail = array(
		"dataEmail" => array(
			"name" => $tennguoidung,
			"email" => $email
		)
	);
	$subject = thucaplaimatkhautu . ' ' . $setting['ten' . $lang];
	$message = $contentMember;
	$file = '';

	if ($emailer->sendEmail("customer", $arrayEmail, $subject, $message, $file)) {
		unset($_SESSION[$login_member]);
		setcookie('login_member_id', "", -1, '/');
		setcookie('login_member_session', "", -1, '/');
		$func->transfer(caplaimatkhauthanhcong.": " . $email, $config_base);
	} else {
		$func->transfer(coloixayravuilonglienhevoichungtoi, $config_base . "account/quen-mat-khau", false);
	}
}

function logout()
{
	global $d, $func, $login_member, $config_base;

	unset($_SESSION[$login_member]);
	setcookie('login_member_id', "", -1, '/');
	setcookie('login_member_session', "", -1, '/');

	$func->redirect($config_base);
}