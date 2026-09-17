<?php
/* Validate URL */
$func->checkUrl($config['website']['index']);

/* Check login */
$func->checkLogin();

/* Mobile detect */
$deviceType = ($detect->isMobile() || $detect->isTablet()) ? 'mobile' : 'computer';
if($deviceType == 'computer') define('TEMPLATE','./templates/');
    // else define('TEMPLATE','./templates-mobile/');
else define('TEMPLATE','./templates/');

/* Watermark */
$wtmPro = $d->rawQueryOne("select hienthi, photo, options from #_photo where type = ? and act = ? limit 0,1",array('watermark','photo_static'));
$wtmNews = $d->rawQueryOne("select hienthi, photo, options from #_photo where type = ? and act = ? limit 0,1",array('watermark-news','photo_static'));

/* Router */
$router->setBasePath($config['database']['url']);
$router->map('GET',array('admin/','admin'), function(){
	global $func, $config;
	$func->redirect($config['database']['url']."admin/index.php");
	exit;
});
$router->map('GET',array('admin','admin'), function(){
	global $func, $config;
	$func->redirect($config['database']['url']."admin/index.php");
	exit;
});
$router->map('GET|POST', '', 'index', 'home');
$router->map('GET|POST', 'index.php', 'index', 'index');
$router->map('GET|POST', 'sitemap.xml', 'sitemap', 'sitemap');
$router->map('GET|POST', '[a:com]', 'allpage', 'show');
$router->map('GET|POST', '[a:com]/[a:lang]/', 'allpagelang', 'lang');
$router->map('GET|POST', 'share/[a:share]', 'share', 'share');
$router->map('GET|POST', '[a:com]/[a:action]', 'account', 'account');


$router->map('GET', THUMBS.'/[i:w]x[i:h]x[i:z]/[**:src]', function($w,$h,$z,$src){
	global $func;
	$func->createThumb($w,$h,$z,$src,null,THUMBS);
},'thumb');
$router->map('GET', WATERMARK.'/product/[i:w]x[i:h]x[i:z]/[**:src]', function($w,$h,$z,$src){
	global $func, $wtmPro;
	$func->createThumb($w,$h,$z,$src,$wtmPro,"product");
},'watermark');
$router->map('GET', WATERMARK.'/news/[i:w]x[i:h]x[i:z]/[**:src]', function($w,$h,$z,$src){
	global $func, $wtmNews;
	$func->createThumb($w,$h,$z,$src,$wtmNews,"news");
},'watermarkNews');
$match = $router->match();
if(is_array($match))
{
	if(is_callable($match['target']))
	{
		call_user_func_array($match['target'], $match['params']); 
	}
	else
	{
		$com = (isset($match['params']['com'])) ? htmlspecialchars($match['params']['com']) : htmlspecialchars($match['target']);
		$get_page = isset($_GET['p']) ? htmlspecialchars($_GET['p']) : 1;

		/* Handle Language Switch */
		if ($com == 'ngon-ngu-vn' || $com == 'ngon-ngu-en' || $com == 'ngon-ngu-jp') {
			if ($com == 'ngon-ngu-vn') $_SESSION['lang'] = 'vi';
			else if ($com == 'ngon-ngu-en') $_SESSION['lang'] = 'en';
			else if ($com == 'ngon-ngu-jp') $_SESSION['lang'] = 'jp';

			$referer = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ngon-ngu-') === false) ? $_SERVER['HTTP_REFERER'] : $config_base;
			header("Location: " . $referer);
			exit();
		}
	}
}
else
{
	header('HTTP/1.0 404 Not Found', true, 404);
	include("404.php");
	exit;
}

/* Setting */
$sqlCache = "select * from #_setting";
$setting = $cache->getCache($sqlCache,'fetch',7200);
$optsetting = (isset($setting['options']) && $setting['options'] != '') ? json_decode($setting['options'],true) : null;

/* Lang */
if(isset($match['params']['lang']) && $match['params']['lang'] != '') $_SESSION['lang'] = $match['params']['lang'];
else if(!isset($_SESSION['lang']) && !isset($match['params']['lang'])) $_SESSION['lang'] = $optsetting['lang_default'];
$lang = $_SESSION['lang'];

/* Slug lang */
$sluglang = 'tenkhongdau'.$lang;

/* SEO Lang */
$seolang = $_SESSION['lang'];

/* Require datas */
require_once LIBRARIES."lang/lang$lang.php";
require_once SOURCES."allpage.php";

/* Tối ưu link */
$requick = array(
	
	array("tbl"=>"news_list","field"=>"idl","source"=>"news","com"=>"tin-tuyen-dung","type"=>"tin-tuyen-dung"),
	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"tin-tuyen-dung","type"=>"tin-tuyen-dung",'menu'=>true),

	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"tin-tuc","type"=>"tin-tuc",'menu'=>true),
	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"tin-tuyen-dung","type"=>"tin-tuyen-dung",'menu'=>true),

	/* Bài viết */
	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"tin-tuc-va-su-kien","type"=>"tin-tuc-va-su-kien",'menu'=>false),
	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"tuyen-dung","type"=>"tuyen-dung",'menu'=>true),
	array("tbl"=>"news","field"=>"id","source"=>"news","com"=>"chinh-sach","type"=>"chinh-sach",'menu'=>false),

	/* Trang tĩnh */
	array("tbl"=>"static","field"=>"id","source"=>"static","com"=>"gioi-thieu","type"=>"gioi-thieu"),

	/* Liên hệ */
	array("tbl"=>"","field"=>"id","source"=>"","com"=>"lien-he","type"=>"",'menu'=>true),
);

/* Find data */
if($com != 'tim-kiem' && $com != 'account' && $com != 'sitemap' && $com != 'share')
{
	foreach($requick as $k => $v)
	{
		$url_tbl = (isset($v['tbl']) && $v['tbl'] != '') ? $v['tbl'] : '';
		$url_tbltag = (isset($v['tbltag']) && $v['tbltag'] != '') ? $v['tbltag'] : '';
		$url_type = (isset($v['type']) && $v['type'] != '') ? $v['type'] : '';
		$url_field = (isset($v['field']) && $v['field'] != '') ? $v['field'] : '';
		$url_com = (isset($v['com']) && $v['com'] != '') ? $v['com'] : '';
		
		if($url_tbl!='' && $url_tbl!='static' && $url_tbl!='photo')
		{
			$row = $d->rawQueryOne("select id from #_$url_tbl where $sluglang = ? and type = ? and hienthi > 0 limit 0,1",array($com,$url_type));
			
			if(!empty($row['id']))
			{
				$_GET[$url_field] = $row['id'];
				$com = $url_com;
				break;
			}
		}
	}
}

/* Switch coms */
switch($com)
{
	case 'lien-he':
	$source = "contact";
	$template = "contact/contact";
	$seo->setSeo('type','object');
	$title_crumb = lienhe;
	break;

	case 'gioi-thieu':
	$source = "static";
	$template = "static/static";
	$type = $com;
	$seo->setSeo('type','article');
	$title_crumb = gioithieu;
	break;

	case 'vong-xoay':
	$source = "vongxoay";
	$template = "vongxoay/vongxoay";
	$seo->setSeo('type',"article");
	$type = $com;
	$title_crumb = vongxoaynhanthuong;
	break;

	case 'tin-tuyen-dung':
	$source = "news";
	$template = isset($_GET['id']) ? "tuyendung/news_detail" : "tuyendung/news";
	$seo->setSeo('type',isset($_GET['id']) ? "article" : "object");
	$type = $com;
	$title_crumb = tintuyendung;
	break;

	case 'tin-tuc-va-su-kien':
	$source = "news";
	$template = isset($_GET['id']) ? "news/news_detail" : "news/news";
	$seo->setSeo('type',isset($_GET['id']) ? "article" : "object");
	$type = $com;
	$title_crumb = tintucvasukien;
	break;

	case 'chinh-sach':
	$source = "news";
	$template = isset($_GET['id']) ? "news/news_detail" : "";
	$seo->setSeo('type','article');
	$type = $com;
	$title_crumb = chinhsach;
	break;

	case 'tim-kiem':
	$source = "search";
	$template = "tuyendung/news";
	$seo->setSeo('type','object');
	$title_crumb = timkiem;
	break;

	case 'account':
	$source = "user";
	break;

	case 'share':
		$source = "share";
		break;

	case 'ngon-ngu-vn':
	case 'ngon-ngu-en':
	case 'ngon-ngu-jp':
		$_SESSION['lang'] = str_replace('ngon-ngu-', '', $com);
		if ($_SESSION['lang'] == 'vn') $_SESSION['lang'] = 'vi';
		$referer = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ngon-ngu-') === false) ? $_SERVER['HTTP_REFERER'] : $config_base;
		header("Location: " . $referer);
		exit();
		break;

	case 'sitemap':
	include_once LIBRARIES."sitemap.php";
	exit();
	
	case '':
	case 'index':
	$source = "index";
	$template ="index/index";
	$seo->setSeo('type','website');
	break;

	default: 
	header('HTTP/1.0 404 Not Found', true, 404);
	include("404.php");
	exit();
}

/* Include sources */
if($source!='') include SOURCES.$source.".php";
if($template=='')
{
	header('HTTP/1.0 404 Not Found', true, 404);
	include("404.php");
	exit();
}
?>
