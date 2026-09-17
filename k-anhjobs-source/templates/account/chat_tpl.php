<?php
if (!function_exists('make_links_clickable')) {
    function make_links_clickable($text) {
        $escaped = htmlspecialchars($text);
        $pattern = '/(https?:\/\/[^\s\r\n]+)/i';
        $replacement = '<a href="$1" target="_blank" style="text-decoration: underline; font-weight: bold; color: inherit;">$1</a>';
        return nl2br(preg_replace($pattern, $replacement, $escaped));
    }
}
$is_ctv = ($_SESSION[$login_member]['role'] == 1);
$id_candidate = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$chat_partner_name = chatvoiadmin;
$chot_jobs = [];

if ($is_ctv && $id_candidate) {
    $candidate_info = $d->rawQueryOne("select * from #_member where id = ? limit 0,1", array($id_candidate));
    if ($candidate_info) {
        $chat_partner_name = "Trò chuyện với: " . ($candidate_info['ten'] ? $candidate_info['ten'] : $candidate_info['username']);
        $chot_jobs = $d->rawQuery("select id_news, ten_news from #_ungtuyen where id_member = ? order by id desc", array($candidate_info['id']));
    }
} else if (!$is_ctv && isset($room['id'])) {
    if (isset($id_ctv) && $id_ctv) {
        $ctv_info = $d->rawQueryOne("select ten, avatar, username from #_member where id = ? limit 0,1", array($id_ctv));
    } else {
        $last_sender = $d->rawQueryOne("select id_send from #_message where id_room = ? and type = 2 order by id desc limit 0,1", array($room['id']));
        if ($last_sender) {
            $ctv_info = $d->rawQueryOne("select ten, avatar, username from #_member where id = ? limit 0,1", array($last_sender['id_send']));
        }
    }
}

// Filter messages for Candidate view to only display messages from/to the selected CTV or Admin
$filtered_messages = [];
if (!$is_ctv && isset($message) && count($message)) {
    $chatting_with_admin = (!isset($_GET['id_ctv']) || $_GET['id_ctv'] == 'admin');
    
    foreach ($message as $m) {
        if ($chatting_with_admin) {
            // Show candidate's own messages to Admin (id_send_type=0, id_receive=0) + Admin messages (id_send_type=2)
            if (($m['id_send_type'] == 0 && $m['id_receive'] == 0) || $m['id_send_type'] == 2) {
                $filtered_messages[] = $m;
            }
        } else {
            // Chatting with a specific CTV: show candidate's own messages to that CTV (id_send_type=0, id_receive=id_ctv) + that CTV's messages
            if (($m['id_send_type'] == 0 && $m['id_receive'] == $id_ctv) || ($m['id_send_type'] == 1 && $m['id_send'] == $id_ctv)) {
                $filtered_messages[] = $m;
            }
        }
    }
}
$message = (!$is_ctv) ? $filtered_messages : $message;

// has_active_chat represents if a room is explicitly loaded in the URL
$has_active_chat = ($is_ctv && isset($_GET['id'])) || (!$is_ctv && isset($_GET['id_ctv']));

$jobs = [];
if ($is_ctv) {
    $jobs = $d->rawQuery("select id, tenvi, tenkhongdauvi from #_news where type = ? and hienthi > 0 order by id desc", array('tin-tuyen-dung'));
}
?>
<style>
  .container-chat-messager {
    display: flex;
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e6ee;
    box-shadow: 0 10px 30px rgba(13,35,64,0.05);
    height: 650px;
    overflow: hidden;
    margin-bottom: 30px;
  }
  .left-chat-messager {
    width: 300px;
    border-right: 1px solid #edf0f5;
    background: #fbfcfe;
    display: flex;
    flex-direction: column;
    height: 100%;
    flex-shrink: 0;
  }
  .list-chat-messager {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }
  .items-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    border-bottom: 1px solid #edf0f5;
    color: #4c5567;
    text-decoration: none !important;
    transition: background 0.15s ease;
  }
  .items-user:hover {
    background: #f3f6fc;
    color: #0d2340;
  }
  .items-user.active {
    background: #e9f0fe;
    border-left: 4px solid #2f6fed;
    color: #0d2340;
  }
  .avatar-user {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    background: #e2e6ee;
    flex-shrink: 0;
  }
  .avatar-user img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .text-user {
    flex: 1;
    min-width: 0;
  }
  .text-user h2 {
    font-size: 13.5px;
    font-weight: 700;
    margin: 0 0 4px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #0d2340;
  }
  .text-user h3 {
    font-size: 11.5px;
    font-weight: 500;
    margin: 0;
    color: #727e94;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .text-user h3 .text-sp-1 {
    display: inline-block;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .read-user {
    width: 8px;
    height: 8px;
    background: transparent;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .read-user.active {
    background: #2f6fed;
  }
  .right-chat-messager {
    flex: 1;
    background: #ffffff;
    height: 100%;
    display: flex;
    min-width: 0;
  }
  .container-chat-messager .chat-container {
    height: 100%;
    display: flex;
    flex-direction: column;
    border: none;
    box-shadow: none;
    border-radius: 0;
    width: 100%;
  }
  .candidate-info-panel {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e6ee;
    padding: 15px;
    box-shadow: 0 10px 30px rgba(13,35,64,0.02);
    height: 100%;
    max-height: 650px;
    overflow-y: auto;
  }
  .info-panel-title {
    font-size: 13px;
    font-weight: 800;
    color: #0d2340;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #0d2340;
    text-transform: uppercase;
  }
  .info-panel-body {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .info-group {
    display: flex;
    flex-direction: column;
    gap: 3px;
    font-size: 12.5px;
  }
  .info-group label {
    font-weight: 700;
    color: #1c4074;
    margin-bottom: 0;
  }
  .info-val {
    color: #1a2233;
  }
  .info-desc-box {
    background: #f8fafc;
    border: 1px dashed #cfe3fa;
    border-radius: 8px;
    padding: 8px;
    font-size: 11.5px;
    color: #4c5567;
    line-height: 1.4;
  }
  .btn-back-chat {
    display: none;
    align-items: center;
    justify-content: center;
    background: #f1f3f5;
    border: 1px solid #dee2e6;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #495057 !important;
    text-decoration: none !important;
  }
  .container-chat-messager.has-active-chat .left-chat-messager {
    display: none !important;
  }
  .container-chat-messager.has-active-chat .btn-back-chat {
    display: inline-flex !important;
  }
  #info-panel-content {
    display: none;
  }
  @media (min-width: 992px) {
    #info-panel-content {
      display: flex !important;
    }
  }

  /* Responsive fixes for mobile and tablet screen sizes */
  @media (max-width: 991px) {
    .container-chat-messager {
      flex-direction: column;
      height: 580px;
    }
    
    .container-chat-messager.has-active-chat .right-chat-messager {
      display: flex !important;
      flex-direction: column;
      height: 100% !important;
    }
    .container-chat-messager:not(.has-active-chat) .left-chat-messager {
      display: block !important;
      width: 100% !important;
    }
    .container-chat-messager:not(.has-active-chat) .right-chat-messager {
      display: none !important;
    }
    
    .right-chat-messager .row {
      display: flex !important;
      flex-direction: column !important;
      flex: 1;
      min-height: 0;
    }
    .right-chat-messager .col-lg-8 {
      flex: 1;
      height: auto !important;
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    .right-chat-messager .col-lg-4 {
      flex-shrink: 0;
      height: auto !important;
      padding-left: 0 !important;
      margin-top: 10px;
      margin-bottom: 10px;
    }
    .candidate-info-panel {
      max-height: 350px;
    }
  }
</style>

<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            <?php if ($is_ctv) { ?>
                <!-- Collaborator chat management with multiple rooms -->
                <div class="container-chat-messager <?= $has_active_chat ? 'has-active-chat' : '' ?>">
                    <div class="left-chat-messager">
                        <div class="list-chat-messager">
                            <?php 
                            if (isset($rooms) && count($rooms)) {
                                foreach($rooms as $r) { 
                                    $newm = $d->rawQueryOne("select * from #_message where id_room = ? order by id desc limit 0,1", array($r['id']));
                                    $member = $d->rawQueryOne("select * from #_member where id = ?", array($r['id_member']));
                                    if ($member) {
                                        $is_active = (isset($room) && $room['id'] == $r['id']) ? 'active' : '';
                                        $avatar_url = $member['avatar'] ? UPLOAD_FILE_L.$member['avatar'] : 'assets/images/noimage.png';
                            ?>
                                        <a class="items-user <?= $is_active ?>" href="account/chat?id=<?= $member['id'] ?>">
                                            <div class="avatar-user">
                                                <img src="<?= $avatar_url ?>" onerror="this.src='assets/images/noimage.png';" alt="<?= htmlspecialchars($member['username']) ?>">
                                            </div>
                                            <div class="text-user">
                                                <h2><?= htmlspecialchars($member['ten'] ? $member['ten'] : $member['username']) ?></h2>
                                                <h3>
                                                    <span class="text-sp-1">
                                                        <?php 
                                                        if ($newm) {
                                                            echo ($newm['type'] == 1 ? '' : 'Bạn: ') . ($newm['message'] ? htmlspecialchars($newm['message']) : 'đã gửi 1 ảnh');
                                                        } else {
                                                            echo 'Chưa có tin nhắn';
                                                        }
                                                        ?>
                                                    </span>
                                                    <?php if ($newm) { ?>
                                                        - <span><?= $func->khoangcach($newm['ngaytao']) ?></span>
                                                    <?php } ?>
                                                </h3>
                                            </div>
                                            <div class="read-user <?= ($newm && $newm['trangthai'] == 1 && $newm['type'] == 1) ? 'active' : '' ?>"></div>
                                        </a>
                            <?php 
                                    }
                                } 
                            } else {
                            ?>
                                <p class="p-3 text-center text-muted">Chưa có cuộc trò chuyện nào</p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="right-chat-messager">
                        <?php if (isset($room) && $room['id'] && isset($candidate_info)) { ?>
                            <div class="row m-0 w-100 h-100">
                                <div class="col-lg-8 col-md-12 p-0 h-100 d-flex flex-column order-2 order-lg-1">
                                    <input type="hidden" id="id_candidate" value="<?= $id_candidate ?>">
                                    <div class="chat-container">
                                        <div class="header-chat d-flex align-items-center">
                                            <a href="account/chat" class="btn-back-chat mr-2"><i class="fas fa-chevron-left mr-1"></i>Quay lại</a>
                                            <p class="m-0"><?= $chat_partner_name ?></p>
                                        </div>
                                        <div class="body-chat">
                                            <?php if(isset($message) && count($message)) { ?> 
                                                <?php foreach($message as $m) { ?> 
                                                    <?php 
                                                    $is_my_message = ($m['type'] == 2);
                                                    if($is_my_message) { 
                                                    ?> 
                                                        <div class="message-right message">
                                                            <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                                <?php if($m['message']) { ?> 
                                                                    <?= make_links_clickable($m['message']) ?>
                                                                <?php } else { ?> 
                                                                    <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                                <?php } ?>   
                                                            </div>
                                                        </div>
                                                    <?php } else { ?> 
                                                        <div class="message-left message">
                                                            <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                                <?php if($m['message']) { ?> 
                                                                    <?= make_links_clickable($m['message']) ?>
                                                                <?php } else { ?> 
                                                                    <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                                <?php } ?> 
                                                            </div>
                                                        </div>
                                                    <?php } ?>    
                                                <?php } ?>
                                            <?php } else { ?> 
                                                <p class="no-images"><?= chuacotinnhannao ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="footer-chat">
                                            <div class="form-send-chat">
                                                <div class="lh1 send-file">
                                                    <i class="fas fa-file-image"></i>
                                                </div>
                                                <div class="lh1 btn-share-job" title="Chia sẻ công việc/Job" style="margin: 0 10px; font-size: 20px; color: #2f6fed; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <input type="file" name="images" id="file_message">
                                                <textarea id="message" name="message" oninput="autoResize(this)" cols="30" rows="1"></textarea>
                                                <div class="lh1">
                                                    <i class="fas fa-paper-plane send-messager-btn"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 p-0 pl-lg-3 mt-3 mt-lg-0 order-1 order-lg-2">
                                    <div class="candidate-info-panel">
                                        <h5 class="info-panel-title d-flex justify-content-between align-items-center mb-0 mb-lg-3" style="cursor: pointer;" id="btn-toggle-info">
                                            <span><i class="fas fa-user-circle mr-2"></i>Thông tin ứng viên</span>
                                            <span class="d-inline-block d-lg-none text-primary" style="font-size:12px;" id="toggle-info-text">[Hiện]</span>
                                        </h5>
                                        <div class="info-panel-body mt-3 mt-lg-0" id="info-panel-content">
                                            <div class="info-group mb-3">
                                                <button class="btn btn-success btn-block font-weight-bold btn-chot-job" style="padding: 10px; border-radius: 8px;" data-candidate-id="<?= $id_candidate ?>"><i class="fas fa-check-circle mr-2"></i>Chốt ứng viên vào Job</button>
                                            </div>
                                            <?php if (count($chot_jobs) > 0) { ?>
                                                <div class="info-group mb-3 p-3 bg-light border rounded" style="border-left: 4px solid #28a745 !important; border-top-left-radius: 0; border-bottom-left-radius: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                    <p class="mb-2 text-success font-weight-bold" style="font-size: 13px; display: flex; align-items: center;"><i class="fas fa-flag mr-2"></i>Đã chốt vào Job:</p>
                                                    <ul class="pl-3 mb-0" style="font-size: 12px; list-style-type: square; color: #495057;">
                                                        <?php foreach ($chot_jobs as $cj) { ?>
                                                            <li class="mb-1 font-weight-bold">
                                                                <?= htmlspecialchars($cj['ten_news']) ?>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <div class="info-group">
                                                <label>Họ tên:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['ten']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Trình độ tiếng Nhật:</label>
                                                <span class="info-val badge bg-info text-white" style="display:inline-block; font-size:11px; padding:3px 6px;"><?= htmlspecialchars($candidate_info['trinhdotiengnhat']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Tư cách cư trú:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['tucachcutru']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Hạn visa:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['thoihanvisa']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Khu vực mong muốn:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['tinhmongmuon']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Ngành nghề mong muốn:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['nganhnghemongmuon']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Mức lương mong muốn:</label>
                                                <span class="info-val text-primary font-weight-bold"><?= htmlspecialchars($candidate_info['mucluongmongmuon']) ?></span>
                                            </div>
                                            <div class="info-group">
                                                <label>Thời gian chuyển việc:</label>
                                                <span class="info-val"><?= htmlspecialchars($candidate_info['thoigianchuyenviec']) ?></span>
                                            </div>
                                            <?php if ($candidate_info['zalo_fb_line_id']) { ?>
                                                <div class="info-group">
                                                    <label>Liên hệ (<?= htmlspecialchars($candidate_info['zalo_fb_line']) ?>):</label>
                                                    <span class="info-val font-weight-bold text-dark"><?= htmlspecialchars($candidate_info['zalo_fb_line_id']) ?></span>
                                                </div>
                                            <?php } ?>
                                            <div class="info-group">
                                                <label>Nội dung mong muốn:</label>
                                                <div class="info-desc-box">
                                                    <?= nl2br(htmlspecialchars($candidate_info['noidungmongmuon'])) ?>
                                                </div>
                                            </div>
                                            <?php if ($candidate_info['mongmuonkhac']) { ?>
                                                <div class="info-group">
                                                    <label>Mong muốn khác:</label>
                                                    <div class="info-desc-box">
                                                        <?= nl2br(htmlspecialchars($candidate_info['mongmuonkhac'])) ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-white p-5 text-center">
                                <p class="text-muted font-italic m-0">Vui lòng chọn hoặc click vào một cuộc trò chuyện để bắt đầu.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Candidate view: always show split inbox layout (Admin + CTVs) -->
                <?php if (true) { ?>
                    <div class="container-chat-messager <?= $has_active_chat ? 'has-active-chat' : '' ?>">
                        <div class="left-chat-messager">
                            <div class="list-chat-messager">
                                <?php 
                                // Show Admin room preview
                                $newm_admin = $d->rawQueryOne("select * from #_message where id_room = ? and ((id_send_type = 0 and id_receive = 0) OR id_send_type = 2) order by id desc limit 0,1", array($room['id']));
                                $is_admin_active = (isset($_GET['id_ctv']) && $_GET['id_ctv'] == 'admin') || (!isset($_GET['id_ctv']) && !isset($id_ctv));
                                ?>
                                <a class="items-user <?= $is_admin_active ? 'active' : '' ?>" href="account/chat?id_ctv=admin">
                                    <div class="avatar-user">
                                        <img src="assets/images/noimage.png" alt="Admin">
                                    </div>
                                    <div class="text-user">
                                        <h2>Admin</h2>
                                        <h3>
                                            <span class="text-sp-1">
                                                <?= $newm_admin && $newm_admin['message'] ? htmlspecialchars($newm_admin['message']) : ($newm_admin ? 'đã gửi 1 ảnh' : 'Chưa có tin nhắn') ?>
                                            </span>
                                            <?php if ($newm_admin) { ?>
                                                - <span><?= $func->khoangcach($newm_admin['ngaytao']) ?></span>
                                            <?php } ?>
                                        </h3>
                                    </div>
                                    <div class="read-user <?= ($newm_admin && $newm_admin['trangthai'] == 1) ? 'active' : '' ?>"></div>
                                </a>
                                <?php
                                if (isset($ctv_senders) && count($ctv_senders) > 0) {

                                foreach($ctv_senders as $sender) { 
                                    $member = $d->rawQueryOne("select * from #_member where id = ?", array($sender['id_send']));
                                    if ($member) {
                                        $newm = $d->rawQueryOne("select * from #_message where id_room = ? and ((id_send_type = 0 and id_receive = ?) OR (id_send_type = 1 and id_send = ?)) order by id desc limit 0,1", array($room['id'], $sender['id_send'], $sender['id_send']));
                                        $is_active = ($id_ctv == $member['id']) ? 'active' : '';
                                        $avatar_url = $member['avatar'] ? UPLOAD_FILE_L.$member['avatar'] : 'assets/images/noimage.png';
                                ?>
                                        <a class="items-user <?= $is_active ?>" href="account/chat?id_ctv=<?= $member['id'] ?>">
                                            <div class="avatar-user">
                                                <img src="<?= $avatar_url ?>" onerror="this.src='assets/images/noimage.png';" alt="<?= htmlspecialchars($member['username']) ?>">
                                            </div>
                                            <div class="text-user">
                                                <h2><?= htmlspecialchars($member['ten'] ? $member['ten'] : $member['username']) ?></h2>
                                                <h3>
                                                    <span class="text-sp-1">
                                                        <?= $newm && $newm['message'] ? htmlspecialchars($newm['message']) : 'đã gửi 1 ảnh' ?>
                                                    </span>
                                                    <?php if ($newm) { ?>
                                                        - <span><?= $func->khoangcach($newm['ngaytao']) ?></span>
                                                    <?php } ?>
                                                </h3>
                                            </div>
                                            <div class="read-user <?= ($newm && $newm['trangthai'] == 1) ? 'active' : '' ?>"></div>
                                        </a>
                                <?php 
                                    }
                                } 
                                }
                                ?>
                            </div>
                        </div>
                        <div class="right-chat-messager">
                            <div class="chat-container">
                                <div class="header-chat d-flex align-items-center">
                                    <a href="account/chat" class="btn-back-chat mr-2"><i class="fas fa-chevron-left mr-1"></i>Quay lại</a>
                                    <?php if (isset($ctv_info) && $ctv_info) { 
                                        $ctv_avatar = $ctv_info['avatar'] ? UPLOAD_FILE_L.$ctv_info['avatar'] : 'assets/images/noimage.png';
                                    ?>
                                        <div class="avatar-user mr-2" style="width:36px; height:36px;">
                                            <img src="<?= $ctv_avatar ?>" onerror="this.src='assets/images/noimage.png';" alt="<?= htmlspecialchars($ctv_info['username']) ?>">
                                        </div>
                                        <p class="m-0">Trò chuyện với: <?= htmlspecialchars($ctv_info['ten'] ? $ctv_info['ten'] : $ctv_info['username']) ?></p>
                                        <input type="hidden" id="id_ctv" value="<?= htmlspecialchars($id_ctv) ?>">
                                    <?php } else { ?>
                                        <p class="m-0"><?= $chat_partner_name ?></p>
                                        <input type="hidden" id="id_ctv" value="admin">
                                    <?php } ?>
                                </div>
                                <div class="body-chat">
                                    <?php if(count($filtered_messages)) { ?> 
                                        <?php foreach($filtered_messages as $m) { ?> 
                                            <?php 
                                            $is_my_message = ($m['type'] == 1);
                                            if($is_my_message) { 
                                            ?> 
                                                <div class="message-right message">
                                                    <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                        <?php if($m['message']) { ?> 
                                                            <?= make_links_clickable($m['message']) ?>
                                                        <?php } else { ?> 
                                                            <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                        <?php } ?>   
                                                    </div>
                                                </div>
                                            <?php } else { ?> 
                                                <div class="message-left message">
                                                    <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                        <?php if($m['message']) { ?> 
                                                            <?= make_links_clickable($m['message']) ?>
                                                        <?php } else { ?> 
                                                            <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                        <?php } ?> 
                                                    </div>
                                                </div>
                                            <?php } ?>    
                                        <?php } ?>
                                    <?php } else { ?> 
                                        <p class="no-images"><?= chuacotinnhannao ?></p>
                                    <?php } ?>
                                </div>
                                <div class="footer-chat">
                                    <div class="form-send-chat">
                                        <div class="lh1 send-file">
                                            <i class="fas fa-file-image"></i>
                                        </div>
                                        <input type="file" name="images" id="file_message">
                                        <textarea id="message" name="message" oninput="autoResize(this)" cols="30" rows="1"></textarea>
                                        <div class="lh1">
                                            <i class="fas fa-paper-plane send-messager-btn"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- Candidate standard view (one chat partner/admin, full-width) -->
                    <div class="chat-container">
                        <div class="header-chat d-flex align-items-center">
                            <?php if (isset($ctv_info) && $ctv_info) { 
                                $ctv_avatar = $ctv_info['avatar'] ? UPLOAD_FILE_L.$ctv_info['avatar'] : 'assets/images/noimage.png';
                            ?>
                                <div class="avatar-user mr-2" style="width:36px; height:36px;">
                                    <img src="<?= $ctv_avatar ?>" onerror="this.src='assets/images/noimage.png';" alt="<?= htmlspecialchars($ctv_info['username']) ?>">
                                </div>
                                <p class="m-0">Trò chuyện với: <?= htmlspecialchars($ctv_info['ten'] ? $ctv_info['ten'] : $ctv_info['username']) ?></p>
                            <?php } else { ?>
                                <p class="m-0"><?= $chat_partner_name ?></p>
                            <?php } ?>
                        </div>
                        <div class="body-chat">
                            <?php if(isset($room) && $room['id'] && count($message)) { ?> 
                                <?php foreach($message as $m) { ?> 
                                    <?php 
                                    $is_my_message = ($m['type'] == 1);
                                    if($is_my_message) { 
                                    ?> 
                                        <div class="message-right message">
                                            <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                <?php if($m['message']) { ?> 
                                                    <?= make_links_clickable($m['message']) ?>
                                                <?php } else { ?> 
                                                    <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                <?php } ?>   
                                            </div>
                                        </div>
                                    <?php } else { ?> 
                                        <div class="message-left message">
                                            <div class="mw-75" title="<?= daguiluc ?> <?= date("h:i d:m:Y", $m['ngaytao']) ?>">
                                                <?php if($m['message']) { ?> 
                                                    <?= make_links_clickable($m['message']) ?>
                                                <?php } else { ?> 
                                                    <img src="<?= UPLOAD_FILE_L . $m['photo'] ?>" alt="">
                                                <?php } ?> 
                                            </div>
                                        </div>
                                    <?php } ?>    
                                <?php } ?>
                            <?php } else { ?> 
                                <p class="no-images"><?= chuacotinnhannao ?></p>
                            <?php } ?>
                        </div>
                        <div class="footer-chat">
                            <div class="form-send-chat">
                                <div class="lh1 send-file">
                                    <i class="fas fa-file-image"></i>
                                </div>
                                <input type="file" name="images" id="file_message">
                                <textarea id="message" name="message" oninput="autoResize(this)" cols="30" rows="1"></textarea>
                                <div class="lh1">
                                    <i class="fas fa-paper-plane send-messager-btn"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    function autoResize(textarea) {
        if(textarea.scrollHeight > 200) {
            return false;
        } else {
            textarea.style.height = 'auto'; // Đặt chiều cao về auto để tính lại chiều cao mới
            textarea.style.height = `${textarea.scrollHeight}px`; // Đặt chiều cao mới dựa trên scrollHeight
        }
    }
</script>