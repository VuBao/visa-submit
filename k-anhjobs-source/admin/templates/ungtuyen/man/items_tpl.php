<?php
	$linkMan = $linkFilter = "index.php?com=ungtuyen&act=man&p=".$curPage;
    $linkAdd = "index.php?com=ungtuyen&act=add&p=".$curPage;
    $linkEdit = "index.php?com=ungtuyen&act=edit&p=".$curPage;
    $linkDelete = "index.php?com=ungtuyen&act=delete&p=".$curPage;
    $linkUser = "index.php?com=user&act=edit_ctv";
    $linkExcel = "index.php?com=excelAll";
    $linkWord = "index.php?com=wordAll";
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item active">Quản lý Tokutei Gino</li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card card-primary card-outline text-sm mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title text-bold text-uppercase text-primary mb-0">
                <i class="fas fa-trophy text-warning mr-2"></i>BẢNG THÀNH TÍCH ỨNG VIÊN
            </h3>
            <form method="get" action="index.php" class="form-inline ml-auto mb-0">
                <input type="hidden" name="com" value="ungtuyen">
                <input type="hidden" name="act" value="man">
                <div class="form-group mr-2 mb-0">
                    <label for="thang_stat" class="mr-1 text-sm">Tháng:</label>
                    <select name="thang" id="thang_stat" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                        <option value="0" <?= (isset($thang_selected) && $thang_selected == 0) ? 'selected' : '' ?>>Tất cả các tháng</option>
                        <?php for($m=1;$m<=12;$m++) { ?>
                            <option value="<?=$m?>" <?= (isset($thang_selected) && $thang_selected == $m) ? 'selected' : '' ?>>Tháng <?=$m?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label for="nam_stat" class="mr-1 text-sm">Năm:</label>
                    <select name="nam" id="nam_stat" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                        <option value="0" <?= (!isset($nam_selected) || $nam_selected == 0) ? 'selected' : '' ?>>Tất cả các năm</option>
                        <?php 
                        $curY = (int)date('Y');
                        for($y=$curY-3;$y<=$curY+1;$y++) { ?>
                            <option value="<?=$y?>" <?= (isset($nam_selected) && $nam_selected == $y) ? 'selected' : '' ?>>Năm <?=$y?></option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- 1. Trước phỏng vấn -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-2" style="background-color: #00c0ef; color: white;">
                        <span class="info-box-icon"><i class="fas fa-comments"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Trước phỏng vấn</span>
                            <span class="info-box-number text-lg"><?=(int)@$count_phongvan?> <small>ứng viên</small></span>
                        </div>
                    </div>
                </div>

                <!-- 2. Đậu naitei -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-2" style="background-color: #ffc107; color: #1f2d3d;">
                        <span class="info-box-icon"><i class="fas fa-award"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Đậu naitei</span>
                            <span class="info-box-number text-lg"><?=(int)@$count_naitei?> <small>ứng viên</small></span>
                        </div>
                    </div>
                </div>

                <!-- 3. Đăng ký xin visa -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-2" style="background-color: #28a745; color: white;">
                        <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Đăng ký xin visa</span>
                            <span class="info-box-number text-lg"><?=(int)@$count_visa?> <small>ứng viên</small></span>
                        </div>
                    </div>
                </div>

                <!-- 4. Đang làm việc -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-2" style="background-color: #1f2937; color: white;">
                        <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Đang làm việc</span>
                            <span class="info-box-number text-lg"><?=(int)@$count_lamviec?> <small>ứng viên</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-footer text-sm sticky-top">
        <a class="btn btn-sm bg-gradient-primary text-white" href="<?=$linkAdd?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?=$linkDelete?>" title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
        <div class="form-inline form-search d-inline-block align-middle ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar text-sm" type="search" id="keyword"
                    placeholder="Tìm theo Mã CTV, Họ tên, Công ty..." aria-label="Tìm kiếm"
                    value="<?=(isset($_GET['keyword'])) ? $_GET['keyword'] : ''?>"
                    onkeypress="doEnter(event,'keyword','<?=$linkMan?>')">
                <div class="input-group-append bg-primary rounded-right">
                    <button class="btn btn-navbar text-white" type="button"
                        onclick="onSearch('keyword','<?=$linkMan?>')">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline text-sm mb-0">
        <div class="card-header">
            <h3 class="card-title card-title-order d-inline-block align-middle float-none">Danh sách ứng viên Tokutei Gino</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="align-middle text-center" width="3%">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input" id="selectall-checkbox">
                                <label for="selectall-checkbox" class="custom-control-label"></label>
                            </div>
                        </th>
                        <th class="align-middle text-center" width="5%">STT</th>
                        <th class="align-middle text-center" width="10%">Mã CTV</th>
                        <th class="align-middle">Họ tên ứng viên</th>
                        <th class="align-middle">Công việc</th>
                        <th class="align-middle">Tên công ty</th>
                        <th class="align-middle text-center" style="width: 18%">Tình trạng</th>
                        <th class="align-middle text-center" style="width: 15%">Thanh toán</th>
                        <th class="align-middle">Ghi chú</th>
                        <th class="align-middle text-center" width="8%">Thao tác</th>
                    </tr>
                </thead>
                <?php if(empty($items)) { ?>
                <tbody>
                    <tr>
                        <td colspan="100" class="text-center">Không có dữ liệu</td>
                    </tr>
                </tbody>
                <?php } else { ?>
                <tbody>
                    <?php 
                    $start_stt = isset($startpoint) ? (int)$startpoint : 0;
                    for($i=0;$i<count($items);$i++) { 
                        $stt_num = $start_stt + $i + 1;
                        
                        // Lấy thông tin CTV giới thiệu
                        $mactv_display = "---";
                        $gt_id = 0;
                        if (!empty($items[$i]['id_member_gt'])) {
                            $gt_id = $items[$i]['id_member_gt'];
                        } else if (!empty($items[$i]['id_member'])) {
                            $c_mem = $d->rawQueryOne("select id_member_gt from #_member where id = ? limit 0,1", array($items[$i]['id_member']));
                            if (!empty($c_mem['id_member_gt'])) {
                                $gt_id = $c_mem['id_member_gt'];
                            }
                        }
                        
                        if ($gt_id) {
                            $gt_mem = $d->rawQueryOne("select id, mactv, ten, username from #_member where id = ? limit 0,1", array($gt_id));
                            if (!empty($gt_mem['mactv'])) {
                                $mactv_display = $gt_mem['mactv'];
                            } else if (!empty($gt_mem['id'])) {
                                $mactv_display = "K-" . $gt_mem['id'];
                            } else {
                                $mactv_display = "---";
                            }
                        }

                        // Tính số ngày làm việc đối với trạng thái "Đang làm việc" (tinhtrang = 5)
                        $is_working_90_days = false;
                        $working_days_count = 0;
                        if($items[$i]['tinhtrang'] == 5 && !empty($items[$i]['working_date']) && $items[$i]['working_date'] > 0) {
                            $working_days_count = floor((time() - $items[$i]['working_date']) / 86400);
                            if($working_days_count >= 90) {
                                $is_working_90_days = true;
                            }
                        }
                        
                        $is_paid = (isset($items[$i]['tinhtrang_gt']) && $items[$i]['tinhtrang_gt'] == 2);
                    ?>
                    <tr>
                        <td class="align-middle text-center">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input select-checkbox"
                                    id="select-checkbox-<?=$items[$i]['id']?>" value="<?=$items[$i]['id']?>">
                                <label for="select-checkbox-<?=$items[$i]['id']?>" class="custom-control-label"></label>
                            </div>
                        </td>
                        <!-- STT liên tục toàn bộ hệ thống -->
                        <td class="align-middle text-center font-weight-bold">
                            <?=$stt_num?>
                        </td>
                        <!-- Mã CTV (Click vào liên kết đến trang cá nhân CTV) -->
                        <td class="align-middle text-center">
                            <?php if($gt_id) { ?>
                                <a class="badge badge-info p-2 font-weight-bold" href="index.php?com=congtacvien&act=man&keyword=<?=urlencode($mactv_display)?>" title="Xem danh sách CTV" target="_blank">
                                    <i class="fas fa-user-tie mr-1"></i><?=$mactv_display?>
                                </a>
                            <?php } else { ?>
                                <span class="text-muted"><?=$mactv_display?></span>
                            <?php } ?>
                        </td>
                        <!-- Họ tên ứng viên -->
                        <td class="align-middle font-weight-bold text-primary">
                            <?php if($items[$i]['id_member']) { ?> 
                                <?php $mem = $d->rawQueryOne("select username, id, ten from #_member where id = ?", array($items[$i]['id_member'])); ?>
                                <a class="text-primary" href="<?=$linkUser?>&id=<?=$items[$i]['id_member']?>" title="Xem thông tin ứng viên"><?= is_array($mem) && !empty($mem['ten']) ? $mem['ten'] : (is_array($mem) ? @$mem['username'] : 'Ứng viên đã xóa') ?></a>
                            <?php } else { ?> 
                                <span><?=$items[$i]['ten']?></span>
                            <?php } ?>
                        </td>
                        <!-- Công việc -->
                        <td class="align-middle">
                            <span><?=$items[$i]['ten_news'] ? $items[$i]['ten_news'] : '<span class="text-muted">Chưa cập nhật</span>'?></span>
                        </td>
                        <!-- Tên công ty -->
                        <td class="align-middle">
                            <span><?=$items[$i]['congty'] ? $items[$i]['congty'] : '<span class="text-muted">Chưa cập nhật</span>'?></span>
                        </td>
                        <!-- Tình trạng -->
                        <td class="align-middle text-center">
                            <select class="form-control text-sm js-select-tinhtrang font-weight-bold" data-id="<?=$items[$i]['id']?>" style="
                                <?php 
                                    if($items[$i]['tinhtrang'] == 2) echo 'background-color: #e0f2fe; color: #0369a1; border-color: #bae6fd;';
                                    elseif($items[$i]['tinhtrang'] == 3) echo 'background-color: #fef9c3; color: #a16207; border-color: #fef08a;';
                                    elseif($items[$i]['tinhtrang'] == 4) echo 'background-color: #dcfce7; color: #15803d; border-color: #bbf7d0;';
                                    elseif($items[$i]['tinhtrang'] == 5) echo 'background-color: #1f2937; color: #ffffff; border-color: #111827;';
                                    else echo 'background-color: #f3f4f6; color: #4b5563;';
                                ?>
                            ">
                                <option value="1" <?= $items[$i]['tinhtrang'] == 1 ? 'selected' : '' ?>>Chọn tình trạng</option>
                                <option value="2" <?= $items[$i]['tinhtrang'] == 2 ? 'selected' : '' ?>>Trước phỏng vấn</option>
                                <option value="3" <?= $items[$i]['tinhtrang'] == 3 ? 'selected' : '' ?>>Đậu naitei</option>
                                <option value="4" <?= $items[$i]['tinhtrang'] == 4 ? 'selected' : '' ?>>Đăng ký xin visa</option>
                                <option value="5" <?= $items[$i]['tinhtrang'] == 5 ? 'selected' : '' ?>>Đang làm việc</option>
                            </select>

                            <!-- Badge đỏ khi đạt điều kiện 90 ngày làm việc -->
                            <?php if($is_working_90_days) { ?>
                                <div class="mt-1">
                                    <span class="badge badge-danger p-1 shadow-sm" style="animation: pulse 1.5s infinite;" title="Đã làm việc <?=$working_days_count?> ngày (Từ <?=date('d/m/Y', $items[$i]['working_date'])?>)">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Đến hạn thanh toán
                                    </span>
                                </div>
                            <?php } elseif($items[$i]['tinhtrang'] == 5 && !empty($items[$i]['working_date'])) { ?>
                                <div class="mt-1">
                                    <small class="text-muted font-italic" style="font-size:11px;">Đã làm <?=$working_days_count?>/90 ngày</small>
                                </div>
                            <?php } ?>
                        </td>

                        <!-- Thanh toán -->
                        <td class="align-middle text-center">
                            <?php if($is_paid) { ?>
                                <span class="badge badge-success p-2 d-block" style="font-size: 13px;">
                                    <i class="fas fa-check-circle mr-1"></i>Đã thanh toán
                                </span>
                            <?php } else { ?>
                                <select class="form-control text-sm js-select-thanhtoan <?= $is_working_90_days ? 'border-danger font-weight-bold' : '' ?>" data-id="<?=$items[$i]['id']?>">
                                    <option value="1" <?= (!isset($items[$i]['tinhtrang_gt']) || $items[$i]['tinhtrang_gt'] == 1) ? 'selected' : '' ?>>Chưa thanh toán</option>
                                    <option value="2" <?= (isset($items[$i]['tinhtrang_gt']) && $items[$i]['tinhtrang_gt'] == 2) ? 'selected' : '' ?>>Đã thanh toán</option>
                                </select>
                            <?php } ?>
                        </td>

                        <!-- Ghi chú -->
                        <td class="align-middle">
                            <span><?=$items[$i]['ghichu'] ? $items[$i]['ghichu'] : '<span class="text-muted font-italic">Không có</span>'?></span>
                        </td>

                        <!-- Thao tác -->
                        <td class="align-middle text-center text-md text-nowrap">
                            <a class="text-primary mr-2" href="<?=$linkEdit?>&id=<?=$items[$i]['id']?>" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                            <a class="text-danger" id="delete-item" data-url="<?=$linkDelete?>&id=<?=$items[$i]['id']?>" title="Xóa"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php if($paging) { ?>
    <div class="card-footer text-sm pb-0">
        <?=$paging?>
    </div>
    <?php } ?>
    <div class="card-footer text-sm">
        <a class="btn btn-sm bg-gradient-primary text-white" href="<?=$linkAdd?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?=$linkDelete?>" title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    </div>
</section>

<!-- AJAX scripts for status and payment updates -->
<script type="text/javascript">
$(document).ready(function() {
    $('.js-select-tinhtrang').change(function() {
        var id = $(this).data('id');
        var tinhtrang = $(this).val();
        $.ajax({
            url: 'ajax/ajax_tinhtrang.php',
            type: 'POST',
            data: {id: id, tinhtrang: tinhtrang},
            success: function(res) {
                if(res == 1) {
                    location.reload();
                } else {
                    alert("Cập nhật trạng thái thất bại!");
                }
            }
        });
    });

    $('.js-select-thanhtoan').change(function() {
        var id = $(this).data('id');
        var thanhtoan = $(this).val();
        if(thanhtoan == 2) {
            if(!confirm("Bạn có chắc chắn muốn xác nhận ĐÃ THANH TOÁN cho CTV này?")) {
                $(this).val(1);
                return false;
            }
        }
        $.ajax({
            url: 'ajax/ajax_thanhtoan.php',
            type: 'POST',
            data: {id: id, thanhtoan: thanhtoan},
            success: function(res) {
                if(res == 1) {
                    location.reload();
                } else {
                    alert("Cập nhật thanh toán thất bại!");
                }
            }
        });
    });
});
</script>

<style>
@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.85; transform: scale(1.03); }
    100% { opacity: 1; transform: scale(1); }
}
</style>