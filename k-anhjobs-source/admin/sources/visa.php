<?php
if (!defined('SOURCES')) die('Error');

require_once LIBRARIES . 'visa.php';
visa_require_admin_permission();

$visa_statuses = array(
    'submitted'=>'提出済み / Đã nộp',
    'checking'=>'確認中 / Đang kiểm tra',
    'incomplete'=>'書類不足 / Thiếu hồ sơ',
    'completed'=>'完了 / Hoàn tất',
    'rejected'=>'差し戻し / Từ chối'
);
$visa_definitions = visa_document_definitions();
$visa_admin_csrf = visa_csrf_token('admin_visa');

switch ($act) {
    case 'man': visa_admin_get_items(); $template='visa/man/items'; break;
    case 'detail': visa_admin_get_item(); $template='visa/man/item_detail'; break;
    case 'status': visa_admin_update_status(); break;
    case 'edit': visa_admin_update_record(); break;
    case 'delete': visa_admin_delete_record(); break;
    case 'file': visa_admin_stream_file(); break;
    default: $template='404';
}

function visa_admin_redirect($url,$message=null,$error=false)
{
    if($message!==null) $_SESSION[$error?'popup_error':'popup_success']=$message;
    header('Location: '.$url); exit;
}

function visa_admin_get_items()
{
    global $d,$func,$curPage,$items,$paging,$visa_statuses;
    $where=' where 1=1'; $params=array();
    $keyword=isset($_GET['keyword'])?trim($_GET['keyword']):'';
    $status=isset($_GET['status'])?trim($_GET['status']):'';
    if($keyword!==''){
        $like='%'.$keyword.'%';
        $where.=' and (a.application_code like ? or a.full_name like ? or a.company_name like ?)';
        array_push($params,$like,$like,$like);
    }
    if(isset($visa_statuses[$status])){$where.=' and a.status = ?';$params[]=$status;}
    $perPage=20; $page=max(1,(int)$curPage); $offset=($page-1)*$perPage;
    $items=$d->rawQuery("select a.*, (select count(*) from #_visa_documents d where d.visa_application_id=a.id) as document_count from #_visa_applications a $where order by a.submitted_at desc, a.id desc limit $offset,$perPage",$params);
    $count=$d->rawQueryOne("select count(*) as total from #_visa_applications a $where",$params);
    $query=$_GET; unset($query['p']);
    $paging=$func->pagination((int)$count['total'],$perPage,$page,'index.php?'.http_build_query(array_merge(array('com'=>'visa','act'=>'man'),$query)));
}

function visa_admin_get_item()
{
    global $d,$item,$documents,$func;
    $id=isset($_GET['id'])?(int)$_GET['id']:0;
    $item=$d->rawQueryOne('select * from #_visa_applications where id = ? limit 1',array($id));
    if(empty($item['id'])) $func->transfer('Hồ sơ không tồn tại','index.php?com=visa&act=man',false);
    $documents=$d->rawQuery('select * from #_visa_documents where visa_application_id = ? order by id asc',array($id));
}

function visa_admin_update_status()
{
    global $d,$visa_statuses;
    if($_SERVER['REQUEST_METHOD']!=='POST'||!visa_verify_csrf('admin_visa',isset($_POST['csrf_token'])?$_POST['csrf_token']:'')) visa_admin_redirect('index.php?com=visa&act=man','Yêu cầu không hợp lệ.',true);
    $id=isset($_POST['id'])?(int)$_POST['id']:0;
    $status=isset($_POST['status'])?$_POST['status']:'';
    $note=isset($_POST['admin_note'])?trim($_POST['admin_note']):'';
    if(!isset($visa_statuses[$status])) visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Trạng thái không hợp lệ.',true);
    $exists=$d->rawQueryOne('select id from #_visa_applications where id = ? limit 1',array($id));
    if(empty($exists['id'])) visa_admin_redirect('index.php?com=visa&act=man','Hồ sơ không tồn tại.',true);
    $d->where('id',$id);
    if(!$d->update('visa_applications',array('status'=>$status,'admin_note'=>$note,'updated_at'=>date('Y-m-d H:i:s')))) visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Không thể cập nhật hồ sơ.',true);
    visa_rotate_csrf('admin_visa');
    visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Đã cập nhật hồ sơ.');
}

function visa_admin_update_record()
{
    global $d,$visa_statuses;
    if($_SERVER['REQUEST_METHOD']!=='POST'||!visa_verify_csrf('admin_visa',isset($_POST['csrf_token'])?$_POST['csrf_token']:'')) visa_admin_redirect('index.php?com=visa&act=man','Yêu cầu không hợp lệ.',true);
    $id=isset($_POST['id'])?(int)$_POST['id']:0;
    $fullName=isset($_POST['full_name'])?trim($_POST['full_name']):'';
    $companyName=isset($_POST['company_name'])?trim($_POST['company_name']):'';
    $status=isset($_POST['status'])?$_POST['status']:'';
    $note=isset($_POST['admin_note'])?trim($_POST['admin_note']):'';
    if($fullName===''||$companyName==='') visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Họ tên và công ty ứng tuyển là bắt buộc.',true);
    if(!isset($visa_statuses[$status])) visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Trạng thái không hợp lệ.',true);
    $exists=$d->rawQueryOne('select id from #_visa_applications where id = ? limit 1',array($id));
    if(empty($exists['id'])) visa_admin_redirect('index.php?com=visa&act=man','Hồ sơ không tồn tại.',true);
    $d->where('id',$id);
    $updated=$d->update('visa_applications',array(
        'full_name'=>mb_substr($fullName,0,191,'UTF-8'),
        'company_name'=>mb_substr($companyName,0,191,'UTF-8'),
        'status'=>$status,
        'admin_note'=>$note,
        'updated_at'=>date('Y-m-d H:i:s')
    ));
    if(!$updated) visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Không thể sửa hồ sơ.',true);
    visa_rotate_csrf('admin_visa');
    visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Đã sửa hồ sơ.');
}

function visa_admin_delete_record()
{
    global $d;
    if($_SERVER['REQUEST_METHOD']!=='POST'||!visa_verify_csrf('admin_visa',isset($_POST['csrf_token'])?$_POST['csrf_token']:'')) visa_admin_redirect('index.php?com=visa&act=man','Yêu cầu không hợp lệ.',true);
    $id=isset($_POST['id'])?(int)$_POST['id']:0;
    $application=$d->rawQueryOne('select id from #_visa_applications where id = ? limit 1',array($id));
    if(empty($application['id'])) visa_admin_redirect('index.php?com=visa&act=man','Hồ sơ không tồn tại.',true);
    $documents=$d->rawQuery('select storage_path from #_visa_documents where visa_application_id = ?',array($id));
    $files=array();
    foreach($documents as $document){$path=visa_resolve_storage_path($document['storage_path']);if($path)$files[]=$path;}
    try{
        $d->startTransaction();
        $d->rawQuery('delete from #_visa_documents where visa_application_id = ?',array($id));
        $d->rawQuery('delete from #_visa_applications where id = ?',array($id));
        $remaining=$d->rawQueryOne('select count(*) as total from #_visa_applications where id = ?',array($id));
        if(!empty($remaining['total'])) throw new RuntimeException('Không thể xóa hồ sơ.');
        $d->commit();
    }catch(Throwable $exception){
        try{$d->rollback();}catch(Throwable $ignored){}
        visa_admin_redirect('index.php?com=visa&act=detail&id='.$id,'Không thể xóa hồ sơ.',true);
    }
    foreach($files as $path) if(is_file($path)) @unlink($path);
    visa_rotate_csrf('admin_visa');
    visa_admin_redirect('index.php?com=visa&act=man','Đã xóa hồ sơ và tài liệu đính kèm.');
}

function visa_admin_stream_file()
{
    global $d;
    $id=isset($_GET['id'])?(int)$_GET['id']:0;
    $download=isset($_GET['download'])&&$_GET['download']==='1';
    $document=$d->rawQueryOne('select * from #_visa_documents where id = ? limit 1',array($id));
    if(empty($document['id'])){http_response_code(404);exit('Not found');}
    $path=visa_resolve_storage_path($document['storage_path']);
    if(!$path){http_response_code(404);exit('Not found');}
    $filename=str_replace(array("\r","\n",'"'),'',$document['original_name']);
    $fallback=preg_replace('/[^A-Za-z0-9._-]/','_',$filename);
    header('Content-Type: '.$document['mime_type']);
    header('Content-Length: '.filesize($path));
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store, max-age=0');
    header('Content-Disposition: '.($download?'attachment':'inline').'; filename="'.$fallback.'"; filename*=UTF-8\'\''.rawurlencode($filename));
    readfile($path); exit;
}
