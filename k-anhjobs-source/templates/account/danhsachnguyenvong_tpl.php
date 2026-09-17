<style>
  .candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
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
  .search-expectations {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    max-width: 500px;
  }
  .search-expectations input {
    flex: 1;
    height: 42px;
    border-radius: 8px;
    border: 1px solid #e2e6ee;
    padding: 0 15px;
    font-size: 14px;
  }
  .search-expectations button {
    background: #0d2340;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 0 20px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  .search-expectations button:hover {
    background: #1c4074;
  }
</style>

<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            <div class="content-primary">
                <div class="job-box">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-12 mt-3 mt-lg-0">
                            <h3>Danh sách nguyện vọng ứng viên ( <?= $total ?> kết quả )</h3>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <form action="" method="GET">
                        <div class="search-expectations">
                            <input type="text" name="keyword" placeholder="Tìm tên, tiếng Nhật, tỉnh, ngành nghề..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                            <button type="submit">Tìm kiếm</button>
                            <?php if (isset($_GET['keyword'])) { ?>
                                <a href="account/danh-sach-nguyen-vong" class="btn btn-secondary d-flex align-items-center justify-content-center" style="border-radius: 8px; padding: 0 15px; height: 42px;">Hủy lọc</a>
                            <?php } ?>
                        </div>
                    </form>

                    <?php if(count($items)) { ?>
                        <div class="candidates-grid">
                            <?php foreach ($items as $candidate) { ?>
                                <a href="account/chat?id=<?= $candidate['id'] ?>" class="candidate-card text-decoration-none">
                                    <div class="candidate-card__header">
                                        <h4><?= htmlspecialchars($candidate['ten']) ?></h4>
                                        <div class="candidate-card__badges">
                                            <span class="candidate-card__badge"><?= htmlspecialchars($candidate['tucachcutru']) ?></span>
                                            <span class="candidate-card__badge">Tiếng Nhật: <?= htmlspecialchars($candidate['trinhdotiengnhat']) ?></span>
                                        </div>
                                    </div>
                                    <div class="candidate-card__body">
                                        <div class="candidate-info-item">
                                            <strong>Khu vực mong muốn:</strong> <?= htmlspecialchars($candidate['tinhmongmuon']) ?>
                                        </div>
                                        <div class="candidate-info-item">
                                            <strong>Ngành nghề mong muốn:</strong> <?= htmlspecialchars($candidate['nganhnghemongmuon']) ?>
                                        </div>
                                        <div class="candidate-info-item">
                                            <strong>Mức lương mong muốn:</strong> <?= htmlspecialchars($candidate['mucluongmongmuon']) ?>
                                        </div>
                                        <div class="candidate-info-item">
                                            <strong>Thời gian chuyển việc:</strong> <?= htmlspecialchars($candidate['thoigianchuyenviec']) ?>
                                        </div>
                                        <div class="candidate-info-item">
                                            <strong>Hạn visa:</strong> <?= htmlspecialchars($candidate['thoihanvisa']) ?>
                                        </div>
                                        <?php if ($candidate['zalo_fb_line_id']) { ?>
                                            <div class="candidate-info-item">
                                                <strong>Liên hệ (<?= htmlspecialchars($candidate['zalo_fb_line']) ?>):</strong> <?= htmlspecialchars($candidate['zalo_fb_line_id']) ?>
                                            </div>
                                        <?php } ?>
                                        <div class="candidate-info-item">
                                            <strong>Nội dung công việc mong muốn:</strong>
                                            <div class="candidate-work-desc">
                                                <?= nl2br(htmlspecialchars($candidate['noidungmongmuon'])) ?>
                                            </div>
                                        </div>
                                        <?php if ($candidate['mongmuonkhac']) { ?>
                                            <div class="candidate-info-item">
                                                <strong>Mong muốn khác:</strong> <?= htmlspecialchars($candidate['mongmuonkhac']) ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="candidate-card__footer">
                                        <span class="btn-chat-ctv">
                                            <i class="fas fa-comments mr-2"></i>Chat ngay
                                        </span>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="pagination-home"><?= (isset($paging) && $paging != '') ? $paging : '' ?></div>
                    <?php } else { ?>
                        <p>Không có dữ liệu để hiển thị</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
