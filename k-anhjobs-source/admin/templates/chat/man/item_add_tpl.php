<?php
	$linkMan = "index.php?com=ungtuyen&act=man&p=".$curPage;
    $linkSave = "index.php?com=ungtuyen&act=save&p=".$curPage;
    $linkUser = "index.php?com=user&act=edit";
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item"><a href="<?=$linkMan?>" title="Quản lý ứng tuyển">Quản lý ứng tuyển</a></li>
                <li class="breadcrumb-item active">Thông tin ứng tuyển <span
                        class="text-primary">#<?=$item['id']?></span></li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <form class="validation-form" novalidate method="post" action="<?=$linkSave?>" enctype="multipart/form-data">
        <div class="card-footer text-sm sticky-top">
            <button type="submit" class="btn btn-sm bg-gradient-primary" disabled><i
                    class="far fa-save mr-2"></i>Lưu</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm
                lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i
                    class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
        </div>
        <div class="card card-primary card-outline text-sm">
            <div class="card-header">
                <h3 class="card-title">Thông tin chính</h3>
            </div>
            <div class="card-body row">
                <div class="form-group col-md-4 col-sm-6">
                    <label>Mã ứng tuyển:</label>
                    <p class="text-primary"><?=@$item['id']?></p>
                </div>

                <div class="form-group col-md-4 col-sm-6">
                <?php $mem = $d->rawQueryOne("select username, id, cv from #_member where id = ?", array(@$item['id_member'])); ?>
                    <label>Ứng viên:</label>
                    <a class="font-weight-bold text-success d-block" href="<?=$linkUser?>&id=<?=@$item['id_member']?>" title="Xem thông tin ứng viên"><?=@$mem['username']?></a>
                </div>

                <div class="form-group col-md-4 col-sm-6">
                    <label>CV ứng viên:</label>
                    <a class="font-weight-bold d-block" href="../upload/file/<?= $mem['cv'] ?>" target="_black">Xem ngay</a>
                </div>

                <div class="form-group col-md-4 col-sm-6">
                    <label>Ngày ứng tuyển:</label>
                    <p><?=date("h:i:s A - d/m/Y", @$item['ngaytao'])?></p>
                </div>

                <div class="form-group col-12">
                <?php $news = $d->rawQueryOne("select tenvi, id from #_news where id = ?", array(@$item['id_news'])); ?>
                    <label>Vị trí ứng tuyển:</label>
                    <p><?=  $news['tenvi'] ?> - Mã: <?= $news['id'] ?></p>
                </div>
			
                <div class="form-group col-12">
                    <label for="tinhtrang" class="mr-2">Tình trạng:</label>
                    <select id="tinhtrang" name="data[tinhtrang]" class="form-control text-sm">
                        <option value="0">Chọn tình trạng</option>
						<option value="1" <?= @$item['tinhtrang'] == 1 ? 'selected' : '' ?>>Ứng tuyển</option>
						<option value="2" <?= @$item['tinhtrang'] == 2 ? 'selected' : '' ?>>Phỏng vấn</option>
						<option value="3" <?= @$item['tinhtrang'] == 3 ? 'selected' : '' ?>>Trúng tuyển</option>
						<option value="4" <?= @$item['tinhtrang'] == 4 ? 'selected' : '' ?>>Đi đơn</option>
						<option value="5" <?= @$item['tinhtrang'] == 5 ? 'selected' : '' ?>>Làm hồ sơ</option>
						<option value="6" <?= @$item['tinhtrang'] == 6 ? 'selected' : '' ?>>Xin visa</option>
						<option value="7" <?= @$item['tinhtrang'] == 7 ? 'selected' : '' ?>>Nhập công ty</option>
						<option value="8" <?= @$item['tinhtrang'] == 8 ? 'selected' : '' ?>>Từ chối</option>
						<option value="9" <?= @$item['tinhtrang'] == 9 ? 'selected' : '' ?>>Huỷ</option>
                    </select>
                </div>
                <div class="form-group col-12">
                    <label for="ghichu">Ghi chú:</label>
                    <textarea class="form-control" name="data[ghichu]" id="ghichu" rows="5"
                        placeholder="Ghi chú"><?=@$item['ghichu']?></textarea>
                </div>
                <?php /* ?>
                <div class="form-group">
                    <label for="stt" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
                    <input type="number" class="form-control form-control-mini d-inline-block align-middle" min="0"
                        name="data[stt]" id="stt" placeholder="Số thứ tự"
                        value="<?=isset($item['stt']) ? $item['stt'] : 1?>">
                </div>
                <?php */ ?>
            </div>
        </div>
        
        <div class="card-footer text-sm">
            <button type="submit" class="btn btn-sm bg-gradient-primary" disabled><i
                    class="far fa-save mr-2"></i>Lưu</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm
                lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i
                    class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
            <input type="hidden" name="id" value="<?=(isset($item['id']) && $item['id'] > 0) ? $item['id'] : ''?>">
        </div>
    </form>
</section>