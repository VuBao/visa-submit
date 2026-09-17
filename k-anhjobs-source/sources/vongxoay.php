<?php  
if(!defined('SOURCES')) die("Error");

$phanthuong = $d->rawQuery("select id, tile, ten$lang as ten, photo from #_product where type = ? order by stt, id desc", array('vong-xoay-may-man'));

$noidung = $d->rawQueryOne("select noidung$lang as noidung from #_static where type = ? limit 0,1",array('vongxoay'));


$result = [];
foreach($phanthuong as $p) {
	$result[] = [
		'id' => $p['id'],
		'text' => $p['ten'],
		'img' => "upload/product/".$p['photo'],
		'percentpage' => 0 // 1%
	];
}

/* SEO */
$title_cat = $pro_cat['ten'];
$seoDB = $seo->getSeoDB($pro_cat['id'],'product','man_cat',$pro_cat['type']);
$seo->setSeo('h1',$pro_cat['ten']);
if(!empty($seoDB['title'.$seolang])) $seo->setSeo('title',$seoDB['title'.$seolang]);
else $seo->setSeo('title',$pro_cat['ten']);
if(!empty($seoDB['keywords'.$seolang])) $seo->setSeo('keywords',$seoDB['keywords'.$seolang]);
if(!empty($seoDB['description'.$seolang])) $seo->setSeo('description',$seoDB['description'.$seolang]);
$seo->setSeo('url',$func->getPageURL());
$img_json_bar = (isset($pro_cat['options']) && $pro_cat['options'] != '') ? json_decode($pro_cat['options'],true) : null;
if(!empty($pro_cat['photo']))
{
	if($img_json_bar == null || ($img_json_bar['p'] != $pro_cat['photo']))
	{
		$img_json_bar = $func->getImgSize($pro_cat['photo'],UPLOAD_PRODUCT_L.$pro_cat['photo']);
		$seo->updateSeoDB(json_encode($img_json_bar),'product_cat',$pro_cat['id']);
	}
	$seo->setSeo('photo',$config_base.THUMBS.'/'.$img_json_bar['w'].'x'.$img_json_bar['h'].'x2/'.UPLOAD_PRODUCT_L.$pro_cat['photo']);
	$seo->setSeo('photo:width',$img_json_bar['w']);
	$seo->setSeo('photo:height',$img_json_bar['h']);
	$seo->setSeo('photo:type',$img_json_bar['m']);
}

/* breadCrumbs */
if(isset($title_crumb) && $title_crumb != '') $breadcr->setBreadCrumbs($com,$title_crumb);
$breadcr->setBreadCrumbs($pro_list[$sluglang],$pro_list['ten']);
$breadcr->setBreadCrumbs($pro_cat[$sluglang],$pro_cat['ten']);
$breadcrumbs = $breadcr->getBreadCrumbs();

?>