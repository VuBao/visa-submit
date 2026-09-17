<div id="menu">
    <div class="center custom-center-menu">

        <div class="search w-clear">
            <input type="text" id="keyword" placeholder="<?= nhaptukhoatimkiem ?>" onkeypress="doEnter(event,'keyword');" />
            <p onclick="onSearch('keyword');"><i class="fas fa-search"></i></p>
        </div>

        <ul class="custom-menu">
            <li>
                <a class="transition <?php if ($com == '' || $com == 'index') {
                                            echo 'active';
                                        }
                                        ?>" href="" title="<?= trangchu ?>"><?= trangchu ?></a>
            </li>
            <li>
                <a class="transition <?php if ($com == 'gioi-thieu') {
                                            echo 'active';
                                        }
                                        ?>" href="gioi-thieu" title="<?= gioithieu ?>"><?= gioithieu ?></a>
            </li>
         

            <li>
                <a class="transition <?php if ($com == 'tin-tuyen-dung') {
                                            echo 'active';
                                        }
                                        ?>" href="tin-tuyen-dung" title="<?= danhsachcongviec ?>"><?= danhsachcongviec ?></a>
            </li>
            <li>
                <a class="transition <?php if ($com == 'tin-tuc-va-su-kien') {
                                            echo 'active';
                                        }
                                        ?>" href="tin-tuc-va-su-kien" title="<?= tintucvasukien ?>"><?= tintucvasukien ?></a>
            </li>
            <li>
                <a class="transition <?php if ($com == 'lien-he') {
                                            echo 'active';
                                        }
                                        ?>" href="lien-he" title="<?= lienhe ?>"><?= lienhe ?></a>
            </li>
        </ul>

        <div class="menu-icon">
            <ul class="mxh footer-mxh menu-icon-flash">
                <li>
                    <a href="account/chat" class="menu-button mr-3" title="Your message">
                        <img src="assets/images/images/bag.png" alt="">
                        <span class="js-chat-badge" style="display: none;">0</span>
                    </a>
                </li>
                <li class="toggle-box-menu">
                    <a class="menu-button mr-3" title="You notification">
                        <img src="assets/images/images/heart.png" alt="">
                        <span><?= count($thongbao) ?></span>
                    </a>
                    <ul>
                        <li>
                            <div class="notification-box">
                                <div class="header">
                                    <h3><?= thongbao ?></h3>
                                    <button><?= danhdautatca ?></button>
                                </div>
                                <div class="content-notification">
                                    <?php if(count($thongbao)) { ?> 
                                    <?php foreach($thongbao as $tb) { ?> 
                                        <div class="items-thongbao">
                                            <p><?= $tb['ten'] ?></p>
                                            <p><?= date('d/m/Y', $tb['ngaytao']) ?></p> 
                                        </div>
                                    <?php } ?>
                                    <?php } else { ?> 
                                        <p><?= chuacothongbao ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="toggle-box-menu">
                    <a class="menu-button" title="You account">
                        <img src="assets/images/images/user.png" alt="">
                    </a>
                    <?php if(isset($_SESSION[$login_member]['active']) && $_SESSION[$login_member]['active'] == true  ) { ?> 
                        
                    <?php } ?>
                    <ul>
                        <?php if (isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active'] == true) { ?>
                            <li>
                                <a class="transition" href="account/thong-tin">
                                    <span> <?= taikhoan ?> </span>
                                </a>
                            </li>
                            <li>
                                <a class="transition" href="account/dang-xuat"> <span> <?= dangxuat ?> </span></a>
                            </li>
                        <?php } else { ?>
                            <li>
                                <a class="transition" href="account/dang-nhap">
                                    <span> <?= dangnhap ?> </span>
                                </a>
                            </li>
                            <li>
                                <a class="transition" href="account/dang-ky"> <span> <?= dangky ?> </span></a>
                            </li>
                        <?php } ?>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="custom-mxh">
<a class="btn-zalo btn-frame text-decoration-none" target="_blank" href="https://zalo.me/<?= preg_replace('/[^0-9]/', '', $optsetting['zalo']); ?>">
    <div class="animated infinite zoomIn kenit-alo-circle"></div>
    <div class="animated infinite pulse kenit-alo-circle-fill"></div>
    <i><img src="assets/images/zl.png" alt="Zalo"></i>
</a>
<?php if(isset($_SESSION[$login_member]['active'])) { ?>
<a class="btn-chat btn-frame text-decoration-none" href="account/chat">
    <div class="animated infinite zoomIn kenit-alo-circle"></div>
    <div class="animated infinite pulse kenit-alo-circle-fill"></div>
    <i><i class="fas fa-comment-dots" style="color: #fff; font-size: 24px;"></i></i>
    <span class="js-chat-badge badge badge-danger" style="display: none; position: absolute; top: -5px; right: -5px; background-color: red; color: white; border-radius: 50%; padding: 3px 6px; font-size: 11px;">0</span>
</a>
<?php } ?>

</div>

