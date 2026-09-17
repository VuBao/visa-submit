<?php
	$linkMan = $linkFilter = "index.php?com=chat&act=man";
?>
<!-- Content Header -->
<div class="container-wrapper-flex">
    <section class="content-header text-sm">
        <div class="container-fluid">
            <div class="row">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
                    <li class="breadcrumb-item active">Quản lí tin nhắn</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content content-chat">
        <div class="container-chat-messager">
            <div class="left-chat-messager">
                <!-- <div class="form-search-messager">
                    <input type="text" placeholder="Tìm kiếm trên tin nhắn">
                    <i class="fas fa-search"></i>
                </div> -->
                <div class="list-chat-messager">
                    <?php foreach($room as $r) { ?>
                    <?php $newm = $d->rawQueryOne("select * from #_message where id_room = ? and ((id_send_type = 0 and id_receive = 0) OR id_send_type = 2) order by id desc limit 0,1", array($r['id'])); ?>
                    <?php $unread_admin = $d->rawQueryOne("select id from #_message where id_room = ? and id_send_type = 0 and id_receive = 0 and trangthai = 1 limit 0,1", array($r['id'])); ?>
                    <?php $member = $d->rawQueryOne("select * from #_member where id = ?", array($r['id_member'])); ?>

                    <a class="items-user room-<?= $member['id'] ?> <?= $room_active['id'] == $r['id'] ? 'active' : '' ?>"
                        href="<?= $linkMan ?>&id=<?= $member['id'] ?>">
                        <div class="avatar-user">
                            <img src="../<?= UPLOAD_FILE_L.$member['avatar'] ?>" alt="<?=$member['username']?>">
                        </div>
                        <div class="text-user">
                            <h2><?= $member['ten'] ? $member['ten'] : $member['username'] ?></h2>
                            <h3> <span class="text-sp-1"><?= ($newm && $newm['id_send_type'] == 0) ? '' : 'Bạn: ' ?>
                                    <?= ($newm && $newm['message']) ? htmlspecialchars($newm['message']) : ($newm ? 'đã gửi 1 ảnh' : 'Chưa có tin nhắn') ?> </span>
                                <?php if ($newm) { ?>
                                - <span><?= $func->khoangcach($newm['ngaytao']) ?></span>
                                <?php } ?>
                            </h3>
                        </div>
                        <div class="read-user <?= $unread_admin ? 'active' : '' ?>">
                            
                        </div>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="right-chat-messager">
                <?php if($room_active['id']) { ?>
                <div class="container-content-messager">
                    <div class="messager-header">
                        <div class="messager-header-left">
                            <img src="../<?= UPLOAD_FILE_L.$member_active['avatar'] ?>"
                                alt="<?=$member_active['username']?>">
                            <h2><?= $member_active['ten'] ? $member_active['ten'] : $member_active['username'] ?></h2>
                        </div>

                        <i class="fas fa-info-circle "></i>
                    </div>
                    <div class="messager-body messager-mem-<?= $member_active['id'] ?>">
                        <?php if(count($message)) { ?>
                        <?php foreach($message as $r) { ?>
                        <?php if($r['type'] == 1) { ?>
                        <div class="message-left message">
                            <div class="mw-75" title="đã gửi lúc <?= date('h:i d:m:Y', $r['ngaytao']) ?>">
                                <?php if($r['message']) { ?>
                                <?= nl2br($r['message']) ?>
                                <?php } else { ?>
                                <img src="../<?= UPLOAD_FILE_L.$r['photo'] ?>" alt="">
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="message-right message">
                            <div class="mw-75" title="đã gửi lúc <?= date('h:i d:m:Y', $r['ngaytao']) ?>">
                                <?php if($r['message']) { ?>
                                <?= nl2br($r['message']) ?>
                                <?php } else { ?>
                                <img src="../<?= UPLOAD_FILE_L.$r['photo'] ?>" alt="">
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                        <?php } else { ?>
                        <p class="no-images">Chưa có tin nhắn nào</p>
                        <?php } ?>
                    </div>
                    <script>
                    function autoResize(textarea) {
                        if (textarea.scrollHeight > 200) {
                            return false;
                        } else {
                            textarea.style.height = 'auto'; // Đặt chiều cao về auto để tính lại chiều cao mới
                            textarea.style.height =
                            `${textarea.scrollHeight}px`; // Đặt chiều cao mới dựa trên scrollHeight
                        }
                    }
                    </script>
                    <div class="messager-footer">
                        <div class="lh1 send-file" title="Gửi ảnh">
                            <i class="fas fa-file-image"></i>
                        </div>
                        <input type="file" name="images" id="file_message">
                        <textarea id="message" name="message" oninput="autoResize(this)" cols="30" rows="1"></textarea>
                        <div class="lh1" title="Gửi">
                            <i class="fas fa-paper-plane send-messager-btn"></i>
                        </div>
                    </div>
                </div>
                <div class="container-info-user-messager">
                    <div class="avatar-user-messager">
                        <img src="../<?= UPLOAD_FILE_L.$member_active['avatar'] ?>"
                            alt="<?=$member_active['username']?>">
                    </div>
                    <h2><?= $member_active['ten'] ? $member_active['ten'] : $member_active['username'] ?></h2>
                    <div class="items-info-user">
                        <p>Quốc tịch</p>
                        <p><?= $member_active['quoctich'] ?></p>
                    </div>
                    <div class="items-info-user">
                        <p>Email</p>
                        <p><?= $member_active['email'] ?></p>
                    </div>
                    <div class="items-info-user">
                        <p>Số điện thoại</p>
                        <p><?= $member_active['dienthoai'] ?></p>
                    </div>
                    <div class="items-info-user">
                        <p>CV</p>
                        <p>
                        <?php if($member_active['cv']) { ?> 
                            <a href="../upload/file/<?= $member_active['cv'] ?>" target="_blank">Xem cv ngay</a> 
                        <?php } else { ?> 
                            Chưa cập nhật
                        <?php } ?> 
                        </p>
                    </div>
                    <div class="items-info-user">
                        <p>Facebook</p>
                        <p>
                        <?php if($member_active['link_facebook']) { ?> 
                            <a href="<?= $member_active['link_facebook'] ?>" target="_black">Truy cập ngay</a> 
                        <?php } else { ?> 
                            Chưa cập nhật
                        <?php } ?> 
                        </p>
                    </div>
                    <div class="items-info-user">
                        <p>Thông tin chi tiết</p>
                        <p><a href="index.php?com=user&act=edit&id=<?= $member_active['id'] ?>"><?= $member_active['ten'] ? $member_active['ten'] : $member_active['username'] ?></a> </p>
                    </div>
                </div>
                <?php } else { ?>
                <div class="d-flex align-items-center justify-content-center">
                    <p>Chọn tin nhắn để hiển thị</p>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>