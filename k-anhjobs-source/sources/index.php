<?php  
if(!defined('SOURCES')) die("Error");

$slider = $d->rawQuery("select IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, photo, link from #_photo where type = ? and hienthi > 0 order by stt,id desc",array('slide'));

$gioithieu = $d->rawQueryOne("select id, type, IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, IF(mota$lang != '' AND mota$lang IS NOT NULL, mota$lang, motavi) as mota, photo, ngaytao, ngaysua from #_static where type = ? limit 0,1",array('gioi-thieu'));

$dm1news = $d->rawQuery("select IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, tenkhongdauvi, IF(tenkhongdau$lang != '' AND tenkhongdau$lang IS NOT NULL, tenkhongdau$lang, tenkhongdauvi) as $sluglang, id, noibat, photo from #_news_list where type = ? and noibat > 0 and hienthi > 0 order by stt,id desc",array('tin-tuyen-dung'));

$dm1newsgim = $d->rawQuery("select IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, tenkhongdauvi, IF(tenkhongdau$lang != '' AND tenkhongdau$lang IS NOT NULL, tenkhongdau$lang, tenkhongdauvi) as $sluglang, id, noibat, photo from #_news_list where type = ? and gim > 0 and hienthi > 0 order by stt,id desc",array('tin-tuyen-dung'));

$tinnoibat = $d->rawQuery("select IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, tenkhongdauvi, IF(tenkhongdau$lang != '' AND tenkhongdau$lang IS NOT NULL, tenkhongdau$lang, tenkhongdauvi) as $sluglang, diadiem, ngaytao, id, photo from #_news where type = ? and noibat > 0 and hienthi > 0 and trangthai = 1 order by stt,id desc",array('tin-tuyen-dung'));

$tintuc = $d->rawQuery("select IF(ten$lang != '' AND ten$lang IS NOT NULL, ten$lang, tenvi) as ten, tenkhongdauvi, IF(tenkhongdau$lang != '' AND tenkhongdau$lang IS NOT NULL, tenkhongdau$lang, tenkhongdauvi) as $sluglang, IF(mota$lang != '' AND mota$lang IS NOT NULL, mota$lang, motavi) as mota, ngaytao, id, photo from #_news where type = ? and hienthi > 0 and trangthai = 1 order by stt,id desc",array('tin-tuc-va-su-kien'));


$thuvienanh = $d->rawQuery("select ten$lang as ten, tenkhongdauvi, tenkhongdauen, mota$lang as mota, ngaytao, id, photo from #_product where type = ? and noibat > 0 and hienthi > 0 order by stt,id desc limit 0,5",array('thu-vien-anh'));

$quangcao = $d->rawQueryOne("select ten$lang as ten, photo, link, hienthi from #_photo where hienthi > 0 and type = ? and act = ? limit 0,1",array('quangcao','photo_static'));

$videonb = $d->rawQuery("select id from #_photo where noibat > 0 and type = ? and hienthi > 0",array('video'));

$doitac = $d->rawQuery("select ten$lang as ten, link, photo from #_photo where type = ? and hienthi > 0 order by stt, id desc",array('doitac'));

$popup = $d->rawQueryOne("select ten$lang as ten, photo, link, hienthi from #_photo where type = ? and act = ? limit 0,1",array('popup','photo_static'));

$candidate_intents = array();
$show_intent_popup = false;
if (isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true) {
    $user_info = $d->rawQueryOne("select role, trinhdotiengnhat from #_member where id = ? limit 0,1", array($_SESSION[$login_member]['id']));
    if ($user_info) {
        if ($user_info['role'] == 1) { // CTV
            $candidate_intents = $d->rawQuery("select * from #_member where role = 0 and trinhdotiengnhat is not null and trinhdotiengnhat != '' and duyetnguyenvong = 1 and id not in (select id_member from #_ungtuyen) order by id desc limit 0,5");
        } else if ($user_info['role'] == 0) { // Candidate
            if (empty($user_info['trinhdotiengnhat'])) {
                $show_intent_popup = true;
            }
        }
    }
}

/* SEO */
$seoDB = $seo->getSeoDB(0,'setting','capnhat','setting');
$seo->setSeo('h1',$seoDB['title'.$seolang]);
$seo->setSeo('title',$seoDB['title'.$seolang]);
$seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
$seo->setSeo('description',$seoDB['description'.$seolang]);
$seo->setSeo('url',$func->getPageURL());
$img_json_bar = (isset($logo['options']) && $logo['options'] != '') ? json_decode($logo['options'],true) : null;
if($img_json_bar == null || ($img_json_bar['p'] != $logo['photo']))
{
    $img_json_bar = $func->getImgSize($logo['photo'],UPLOAD_PHOTO_L.$logo['photo']);
    $seo->updateSeoDB(json_encode($img_json_bar),'photo',$logo['id']);
}
$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x1/'.UPLOAD_PHOTO_L.$logo['photo']);
$seo->setSeo('photo:width',$img_json_bar['w']);
$seo->setSeo('photo:height',$img_json_bar['h']);
$seo->setSeo('photo:type',$img_json_bar['m']);
?>