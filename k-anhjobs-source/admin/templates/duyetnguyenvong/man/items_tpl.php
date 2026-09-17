<?php
    $curDuyet = isset($_REQUEST['duyetnguyenvong']) ? (int)$_REQUEST['duyetnguyenvong'] : 0;
	$linkMan = "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$curDuyet."&p=".$curPage;
    $linkDuyet = "index.php?com=duyetnguyenvong&act=duyet&duyetnguyenvong=".$curDuyet."&p=".$curPage;
    $linkBoDuyet = "index.php?com=duyetnguyenvong&act=boduyet&duyetnguyenvong=".$curDuyet."&p=".$curPage;
    $linkDelete = "index.php?com=duyetnguyenvong&act=delete&duyetnguyenvong=".$curDuyet."&p=".$curPage;
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item active">Phê duyệt nguyện vọng</li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card-footer text-sm sticky-top">
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all-expectations" data-url="<?=$linkDelete?>"
            title="Xóa nguyện vọng đã chọn"><i class="far fa-trash-alt mr-2"></i>Xóa nguyện vọng đã chọn</a>
    </div>

    <!-- Filters & Search -->
    <div class="card card-primary card-outline text-sm">
        <div class="card-header">
            <h3 class="card-title">Bộ lọc & Tìm kiếm</h3>
        </div>
        <div class="card-body row align-items-center">
            <div class="form-group col-md-3 col-sm-3 mb-0">
                <label>Trạng thái duyệt:</label>
                <select id="filter_duyetnguyenvong" class="form-control text-sm">
                    <option value="0" <?= $curDuyet == 0 ? 'selected' : '' ?>>Chờ duyệt (Pending)</option>
                    <option value="1" <?= $curDuyet == 1 ? 'selected' : '' ?>>Đã duyệt (Approved)</option>
                    <option value="2" <?= $curDuyet == 2 ? 'selected' : '' ?>>Tất cả (All)</option>
                </select>
            </div>
            <div class="form-group col-md-4 col-sm-4 mb-0">
                <label>Từ khóa tìm kiếm:</label>
                <div class="input-group">
                    <input type="text" id="keyword_expectations" class="form-control text-sm" placeholder="Nhập tên, email, tỉnh mong muốn..." value="<?=(isset($_GET['keyword'])) ? htmlspecialchars($_GET['keyword']) : ''?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" id="btn-search-expectations" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-3 mb-0 d-flex align-items-end" style="height: 60px;">
                <?php if(isset($_GET['keyword'])) { ?>
                    <a class="btn btn-sm bg-gradient-danger text-white ml-2" href="index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=<?=$curDuyet?>" title="Hủy lọc"><i class="fas fa-times mr-1"></i>Hủy lọc</a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Table Grid -->
    <div class="card card-primary card-outline text-sm mb-0">
        <div class="card-header">
            <h3 class="card-title d-inline-block align-middle float-none">Danh sách nguyện vọng ứng viên</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="align-middle" width="5%">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input" id="selectall-checkbox-expectations">
                                <label for="selectall-checkbox-expectations" class="custom-control-label"></label>
                            </div>
                        </th>
                        <th class="align-middle" width="5%">ID</th>
                        <th class="align-middle" width="12%">Họ tên / Email / SĐT</th>
                        <th class="align-middle" width="10%">Tiếng Nhật / Tư cách cư trú / Hạn visa</th>
                        <th class="align-middle" width="12%">Khu vực / Ngành nghề / Mức lương</th>
                        <th class="align-middle" width="15%">Nội dung mong muốn / Khác</th>
                        <th class="align-middle" width="10%">Liên hệ</th>
                        <th class="align-middle" width="8%">Trạng thái</th>
                        <th class="align-middle text-right" width="18%">Thao tác</th>
                    </tr>
                </thead>
                <?php if(empty($items)) { ?>
                <tbody>
                    <tr>
                        <td colspan="100" class="text-center text-muted py-3">Không có dữ liệu nguyện vọng chờ duyệt</td>
                    </tr>
                </tbody>
                <?php } else { ?>
                <tbody>
                    <?php for($i=0;$i<count($items);$i++) { ?>
                    <tr>
                        <td class="align-middle">
                            <div class="custom-control custom-checkbox my-checkbox">
                                <input type="checkbox" class="custom-control-input select-checkbox-expectation"
                                    id="select-checkbox-<?=$items[$i]['id']?>" value="<?=$items[$i]['id']?>">
                                <label for="select-checkbox-<?=$items[$i]['id']?>" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?=$items[$i]['id']?>
                        </td>
                        <td class="align-middle">
                            <strong><?=$items[$i]['ten']?></strong><br>
                            <span class="text-muted"><i class="far fa-envelope mr-1"></i><?=$items[$i]['email']?></span><br>
                            <span class="text-muted"><i class="fas fa-phone mr-1"></i><?=$items[$i]['dienthoai']?></span>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-gradient-info text-sm">JLPT: <?=$items[$i]['trinhdotiengnhat']?></span><br>
                            <span class="text-sm">Tư cách: <?=$items[$i]['tucachcutru']?></span><br>
                            <span class="text-sm text-secondary">Hạn visa: <?=$items[$i]['thoihanvisa']?></span>
                        </td>
                        <td class="align-middle">
                            <span class="text-sm">Tỉnh: <strong><?=$items[$i]['tinhmongmuon']?></strong></span><br>
                            <span class="text-sm">Ngành: <?=$items[$i]['nganhnghemongmuon']?></span><br>
                            <span class="text-sm">Lương: <strong class="text-primary"><?=$items[$i]['mucluongmongmuon']?></strong></span>
                        </td>
                        <td class="align-middle">
                            <span class="text-sm text-dark d-block" style="max-height: 80px; overflow-y: auto;">
                                <strong>Công việc:</strong> <?=nl2br(htmlspecialchars($items[$i]['noidungmongmuon']))?>
                            </span>
                            <?php if ($items[$i]['mongmuonkhac']) { ?>
                                <span class="text-sm text-secondary mt-1 d-block">
                                    <strong>Khác:</strong> <?=$items[$i]['mongmuonkhac']?>
                                </span>
                            <?php } ?>
                        </td>
                        <td class="align-middle">
                            <?php if ($items[$i]['zalo_fb_line_id']) { ?>
                                <span class="badge bg-gradient-secondary"><?=$items[$i]['zalo_fb_line']?></span><br>
                                <span class="text-sm font-weight-bold"><?=$items[$i]['zalo_fb_line_id']?></span>
                            <?php } else { ?>
                                <span class="text-muted text-sm">Chưa cung cấp</span>
                            <?php } ?>
                        </td>
                        <td class="align-middle">
                            <?php if($items[$i]['duyetnguyenvong'] == 1) { ?>
                                <span class="badge badge-success text-sm py-1 px-2">Đã duyệt</span>
                            <?php } else { ?>
                                <span class="badge badge-warning text-sm py-1 px-2">Chờ duyệt</span>
                            <?php } ?>
                        </td>
                        <td class="align-middle text-right text-sm text-nowrap">
                            <?php if($items[$i]['duyetnguyenvong'] == 1) { ?>
                                <a href="#" id="boduyet-nguyenvong-btn" data-url="<?=$linkBoDuyet?>&id=<?=$items[$i]['id']?>" class="btn btn-sm btn-outline-warning mr-2"><i class="fas fa-undo mr-1"></i>Bỏ duyệt</a>
                            <?php } else { ?>
                                <a href="#" id="duyet-nguyenvong-btn" data-url="<?=$linkDuyet?>&id=<?=$items[$i]['id']?>" class="btn btn-sm btn-outline-success mr-2"><i class="fas fa-check mr-1"></i>Duyệt</a>
                            <?php } ?>
                            <a href="#" class="btn btn-sm btn-outline-danger" id="delete-expectation-btn" data-url="<?=$linkDelete?>&id=<?=$items[$i]['id']?>"
                                title="Xóa nguyện vọng"><i class="fas fa-trash-alt"></i></a>
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
        <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all-expectations" data-url="<?=$linkDelete?>"
            title="Xóa nguyện vọng đã chọn"><i class="far fa-trash-alt mr-2"></i>Xóa nguyện vọng đã chọn</a>
    </div>
</section>

<!-- Custom scripts for Duyet Nguyen Vong -->
<script type="text/javascript">
$(document).ready(function() {
    // Filter status change
    $('#filter_duyetnguyenvong').change(function() {
        var status = $(this).val();
        var url = "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=" + status;
        var keyword = $('#keyword_expectations').val();
        if (keyword) {
            url += "&keyword=" + encodeURI(keyword);
        }
        window.location = url;
    });

    // Search trigger
    $('#btn-search-expectations').click(function() {
        var keyword = $('#keyword_expectations').val();
        var status = $('#filter_duyetnguyenvong').val();
        var url = "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=" + status;
        if (keyword) {
            url += "&keyword=" + encodeURI(keyword);
        }
        window.location = url;
    });

    $('#keyword_expectations').keypress(function(e) {
        if(e.which == 13) {
            $('#btn-search-expectations').trigger('click');
        }
    });

    // Action confirmation popups
    $('body').on('click', '#duyet-nguyenvong-btn', function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        confirmDialog("delete-item", "Bạn có chắc chắn muốn DUYỆT nguyện vọng ứng tuyển này không? Sau khi duyệt, nguyện vọng này sẽ xuất hiện trên trang chủ của Cộng tác viên.", url);
    });

    $('body').on('click', '#boduyet-nguyenvong-btn', function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        confirmDialog("delete-item", "Bạn có chắc chắn muốn HỦY DUYỆT nguyện vọng ứng tuyển này không? Nguyện vọng này sẽ bị ẩn khỏi trang của Cộng tác viên.", url);
    });

    $('body').on('click', '#delete-expectation-btn', function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        confirmDialog("delete-item", "Bạn có chắc chắn muốn XÓA nguyện vọng ứng tuyển này không? Hành động này chỉ xóa thông tin nguyện vọng và KHÔNG xóa tài khoản ứng viên.", url);
    });

    // Check all checkboxes
    $('body').on('click', '#selectall-checkbox-expectations', function() {
        var parentTable = $(this).parents('table');
        var input = parentTable.find('input.select-checkbox-expectation');
        if ($(this).is(':checked')) {
            input.each(function() {
                $(this).prop('checked', true);
            });
        } else {
            input.each(function() {
                $(this).prop('checked', false);
            });
        }
    });

    // Delete all checked expectations
    $('body').on('click', '#delete-all-expectations', function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        var listid = "";
        $("input.select-checkbox-expectation").each(function() {
            if (this.checked) listid = listid + "," + this.value;
        });
        listid = listid.substr(1);
        if (listid == "") {
            notifyDialog("Vui lòng chọn ít nhất 1 nguyện vọng để xóa!");
            return false;
        }
        confirmDialog("delete-all", "Bạn có chắc chắn muốn XÓA TẤT CẢ các nguyện vọng đã chọn không? Các tài khoản ứng viên vẫn sẽ được giữ lại.", url + "&listid=" + listid);
    });
});
</script>
