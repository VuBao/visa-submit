<section class="content-header text-sm"><div class="container-fluid"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?com=visa&act=man">ビザ申請書類</a></li><li class="breadcrumb-item active"><?=visa_escape($item['application_code'])?></li></ol></div></section>
<section class="content text-sm"><div class="row"><div class="col-lg-8">
    <div class="card card-outline card-warning"><div class="card-header"><h3 class="card-title">応募者情報 / Thông tin ứng viên</h3></div><div class="card-body"><div class="row">
        <?php $fields=array('application_code'=>'Mã hồ sơ','full_name'=>'Họ tên','company_name'=>'Công ty ứng tuyển','submitted_at'=>'Ngày nộp'); foreach($fields as $key=>$label){ ?><div class="col-md-6 mb-3"><small class="text-muted"><?=$label?></small><div class="font-weight-bold"><?=visa_escape($item[$key])?></div></div><?php } ?>
    </div></div></div>
    <div class="card"><div class="card-header"><h3 class="card-title">書類 / Tài liệu</h3></div><div class="card-body">
        <?php foreach($documents as $document){ $definition=isset($visa_definitions[$document['document_type']])?$visa_definitions[$document['document_type']]:array('ja'=>$document['document_type'],'vi'=>$document['document_type']); ?>
        <div class="border rounded p-3 mb-3"><div class="row align-items-center"><div class="col-md-5"><h5><?=visa_escape($definition['ja'])?></h5><p class="text-muted mb-1"><?=visa_escape($definition['vi'])?></p><small><?=visa_escape($document['original_name'])?> · <?=number_format($document['file_size']/1024,1)?> KB</small></div><div class="col-md-7 text-md-right mt-2 mt-md-0">
            <?php if(strpos($document['mime_type'],'image/')===0){ ?><a target="_blank" rel="noopener" href="index.php?com=visa&act=file&id=<?=(int)$document['id']?>"><img src="index.php?com=visa&act=file&id=<?=(int)$document['id']?>" alt="Preview" style="max-width:180px;max-height:130px;border-radius:8px"></a><?php }else{ ?><a class="btn btn-outline-danger" target="_blank" rel="noopener" href="index.php?com=visa&act=file&id=<?=(int)$document['id']?>"><i class="fas fa-file-pdf mr-1"></i>Xem PDF</a><?php } ?>
            <a class="btn btn-outline-secondary ml-1" href="index.php?com=visa&act=file&id=<?=(int)$document['id']?>&download=1"><i class="fas fa-download mr-1"></i>Tải</a>
        </div></div></div><?php } ?>
    </div></div>
</div><div class="col-lg-4"><div class="card card-outline card-warning"><div class="card-header"><h3 class="card-title">Sửa hồ sơ</h3></div><div class="card-body">
    <form method="post" action="index.php?com=visa&act=edit"><input type="hidden" name="csrf_token" value="<?=visa_escape($visa_admin_csrf)?>"><input type="hidden" name="id" value="<?=(int)$item['id']?>">
        <div class="form-group"><label>Họ và tên</label><input class="form-control" name="full_name" maxlength="191" required value="<?=visa_escape($item['full_name'])?>"></div>
        <div class="form-group"><label>Công ty ứng tuyển</label><input class="form-control" name="company_name" maxlength="191" required value="<?=visa_escape($item['company_name'])?>"></div>
        <div class="form-group"><label>Trạng thái hồ sơ</label><select class="form-control" name="status"><?php foreach($visa_statuses as $key=>$label){ ?><option value="<?=$key?>" <?=$item['status']===$key?'selected':''?>><?=$label?></option><?php } ?></select></div>
        <div class="form-group"><label>管理者メモ / Ghi chú Admin</label><textarea class="form-control" name="admin_note" rows="5"><?=visa_escape($item['admin_note'])?></textarea></div>
        <button class="btn btn-warning btn-block">Lưu thay đổi</button>
    </form>
    <hr><form method="post" action="index.php?com=visa&act=delete" onsubmit="return confirm('Xóa vĩnh viễn hồ sơ này và toàn bộ tài liệu?');"><input type="hidden" name="csrf_token" value="<?=visa_escape($visa_admin_csrf)?>"><input type="hidden" name="id" value="<?=(int)$item['id']?>"><button class="btn btn-outline-danger btn-block"><i class="fas fa-trash mr-1"></i>Xóa hồ sơ</button></form>
</div></div></div></div></section>
