<?php
	$linkMan = $linkFilter = "index.php?com=congtacvien&act=man&p=".$curPage;
    $linkDuyet = "index.php?com=congtacvien&act=cap-nhat&p=".$curPage;
    $linkDelete = "index.php?com=congtacvien&act=delete&p=".$curPage;
    $linkExcel = "index.php?com=excelAll";
    $linkWord = "index.php?com=wordAll";
    $arrStatus = array("text-primary","text-info","text-warning","text-success","text-danger");
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item active">Danh sách cộng tác viên</li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
   
    <div class="card-footer text-sm sticky-top">
        <a class="btn btn-sm bg-gradient-success text-white mr-1" id="duyet-all" data-url="<?=$linkDuyet?>"
            title="Duyệt tất cả"><i class="fas fa-check mr-2"></i>Duyệt tất cả</a>
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?=$linkDelete?>"
            title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    </div>
    <?php if(isset($config['order']['search']) && $config['order']['search'] == true) { ?>
    <div class="card card-primary card-outline text-sm">
        <div class="card-header">
            <h3 class="card-title">Tìm kiếm đơn ứng tuyển</h3>
        </div>
        <div class="card-body row">
            <div class="form-group col-md-3 col-sm-3">
                <label>Ngày tạo:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control float-right" name="ngaydat" id="ngaydat"
                        value="<?=(isset($_GET['ngaydat'])) ? $_GET['ngaydat'] : ''?>" readonly>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-3">
                <label>Tình trạng:</label>
                <select id="tinhtrang" name="data[tinhtrang]" class="form-control text-sm">
                    <option value="0">Chọn tình trạng</option>
                    <option value="1" <?= $_GET['tinhtrang'] == 1 ? 'selected' : '' ?>>Ứng tuyển</option>
                    <option value="2" <?= $_GET['tinhtrang'] == 2 ? 'selected' : '' ?>>Phỏng vấn</option>
                    <option value="3" <?= $_GET['tinhtrang'] == 3 ? 'selected' : '' ?>>Trúng tuyển</option>
                    <option value="4" <?= $_GET['tinhtrang'] == 4 ? 'selected' : '' ?>>Đi đơn</option>
                    <option value="5" <?= $_GET['tinhtrang'] == 5 ? 'selected' : '' ?>>Làm hồ sơ</option>
                    <option value="6" <?= $_GET['tinhtrang'] == 6 ? 'selected' : '' ?>>Xin visa</option>
                    <option value="7" <?= $_GET['tinhtrang'] == 7 ? 'selected' : '' ?>>Nhập công ty</option>
                    <option value="8" <?= $_GET['tinhtrang'] == 8 ? 'selected' : '' ?>>Từ chối</option>
                    <option value="9" <?= $_GET['tinhtrang'] == 9 ? 'selected' : '' ?>>Huỷ</option>
                </select>
            </div>


            <div class="form-group col-md-3 col-sm-3 d-flex align-items-end text-center">
                <a class="btn btn-sm bg-gradient-success text-white" onclick="actionOrder('<?=$linkFilter?>')"
                    title="Tìm kiếm"><i class="fas fa-search mr-1"></i>Tìm kiếm</a>
                <a class="btn btn-sm bg-gradient-danger text-white ml-1" href="<?=$linkMan?>" title="Hủy lọc"><i
                        class="fas fa-times mr-1"></i>Hủy lọc</a>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="card card-primary card-outline text-sm mb-0">
        <div class="card-header">
            <h3 class="card-title card-title-order d-inline-block align-middle float-none">Danh sách cộng tác viên</h3>
            <?php if(isset($config['order']['excelall']) && $config['order']['excelall'] == true) { ?>
            <a class="btn btn-sm bg-gradient-success btn-export-excel btn-sm d-inline-block align-middle ml-2 text-white"
                onclick="actionOrder('<?=$linkExcel?>')" title="Xuất file Excel"><i
                    class="far fa-file-excel mr-1"></i>Xuất file Excel</a>
            <?php } ?>
            <?php if(isset($config['order']['wordall']) && $config['order']['wordall'] == true) { ?>
            <a class="btn btn-sm bg-gradient-primary btn-export-word btn-sm d-inline-block align-middle ml-2 text-white"
                onclick="actionOrder('<?=$linkWord?>')" title="Xuất file Word"><i class="far fa-file-word mr-1"></i>Xuất
                file Word</a>
            <?php } ?>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="align-middle" width="5%">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input" id="selectall-checkbox">
                                <label for="selectall-checkbox" class="custom-control-label"></label>
                            </div>
                        </th>
                        <?php /* ?><th class="align-middle text-center" width="10%">STT</th><?php */ ?>
                        <th class="align-middle">Mã CTV</th>
                        <th class="align-middle">Tên</th>
                        <th class="align-middle">Email</th>
                        <th class="align-middle text-center">Trạng thái</th>
                       
                        <?php if(
                            (isset($config['order']['excel']) && $config['order']['excel'] == true) || 
                            (isset($config['order']['word']) && $config['order']['word'] == true)
                        ) { ?>
                        <th class="align-middle">Export</th>
                        <?php } ?>
                        <th class="align-middle text-right">Thao tác</th>
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
                    <?php for($i=0;$i<count($items);$i++) { ?>
                    <tr>
                        <td class="align-middle">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input select-checkbox"
                                    id="select-checkbox-<?=$items[$i]['id']?>" value="<?=$items[$i]['id']?>">
                                <label for="select-checkbox-<?=$items[$i]['id']?>" class="custom-control-label"></label>
                            </div>
                        </td>
                        <?php /* ?>
                        <td class="align-middle">
                            <input type="number" class="form-control form-control-mini m-auto update-stt" min="0"
                                value="<?=$items[$i]['stt']?>" data-id="<?=$items[$i]['id']?>" data-table="order">
                        </td>
                        <?php */ ?>
                        <td class="align-middle">
                            <a class="text-primary font-weight-bold" href="index.php?com=user&act=edit_ctv&id=<?=$items[$i]['id']?>" title="Xem chi tiết CTV">
                                <?=($items[$i]['mactv']) ? $items[$i]['mactv'] : 'Chưa gán'?>
                            </a>
                        </td>
                        <td class="align-middle">
                           <?=$items[$i]['ten']?>
                        </td>
                        <td class="align-middle">
                            <?=$items[$i]['email']?>
                        </td>
                        <td class="align-middle text-center">
                            <?php if(isset($items[$i]['tinhtrang']) && $items[$i]['tinhtrang'] == 1) { ?>
                                <span class="badge badge-success">Đã duyệt</span>
                            <?php } else { ?>
                                <span class="badge badge-warning">Chưa duyệt</span>
                            <?php } ?>
                        </td>
                        
                       
                        <?php if(
                                     (isset($config['order']['excel']) && $config['order']['excel'] == true) || 
                                     (isset($config['order']['word']) && $config['order']['word'] == true)
                                 ) { ?>
                        <td class="align-middle text-center text-lg text-nowrap">
                            <?php if(isset($config['order']['excel']) && $config['order']['excel'] == true) { ?>
                            <a class="text-primary mr-2" href="index.php?com=excel&id=<?=$items[$i]['id']?>"
                                title="Xuất file excel"><i class="far fa-file-excel"></i></a>
                            <?php } ?>
                            <?php if(isset($config['order']['word']) && $config['order']['word'] == true) { ?>
                            <a class="text-primary" href="index.php?com=word&id=<?=$items[$i]['id']?>"
                                title="Xuất file word"><i class="far fa-file-word"></i></a>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="align-middle text-right text-md text-nowrap">
                            <?php if(!isset($items[$i]['tinhtrang']) || $items[$i]['tinhtrang'] == 0) { ?>
                                <a href="#" class="btn btn-sm btn-outline-success mr-3 duyet-ctv-btn" data-url="<?=$linkDuyet?>&id=<?=$items[$i]['id']?>">Duyệt</a>
                            <?php } ?>
                            <a href="#" class="text-danger" id="delete-item" data-url="<?=$linkDelete?>&id=<?=$items[$i]['id']?>"
                                title="Xóa"><i class="fas fa-trash-alt"></i></a>
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
        <a class="btn btn-sm bg-gradient-success text-white mr-1" id="duyet-all" data-url="<?=$linkDuyet?>"
            title="Duyệt tất cả"><i class="fas fa-check mr-2"></i>Duyệt tất cả</a>
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?=$linkDelete?>"
            title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    </div>
</section>

<?php if(isset($config['order']['search']) && $config['order']['search'] == true) { ?>
<!-- Order js -->
<script type="text/javascript">
$(document).ready(function() {
    /* Date range picker */
    $('#ngaydat').daterangepicker({
        callback: this.render,
        autoUpdateInput: false,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY'
            // format: 'DD/MM/YYYY hh:mm A'
        }
    })
    $('#ngaydat').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
            'DD/MM/YYYY'));
    });
    $('#ngaydat').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    /* rangeSlider */
    $('#khoanggia').ionRangeSlider({
        skin: "flat",
        min: <?=($minTotal)?$minTotal:1?>,
        max: <?=($maxTotal)?$maxTotal:1?>,
        from: <?=($giatu)?$giatu:1?>,
        to: <?=($giaden)?$giaden:$maxTotal?>,
        type: 'double',
        step: 1,
        // prefix  : 'đ ',
        postfix: ' đ',
        prettify: true,
        hasGrid: true
    })
})
</script>
<?php } ?>