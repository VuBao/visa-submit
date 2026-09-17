<div id="menu-mobile">
    <div class="menu-bar-res">
        <a id="hamburger" href="#mmenu" title="Menu"><span></span></a>
    </div>
    <div class="search_mobi">
        <input type="text" id="keyword2" placeholder="<?=nhaptukhoatimkiem?>" onkeypress="doEnter(event,'keyword2');"
            value="">
        <i class="fa fa-search" aria-hidden="true" onclick="onSearch('keyword2');"></i>
    </div>
    <nav id="mmenu">
        <ul>
            <li>
                <a class="transition <?php if($com=='' || $com=='index') echo 'active'; ?>" href=""
                    title="<?=trangchu?>"><?=trangchu?></a>
            </li>
            <li>
                <a class="transition <?php if($com=='gioi-thieu') echo 'active'; ?>" href="gioi-thieu"
                    title="<?=gioithieu?>"><?=gioithieu?></a>
            </li>

            <li>
                <a class="transition <?php if ($com == 'tin-tuyen-dung') {
                                            echo 'active';
                                        }
                                        ?>" href="tin-tuyen-dung"
                    title="<?= danhsachcongviec ?>"><?= danhsachcongviec ?></a>
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
            <?php if (isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active'] == true) { ?>
            <li>
                <a class="transition" href="account/thong-tin" title="<?= taikhoan ?>"><?= taikhoan ?></a>
            </li>
            <li>
                <a class="transition" href="account/dang-xuat" title="<?= dangxuat ?>"><?= dangxuat ?></a>
            </li>
            <?php } else { ?>
            <li>
                <a class="transition" href="account/dang-nhap" title="<?= dangnhap ?>"><?= dangnhap ?></a>
            </li>
            <li>
                <a class="transition" href="account/dang-ky" title="<?= dangky ?>"><?= dangky ?></a>
            </li>
            <?php } ?>

           
        </ul>

    </nav>
</div>