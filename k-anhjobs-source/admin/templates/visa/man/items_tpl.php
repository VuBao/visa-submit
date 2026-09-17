<?php $q=function($key){return visa_escape(isset($_GET[$key])?$_GET[$key]:'');}; ?>
<section class="content-header text-sm"><div class="container-fluid"><h1>ビザ申請書類 <small class="text-muted">Quản lý hồ sơ visa</small></h1></div></section>
<section class="content text-sm">
    <div class="card card-outline card-warning"><div class="card-body"><form method="get" class="row">
        <input type="hidden" name="com" value="visa"><input type="hidden" name="act" value="man">
        <div class="col-md-6 mb-2"><input class="form-control" name="keyword" value="<?=$q('keyword')?>" placeholder="Mã hồ sơ, họ tên hoặc công ty ứng tuyển"></div>
        <div class="col-md-4 mb-2"><select class="form-control" name="status"><option value="">すべての状態 / Tất cả trạng thái</option><?php foreach($visa_statuses as $key=>$label){ ?><option value="<?=$key?>" <?=$q('status')===$key?'selected':''?>><?=$label?></option><?php } ?></select></div>
        <div class="col-md-2 mb-2"><button class="btn btn-warning btn-block"><i class="fas fa-search mr-1"></i>Tìm kiếm</button></div>
    </form></div></div>
    <div class="card"><div class="card-body table-responsive p-0"><table class="table table-hover text-nowrap">
        <thead><tr><th>Mã hồ sơ</th><th>Họ tên</th><th>Công ty ứng tuyển</th><th>Trạng thái</th><th>Tài liệu</th><th>Ngày nộp</th><th></th></tr></thead>
        <tbody><?php if(empty($items)){ ?><tr><td colspan="7" class="text-center py-4 text-muted">データがありません / Chưa có hồ sơ</td></tr><?php } foreach($items as $row){ ?>
            <tr><td><strong><?=visa_escape($row['application_code'])?></strong></td><td><?=visa_escape($row['full_name'])?></td><td><?=visa_escape($row['company_name'])?></td><td><span class="badge badge-warning p-2"><?=isset($visa_statuses[$row['status']])?$visa_statuses[$row['status']]:visa_escape($row['status'])?></span></td><td><?=(int)$row['document_count']?></td><td><?=visa_escape($row['submitted_at'])?></td><td><a class="btn btn-xs btn-info" href="index.php?com=visa&act=detail&id=<?=(int)$row['id']?>"><i class="fas fa-eye mr-1"></i>Chi tiết</a></td></tr>
        <?php } ?></tbody>
    </table></div><?php if($paging){ ?><div class="card-footer pb-0"><?=$paging?></div><?php } ?></div>
</section>
