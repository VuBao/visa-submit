<?php
	if($act=="add") $labelAct = "Thêm mới";
	else if($act=="edit") $labelAct = "Chỉnh sửa";
	else if($act=="copy")  $labelAct = "Sao chép";

	$linkMan = "index.php?com=news&act=man&type=".$type."&p=".$curPage;
	if($act=='add') $linkFilter = "index.php?com=news&act=add&type=".$type."&p=".$curPage;
	else if($act=='edit') $linkFilter = "index.php?com=news&act=edit&type=".$type."&p=".$curPage."&id=".$id;
    if($act=="copy") $linkSave = "index.php?com=news&act=save_copy&type=".$type."&p=".$curPage;
    else $linkSave = "index.php?com=news&act=save&type=".$type."&p=".$curPage;

    /* Check cols */
    if(isset($config['news'][$type]['gallery']) && count($config['news'][$type]['gallery']) > 0)
    {
        foreach($config['news'][$type]['gallery'] as $key => $value)
        {
            if($key == $type)
            {
                $flagGallery = true;
                break;
            }
        }
    }

    if(
        (isset($config['news'][$type]['dropdown']) && $config['news'][$type]['dropdown'] == true) || 
        (isset($config['news'][$type]['tags']) && $config['news'][$type]['tags'] == true) || 
        (isset($config['news'][$type]['images']) && $config['news'][$type]['images'] == true))
    {
        $colLeft = "col-xl-8";
        $colRight = "col-xl-4";
    }
    else
    {
        $colLeft = "col-12";
        $colRight = "d-none";   
    }
?>
<!-- Content Header -->
<section class="content-header text-sm">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                <li class="breadcrumb-item active"><?=$labelAct?> <?=$config['news'][$type]['title_main']?></li>
            </ol>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <form class="validation-form" novalidate method="post" action="<?=$linkSave?>" enctype="multipart/form-data">
        <div class="card-footer text-sm sticky-top">
            <button type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
                    class="far fa-save mr-2"></i>Lưu</button>
            <button type="submit" class="btn btn-sm bg-gradient-success submit-check" name="save-here"><i
                    class="far fa-save mr-2"></i>Lưu tại trang</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm
                lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i
                    class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
        </div>
        <div class="row">
            <div class="<?=$colLeft?>">
                <?php
                    if(isset($config['news'][$type]['slug']) && $config['news'][$type]['slug'] == true)
                    {
                        $slugchange = ($act=='edit') ? 1 : 0;
                        $copy = ($act!='copy') ? 0 : 1;
                        include TEMPLATE.LAYOUT."slug.php";
                    }
                ?>
                <div class="card card-primary card-outline text-sm">
                    <div class="card-header">
                        <h3 class="card-title">Nội dung <?=$config['news'][$type]['title_main']?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                                    <?php foreach($config['website']['lang'] as $k => $v) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?=($k=='vi')?'active':''?>" id="tabs-lang"
                                            data-toggle="pill" href="#tabs-lang-<?=$k?>" role="tab"
                                            aria-controls="tabs-lang-<?=$k?>" aria-selected="true"><?=$v?></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="card-body card-article">
                                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                                    <?php foreach($config['website']['lang'] as $k => $v) { ?>
                                    <div class="tab-pane fade show <?=($k=='vi')?'active':''?>" id="tabs-lang-<?=$k?>"
                                        role="tabpanel" aria-labelledby="tabs-lang">
                                        <div class="form-group">
                                            <label for="ten<?=$k?>">Tiêu đề (<?=$k?>):</label>
                                            <input type="text" class="form-control for-seo" name="data[ten<?=$k?>]"
                                                id="ten<?=$k?>" placeholder="Tiêu đề (<?=$k?>)"
                                                value="<?=@$item['ten'.$k]?>" required>
                                        </div>
                                        <?php if(isset($config['news'][$type]['mota']) && $config['news'][$type]['mota'] == true) { ?>
                                        <div class="form-group">
                                            <label for="mota<?=$k?>">Mô tả (<?=$k?>):</label>
                                            <textarea
                                                class="form-control for-seo <?=(isset($config['news'][$type]['mota_cke']) && $config['news'][$type]['mota_cke'] == true)?'form-control-ckeditor':''?>"
                                                name="data[mota<?=$k?>]" id="mota<?=$k?>" rows="5"
                                                placeholder="Mô tả (<?=$k?>)"><?=htmlspecialchars_decode(@$item['mota'.$k])?></textarea>
                                        </div>
                                        <?php } ?>
                                        <?php if(isset($config['news'][$type]['noidung']) && $config['news'][$type]['noidung'] == true) { ?>
                                        <div class="form-group">
                                            <label for="noidung<?=$k?>">Nội dung (<?=$k?>):</label>
                                            <textarea
                                                class="form-control for-seo <?=(isset($config['news'][$type]['noidung_cke']) && $config['news'][$type]['noidung_cke'] == true)?'form-control-ckeditor':''?>"
                                                name="data[noidung<?=$k?>]" id="noidung<?=$k?>" rows="5"
                                                placeholder="Nội dung (<?=$k?>)"><?=htmlspecialchars_decode(@$item['noidung'.$k])?></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="<?=$colRight?>">
                <?php if(
                    (isset($config['news'][$type]['dropdown']) && $config['news'][$type]['dropdown'] == true) || 
                    (isset($config['news'][$type]['tags']) && $config['news'][$type]['tags'] == true)
                ) { ?>
                <div class="card card-primary card-outline text-sm">
                    <div class="card-header">
                        <h3 class="card-title">Danh mục <?=$config['news'][$type]['title_main']?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group-category row">
                            <?php if(isset($config['news'][$type]['dropdown']) && $config['news'][$type]['dropdown'] == true) { ?>
                            <?php if(isset($config['news'][$type]['list']) && $config['news'][$type]['list'] == true) { ?>
                            <div class="form-group col-xl-6 col-sm-4">
                                <label class="d-block" for="id_list"><?= $config['news'][$type]['title_main_list'] ?>:</label>
                                <?=$func->get_ajax_category('news', 'list', $type)?>
                            </div>
                            <?php } ?>
                            <?php if(isset($config['news'][$type]['cat']) && $config['news'][$type]['cat'] == true) { ?>
                            <div class="form-group col-xl-6 col-sm-4">
                                <label class="d-block" for="id_cat">Danh mục cấp 2:</label>
                                <?=$func->get_ajax_category('news', 'cat', $type)?>
                            </div>
                            <?php } ?>
                            <?php if(isset($config['news'][$type]['item']) && $config['news'][$type]['item'] == true) { ?>
                            <div class="form-group col-xl-6 col-sm-4">
                                <label class="d-block" for="id_item">Danh mục cấp 3:</label>
                                <?=$func->get_ajax_category('news', 'item', $type)?>
                            </div>
                            <?php } ?>
                            <?php if(isset($config['news'][$type]['sub']) && $config['news'][$type]['sub'] == true) { ?>
                            <div class="form-group col-xl-6 col-sm-4">
                                <label class="d-block" for="id_sub">Danh mục cấp 4:</label>
                                <?=$func->get_ajax_category('news', 'sub', $type)?>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php if(isset($config['news'][$type]['tags']) && $config['news'][$type]['tags'] == true) { ?>
                            <div class="form-group col-xl-6 col-sm-4">
                                <label class="d-block" for="id_tags">Danh mục tags:</label>
                                <?=$func->get_tags(@$item['id'], 'tags_group', 'news', $type)?>
                            </div>
                            <?php } ?>
                            <?php if(isset($config['news'][$type]['tinhthanh']) && $config['news'][$type]['tinhthanh'] == true) { ?>
                                <div class="form-group col-xl-6 col-sm-4">
                                    <label class="d-block" for="id_tinhthanh">Tỉnh thành:</label>
                                    <?=$func->get_search('search','tinh-thanh','vi','Chọn tỉnh thành','select-tinh-thanh', @$item['id_tinh-thanh'])?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(isset($config['news'][$type]['images']) && $config['news'][$type]['images'] == true) { ?>
                <div class="card card-primary card-outline text-sm">
                    <div class="card-header">
                        <h3 class="card-title">Hình ảnh <?=$config['news'][$type]['title_main']?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                                $photoDetail = ($act != 'copy') ? UPLOAD_NEWS.@$item['photo'] : '';
                                $dimension = "Width: ".$config['news'][$type]['width']." px - Height: ".$config['news'][$type]['height']." px (".$config['news'][$type]['img_type'].")";
                                include TEMPLATE.LAYOUT."image.php";
                            ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="card card-primary card-outline text-sm">
            <div class="card-header">
                <h3 class="card-title">Thông tin <?=$config['news'][$type]['title_main']?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="hienthi" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input hienthi-checkbox" name="data[hienthi]"
                            id="hienthi-checkbox" <?=(!isset($item['hienthi']) || $item['hienthi']==1)?'checked':''?>>
                        <label for="hienthi-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php if(isset($config['news'][$type]['check1']) && $config['news'][$type]['check1'] == true) { ?>
                    <label for="tienthuong" class="d-inline-block align-middle mb-0 mr-2">Tiền thưởng:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input tienthuong-checkbox" name="data[tienthuong]"
                            id="tienthuong-checkbox" <?=$item['tienthuong']==1 ?'checked':''?>>
                        <label for="tienthuong-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['check1']) && $config['news'][$type]['check1'] == true) { ?>
                    <label for="1phong" class="d-inline-block align-middle mb-0 mr-2">1 người 1 phòng:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input 1phong-checkbox" name="data[1phong]"
                            id="1phong-checkbox" <?=$item['1phong']==1 ?'checked':''?>>
                        <label for="1phong-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['check1']) && $config['news'][$type]['check1'] == true) { ?>
                    <label for="lamthem" class="d-inline-block align-middle mb-0 mr-2">Làm thêm:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input lamthem-checkbox" name="data[lamthem]"
                            id="lamthem-checkbox" <?=$item['lamthem']==1?'checked':''?>>
                        <label for="lamthem-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['check1']) && $config['news'][$type]['check1'] == true) { ?>
                    <label for="trocap" class="d-inline-block align-middle mb-0 mr-2">Trợ cấp:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input trocap-checkbox" name="data[trocap]"
                            id="trocap-checkbox" <?=$item['trocap']==1?'checked':''?>>
                        <label for="trocap-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['check1']) && $config['news'][$type]['check1'] == true) { ?>
                    <label for="tangluong" class="d-inline-block align-middle mb-0 mr-2">Tăng lương:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                        <input type="checkbox" class="custom-control-input tangluong-checkbox" name="data[tangluong]"
                            id="tangluong-checkbox" <?=$item['tangluong']==1?'checked':''?>>
                        <label for="tangluong-checkbox" class="custom-control-label"></label>
                    </div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="stt" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
                    <input type="number" class="form-control form-control-mini d-inline-block align-middle" min="0"
                        name="data[stt]" id="stt" placeholder="Số thứ tự"
                        value="<?=isset($item['stt']) ? $item['stt'] : 1?>">
                </div>
                <div class="row">
                    <?php if(isset($config['news'][$type]['congty']) && $config['news'][$type]['congty'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="congty">Công ty:</label>
                        <input type="text" class="form-control" name="data[congty]" id="congty"
                            placeholder="Công ty" value="<?=@$item['congty']?>" >
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['luong']) && $config['news'][$type]['luong'] == true) { ?>
                        <div class="form-group col-md-6">
                            <label class="d-block" for="luong">Lương:</label>
                            <input type="text" class="form-control" name="data[luong]" id="luong"
                                placeholder="Lương" value="<?=@$item['luong']?>">
                        </div>
                    <?php } ?>
                    
                    <?php if(isset($config['news'][$type]['luongcoban']) && $config['news'][$type]['luongcoban'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="luongcoban">Lương cơ bản:</label>
                        <input type="text" class="form-control" name="data[luongcoban]" id="luongcoban"
                            placeholder="Lương cơ bản" value="<?=@$item['luongcoban']?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['thoigianlamviec']) && $config['news'][$type]['thoigianlamviec'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="thoigianlamviec">Thời gian làm việc:</label>
                        <input type="text" class="form-control" name="data[thoigianlamviec]" id="thoigianlamviec"
                            placeholder="Thời gian làm việc" value="<?=@$item['thoigianlamviec']?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['quoctich']) && $config['news'][$type]['quoctich'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="quoctich">Quốc tịch:</label>
                        <input type="text" class="form-control" name="data[quoctich]" id="quoctich"
                            placeholder="Quốc tịch" value="<?=@$item['quoctich']?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['diadiem']) && $config['news'][$type]['diadiem'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="diadiem">Địa điểm:</label>
                        <input type="text" class="form-control" name="data[diadiem]" id="diadiem" placeholder="Địa điểm"
                            value="<?=@$item['diadiem']?>" required>
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['ngayvaolam']) && $config['news'][$type]['ngayvaolam'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="ngayvaolam">Ngày vào làm:</label>
                        <input type="date" class="form-control" name="data[ngayvaolam]" id="ngayvaolam"
                            placeholder="Ngày vào làm" value="<?= @$item['ngayvaolam'] ? date('Y-m-d', @$item['ngayvaolam']) : ''  ?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['tinhtrangcutru']) && $config['news'][$type]['tinhtrangcutru'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="tinhtrangcutru">Tình trạng cư trú:</label>
                        <input type="text" class="form-control" name="data[tinhtrangcutru]" id="tinhtrangcutru"
                            placeholder="Tình trạng cư trú" value="<?=@$item['tinhtrangcutru']?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['soluong']) && $config['news'][$type]['soluong'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="soluong">Số lượng:</label>
                        <input type="number" class="form-control" name="data[soluong]" id="soluong"
                            placeholder="Số lượng" value="<?=@$item['soluong']?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['handangtuyen']) && $config['news'][$type]['handangtuyen'] == true) { ?>
                    <div class="form-group col-md-6">
                        <label class="d-block" for="handangtuyen">Hạn đăng tuyển:</label>
                        <input type="date" class="form-control" name="data[handangtuyen]" id="handangtuyen"
                            placeholder="Hạn đăng tuyển" value="<?= @$item['handangtuyen'] ? date('Y-m-d', @$item['handangtuyen']) : '' ?>">
                    </div>
                    <?php } ?>
                    <?php if(isset($config['news'][$type]['trangthai']) && $config['news'][$type]['trangthai'] == true) { ?>
                        <?php if(@$item['trangthai']) { ?> 
                            <div class="form-group col-md-6">
                                <label class="d-block" for="trangthai">Trạng thái:</label>
                                <select id="trangthai" name="data[trangthai]" class="form-control text-sm">
                                    <option value="0">Chọn tình trạng</option>
                                    <option value="1" <?= @$item['trangthai'] == 1 ? 'selected' : '' ?>>Bình thường</option>
                                    <option value="2" <?= @$item['trangthai'] == 2 ? 'selected' : '' ?>>Hết hạn</option>
                                    <option value="3" <?= @$item['trangthai'] == 3 ? 'selected' : '' ?>>Tạm dừng</option>
                                </select>
                            </div>
                        <?php } ?>
                   
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if(isset($flagGallery) && $flagGallery == true) { ?>
        <div class="card card-primary card-outline text-sm">
            <div class="card-header">
                <h3 class="card-title">Bộ sưu tập <?=$config['news'][$type]['title_main']?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="filer-gallery" class="label-filer-gallery mb-3">Album hình:
                        (<?=$config['news'][$type]['gallery'][$key]['img_type_photo']?>)</label>
                    <input type="file" name="files[]" id="filer-gallery" multiple="multiple">
                    <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
                    <input type="hidden" class="act-filer" value="man">
                    <input type="hidden" class="folder-filer" value="news">
                </div>
                <?php if(isset($gallery) && count($gallery) > 0) { ?>
                <div class="form-group form-group-gallery">
                    <label class="label-filer">Album hiện tại:</label>
                    <div class="action-filer mb-3">
                        <a class="btn btn-sm bg-gradient-primary text-white check-all-filer mr-1"><i
                                class="far fa-square mr-2"></i>Chọn tất cả</a>
                        <button type="button" class="btn btn-sm bg-gradient-success text-white sort-filer mr-1"><i
                                class="fas fa-random mr-2"></i>Sắp xếp</button>
                        <a class="btn btn-sm bg-gradient-danger text-white delete-all-filer"><i
                                class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
                    </div>
                    <div class="alert my-alert alert-sort-filer alert-info text-sm text-white bg-gradient-info"><i
                            class="fas fa-info-circle mr-2"></i>Có thể chọn nhiều hình để di chuyển</div>
                    <div class="jFiler-items my-jFiler-items jFiler-row">
                        <ul class="jFiler-items-list jFiler-items-grid row scroll-bar" id="jFilerSortable">
                            <?php foreach($gallery as $v) echo $func->galleryFiler($v['stt'],$v['id'],$v['photo'],$v['tenvi'],'news','col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6'); ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if(isset($config['news'][$type]['seo']) && $config['news'][$type]['seo'] == true) { ?>
        <div class="card card-primary card-outline text-sm">
            <div class="card-header">
                <h3 class="card-title">Nội dung SEO</h3>
                <a class="btn btn-sm bg-gradient-success d-inline-block text-white float-right create-seo"
                    title="Tạo SEO">Tạo SEO</a>
            </div>
            <div class="card-body">
                <?php
                        $seoDB = $seo->getSeoDB($id,$com,'man',$type);
                        include TEMPLATE.LAYOUT."seo.php";
                    ?>
            </div>
        </div>
        <?php } ?>
        <div class="card-footer text-sm">
            <button type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
                    class="far fa-save mr-2"></i>Lưu</button>
            <button type="submit" class="btn btn-sm bg-gradient-success submit-check" name="save-here"><i
                    class="far fa-save mr-2"></i>Lưu tại trang</button>
            <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm
                lại</button>
            <a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i
                    class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
            <input type="hidden" name="id" value="<?=(isset($item['id']) && $item['id'] > 0) ? $item['id'] : ''?>">
        </div>
    </form>
</section>