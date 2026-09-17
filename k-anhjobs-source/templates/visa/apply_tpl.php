<?php $visa_value=function($key)use($visa_old){return visa_escape(isset($visa_old[$key])?$visa_old[$key]:'');}; ?>
<link rel="stylesheet" href="<?=$config_base?>assets/css/visa.css?v=20260916">
<main class="visa-page"><div class="visa-shell">
    <header class="visa-hero">
        <div class="visa-brand">K-ANH INC.</div>
        <div class="visa-eyebrow">特定技能1号 / KỸ NĂNG ĐẶC ĐỊNH SỐ 1</div>
        <h1>ビザ申請書類の提出</h1><p class="visa-subtitle">Nộp hồ sơ xin visa</p>
        <p>情報を入力し、必要書類を選択してください。<br><span>Nhập thông tin và chọn các giấy tờ cần thiết.</span></p>
    </header>

    <?php if($visa_success_code){ ?>
    <section class="visa-result" role="status"><div class="visa-result-icon">✓</div><h2>提出が完了しました<br><span>Đã gửi hồ sơ thành công</span></h2><p>提出番号 / Mã hồ sơ</p><strong><?=visa_escape($visa_success_code)?></strong></section>
    <?php }else{ ?>
    <?php if($visa_errors){ ?><div class="visa-alert" role="alert"><strong>入力内容を確認してください / Vui lòng kiểm tra lại</strong><ul><?php foreach($visa_errors as $error){ ?><li><?=visa_escape($error)?></li><?php } ?></ul></div><?php } ?>
    <form id="visa-application-form" class="visa-form" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?=visa_escape($visa_csrf)?>">
        <div class="visa-honeypot" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <section class="visa-panel"><div class="visa-section-heading"><span>01</span><div><h2>応募者情報</h2><p>Thông tin ứng viên</p></div></div>
            <div class="visa-fields">
                <label><b>お名前 <em>必須</em></b><span>Họ và tên</span><input name="full_name" value="<?=$visa_value('full_name')?>" maxlength="191" required></label>
                <label><b>応募先企業 <em>必須</em></b><span>Công ty ứng tuyển</span><input name="company_name" value="<?=$visa_value('company_name')?>" maxlength="191" required></label>
            </div>
        </section>
        <section class="visa-panel"><div class="visa-section-heading"><span>02</span><div><h2>必要書類</h2><p>Giấy tờ cần upload</p></div></div>
            <div class="visa-document-grid"><?php foreach($visa_documents as $type=>$document){ ?>
                <article class="visa-document-card"><div class="visa-document-title"><div><h3><?=visa_escape($document['ja'])?></h3><p><?=visa_escape($document['vi'])?></p></div></div>
                    <div class="visa-required"><?=!empty($document['required'])?'必須 / Bắt buộc':'該当者のみ / Nếu có'?></div>
                    <label class="visa-file-picker"><input type="file" name="documents[<?=$type?>]" accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" <?=!empty($document['required'])?'required':''?>><b>ファイルを選ぶ</b><span>Chọn ảnh hoặc PDF</span><small>JPG, PNG, WebP, PDF · 最大 10 MB</small></label>
                    <div class="visa-file-name">未選択 / Chưa chọn</div>
                </article>
            <?php } ?></div>
        </section>
        <section class="visa-submit-panel"><label class="visa-consent"><input type="checkbox" name="consent" value="1" required><span>個人情報の提供に同意します。<small>Tôi đồng ý cung cấp thông tin để xử lý hồ sơ visa.</small></span></label><button type="submit" name="visa_submit" value="1" class="visa-button">書類を送信する <span>Gửi hồ sơ</span></button></section>
    </form>
    <?php } ?>
</div></main>
<script src="<?=$config_base?>assets/js/visa.js?v=20260916-2" defer></script>
