
<?php if (count($candidate_intents) > 0) { ?>
<style>
  .ctv-candidates-section {
    padding: 50px 0;
    background: #f4f7f6;
    border-bottom: 1px solid #e2e6ee;
  }
  .ctv-title-box {
    margin-bottom: 30px;
    border-left: 5px solid #0d2340;
    padding-left: 15px;
  }
  .ctv-title-box h3 {
    font-size: 24px;
    font-weight: 800;
    color: #0d2340;
    margin: 0 0 5px;
    text-transform: uppercase;
  }
  .ctv-title-box p {
    font-size: 14px;
    color: #7c8699;
    margin: 0;
  }
  .candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 25px;
    margin-top: 20px;
  }
  .candidate-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e6ee;
    box-shadow: 0 10px 30px rgba(13,35,64,0.05);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .candidate-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(13,35,64,0.1);
  }
  .candidate-card__header {
    padding: 22px 20px;
    background: linear-gradient(135deg, #0d2340, #1c4074);
    color: #ffffff;
  }
  .candidate-card__header h4 {
    margin: 0 0 10px;
    font-size: 18px;
    font-weight: 700;
  }
  .candidate-card__badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .candidate-card__badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 6px;
    background: rgba(255,255,255,0.15);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.2);
    font-weight: 500;
  }
  .candidate-card__body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .candidate-info-item {
    font-size: 13.5px;
    line-height: 1.4;
    color: #1a2233;
  }
  .candidate-info-item strong {
    color: #1c4074;
    font-weight: 600;
  }
  .candidate-work-desc {
    background: #f8fafc;
    border: 1px dashed #cfe3fa;
    border-radius: 8px;
    padding: 10px;
    font-size: 13px;
    color: #4c5567;
    margin-top: 5px;
    line-height: 1.5;
  }
  .candidate-card__footer {
    padding: 15px 20px;
    background: #fbfcfe;
    border-top: 1px solid #edf0f5;
    display: flex;
    justify-content: flex-end;
  }
  .btn-chat-ctv {
    display: inline-flex;
    align-items: center;
    background: #2f6fed;
    color: #ffffff !important;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.15s ease;
    box-shadow: 0 4px 10px rgba(47,111,237,0.25);
  }
  .btn-chat-ctv:hover {
    background: #1c52c9;
  }
  
  /* Concise style for candidate cards on homepage */
  .candidate-card-concise {
    box-shadow: 0 4px 15px rgba(13,35,64,0.03) !important;
    border-radius: 12px !important;
    border: 1px solid #e2e6ee !important;
  }
  .candidate-card-concise .candidate-card__header {
    padding: 12px 15px !important;
    background: linear-gradient(135deg, #0d2340, #1c4074) !important;
  }
  .candidate-card-concise .candidate-card__header h4 {
    font-size: 15px !important;
    margin-bottom: 5px !important;
    color: #ffffff !important;
    font-weight: 700 !important;
  }
  .candidate-card-concise .candidate-card__badges {
    gap: 4px !important;
  }
  .candidate-card-concise .candidate-card__badge {
    font-size: 9.5px !important;
    padding: 1px 6px !important;
    border-radius: 4px !important;
    background: rgba(255,255,255,0.15) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255,255,255,0.2) !important;
  }
  .candidate-card-concise .candidate-card__body {
    padding: 12px 15px !important;
    gap: 6px !important;
    background: #ffffff !important;
  }
  .candidate-card-concise .candidate-info-item {
    font-size: 12px !important;
    color: #1a2233 !important;
  }
  .candidate-card-concise .candidate-info-item strong {
    font-weight: 600 !important;
    color: #1c4074 !important;
  }

  /* Desktop right-side widget style */
  .nguyenvong-widget-desktop {
    position: absolute;
    top: 0;
    left: calc(1230px + ( (100% - 1230px) / 2 ));
    width: calc(100% - 1245px - ((100% - 1230px) / 2 ));
    height: 100%;
    max-height: 480px;
    max-width: 350px;
    z-index: 10;
    overflow: hidden;
  }
  .box-tems-nguyenvong {
    position: relative;
    top: 0;
    right: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 0 10px 10px 10px;
    max-height: 470px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.3) transparent;
  }
  .box-tems-nguyenvong::-webkit-scrollbar {
    width: 4px;
  }
  .box-tems-nguyenvong::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 4px;
  }
  .nguyenvong-widget-title {
    font-size: 14px;
    font-weight: 800;
    color: #ffffff;
    margin: 10px 0 5px 0;
    text-transform: uppercase;
    border-left: 4px solid #ffffff;
    padding-left: 10px;
    letter-spacing: 0.5px;
  }
  .btn-xem-tat-ca-nguyenvong {
    display: inline-block;
    width: 100%;
    padding: 10px;
    background: #0d2340;
    color: #fff !important;
    text-align: center;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    transition: background 0.2s, transform 0.1s;
    box-shadow: 0 4px 10px rgba(13,35,64,0.15);
  }
  .btn-xem-tat-ca-nguyenvong:hover {
    background: #1c4074;
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(13,35,64,0.2);
  }

  /* Responsive layout adjustments */
  @media (min-width: 1200px) {
    .ctv-candidates-section {
      display: none !important;
    }
  }
  @media (max-width: 1199px) {
    .nguyenvong-widget-desktop {
      display: none !important;
    }
    .ctv-candidates-section {
      display: block !important;
    }
  }
</style>
<div class="ctv-candidates-section">
    <div class="center">
        <div class="ctv-title-box">
            <h3>Danh sách ứng viên đăng ký nguyện vọng</h3>
            <p>Tìm ứng viên phù hợp với các tin tuyển dụng hiện có của bạn và liên hệ tư vấn ngay</p>
        </div>
        <div class="candidates-grid">
            <?php 
            $mobile_candidates = array_slice($candidate_intents, 0, 3);
            foreach ($mobile_candidates as $candidate) { 
            ?>
                <a href="account/chat?id=<?= $candidate['id'] ?>" class="candidate-card candidate-card-concise text-decoration-none">
                    <div class="candidate-card__header">
                        <h4><?= htmlspecialchars($candidate['ten']) ?></h4>
                    </div>
                    <div class="candidate-card__body">
                        <div class="candidate-info-item">
                            <strong>Visa:</strong> <?= htmlspecialchars($candidate['tucachcutru']) ?><?= !empty($candidate['thoihanvisa']) ? ' (còn ' . htmlspecialchars($candidate['thoihanvisa']) . ')' : '' ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Tiếng Nhật:</strong> <?= htmlspecialchars($candidate['trinhdotiengnhat']) ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Mong muốn:</strong> <?= htmlspecialchars($candidate['nganhnghemongmuon']) ?><?= !empty($candidate['mucluongmongmuon']) ? ', lương từ ' . htmlspecialchars($candidate['mucluongmongmuon']) : '' ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Khu vực:</strong> <?= htmlspecialchars($candidate['tinhmongmuon']) ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Ngày đăng ký:</strong> <?= !empty($candidate['ngaydangkynguyenvong']) ? date("d/m/Y", $candidate['ngaydangkynguyenvong']) : 'N/A' ?>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
        <div class="text-center mt-4">
            <a href="account/danh-sach-nguyen-vong" class="btn-xem-tat-ca-nguyenvong" style="max-width: 220px; margin: 0 auto;">Xem tất cả</a>
        </div>
    </div>
</div>
<?php } ?>

<div class="gim-index">
    <div class="box-tems-gim">
    <?php foreach($dm1newsgim as $dgg) { ?> 
        <div class="items-ghim">
            <a href="<?= $dgg[$sluglang] ?>" title="<?= $dgg['ten'] ?>">
                <img onerror="this.src='<?= THUMBS ?>/170x170x1/assets/images/noimage.png';"
                    src="<?= THUMBS ?>/170x170x1/<?= UPLOAD_NEWS_L . $dgg['photo'] ?>" alt="<?= $dm1['ten'] ?>">
            </a>
            <h2>
                <a href="<?= $dgg[$sluglang] ?>"><?= $dgg['ten'] ?></a>
                <a href="<?= $dgg[$sluglang] ?>">Xem chi tiết</a>
            </h2>
        </div>
    <?php } ?>
    </div>
   
</div>

<?php if (count($candidate_intents) > 0) { ?>
    <div class="nguyenvong-widget-desktop">
        <div class="box-tems-nguyenvong">
            <h3 class="nguyenvong-widget-title">Nguyện vọng mới</h3>
            <?php foreach ($candidate_intents as $candidate) { ?>
                <a href="account/chat?id=<?= $candidate['id'] ?>" class="candidate-card candidate-card-concise text-decoration-none">
                    <div class="candidate-card__header">
                        <h4><?= htmlspecialchars($candidate['ten']) ?></h4>
                    </div>
                    <div class="candidate-card__body">
                        <div class="candidate-info-item">
                            <strong>Visa:</strong> <?= htmlspecialchars($candidate['tucachcutru']) ?><?= !empty($candidate['thoihanvisa']) ? ' (còn ' . htmlspecialchars($candidate['thoihanvisa']) . ')' : '' ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Tiếng Nhật:</strong> <?= htmlspecialchars($candidate['trinhdotiengnhat']) ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Mong muốn:</strong> <?= htmlspecialchars($candidate['nganhnghemongmuon']) ?><?= !empty($candidate['mucluongmongmuon']) ? ', lương từ ' . htmlspecialchars($candidate['mucluongmongmuon']) : '' ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Khu vực:</strong> <?= htmlspecialchars($candidate['tinhmongmuon']) ?>
                        </div>
                        <div class="candidate-info-item">
                            <strong>Ngày đăng ký:</strong> <?= !empty($candidate['ngaydangkynguyenvong']) ? date("d/m/Y", $candidate['ngaydangkynguyenvong']) : 'N/A' ?>
                        </div>
                    </div>
                </a>
            <?php } ?>
            <a href="account/danh-sach-nguyen-vong" class="btn-xem-tat-ca-nguyenvong">Xem tất cả</a>
        </div>
    </div>
<?php } ?>
<div class="index-container">
    <div class="main-danhmuc">
        <div class="center">
            <div class="box-title">
                <h3>Top <?= count($dm1news) ?> <?= topnganhnghenoibat ?></h3>
                <p><?= banmuontimviec ?> <a href="tin-tuyen-dung"><?= taiday ?></a></p>
            </div>
            <div class="container-danhmuc">
                <?php foreach($dm1news as $dm1) { ?>
                <?php $sobaiviet = $d->rawQueryOne("select count(id) as count from #_news where id_list = ? and hienthi > 0 and trangthai = 1", array($dm1['id'])); ?>
                <div class="items-dm1tin">
                    <a href="<?= $dm1[$sluglang] ?>" title="<?= $dm1['ten'] ?>">
                        <img onerror="this.src='<?= THUMBS ?>/170x170x1/assets/images/noimage.png';"
                            src="<?= THUMBS ?>/170x170x1/<?= UPLOAD_NEWS_L . $dm1['photo'] ?>" alt="<?= $dm1['ten'] ?>">
                    </a>
                    <h2>
                        <a href="<?= $dm1[$sluglang] ?>" title="<?= $dm1['ten'] ?>">
                            <?= $dm1['ten'] ?>
                        </a>
                    </h2>
                    <a class="a-dm1tin" href="<?= $dm1[$sluglang] ?>"
                        title="<?= $sobaiviet['count'] . ' công việc '. $dm1['ten']  ?>"><?= number_format($sobaiviet['count']) ?>
                        <?= vieclam ?></a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="main-tinnoibat">
        <div class="center">
            <div class="box-title">
                <h3><?= topcongviecnoibat ?></h3>
                <p><?= banmuontimviec ?> <a href="tin-tuyen-dung"><?= taiday ?></a></p>
            </div>
            <div class="owl-carousel owl-theme container-tinnoibat">
                <?php foreach($tinnoibat as $key => $tin) { ?>
                <?php if($key %2 == 0) { ?>
                <div class="owl-column">
                    <?php } ?>
                    <div class="items-tinnoibat">
                        <a href="<?= $tin[$sluglang] ?>" title="<?= $tin['ten'] ?>" class="scale-img">
                            <img onerror="this.src='<?= THUMBS ?>/280x180x2/assets/images/noimage.png';"
                                src="<?= THUMBS ?>/280x180x1/<?= UPLOAD_NEWS_L . $tin['photo'] ?>" alt="<?= $tin['ten'] ?>">
                        </a>
                        <div class="text-tin">
                            <div class="left-text-tin">
                                <h2><a class="text-split text-split-1" href="<?= $tin[$sluglang] ?>"><?= $tin['ten'] ?></a>
                                </h2>
                                <p class="text-split text-split-1"><?= $tin['diadiem'] ? $tin['diadiem'] : 'Nan' ?></p>
                            </div>
                            <a href="<?= $tin[$sluglang] ?>"> <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                    <?php if($key %2 == 1 || $key + 1 == count($tinnoibat)) { ?>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="main-tintuc">
        <div class="center">
            <div class="box-title">
                <h3><?= tintucvasukien ?></h3>
            </div>
            <div class="container-tintuc owl-carousel owl-theme">
                <?php foreach($tintuc as $n) { ?> 
                    <?php $cat_lang = in_array($lang, ['vi', 'en']) ? $lang : 'vi'; ?>
                    <?php $category = $d->rawQueryOne("select IF(ten$cat_lang != '' AND ten$cat_lang IS NOT NULL, ten$cat_lang, tenvi) as ten, tenkhongdauvi, IF(tenkhongdau$cat_lang != '' AND tenkhongdau$cat_lang IS NOT NULL, tenkhongdau$cat_lang, tenkhongdauvi) as slug, id from #_news_cat where id_list = ? and hienthi > 0 order by stt,id desc limit 0,1",array($n['id'])); ?>
                    <div class="item-news">
                        <div class="pics-news">
                            <a class="text-decoration-none scale-img" href="<?= !empty($n[$sluglang]) ? $n[$sluglang] : $n['tenkhongdauvi'] ?>" title="<?=$n['ten']?>">
                                <img onerror="this.src='<?=THUMBS?>/599x360x2/assets/images/noimage.png';" src="<?=THUMBS?>/599x360x1/<?=UPLOAD_NEWS_L.$n['photo']?>" alt="<?=$n['ten']?>">
                            </a>
                        </div>
                        <div class="content-news">
                            <div class="category-news">
                                <a href="<?= !empty($category['slug']) ? $category['slug'] : (!empty($category['tenkhongdauvi']) ? $category['tenkhongdauvi'] : 'javascript:void(0);') ?>" title="<?= !empty($category['ten']) ? $category['ten'] : tintucvasukien ?>"><?= !empty($category['ten']) ? $category['ten'] : tintucvasukien ?></a>
                                <span> &nbsp; &#8226; &nbsp; <?=date("d/m/Y h:i A",$n['ngaytao'])?></span>
                            </div>
                            <div class="name-news">
                                <a class="text-decoration-none scale-img" href="<?= !empty($n[$sluglang]) ? $n[$sluglang] : $n['tenkhongdauvi'] ?>" title="<?=$n['ten']?>">
                                    <?=$n['ten']?>
                                </a>
                            </div>
                            
                            <div class="description-news">
                                <p><?=$n['mota']?></p>
                            </div>
                            <div class="link-news">
                                <a href="<?= !empty($n[$sluglang]) ? $n[$sluglang] : $n['tenkhongdauvi'] ?>" title="<?=$n['ten']?>"><?= xemthem ?></a>
                            </div>
                        </div>
                    
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>

<?php if (isset($show_intent_popup) && $show_intent_popup === true) { ?>
<!-- Popup Đăng ký nguyện vọng -->
<div id="intent-modal" class="intent-modal-overlay">
    <div class="intent-modal-card">
        <button class="intent-modal-close" id="intent-modal-close-btn">&times;</button>
        <div class="intent-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h3 class="intent-modal-title">ĐĂNG KÝ NGUYỆN VỌNG TÌM VIỆC</h3>
        <span class="intent-modal-subtitle">希望条件の登録</span>
        <p class="intent-modal-desc">
            Chào bạn! Hãy cập nhật ngay nguyện vọng tìm việc (loại visa, mức lương, khu vực mong muốn,...) để hệ thống tự động tìm và kết nối bạn với những công việc tốt nhất tại Nhật Bản.
        </p>
        <p class="intent-modal-desc jp">
            より適した求人情報を提供するため, 希望条件（ビザの種類, 希望給与, 勤務地など）を登録してください。
        </p>
        <div class="intent-modal-actions">
            <a href="account/dang-ky-nguyen-vong" class="intent-btn-primary">Đăng ký ngay / 登録する</a>
            <button id="intent-modal-snooze-btn" class="intent-btn-secondary">Để sau / 後で</button>
        </div>
    </div>
</div>

<style>
    .intent-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(13, 35, 64, 0.6);
        backdrop-filter: blur(8px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .intent-modal-overlay.show {
        display: flex;
        opacity: 1;
    }
    .intent-modal-card {
        background: #ffffff;
        border-radius: 24px;
        width: 90%;
        max-width: 500px;
        padding: 35px 30px;
        box-shadow: 0 20px 50px rgba(13, 35, 64, 0.3);
        position: relative;
        text-align: center;
        transform: scale(0.85);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .intent-modal-overlay.show .intent-modal-card {
        transform: scale(1);
    }
    .intent-modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: none;
        border: none;
        font-size: 28px;
        color: #7c8699;
        cursor: pointer;
        transition: color 0.15s ease;
        line-height: 1;
    }
    .intent-modal-close:hover {
        color: #0d2340;
    }
    .intent-modal-icon {
        width: 70px;
        height: 70px;
        background: #eaf3fc;
        color: #2f6fed;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 20px rgba(47, 111, 237, 0.15);
    }
    .intent-modal-icon svg {
        width: 36px;
        height: 36px;
    }
    .intent-modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #0d2340;
        margin: 0 0 2px;
        letter-spacing: 0.5px;
    }
    .intent-modal-subtitle {
        display: block;
        font-size: 13px;
        color: #7c8699;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .intent-modal-desc {
        font-size: 14px;
        line-height: 1.6;
        color: #4c5567;
        margin: 0 0 12px;
    }
    .intent-modal-desc.jp {
        font-size: 12.5px;
        color: #7c8699;
        margin-bottom: 25px;
    }
    .intent-modal-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .intent-btn-primary {
        background: linear-gradient(135deg, #153259, #0d2340);
        color: #ffffff !important;
        padding: 13px 25px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 8px 20px rgba(13, 35, 64, 0.25);
    }
    .intent-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(13, 35, 64, 0.35);
    }
    .intent-btn-secondary {
        background: #f1f3f7;
        color: #4c5567;
        border: none;
        padding: 13px 25px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .intent-btn-secondary:hover {
        background: #e4e7ed;
    }
    @media (max-width: 576px) {
        .intent-modal-card {
            padding: 30px 20px;
        }
        .intent-modal-actions {
            flex-direction: column;
        }
        .intent-btn-primary, .intent-btn-secondary {
            width: 100%;
        }
    }
</style>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var snoozeTime = localStorage.getItem("intent_modal_snooze");
        var now = Date.now();
        
        if (snoozeTime && (now - parseInt(snoozeTime)) < 604800000) {
            return;
        }
        
        setTimeout(function() {
            var modal = document.getElementById("intent-modal");
            if (modal) {
                modal.style.display = "flex";
                setTimeout(function() {
                    modal.classList.add("show");
                }, 50);
            }
        }, 5000);
        
        function hideModal() {
            var modal = document.getElementById("intent-modal");
            if (modal) {
                modal.classList.remove("show");
                setTimeout(function() {
                    modal.style.display = "none";
                }, 400);
            }
        }
        
        var closeBtn = document.getElementById("intent-modal-close-btn");
        var snoozeBtn = document.getElementById("intent-modal-snooze-btn");
        
        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                localStorage.setItem("intent_modal_snooze", Date.now());
                hideModal();
            });
        }
        
        if (snoozeBtn) {
            snoozeBtn.addEventListener("click", function() {
                localStorage.setItem("intent_modal_snooze", Date.now());
                hideModal();
            });
        }
    });
</script>
<?php } ?>