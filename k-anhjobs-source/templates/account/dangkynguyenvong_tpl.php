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

  .intent-card {
    background: var(--card-bg);
    border-radius: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 60px -25px rgba(13,35,64,.35), 0 2px 8px rgba(13,35,64,.06);
    overflow: hidden;
    margin-bottom: 30px;
  }

  .intent-card__top {
    height: 6px;
    background: linear-gradient(90deg, var(--navy-900), var(--blue-accent) 55%, #7fb0ff);
  }

  .intent-card__body {
    padding: 40px 30px;
  }

  .intent-card h1 {
    margin: 0 0 4px;
    font-size: 24px;
    font-weight: 800;
    color: var(--navy-900);
    letter-spacing: .01em;
    line-height: 1.3;
  }

  .intent-card h1 .jp-title {
    display: block;
    font-family: 'Noto Sans JP', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--navy-700);
    margin-top: 4px;
  }

  .intent-card .rule {
    width: 64px;
    height: 4px;
    border-radius: 4px;
    background: linear-gradient(90deg, var(--navy-900), var(--blue-accent));
    margin: 18px 0 22px;
  }

  .intent-card .intro {
    font-size: 14px;
    line-height: 1.6;
    color: #4c5567;
    margin: 0 0 30px;
  }

  .intent-card .intro .jp {
    display: block;
    font-size: 12.5px;
    margin-top: 3px;
    color: var(--text-jp);
  }

  .intent-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px 20px;
  }

  .intent-field { display: flex; flex-direction: column; }
  .intent-field.span-2 { grid-column: 1 / -1; }

  .intent-card label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--navy-900);
    margin-bottom: 8px;
    line-height: 1.4;
  }

  .intent-card .num {
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

  .intent-card label .required { color: #e0563f; margin-left: 2px; }

  .intent-card label .label-text .jp {
    display: block;
    font-size: 12px;
    font-weight: 500;
    margin-top: 2px;
    color: var(--text-jp);
  }

  .intent-card .input-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }

  .intent-card .input-wrap svg {
    position: absolute;
    left: 16px;
    width: 18px;
    height: 18px;
    stroke: var(--blue-accent);
    fill: none;
    pointer-events: none;
  }

  .intent-card input[type="text"],
  .intent-card select,
  .intent-card textarea {
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

  .intent-card textarea {
    padding-left: 16px;
    resize: vertical;
    min-height: 80px;
    line-height: 1.55;
  }

  .intent-card select {
    padding-right: 40px;
    cursor: pointer;
  }

  .intent-card select:invalid { color: var(--text-placeholder); }

  .intent-card .select-wrap::after {
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

  .intent-card input::placeholder, .intent-card textarea::placeholder { color: var(--text-placeholder); }

  .intent-card input:focus, .intent-card select:focus, .intent-card textarea:focus {
    border-color: var(--blue-accent);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(47,111,237,.12);
  }

  /* Validation styles */
  .was-validated .intent-form input:invalid,
  .was-validated .intent-form select:invalid,
  .was-validated .intent-form textarea:invalid,
  .intent-form input.is-invalid,
  .intent-form select.is-invalid,
  .intent-form textarea.is-invalid {
    border-color: #e0563f;
    background: #fff9f9;
    box-shadow: 0 0 0 4px rgba(224, 86, 63, 0.15);
  }

  .intent-card .hint {
    font-size: 11.5px;
    color: #a6aebd;
    margin-top: 5px;
    padding-left: 2px;
  }
  .intent-card .hint .jp { display: inline; font-size: 11.5px; }
  .intent-card .hint .sep { margin: 0 4px; color: #c7cedc; }

  .intent-card .contact-row {
    display: grid;
    grid-template-columns: 130px 1fr;
    gap: 10px;
    width: 100%;
  }
  .intent-card .contact-row select { padding-left: 16px; }

  .intent-card .notice {
    grid-column: 1 / -1;
    display: flex;
    gap: 12px;
    background: var(--blue-soft);
    border: 1px solid var(--blue-soft-border);
    border-radius: 12px;
    padding: 16px 18px;
    margin-top: 6px;
  }
  .intent-card .notice svg { flex: none; width: 20px; height: 20px; stroke: var(--blue-accent); fill: none; margin-top: 1px; }
  .intent-card .notice p {
    margin: 0;
    font-size: 12.5px;
    line-height: 1.6;
    color: #3d4a63;
  }
  .intent-card .notice p.jp { margin-top: 3px; font-size: 11.5px; color: var(--text-jp); }

  .intent-card .submit-wrap { grid-column: 1 / -1; margin-top: 6px; }

  .intent-card button.submit {
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
  .intent-card button.submit:hover { transform: translateY(-1px); box-shadow: 0 18px 34px -14px rgba(13,35,64,.6); }
  .intent-card button.submit:active { transform: translateY(0); }
  .intent-card button.submit svg { width: 18px; height: 18px; stroke: #fff; fill: none; }

  .intent-card .submit-text { text-align: left; line-height: 1.3; }
  .intent-card .submit-text .vi { font-weight: 700; font-size: 15px; }
  .intent-card .submit-text .jp { color: #c7d5ec; font-size: 12px; margin-top: 1px; }

  .intent-card .consent {
    grid-column: 1 / -1;
    text-align: center;
    font-size: 11.5px;
    color: #9aa3b5;
    margin-top: 2px;
    line-height: 1.6;
  }
  .intent-card .consent .jp { display: block; margin-top: 1px; font-size: 11px; }

  @media (max-width: 768px) {
    .intent-form { grid-template-columns: 1fr; gap: 20px; }
    .intent-card__body { padding: 24px 16px; }
    .intent-card .contact-row { grid-template-columns: 1fr; }
  }
</style>

<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            
            <div class="intent-card">
                <div class="intent-card__top"></div>
                <div class="intent-card__body">

                    <h1>ĐĂNG KÝ NGUYỆN VỌNG ỨNG TUYỂN
                        <span class="jp-title">応募希望登録フォーム</span>
                    </h1>
                    <div class="rule"></div>

                    <p class="intro">
                        Vui lòng điền các nguyện vọng tuyển dụng dưới đây để chúng tôi hỗ trợ kết nối bạn với những công việc phù hợp nhất tại Nhật Bản.
                        <span class="jp">より良いサポートのため、以下の情報をご入力ください。</span>
                    </p>

                    <form class="intent-form form-contact validation-contact" novalidate method="post" action="">
                        
                        <!-- 1. Họ và tên -->
                        <div class="intent-field">
                            <label><span class="num">1</span><span class="label-text">Họ và tên<span class="required">*</span><span class="jp">氏名 *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                                <input type="text" name="ten" placeholder="Nhập họ và tên" required value="<?= $row_detail['ten'] ?>">
                            </div>
                            <div class="hint">Nhập đầy đủ cả họ và tên<span class="sep"> | </span><span class="jp">氏名を入力してください</span></div>
                        </div>

                        <!-- 2. Loại visa hiện tại -->
                        <div class="intent-field">
                            <label><span class="num">2</span><span class="label-text">Loại visa hiện tại<span class="required">*</span><span class="jp">現在のビザの種類 *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                                <select name="tucachcutru" required>
                                    <option value="" disabled <?= empty($row_detail['tucachcutru']) ? 'selected' : '' ?> hidden>Chọn loại visa</option>
                                    <option value="Kỹ năng đặc định (Tokutei Ginou)" <?= $row_detail['tucachcutru'] == 'Kỹ năng đặc định (Tokutei Ginou)' ? 'selected' : '' ?>>Kỹ năng đặc định (Tokutei Ginou)</option>
                                    <option value="Kỹ sư / Tri thức nhân văn" <?= $row_detail['tucachcutru'] == 'Kỹ sư / Tri thức nhân văn' ? 'selected' : '' ?>>Kỹ sư / Tri thức nhân văn</option>
                                    <option value="Thực tập sinh kỹ năng" <?= $row_detail['tucachcutru'] == 'Thực tập sinh kỹ năng' ? 'selected' : '' ?>>Thực tập sinh kỹ năng</option>
                                    <option value="Vĩnh trú / Định trú" <?= $row_detail['tucachcutru'] == 'Vĩnh trú / Định trú' ? 'selected' : '' ?>>Vĩnh trú / Định trú</option>
                                    <option value="Vợ/chồng người Nhật" <?= $row_detail['tucachcutru'] == 'Vợ/chồng người Nhật' ? 'selected' : '' ?>>Vợ/chồng người Nhật</option>
                                    <option value="Du học" <?= $row_detail['tucachcutru'] == 'Du học' ? 'selected' : '' ?>>Du học</option>
                                    <option value="Khác" <?= $row_detail['tucachcutru'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>
                            <div class="hint">Chọn tư cách cư trú hiện tại<span class="sep"> | </span><span class="jp">ビザの種類を選択してください</span></div>
                        </div>

                        <!-- 3. Thời hạn visa -->
                        <div class="intent-field">
                            <label><span class="num">3</span><span class="label-text">Thời hạn của visa<span class="required">*</span><span class="jp">ビザの有効期限 *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <select name="thoihanvisa" required>
                                    <option value="" disabled <?= empty($row_detail['thoihanvisa']) ? 'selected' : '' ?> hidden>Chọn thời hạn</option>
                                    <option value="Dưới 3 tháng" <?= $row_detail['thoihanvisa'] == 'Dưới 3 tháng' ? 'selected' : '' ?>>Dưới 3 tháng</option>
                                    <option value="3 - 6 tháng" <?= $row_detail['thoihanvisa'] == '3 - 6 tháng' ? 'selected' : '' ?>>3 - 6 tháng</option>
                                    <option value="6 - 12 tháng" <?= $row_detail['thoihanvisa'] == '6 - 12 tháng' ? 'selected' : '' ?>>6 - 12 tháng</option>
                                    <option value="Trên 1 năm" <?= $row_detail['thoihanvisa'] == 'Trên 1 năm' ? 'selected' : '' ?>>Trên 1 năm</option>
                                    <option value="Không thời hạn" <?= $row_detail['thoihanvisa'] == 'Không thời hạn' ? 'selected' : '' ?>>Không thời hạn</option>
                                </select>
                            </div>
                            <div class="hint">Thời gian hiệu lực còn lại<span class="sep"> | </span><span class="jp">残りの有効期限を選択してください</span></div>
                        </div>

                        <!-- 4. Trình độ tiếng Nhật -->
                        <div class="intent-field">
                            <label><span class="num">4</span><span class="label-text">Trình độ tiếng Nhật<span class="required">*</span><span class="jp">日本語レベル *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h9M9 3v2c0 4-2.5 8-6 10M6 9c1 3 3 5 6 6M14 21l4-9 4 9M15.5 18h5"/></svg>
                                <select name="trinhdotiengnhat" required>
                                    <option value="" disabled <?= empty($row_detail['trinhdotiengnhat']) ? 'selected' : '' ?> hidden>Chọn trình độ</option>
                                    <option value="N1" <?= $row_detail['trinhdotiengnhat'] == 'N1' ? 'selected' : '' ?>>N1</option>
                                    <option value="N2" <?= $row_detail['trinhdotiengnhat'] == 'N2' ? 'selected' : '' ?>>N2</option>
                                    <option value="N3" <?= $row_detail['trinhdotiengnhat'] == 'N3' ? 'selected' : '' ?>>N3</option>
                                    <option value="N4" <?= $row_detail['trinhdotiengnhat'] == 'N4' ? 'selected' : '' ?>>N4</option>
                                    <option value="N5" <?= $row_detail['trinhdotiengnhat'] == 'N5' ? 'selected' : '' ?>>N5</option>
                                    <option value="Giao tiếp cơ bản" <?= $row_detail['trinhdotiengnhat'] == 'Giao tiếp cơ bản' ? 'selected' : '' ?>>Giao tiếp cơ bản</option>
                                </select>
                            </div>
                            <div class="hint">Năng lực tiếng Nhật hiện tại<span class="sep"> | </span><span class="jp">日本語レベルを選択してください</span></div>
                        </div>

                        <!-- 5. Tỉnh/thành phố mong muốn -->
                        <div class="intent-field">
                            <label><span class="num">5</span><span class="label-text">Tỉnh/thành phố mong muốn làm việc<span class="required">*</span><span class="jp">希望勤務地（都道府県） *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <input type="text" name="tinhmongmuon" placeholder="Nhập tỉnh/thành phố mong muốn" required value="<?= $row_detail['tinhmongmuon'] ?>">
                            </div>
                            <div class="hint">Nơi làm việc mong muốn<span class="sep"> | </span><span class="jp">希望する都道府県を入力してください</span></div>
                        </div>

                        <!-- 6. Ngành nghề mong muốn -->
                        <div class="intent-field">
                            <label><span class="num">6</span><span class="label-text">Ngành nghề mong muốn<span class="required">*</span><span class="jp">希望する業種 *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <select name="nganhnghemongmuon" required>
                                    <option value="" disabled <?= empty($row_detail['nganhnghemongmuon']) ? 'selected' : '' ?> hidden>Chọn ngành nghề</option>
                                    <option value="Cơ khí / Chế tạo" <?= $row_detail['nganhnghemongmuon'] == 'Cơ khí / Chế tạo' ? 'selected' : '' ?>>Cơ khí / Chế tạo</option>
                                    <option value="Xây dựng" <?= $row_detail['nganhnghemongmuon'] == 'Xây dựng' ? 'selected' : '' ?>>Xây dựng</option>
                                    <option value="Thực phẩm" <?= $row_detail['nganhnghemongmuon'] == 'Thực phẩm' ? 'selected' : '' ?>>Thực phẩm</option>
                                    <option value="Điều dưỡng / Chăm sóc" <?= $row_detail['nganhnghemongmuon'] == 'Điều dưỡng / Chăm sóc' ? 'selected' : '' ?>>Điều dưỡng / Chăm sóc</option>
                                    <option value="Nông nghiệp" <?= $row_detail['nganhnghemongmuon'] == 'Nông nghiệp' ? 'selected' : '' ?>>Nông nghiệp</option>
                                    <option value="IT / Kỹ sư" <?= $row_detail['nganhnghemongmuon'] == 'IT / Kỹ sư' ? 'selected' : '' ?>>IT / Kỹ sư</option>
                                    <option value="Khác" <?= $row_detail['nganhnghemongmuon'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>
                            <div class="hint">Ngành nghề bạn mong muốn làm việc<span class="sep"> | </span><span class="jp">希望する業種を選択してください</span></div>
                        </div>

                        <!-- 7. Nội dung công việc mong muốn -->
                        <div class="intent-field span-2">
                            <label><span class="num">7</span><span class="label-text">Nội dung công việc mong muốn<span class="required">*</span><span class="jp">希望する仕事内容 *</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                <textarea name="noidungmongmuon" placeholder="Ví dụ: Chế biến thực phẩm trong nhà máy, Lắp đặt giàn giáo..." required><?= htmlspecialchars_decode($row_detail['noidungmongmuon']) ?></textarea>
                            </div>
                            <div class="hint">Mô tả công việc mong muốn<span class="sep"> | </span><span class="jp">希望する仕事内容を入力してください</span></div>
                        </div>

                        <!-- 8. Mức lương mong muốn -->
                        <div class="intent-field">
                            <label><span class="num">8</span><span class="label-text">Mức lương mong muốn<span class="required">*</span><span class="jp">希望する給y *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v10M15 9.5c0-1.4-1.3-2.5-3-2.5s-3 1-3 2.3c0 3 6 1.4 6 4.4 0 1.4-1.3 2.3-3 2.3s-3-1.1-3-2.5"/></svg>
                                <select name="mucluongmongmuon" required>
                                    <option value="" disabled <?= empty($row_detail['mucluongmongmuon']) ? 'selected' : '' ?> hidden>Chọn mức lương</option>
                                    <option value="Dưới 150,000 yên" <?= $row_detail['mucluongmongmuon'] == 'Dưới 150,000 yên' ? 'selected' : '' ?>>Dưới 150,000 yên</option>
                                    <option value="150,000 - 200,000 yên" <?= $row_detail['mucluongmongmuon'] == '150,000 - 200,000 yên' ? 'selected' : '' ?>>150,000 - 200,000 yên</option>
                                    <option value="200,000 - 250,000 yên" <?= $row_detail['mucluongmongmuon'] == '200,000 - 250,000 yên' ? 'selected' : '' ?>>200,000 - 250,000 yên</option>
                                    <option value="250,000 - 300,000 yên" <?= $row_detail['mucluongmongmuon'] == '250,000 - 300,000 yên' ? 'selected' : '' ?>>250,000 - 300,000 yên</option>
                                    <option value="Trên 300,000 yên" <?= $row_detail['mucluongmongmuon'] == 'Trên 300,000 yên' ? 'selected' : '' ?>>Trên 300,000 yên</option>
                                </select>
                            </div>
                            <div class="hint">Mức lương mong muốn nhận được (Yên)<span class="sep"> | </span><span class="jp">希望する給与を選択してください</span></div>
                        </div>

                        <!-- 9. Thời gian có thể chuyển việc -->
                        <div class="intent-field">
                            <label><span class="num">9</span><span class="label-text">Thời gian có thể chuyển việc<span class="required">*</span><span class="jp">転職可能時期 *</span></span></label>
                            <div class="input-wrap select-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l4 2"/></svg>
                                <select name="thoigianchuyenviec" required>
                                    <option value="" disabled <?= empty($row_detail['thoigianchuyenviec']) ? 'selected' : '' ?> hidden>Chọn thời gian</option>
                                    <option value="Có thể bắt đầu ngay" <?= $row_detail['thoigianchuyenviec'] == 'Có thể bắt đầu ngay' ? 'selected' : '' ?>>Có thể bắt đầu ngay</option>
                                    <option value="Trong vòng 1 tháng" <?= $row_detail['thoigianchuyenviec'] == 'Trong vòng 1 tháng' ? 'selected' : '' ?>>Trong vòng 1 tháng</option>
                                    <option value="Trong vòng 3 tháng" <?= $row_detail['thoigianchuyenviec'] == 'Trong vòng 3 tháng' ? 'selected' : '' ?>>Trong vòng 3 tháng</option>
                                    <option value="Sau 3 tháng" <?= $row_detail['thoigianchuyenviec'] == 'Sau 3 tháng' ? 'selected' : '' ?>>Sau 3 tháng</option>
                                    <option value="Chưa xác định" <?= $row_detail['thoigianchuyenviec'] == 'Chưa xác định' ? 'selected' : '' ?>>Chưa xác định</option>
                                </select>
                            </div>
                            <div class="hint">Dự kiến thời điểm bắt đầu đi làm<span class="sep"> | </span><span class="jp">転職可能時期を選択してください</span></div>
                        </div>

                        <!-- 10. Thông tin liên hệ -->
                        <div class="intent-field span-2">
                            <label><span class="num">10</span><span class="label-text">Thông tin liên hệ (Zalo / Facebook / LINE)<span class="required">*</span><span class="jp">連絡先情報（Zalo／Facebook／LINE） *</span></span></label>
                            <div class="contact-row">
                                <div class="input-wrap select-wrap">
                                    <select name="zalo_fb_line" required>
                                        <option value="" disabled <?= empty($row_detail['zalo_fb_line']) ? 'selected' : '' ?> hidden>Nền tảng</option>
                                        <option value="Zalo" <?= $row_detail['zalo_fb_line'] == 'Zalo' ? 'selected' : '' ?>>Zalo</option>
                                        <option value="Facebook" <?= $row_detail['zalo_fb_line'] == 'Facebook' ? 'selected' : '' ?>>Facebook</option>
                                        <option value="LINE" <?= $row_detail['zalo_fb_line'] == 'LINE' ? 'selected' : '' ?>>LINE</option>
                                    </select>
                                </div>
                                <div class="input-wrap">
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>
                                    <input type="text" name="zalo_fb_line_id" placeholder="Nhập số điện thoại hoặc ID tài khoản" required value="<?= $row_detail['zalo_fb_line_id'] ?>">
                                </div>
                            </div>
                            <div class="hint">SĐT hoặc ID liên hệ<span class="sep"> | </span><span class="jp">電話番号またはIDを入力してください</span></div>
                        </div>

                        <!-- 11. Mong muốn khác -->
                        <div class="intent-field span-2">
                            <label><span class="num">11</span><span class="label-text">Mong muốn khác<span class="jp">その他の希望</span></span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 1 1-4-7.5"/></svg>
                                <input type="text" name="mongmuonkhac" placeholder="Nhập mong muốn khác (nếu có)" value="<?= $row_detail['mongmuonkhac'] ?>">
                            </div>
                            <div class="hint">Các yêu cầu hoặc ghi chú thêm<span class="sep"> | </span><span class="jp">その他の希望があれば入力してください</span></div>
                        </div>

                        <!-- notice -->
                        <div class="notice">
                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v5h1"/></svg>
                            <div>
                                <p>Thông tin của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích hỗ trợ ứng tuyển.</p>
                                <p class="jp">ご入力いただいた情報は、応募サポートの目的のみに使用し、厳重に管理いたします。</p>
                            </div>
                        </div>

                        <!-- submit -->
                        <div class="submit-wrap">
                            <button type="submit" class="submit" name="submit-nguyenvong">
                                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4Z"/></svg>
                                <span class="submit-text">
                                    <span class="vi">ĐĂNG KÝ NGUYỆN VỌNG</span>
                                    <span class="jp">応募希望を登録する</span>
                                </span>
                            </button>
                        </div>

                        <p class="consent">
                            Bằng việc nhấn nút, bạn đồng ý với việc xử lý thông tin cá nhân của chúng tôi.
                            <span class="jp">ボタンをクリックすることで、個人情報の取り扱いに同意したものとみなします。</span>
                        </p>

                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
