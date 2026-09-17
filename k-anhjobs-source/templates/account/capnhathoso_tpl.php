<style>
  :root {
    --navy-900: #0d2340;
    --navy-800: #153259;
    --navy-700: #1c4074;
    --blue-accent: #2f6fed;
    --blue-soft: #eaf3fc;
    --blue-soft-border: #cfe3fa;
    --page-bg: #eef1f6;
    --card-bg: #ffffff;
    --border: #e2e6ee;
    --border-soft: #edf0f5;
    --text-main: #1a2233;
    --text-jp: #7c8699;
    --text-placeholder: #9aa3b5;
    --radius: 14px;
  }

  .profile-card {
    background: var(--card-bg);
    border-radius: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 60px -25px rgba(13,35,64,.35), 0 2px 8px rgba(13,35,64,.06);
    overflow: hidden;
    margin-bottom: 30px;
  }

  .profile-card__top {
    height: 6px;
    background: linear-gradient(90deg, var(--navy-900), var(--blue-accent) 55%, #7fb0ff);
  }

  .profile-card__body {
    padding: 40px 30px;
  }

  .profile-card h1 {
    margin: 0 0 4px;
    font-size: 24px;
    font-weight: 800;
    color: var(--navy-900);
    letter-spacing: .01em;
    line-height: 1.3;
  }

  .profile-card h1 .jp-title {
    display: block;
    font-family: 'Noto Sans JP', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--navy-700);
    margin-top: 4px;
  }

  .profile-card .rule {
    width: 64px;
    height: 4px;
    border-radius: 4px;
    background: linear-gradient(90deg, var(--navy-900), var(--blue-accent));
    margin: 18px 0 22px;
  }

  .profile-card .intro {
    font-size: 14px;
    line-height: 1.6;
    color: #4c5567;
    margin: 0 0 30px;
  }

  .profile-card .intro .jp {
    display: block;
    font-size: 12.5px;
    margin-top: 3px;
    color: var(--text-jp);
  }

  .profile-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px 20px;
  }

  .profile-field { display: flex; flex-direction: column; }
  .profile-field.span-2 { grid-column: 1 / -1; }

  .profile-card label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--navy-900);
    margin-bottom: 8px;
    line-height: 1.4;
  }

  .profile-card .num {
    flex: none;
    width: 22px;
    height: 22px;
    border-radius: 6px 6px 6px 2px;
    background: var(--blue-soft);
    border: 1px solid var(--blue-soft-border);
    color: var(--navy-800);
    font-weight: 700;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
  }

  .profile-card label .required { color: #e0563f; margin-left: 2px; }

  .profile-card label .label-text .jp {
    display: block;
    font-size: 12px;
    font-weight: 500;
    margin-top: 2px;
    color: var(--text-jp);
  }

  .profile-card .input-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }

  .profile-card .input-wrap svg {
    position: absolute;
    left: 16px;
    width: 18px;
    height: 18px;
    stroke: var(--blue-accent);
    fill: none;
    pointer-events: none;
  }

  .profile-card input[type="text"],
  .profile-card input[type="email"],
  .profile-card select,
  .profile-card textarea {
    width: 100%;
    font-family: inherit;
    font-size: 14px;
    color: var(--text-main);
    background: #fbfcfe;
    border: 1.5px solid var(--border);
    border-radius: 11px;
    padding: 13px 16px 13px 46px;
    outline: none;
    transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    appearance: none;
  }

  .profile-card textarea {
    padding-left: 16px;
    resize: vertical;
    min-height: 80px;
    line-height: 1.55;
  }

  .profile-card select {
    padding-right: 40px;
    cursor: pointer;
  }

  .profile-card .select-wrap::after {
    content: "";
    position: absolute;
    right: 16px;
    width: 9px;
    height: 9px;
    border-right: 2px solid #9aa3b5;
    border-bottom: 2px solid #9aa3b5;
    transform: rotate(45deg);
    pointer-events: none;
  }

  .profile-card input:focus, .profile-card select:focus, .profile-card textarea:focus {
    border-color: var(--blue-accent);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(47,111,237,.12);
  }

  /* Validation styles */
  .was-validated .profile-form input:invalid,
  .was-validated .profile-form select:invalid,
  .was-validated .profile-form textarea:invalid,
  .profile-form input.is-invalid,
  .profile-form select.is-invalid,
  .profile-form textarea.is-invalid {
    border-color: #e0563f;
    background: #fff9f9;
    box-shadow: 0 0 0 4px rgba(224, 86, 63, 0.15);
  }

  .profile-card .hint {
    font-size: 11.5px;
    color: #a6aebd;
    margin-top: 5px;
    padding-left: 2px;
  }
  
  .profile-card .hint .jp { display: inline; font-size: 11.5px; }

  .profile-card .submit-wrap { grid-column: 1 / -1; margin-top: 10px; }

  .profile-card button.submit {
    width: 100%;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, var(--navy-800), var(--navy-900));
    color: #fff;
    padding: 15px 20px;
    border-radius: 12px;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 14px 28px -12px rgba(13,35,64,.55);
    transition: transform .12s ease, box-shadow .12s ease, background .12s ease;
  }
  .profile-card button.submit:hover { transform: translateY(-1px); box-shadow: 0 18px 34px -14px rgba(13,35,64,.6); }
  .profile-card button.submit:active { transform: translateY(0); }
  .profile-card button.submit svg { width: 18px; height: 18px; stroke: #fff; fill: none; }

  .profile-card .submit-text { text-align: left; line-height: 1.3; }
  .profile-card .submit-text .vi { font-weight: 700; font-size: 15px; }
  .profile-card .submit-text .jp { color: #c7d5ec; font-size: 12px; margin-top: 1px; }

  @media (max-width: 768px) {
    .profile-form { grid-template-columns: 1fr; gap: 20px; }
    .profile-card__body { padding: 24px 16px; }
  }
</style>

<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            
            <div class="profile-card">
                <div class="profile-card__top"></div>
                <div class="profile-card__body">

                    <h1>CẬP NHẬT HỒ SƠ CỦA TÔI
                        <span class="jp-title">マイプロフィール更新</span>
                    </h1>
                    <div class="rule"></div>

                    <p class="intro">
                        Vui lòng cập nhật đầy đủ thông tin cá nhân của bạn dưới đây. Các thông tin này sẽ giúp chúng tôi xác thực tài khoản và hoàn thiện CV của bạn.
                        <span class="jp">アカウントの確認および履歴書の作成のため、個人情報を完全に入力してください。</span>
                    </p>

                    <form class="profile-form form-contact validation-contact" novalidate method="post" action="" enctype="multipart/form-data">
                        
                        <!-- 1. Ảnh đại diện -->
                        <div class="profile-field span-2">
                            <label><span class="num">1</span><span class="label-text">Ảnh đại diện<span class="jp">プロフィール写真</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <input type="text" id="file_avatar" class="input-open-file" placeholder="Chọn ảnh làm đại diện" readonly value="<?= $row_detail['avatar'] ?>" style="cursor:pointer;" />
                                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                            </div>
                            <div class="hint">Nhấp vào đây để chọn ảnh chân dung rõ nét<span class="sep"> | </span><span class="jp">ここをクリックして写真を選択してください</span></div>
                        </div>

                        <?php if ($_SESSION[$login_member]['role'] == 1) { ?>
                        <!-- Họ tên CTV -->
                        <div class="profile-field span-2">
                            <label><span class="num">A</span><span class="label-text">Họ tên CTV<span class="required">*</span><span class="jp">氏名 *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <input type="text" id="ten" name="ten" value="<?= $row_detail['ten'] ?>" placeholder="Nhập họ tên của bạn" required>
                            </div>
                            <div class="hint">Vui lòng nhập đầy đủ họ tên của bạn<span class="sep"> | </span><span class="jp">氏名を入力してください</span></div>
                        </div>
                        <?php } ?>

                        <!-- 2. Email -->
                        <div class="profile-field">
                            <label><span class="num">2</span><span class="label-text">Email liên hệ<span class="required">*</span><span class="jp">メールアドレス *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                                <input type="email" id="email" name="email" value="<?= $row_detail['email'] ?>" placeholder="Nhập địa chỉ Email" required>
                            </div>
                            <div class="hint">Dùng để nhận thông báo công việc<span class="sep"> | </span><span class="jp">メールアドレスを入力してください</span></div>
                        </div>

                        <!-- 3. Giới tính -->
                        <div class="profile-field">
                            <label><span class="num">3</span><span class="label-text">Giới tính<span class="jp">性別</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M12 13v9M9 19h6"/></svg>
                                <select id="gioitinh" name="gioitinh">
                                    <option value="" disabled <?= empty($row_detail['gioitinh']) ? 'selected' : '' ?>>Chọn giới tính</option>
                                    <option value="Nam" <?= $row_detail['gioitinh'] == 'Nam' ? 'selected' : '' ?>>Nam / 男性</option>
                                    <option value="Nữ" <?= $row_detail['gioitinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ / 女性</option>
                                    <option value="Khác" <?= $row_detail['gioitinh'] == 'Khác' ? 'selected' : '' ?>>Khác / その他</option>
                                </select>
                            </div>
                            <div class="hint">Vui lòng chọn giới tính<span class="sep"> | </span><span class="jp">性別を選択してください</span></div>
                        </div>

                        <?php if (!$_SESSION[$login_member]['role']) { ?>
                        <!-- 4. Quốc tịch -->
                        <div class="profile-field">
                            <label><span class="num">4</span><span class="label-text">Quốc tịch<span class="jp">国籍</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                                <input type="text" id="quoctich" name="quoctich" placeholder="Nhập quốc tịch" value="<?= $row_detail['quoctich'] ?>">
                            </div>
                            <div class="hint">Ví dụ: Việt Nam<span class="sep"> | </span><span class="jp">国籍を入力してください</span></div>
                        </div>
                        <?php } ?>

                        <!-- 5. Ngày sinh -->
                        <div class="profile-field">
                            <label><span class="num">5</span><span class="label-text">Ngày sinh<span class="jp">生年月日</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <input type="text" id="ngaysinh" class="format-date" name="ngaysinh" placeholder="Chọn ngày sinh" value="<?= $row_detail['ngaysinh'] ? date('d/m/Y', $row_detail['ngaysinh']) : '' ?>" readonly style="cursor:pointer;">
                            </div>
                            <div class="hint">Định dạng Ngày/Tháng/Năm<span class="sep"> | </span><span class="jp">生年月日を選択してください</span></div>
                        </div>

                        <!-- 6. Địa chỉ hiện tại -->
                        <div class="profile-field">
                            <label><span class="num">6</span><span class="label-text">Địa chỉ hiện tại<span class="jp">現住所</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <input type="text" id="diachi" name="diachi" placeholder="Nhập địa chỉ hiện tại" value="<?= $row_detail['diachi'] ?>">
                            </div>
                            <div class="hint">Ví dụ: Tokyo, Japan<span class="sep"> | </span><span class="jp">現在の住所を入力してください</span></div>
                        </div>

                        <?php if (!$_SESSION[$login_member]['role']) { ?>
                        <!-- 7. Kinh nghiệm làm việc -->
                        <div class="profile-field span-2">
                            <label><span class="num">7</span><span class="label-text">Kinh nghiệm làm việc<span class="jp">職歴・仕事経験</span></span></label>
                            <div class="input-wrap">
                                <textarea id="kinhnghiemlamviec" name="kinhnghiemlamviec" placeholder="Mô tả ngắn gọn các công việc đã làm..." rows="5"><?= htmlspecialchars_decode($row_detail['kinhnghiemlamviec']) ?></textarea>
                            </div>
                            <div class="hint">Liệt kê công ty, vị trí và thời gian làm việc<span class="sep"> | </span><span class="jp">職歴を入力してください</span></div>
                        </div>

                        <!-- 8. Giới thiệu bản thân -->
                        <div class="profile-field span-2">
                            <label><span class="num">8</span><span class="label-text">Giới thiệu bản thân<span class="jp">自己紹介</span></span></label>
                            <div class="input-wrap">
                                <textarea id="gioithieubanthan" name="gioithieubanthan" placeholder="Sở thích, ưu điểm và định hướng nghề nghiệp..." rows="4"><?= htmlspecialchars_decode($row_detail['gioithieubanthan']) ?></textarea>
                            </div>
                            <div class="hint">Giới thiệu ngắn về bản thân bạn<span class="sep"> | </span><span class="jp">自己紹介を入力してください</span></div>
                        </div>

                        <!-- 9. Sơ yếu lý lịch -->
                        <div class="profile-field">
                            <label><span class="num">9</span><span class="label-text">Sơ yếu lý lịch<span class="jp">履歴書ファイル</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                <input type="text" id="soyeulilich" placeholder="Chọn file sơ yếu lý lịch" readonly value="<?= $row_detail['soyeulilich'] ?>" style="cursor:pointer;" />
                                <input type="file" name="file_soyeulilich" class="d-none" id="file_soyeulilich" accept=".pdf,.doc,.docx,.jpg,.png">
                            </div>
                            <div class="hint">Chấp nhận file PDF, Word hoặc Ảnh<span class="sep"> | </span><span class="jp">履歴書を選択してください</span></div>
                        </div>

                        <!-- 10. File CV đính kèm -->
                        <div class="profile-field">
                            <label><span class="num">10</span><span class="label-text">File CV ứng tuyển<span class="required">*</span><span class="jp">CVファイル *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <input type="text" id="cv_input" placeholder="Chọn file CV ứng tuyển" readonly value="<?= $row_detail['cv'] ?>" style="cursor:pointer;" />
                                <input type="file" name="file_cv" class="d-none" id="file_cv" accept=".pdf,.doc,.docx,.jpg,.png" <?= $row_detail['cv'] ? '' : 'required' ?>>
                            </div>
                            <div class="hint">File CV bắt buộc để ứng tuyển<span class="sep"> | </span><span class="jp">CVファイルを選択してください</span></div>
                        </div>
                        <?php } ?>

                        <!-- submit -->
                        <div class="submit-wrap">
                            <button type="submit" class="submit" name="submit-capnhat">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                <span class="submit-text">
                                    <span class="vi">CẬP NHẬT HỒ SƠ</span>
                                    <span class="jp">プロフィールを保存する</span>
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>