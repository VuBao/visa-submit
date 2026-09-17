<div class="left-sibar hr-menu pb-1">
    <ul class="list-unstyled">
        <?php if($_SESSION[$login_member]['role']) { ?>
            <li class="<?= $com == 'account' && $action == 'danh-sach-gioi-thieu' ? 'active' : '' ?>">
                <a href="account/danh-sach-gioi-thieu">
                    <i class="fas fa-trophy mr-1 text-warning"></i> BẢNG THÀNH TÍCH
                </a>
            </li>
            <li class="<?= $com == 'account' && $action == 'danh-sach-nguyen-vong' ? 'active' : '' ?>">
                <a href="account/danh-sach-nguyen-vong">
                    Danh sách nguyện vọng
                </a>
            </li>
        <?php } ?>
        <li class="<?= $com == 'account' && $action == 'danh-sach-ung-tuyen' ? 'active' : '' ?>">
            <a href="account/danh-sach-ung-tuyen">
                <?= (isset($_SESSION[$login_member]['role']) && $_SESSION[$login_member]['role'] == 1) ? 'Danh sách ứng viên của tôi' : danhsachungtuyen ?>
            </a>
        </li>
        <?php if(!$_SESSION[$login_member]['role']) { ?>
        <li class="<?= $com == 'account' && $action == 'danh-sach-da-luu' ? 'active' : '' ?>">
            <a href="account/danh-sach-da-luu">
                <?= danhsachdaluu ?>
            </a>
        </li>
        <?php } ?>
        <li class="<?= $com == 'account' && $action == 'chat' ? 'active' : '' ?>">
            <a href="account/chat" class="d-flex align-items-center">
                Tin nhắn
                <span class="badge badge-danger ml-2 js-chat-badge-sidebar" style="display: none; background-color: rgb(236, 59, 59); color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; line-height: 1;">0</span>
            </a>
        </li>
       
        <li class="<?= $com == 'account' && $action == 'thong-tin' ? 'active' : '' ?>">
            <a href="account/thong-tin">
                <?= hosocuatoi ?>
            </a>
        </li>
        <?php if(!$_SESSION[$login_member]['role']) { ?>
            <li class="<?= $com == 'account' && $action == 'dang-ky-nguyen-vong' ? 'active' : '' ?>">
                <a href="account/dang-ky-nguyen-vong">
                    Đăng ký nguyện vọng
                </a>
            </li>
        <?php } ?>
    </ul>
</div>