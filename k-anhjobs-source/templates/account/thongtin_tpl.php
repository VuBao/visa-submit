<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile" id="lightgallery">
            <!-- Header Banner -->
            <div class="profile-header mb-4" style="background: linear-gradient(135deg, #0072bc 0%, #005a96 100%); color: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,114,188,0.15);">
                <h2 style="font-size: 24px; font-weight: 700; margin: 0; color: #fff;"><?= $row_detail['ten'] ? $row_detail['ten'] : 'Hồ sơ cá nhân' ?></h2>
                <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 14px; font-weight: 500;">
                    <i class="fas <?= $row_detail['role'] == 1 ? 'fa-user-tie' : 'fa-user-graduate' ?> mr-2"></i>
                    <?= $row_detail['role'] == 1 ? 'Cộng tác viên' : 'Ứng viên' ?>
                </p>
            </div>

            <?php 
                $profile_photo = '';
                if (!empty($row_detail['avatar'])) {
                    $profile_photo = $row_detail['avatar'];
                } elseif (!empty($row_detail['soyeulilich'])) {
                    $ext = strtolower(pathinfo($row_detail['soyeulilich'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $profile_photo = $row_detail['soyeulilich'];
                    }
                }
            ?>
            <!-- Personal Info Card -->
            <div class="card-profile mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="font-size: 18px; font-weight: 700; color: #0072bc; margin-bottom: 20px; border-bottom: 2px solid #0072bc; padding-bottom: 8px; display: inline-block; margin-top: 0;">
                    <i class="fas fa-id-card mr-2"></i><?= thongtincanhan ?>
                </h3>
                
                <div class="user-img-wrapper" style="display: flex; flex-direction: column; align-items: flex-start; gap: 12px;">
                    <span style="font-weight: 600; color: #4a5568; font-size: 15px;">
                        <?= anhsoyeulilich ?> (<?= anhhoso ?>)
                    </span>
                    <a class="gallery" href="<?= !empty($profile_photo) ? UPLOAD_FILE_L . $profile_photo : 'assets/images/noimage.png' ?>" target="_blank" style="display: block; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px; background-color: #fff; transition: all 0.2s;" onmouseover="this.style.borderColor='#0072bc'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <img onerror="this.src='<?= THUMBS ?>/200x220x2/assets/images/noimage.png';"
                             src="<?= !empty($profile_photo) ? THUMBS . '/200x220x1/' . UPLOAD_FILE_L . $profile_photo : THUMBS . '/200x220x2/assets/images/noimage.png' ?>" 
                             alt="<?= htmlspecialchars($row_detail['ten']) ?>"
                             style="border-radius: 6px; max-width: 220px; height: auto; display: block;">
                    </a>
                </div>

                <?php if(!empty($row_detail['soyeulilich']) || !empty($row_detail['cv'])) { ?>
                <div class="user-files-wrapper mt-3 pt-3" style="border-top: 1px dashed #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                    <?php if(!empty($row_detail['soyeulilich'])) { ?>
                        <div>
                            <strong style="color: #4a5568;"><i class="fas fa-paperclip mr-1"></i>Sơ yếu lý lịch:</strong> 
                            <a href="<?= UPLOAD_FILE_L . $row_detail['soyeulilich'] ?>" target="_blank" class="text-primary font-weight-bold ml-1">
                                <?= $row_detail['soyeulilich'] ?> <i class="fas fa-external-link-alt ml-1" style="font-size:12px;"></i>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(!empty($row_detail['cv'])) { ?>
                        <div>
                            <strong style="color: #4a5568;"><i class="fas fa-file-pdf mr-1"></i>File CV ứng tuyển:</strong> 
                            <a href="<?= UPLOAD_FILE_L . $row_detail['cv'] ?>" target="_blank" class="text-primary font-weight-bold ml-1">
                                <?= $row_detail['cv'] ?> <i class="fas fa-external-link-alt ml-1" style="font-size:12px;"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            
            <!-- Job Expectations Card (For Candidates Only) -->
            <?php if(!$row_detail['role']) { ?>
            <div class="card-profile mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="font-size: 18px; font-weight: 700; color: #0072bc; margin-bottom: 20px; border-bottom: 2px solid #0072bc; padding-bottom: 8px; display: inline-block; margin-top: 0;">
                    <i class="fas fa-bullseye mr-2"></i>Nguyện vọng ứng tuyển
                </h3>
                
                <div class="card-nguyenvong p-3" style="border: 1px solid #d6e4f0; border-radius: 12px; background: #fafbfc;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Họ và tên:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['ten'] ? $row_detail['ten'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Loại visa hiện tại:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['tucachcutru'] ? $row_detail['tucachcutru'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Thời hạn visa:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['thoihanvisa'] ? $row_detail['thoihanvisa'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Trình độ tiếng Nhật:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['trinhdotiengnhat'] ? $row_detail['trinhdotiengnhat'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Khu vực mong muốn:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['tinhmongmuon'] ? $row_detail['tinhmongmuon'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Ngành nghề mong muốn:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['nganhnghemongmuon'] ? $row_detail['nganhnghemongmuon'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Nội dung công việc mong muốn:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['noidungmongmuon'] ? nl2br($row_detail['noidungmongmuon']) : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Mức lương mong muốn:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['mucluongmongmuon'] ? $row_detail['mucluongmongmuon'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Thời gian có thể chuyển việc:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['thoigianchuyenviec'] ? $row_detail['thoigianchuyenviec'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Liên hệ (<?= $row_detail['zalo_fb_line'] ? $row_detail['zalo_fb_line'] : 'Zalo/FB/LINE' ?>):</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['zalo_fb_line_id'] ? $row_detail['zalo_fb_line_id'] : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="col-md-12">
                            <strong style="color:#0072bc; display: block; margin-bottom: 4px;">Mong muốn khác:</strong>
                            <div style="color: #2d3748; font-weight: 500;"><?= $row_detail['mongmuonkhac'] ? $row_detail['mongmuonkhac'] : '<span class="text-muted">Không có</span>' ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Action Buttons -->
            <div class="profile-actions" style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 24px;">
                <a class="btn-ungtuyen" href="account/cap-nhat-ho-so" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; font-weight: 600; border-radius: 8px; transition: all 0.2s;">
                    <i class="fas fa-user-edit"></i><?= capnhathosongay ?>
                </a>
                <?php if(!$row_detail['role']) { ?>
                    <a class="btn-ungtuyen" href="account/dang-ky-nguyen-vong" style="background-color: #0072bc; border-color: #0072bc; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; font-weight: 600; border-radius: 8px; transition: all 0.2s;">
                        <i class="fas fa-edit"></i>Cập nhật nguyện vọng
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>