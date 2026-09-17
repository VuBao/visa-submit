<?php
if (!defined('SOURCES')) die("Error");

$share = htmlspecialchars($match['params']['share']);

/* SEO */
$seo->setSeo('title', $title_crumb);

/* breadCrumbs */
if (isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs('', $title_crumb);
$breadcrumbs = $breadcr->getBreadCrumbs();

if(!$share) {
	$func->transfer("Trang không tồn tại", $config_base, false);
} else {
	$share = $func->giaima_share($share);
	$share = explode(",", $share);
	
	$row_detail = $d->rawQueryOne("select * from #_news where id = ? and hienthi > 0 limit 0,1",array($share[1]));
	
	$news_list = $d->rawQueryOne("select * from #_news_list where id = ?  and hienthi > 0 limit 0,1",array($row_detail['id_list']));

	$hinhanhtt = $d->rawQuery("select photo from #_gallery where id_photo = ? and com='news' and type = ? and kind='man' and val = ? and hienthi > 0 order by stt,id desc",array($row_detail['id'],"tin-tuyen-dung","tin-tuyen-dung"));
	/* Cập nhật lượt xem */
	$data_luotxem['luotxem'] = $row_detail['luotxem'] + 1;
	$d->where('id',$row_detail['id']);
	$d->update('news',$data_luotxem);

	if(isset($_POST['ungtuyen'])) {
		$iduser = $_SESSION[$login_member]['id'];
		if ($iduser) {
			if($iduser == $share[0]) { $func->transfer("Không thể thực hiện", $config_base, false); }

			$member = $d->rawQueryOne('select * from #_member where id = ? and hienthi > 0', array($iduser));
			if(!$member['cv']) {
				$func->transfer(vuilongboxungcv, $config_base.'account/cap-nhat-ho-so', false);
			}else {
				$data['id_member'] = $member['id'];
				$data['id_member_gt'] = $share[0];
				$data['id_news'] = $row_detail['id'];
				$data['tinhtrang'] = 1;
				$data['ngaytao'] = time();
				$d->insert('ungtuyen', $data);
				$func->transfer(ungtuyenthanhcong, $config_base.'account/danh-sach-ung-tuyen');
			}
		} else {
			$func->transfer(dangnhapdetieptuc, $config_base.'account/dang-nhap', false);
		}
	}
	$template = "tuyendung/news_detail";
}





