<?php
	$linkMan = "index.php?com=ungtuyen&act=man&p=".$curPage;
    $linkSave = "index.php?com=ungtuyen&act=save&p=".$curPage;
    $linkUser = "index.php?com=user&act=edit";

    // Fetch all active CTVs
    $ctvs = $d->rawQuery("select id, ten, username, mactv from #_member where role = 1 order by CAST(SUBSTRING(mactv, 2) AS UNSIGNED) asc, id asc");
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item"><a href="<?=$linkMan?>" title="Quản lý Tokutei Gino">Quản lý Tokutei Gino</a></li>
                <li class="breadcrumb-item active">Thông tin Tokutei Gino <span class="text-primary">#<?=$item['id']?></span></li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <form class="validation-form" novalidate method="post" action="<?=$linkSave?>" enctype="multipart/form-data">
        <div class="card-footer text-sm sticky-top">
            <button type="submit" class="btn btn-sm bg-gradient-primary"><i class="far fa-save mr-2"></i>Lưu</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
        </div>
        <div class="card card-primary card-outline text-sm">
            <div class="card-header">
                <h3 class="card-title">Thông tin ứng viên Tokutei Gino</h3>
            </div>
            <div class="card-body row">
                <!-- 1. Họ tên người giới thiệu (mã CTV) -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="id_member_gt" class="d-block font-weight-bold">Họ tên người giới thiệu (Mã CTV): <span class="text-danger">*</span></label>
                    <select id="id_member_gt" name="data[id_member_gt]" class="form-control select2 text-sm" required>
                        <option value="">-- Chọn CTV giới thiệu --</option>
                        <?php foreach($ctvs as $c) { 
                            $mactv_text = !empty($c['mactv']) ? $c['mactv'] : 'K-CTV';
                            $c_name = $c['ten'] ? $c['ten'] : $c['username'];
                            $selected = (isset($item['id_member_gt']) && $item['id_member_gt'] == $c['id']) ? 'selected' : '';
                        ?>
                            <option value="<?=$c['id']?>" <?=$selected?>><?=$mactv_text?> - <?=$c_name?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- 2. Họ tên ứng viên -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="ten" class="d-block font-weight-bold">Họ tên ứng viên: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="data[ten]" id="ten" placeholder="Nhập họ tên ứng viên (Ví dụ: Nguyễn Văn A)" value="<?=@$item['ten']?>" required>
                </div>

                <!-- 3. Tên công ty ứng tuyển -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="congty" class="d-block font-weight-bold">Tên công ty ứng tuyển:</label>
                    <input type="text" class="form-control" name="data[congty]" id="congty" placeholder="Nhập tên công ty" value="<?=@$item['congty']?>">
                </div>

                <!-- 4. Công việc (Vị trí) -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="ten_news" class="d-block font-weight-bold">Công việc (Ngành nghề):</label>
                    <input type="text" class="form-control" name="data[ten_news]" id="ten_news" placeholder="Nhập tên công việc (Ví dụ: Nhà hàng, Thực phẩm...)" value="<?=@$item['ten_news']?>">
                </div>

                <!-- 5. Tình trạng ứng viên -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="tinhtrang" class="d-block font-weight-bold">Tình trạng ứng viên: <span class="text-danger">*</span></label>
                    <select id="tinhtrang" name="data[tinhtrang]" class="form-control text-sm" required>
                        <option value="1" <?= (!isset($item['tinhtrang']) || @$item['tinhtrang'] == 1) ? 'selected' : '' ?>>Chọn tình trạng</option>
                        <option value="2" <?= @$item['tinhtrang'] == 2 ? 'selected' : '' ?>>Trước phỏng vấn</option>
                        <option value="3" <?= @$item['tinhtrang'] == 3 ? 'selected' : '' ?>>Đậu naitei</option>
                        <option value="4" <?= @$item['tinhtrang'] == 4 ? 'selected' : '' ?>>Đăng ký xin visa</option>
                        <option value="5" <?= @$item['tinhtrang'] == 5 ? 'selected' : '' ?>>Đang làm việc</option>
                    </select>
                </div>

                <!-- 6. SĐT liên hệ -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="sodienthoai" class="d-block font-weight-bold">SĐT liên hệ:</label>
                    <input type="text" class="form-control" name="data[sodienthoai]" id="sodienthoai" placeholder="Nhập số điện thoại liên hệ" value="<?=@$item['sodienthoai']?>">
                </div>

                <!-- 7. Link Facebook -->
                <div class="form-group col-md-12 col-sm-12">
                    <label for="link_facebook" class="d-block font-weight-bold">Link Facebook:</label>
                    <input type="text" class="form-control" name="data[link_facebook]" id="link_facebook" placeholder="Nhập đường dẫn trang Facebook" value="<?=@$item['link_facebook']?>">
                </div>

                <!-- 8. Ghi chú -->
                <div class="form-group col-12">
                    <label for="ghichu" class="font-weight-bold">Ghi chú:</label>
                    <textarea class="form-control" name="data[ghichu]" id="ghichu" rows="3" placeholder="Ghi chú thêm..."><?=@$item['ghichu']?></textarea>
                </div>
            </div>
        </div>

        <div class="card-footer text-sm">
            <button type="submit" class="btn btn-sm bg-gradient-primary"><i class="far fa-save mr-2"></i>Lưu</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
            <input type="hidden" name="id" value="<?=(isset($item['id']) && $item['id'] > 0) ? $item['id'] : ''?>">
        </div>
    </form>
</section>

<!-- Initialize Select2 -->
<script type="text/javascript">
$(document).ready(function() {
    if ($('.select2').length > 0) {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Chọn CTV giới thiệu --",
            allowClear: true
        });
    }
});
</script>