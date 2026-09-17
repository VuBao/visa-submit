<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            <div class="content-primary">
                <div class="job-box">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <h3 class="mb-2 mb-md-0 font-weight-bold text-uppercase" style="color: #1e293b; font-size: 20px;">
                            <i class="fas fa-trophy text-warning mr-2"></i>BẢNG THÀNH TÍCH
                        </h3>

                        <!-- Bộ lọc Tháng & Năm -->
                        <form method="get" action="account/danh-sach-gioi-thieu" class="form-inline d-flex align-items-center">
                            <div class="form-group mr-2 mb-0">
                                <label for="thang_select" class="mr-1 text-sm font-weight-bold">Tháng:</label>
                                <select name="thang" id="thang_select" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                                    <option value="0" <?= (isset($thang_selected) && $thang_selected == 0) ? 'selected' : '' ?>>Tất cả các tháng</option>
                                    <?php for($m=1; $m<=12; $m++) { ?>
                                        <option value="<?=$m?>" <?= (isset($thang_selected) && $thang_selected == $m) ? 'selected' : '' ?>>Tháng <?=$m?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mr-2 mb-0">
                                <label for="nam_select" class="mr-1 text-sm font-weight-bold">Năm:</label>
                                <select name="nam" id="nam_select" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                                    <option value="0" <?= (!isset($nam_selected) || $nam_selected == 0) ? 'selected' : '' ?>>Tất cả các năm</option>
                                    <?php 
                                    $currentYear = (int)date('Y');
                                    for($y=$currentYear-3; $y<=$currentYear+1; $y++) { ?>
                                        <option value="<?=$y?>" <?= (isset($nam_selected) && $nam_selected == $y) ? 'selected' : '' ?>>Năm <?=$y?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- 4 Thẻ thống kê thành tích tương ứng màu sắc -->
                    <div class="row mb-4">
                        <!-- 1. Trước phỏng vấn -->
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 rounded text-white shadow-sm" style="background-color: #00c0ef;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="font-weight-bold text-sm">Trước phỏng vấn</span>
                                    <i class="fas fa-comments fa-lg" style="opacity:0.8;"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0 text-white"><?= (int)@$count_phongvan ?></h2>
                                <small style="color: rgba(255,255,255,0.85);">Ứng viên</small>
                            </div>
                        </div>

                        <!-- 2. Đậu naitei -->
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 rounded shadow-sm" style="background-color: #ffc107; color: #1f2d3d;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="font-weight-bold text-sm">Đậu naitei</span>
                                    <i class="fas fa-award fa-lg" style="opacity:0.8;"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0" style="color: #1f2d3d;"><?= (int)@$count_naitei ?></h2>
                                <small style="color: #4b5563;">Ứng viên</small>
                            </div>
                        </div>

                        <!-- 3. Đăng ký xin visa -->
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 rounded text-white shadow-sm" style="background-color: #28a745;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="font-weight-bold text-sm">Đăng ký xin visa</span>
                                    <i class="fas fa-passport fa-lg" style="opacity:0.8;"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0 text-white"><?= (int)@$count_visa ?></h2>
                                <small style="color: rgba(255,255,255,0.85);">Ứng viên</small>
                            </div>
                        </div>

                        <!-- 4. Đang làm việc -->
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 rounded text-white shadow-sm" style="background-color: #1f2937;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="font-weight-bold text-sm">Đang làm việc</span>
                                    <i class="fas fa-briefcase fa-lg" style="opacity:0.8;"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0 text-white"><?= (int)@$count_lamviec ?></h2>
                                <small style="color: rgba(255,255,255,0.85);">Ứng viên</small>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($news)) { ?>
                    <?php foreach ($news as $n) { ?>
                    <?php $new_detail = $d->rawQueryOne("select * from #_news where id = ?", array($n['id_news'])) ?>
                    <?php if(!empty($new_detail)) { ?>
                    <div class="job">
                        <div class="grid-job">
                            <div class="job-img">
                                <a href="<?= $new_detail[$sluglang] ?>" class="scale-img">
                                    <img onerror="this.src='<?= THUMBS ?>/200x200x2/assets/images/noimage.png';"
                                        src="<?= THUMBS ?>/200x200x1/<?= UPLOAD_NEWS_L . $new_detail['photo'] ?>"
                                        alt="<?= $new_detail['ten'.$lang] ?>">
                                </a>
                            </div>
                            <div class="flex-grow-1 job-detail">
                                <div class="job-head d-lg-flex justify-content-between mb-3">
                                    <div class="job-left">
                                        <a href="<?= $new_detail[$sluglang] ?>"
                                            title="<?= $new_detail['ten'.$lang] ?>">#<?= $new_detail['id'] ?></a>
                                        <h3> <a href="<?= $new_detail[$sluglang] ?>"
                                                title="<?= $new_detail['ten'.$lang] ?>"
                                                class="text-split text-split-2"><?= $new_detail['ten'.$lang] ?></a>
                                        </h3>
                                    </div>
                                    <div class="job-right">
                                        <div class="d-flex">
                                            <?php if(isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active']) { ?>
                                            <?php $check = $d->rawQuery("select * from #_ungtuyen where id_news = ? and id_member = ?", array($new_detail['id'], $_SESSION[$login_member]['id']) ) ?>
                                            <?php if($check) { ?>
                                            <a class="btn-ungtuyen mr-2" href="<?= $new_detail[$sluglang] ?>"><?= daungtuyen ?></a>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2" href="account/ung-tuyen-ngay?id_tuyendung=<?= $new_detail['id'] ?>"><?= ungtuyenngay ?></a>
                                            <?php } ?>

                                            <?php $checkwl = $d->rawQueryOne("select * from #_uuthich where id_news = ? and id_member = ?", array($new_detail['id'], $_SESSION[$login_member]['id'])) ?>
                                            <?php if($checkwl) { ?>
                                            <a class="btn-whistlist active wishlist"
                                                data-news="<?= $new_detail['id'] ?>" href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } else { ?>
                                            <a class="btn-whistlist wishlist" data-news="<?= $new_detail['id'] ?>"
                                                href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2" href="account/ung-tuyen-ngay?id_tuyendung=<?= $new_detail['id'] ?>"><?= ungtuyenngay ?></a>
                                            <a class="btn-whistlist" href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="job-info d-lg-flex justify-content-between align-items-baseline">
                                    <div class="d-lg-flex justify-content-between">
                                        <div class="job-price d-flex align-items-baseline mb-2 mb-md-0">
                                            <img src="assets/images/money.png" alt="Tiền thưởng">
                                            <strong><?= $new_detail['luong'] ?></strong>
                                            <span> <?= yen ?> / <?= thang ?></span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="job-time d-lg-flex align-items-start">
                                    <span class="d-flex align-items-center mr-2 time-left">
                                        <?= thoigianlamviec ?>
                                        <span class="d-flex flex-column cl-accent time-center">
                                            <strong><?= $new_detail['thoigianlamviec'] ?></strong>
                                        </span>
                                    </span>
                                    
                                    <div class="d-flex hr-jobs__icon ml-auto pl-lg-2">
                                        <?php if($new_detail['tienthuong'] == 1) { ?>
                                        <img src="assets/images/money.png" alt="<?= tienthuong ?>">
                                        <?php } ?>
                                        <?php if($new_detail['1phong'] == 1) { ?>
                                        <img src="assets/images/1phong.png" alt="<?= nguoi1phong ?>">
                                        <?php } ?>
                                        <?php if($new_detail['lamthem'] == 1) { ?>
                                        <img src="assets/images/tangca.png" alt="<?= colamthem ?>">
                                        <?php } ?>
                                        <?php if($new_detail['trocap'] == 1) { ?>
                                        <img src="assets/images/trocap.png" alt="<?= trocap ?>">
                                        <?php } ?>
                                        <?php if($new_detail['tangluong'] == 1) { ?>
                                        <img src="assets/images/tangluong.png" alt="<?= tangluong ?>">
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="job-sumary2 d-flex align-items-center justify-content-between">
                            <?php $row_gt = $d->rawQuery("select * from #_ungtuyen where id_member_gt = ? and id_news = ?", array($_SESSION[$login_member]['id'], $n['id_news'])) ?>
                            
                            <div class="text-truncate mr-5" id="">
                            <i class="far fa-user"></i> <strong style="color: white"><?= !empty($row_gt) ? count($row_gt) : 0 ?> Người </strong> <?= dathamgia ?>
                            </div>
                            <div class="job-meta morongdanhsach" style="cursor: pointer;">
                                <span class="d-block"><?= morongdanhsach ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <div class="table-gioithieu">
                            <div class="job-sumary d-flex align-items-center justify-content-between">
                                <span>Ngày ứng tuyển</span>
                                <span>Ứng viên</span>
                                <span>Trạng thái</span>
                            </div>
                            <?php if(!empty($row_gt)) { foreach($row_gt as $gtt) { ?> 
                                <div class="job-sumary4 d-flex align-items-center justify-content-between">
                                    <span><?= date('d/m/Y', $gtt['ngaytao']) ?></span>
                                    <?php $mem = $d->rawQueryOne('select username, ten from #_member where id = ?', array($gtt['id_member'])); ?>
                                    <span><?= !empty($gtt['ten']) ? $gtt['ten'] : (is_array($mem) && !empty($mem['ten']) ? $mem['ten'] : (is_array($mem) ? @$mem['username'] : 'Người dùng đã xóa')) ?></span>
                                    <span class="d-block">
                                        <?php if ($gtt['tinhtrang'] == 2) { ?>
                                        <span class="badge" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">Trước phỏng vấn</span>
                                        <?php } else if ($gtt['tinhtrang'] == 3) { ?>
                                        <span class="badge" style="background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a;">Đậu naitei</span>
                                        <?php } else if ($gtt['tinhtrang'] == 4) { ?>
                                        <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Đăng ký xin visa</span>
                                        <?php } else if ($gtt['tinhtrang'] == 5) { ?>
                                        <span class="badge" style="background-color: #1f2937; color: #ffffff; border: 1px solid #111827;">Đang làm việc</span>
                                        <?php } else { ?>
                                        <span class="badge" style="background-color: #f3f4f6; color: #4b5563;">Chưa cập nhật</span>
                                        <?php } ?>
                                    </span>
                                </div>
                            <?php } } ?>
                            
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>

                    <div class="pagination-home"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
                    <?php } else { ?>
                    <p><?= khongcodulieudehienthi ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>