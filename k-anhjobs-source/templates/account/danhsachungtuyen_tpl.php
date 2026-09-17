<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            <div class="content-primary">
                <div class="job-box">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <div class="d-flex align-items-center flex-wrap">
                            <?php if(isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1) { ?>
                                <button type="button" class="btn text-danger font-weight-bold p-0 mr-4" style="font-size: 16px; text-decoration: none; border: none; background: transparent;" data-toggle="modal" data-target="#modal-add-ungvien">
                                    + THÊM ỨNG VIÊN
                                </button>
                            <?php } else { ?>
                                <h3 class="mb-0 font-weight-bold" style="color: #1e293b; font-size: 20px;">
                                    <?= danhsachtindaungtuyen ?> ( <?= $total ?> <?= ketqua ?> )
                                </h3>
                            <?php } ?>
                        </div>
                        <div class="mt-2 mt-md-0" style="min-width: 160px;">
                            <select class="form-control form-control-sm" id="filter-dscv">
                                <option value="0" <?= !isset($_GET['tinhtrang']) || $_GET['tinhtrang'] == 0 ? 'selected' : '' ?>><?= tatca ?></option>
                                <option value="2" <?= isset($_GET['tinhtrang']) && $_GET['tinhtrang'] == 2 ? 'selected' : '' ?>>Trước phỏng vấn</option>
                                <option value="3" <?= isset($_GET['tinhtrang']) && $_GET['tinhtrang'] == 3 ? 'selected' : '' ?>>Đậu naitei</option>
                                <option value="4" <?= isset($_GET['tinhtrang']) && $_GET['tinhtrang'] == 4 ? 'selected' : '' ?>>Đăng ký xin visa</option>
                                <option value="5" <?= isset($_GET['tinhtrang']) && $_GET['tinhtrang'] == 5 ? 'selected' : '' ?>>Đang làm việc</option>
                            </select>
                        </div>
                    </div>

                    <?php if(count($news)) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-3" style="border-color: #fca5a5;">
                            <thead style="background-color: #fff5f5;">
                                <tr style="color: #dc2626; font-weight: 700; font-size: 14px;">
                                    <th style="width: 60px; color: #dc2626; border-color: #fca5a5;" class="text-center">STT</th>
                                    <th style="min-width: 150px; color: #dc2626; border-color: #fca5a5;" class="text-center">HỌ TÊN</th>
                                    <th style="min-width: 180px; color: #dc2626; border-color: #fca5a5;" class="text-left">CÔNG TY</th>
                                    <th style="min-width: 140px; color: #dc2626; border-color: #fca5a5;" class="text-center">TÌNH TRẠNG</th>
                                    <th style="min-width: 130px; color: #dc2626; border-color: #fca5a5;" class="text-center">THANH TOÁN</th>
                                    <th style="min-width: 150px; color: #dc2626; border-color: #fca5a5;" class="text-left">GHI CHÚ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $curPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
                                $start_stt = ($curPage - 1) * 10;
                                foreach ($news as $k => $n) { 
                                    $stt_num = $start_stt + $k + 1;
                                    
                                    // Ứng viên name
                                    if(!empty($n['id_member']) && $n['id_member'] > 0) {
                                        $mem_uv = $d->rawQueryOne('select ten, username from #_member where id = ?', array($n['id_member']));
                                        $ho_ten = !empty($mem_uv['ten']) ? $mem_uv['ten'] : (!empty($mem_uv['username']) ? $mem_uv['username'] : (!empty($n['ten']) ? $n['ten'] : '---'));
                                    } else {
                                        $ho_ten = !empty($n['ten']) ? $n['ten'] : '---';
                                    }

                                    // Công ty / Job title
                                    $new_detail = array();
                                    if(!empty($n['id_news'])) {
                                        $new_detail = $d->rawQueryOne("select tenvi, tenen, tenjp, congty from #_news where id = ?", array($n['id_news']));
                                    }
                                    $l_code = (!empty($lang) && in_array($lang, ['vi','en','jp'])) ? $lang : 'vi';
                                    $job_name = !empty($new_detail['ten' . $l_code]) ? $new_detail['ten' . $l_code] : (!empty($new_detail['tenvi']) ? $new_detail['tenvi'] : '');
                                    $congty_name = !empty($n['congty']) ? $n['congty'] : (!empty($n['ten_news']) ? $n['ten_news'] : (!empty($new_detail['congty']) ? $new_detail['congty'] : (!empty($job_name) ? $job_name : '---')));

                                    // Số ngày đã làm việc (tinhtrang = 5)
                                    $working_days_count = 0;
                                    if($n['tinhtrang'] == 5 && !empty($n['working_date']) && $n['working_date'] > 0) {
                                        $working_days_count = floor((time() - $n['working_date']) / 86400);
                                    }
                                ?>
                                <tr style="border-color: #fca5a5;">
                                    <td class="align-middle text-center font-weight-bold" style="border-color: #fca5a5;"><?= $stt_num ?></td>
                                    <td class="align-middle text-center font-weight-bold text-dark" style="border-color: #fca5a5;"><?= htmlspecialchars($ho_ten) ?></td>
                                    <td class="align-middle text-left" style="border-color: #fca5a5;"><?= htmlspecialchars($congty_name) ?></td>
                                    <td class="align-middle text-center" style="border-color: #fca5a5;">
                                        <?php if ($n['tinhtrang'] == 2) { ?>
                                            <span class="badge" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 6px 12px; font-size: 13px; font-weight: 600;">Trước phỏng vấn</span>
                                        <?php } else if ($n['tinhtrang'] == 3) { ?>
                                            <span class="badge" style="background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; padding: 6px 12px; font-size: 13px; font-weight: 600;">Đậu naitei</span>
                                        <?php } else if ($n['tinhtrang'] == 4) { ?>
                                            <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 6px 12px; font-size: 13px; font-weight: 600;">Đăng ký xin visa</span>
                                        <?php } else if ($n['tinhtrang'] == 5) { ?>
                                            <span class="badge" style="background-color: #1f2937; color: #ffffff; border: 1px solid #111827; padding: 6px 12px; font-size: 13px; font-weight: 600;">Đang làm việc</span>
                                            <?php if(!empty($n['working_date']) && $n['working_date'] > 0) { ?>
                                                <div class="mt-1">
                                                    <small class="text-muted font-italic" style="font-size: 11px;">Đã làm <?= $working_days_count ?> ngày (từ <?= date('d/m/Y', $n['working_date']) ?>)</small>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <span class="badge" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; padding: 6px 12px; font-size: 13px; font-weight: 600;">Chưa cập nhật</span>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle text-center" style="border-color: #fca5a5;">
                                        <?php if (isset($n['tinhtrang_gt']) && $n['tinhtrang_gt'] == 2) { ?>
                                            <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 6px 12px; font-size: 12px; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i>Đã thanh toán</span>
                                        <?php } else { ?>
                                            <span class="badge" style="background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; font-size: 12px; font-weight: 600;"><i class="fas fa-clock mr-1"></i>Chưa thanh toán</span>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle text-left" style="border-color: #fca5a5;">
                                        <?= !empty($n['ghichu']) ? htmlspecialchars($n['ghichu']) : '<span class="text-muted font-italic">---</span>' ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-home"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
                    <?php } else { ?>
                    <p class="text-muted py-4 text-center"><?= khongcodulieudehienthi ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1) { ?>
<!-- Modal thêm ứng viên -->
<div class="modal fade" id="modal-add-ungvien" tabindex="-1" role="dialog" aria-labelledby="modal-add-ungvien-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold" id="modal-add-ungvien-label">Thêm ứng viên</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-ungvien" onsubmit="return false;">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="d-block font-weight-bold">Họ tên người giới thiệu (Mã CTV):</label>
                            <input type="text" class="form-control" value="<?= isset($ten_gioithieu) ? htmlspecialchars($ten_gioithieu) : '' ?>" readonly disabled>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="add-uv-ten" class="d-block font-weight-bold">Họ tên ứng viên: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten" id="add-uv-ten" placeholder="Nhập họ tên ứng viên (Ví dụ: Nguyễn Văn A)" required>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="add-uv-congty" class="d-block font-weight-bold">Tên công ty ứng tuyển:</label>
                            <input type="text" class="form-control" name="congty" id="add-uv-congty" placeholder="Nhập tên công ty">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="add-uv-ten_news" class="d-block font-weight-bold">Công việc (Ngành nghề):</label>
                            <input type="text" class="form-control" name="ten_news" id="add-uv-ten_news" placeholder="Nhập tên công việc (Ví dụ: Nhà hàng, Thực phẩm...)">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="add-uv-tinhtrang" class="d-block font-weight-bold">Tình trạng ứng viên: <span class="text-danger">*</span></label>
                            <select class="form-control" name="tinhtrang" id="add-uv-tinhtrang" required>
                                <option value="1" selected>Chọn tình trạng</option>
                                <option value="2">Trước phỏng vấn</option>
                                <option value="3">Đậu naitei</option>
                                <option value="4">Đăng ký xin visa</option>
                                <option value="5">Đang làm việc</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="add-uv-sodienthoai" class="d-block font-weight-bold">SĐT liên hệ:</label>
                            <input type="text" class="form-control" name="sodienthoai" id="add-uv-sodienthoai" placeholder="Nhập số điện thoại liên hệ">
                        </div>
                        <div class="form-group col-12">
                            <label for="add-uv-link_facebook" class="d-block font-weight-bold">Link Facebook:</label>
                            <input type="text" class="form-control" name="link_facebook" id="add-uv-link_facebook" placeholder="Nhập đường dẫn trang Facebook">
                        </div>
                        <div class="form-group col-12">
                            <label for="add-uv-ghichu" class="font-weight-bold">Ghi chú:</label>
                            <textarea class="form-control" name="ghichu" id="add-uv-ghichu" rows="3" placeholder="Ghi chú thêm..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Lưu ứng viên</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>