<?php /* if(isset($config['cart']['active']) && $config['cart']['active']==true) { ?>
    <a class="cart-fixed text-decoration-none" href="gio-hang" title="Giỏ hàng">
        <i class="fas fa-shopping-bag"></i>
        <span class="count-cart"><?=(isset($_SESSION['cart'])) ? count($_SESSION['cart']) : 0?></span>
    </a>
<?php } */ ?>



<?php if (count($mxh2) > 0) { ?>
    <ul class="mxh ungdung">
        <?php for ($i = 0, $count = count($mxh2); $i < $count; $i++) { ?>
            <li>
                <a href="<?= $mxh2[$i]['link'] ?>" target="_blank">
                    <img onerror="this.src='<?= THUMBS ?>/56x56x2/assets/images/noimage.png';" src="<?= THUMBS ?>/56x56x1/<?= UPLOAD_PHOTO_L . $mxh2[$i]['photo'] ?>" alt="<?= $mxh2[$i]['ten'] ?>">
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

<div class="fix-toolbar">
    <ul>
        <li>
            <a id="goidien" href="tel:<?= preg_replace('/[^0-9]/', '', $optsetting['hotline']) ?>" title="title">
                <img src="assets/images/fp-phone.png" alt="images"><br>
                <span>Gọi điện</span>
            </a>
        </li>
        <li>
            <a id="sms" href="sms:<?= preg_replace('/[^0-9]/', '', $optsetting['hotline']) ?>" title="title">
                <img src="assets/images/fp-sms.png" alt="images"><br>
                <span>Nhắn tin</span>
            </a>
        </li>
        <li>
            <a target="_blank" href="https://www.google.com/maps/dir/?api=1&origin=&destination=<?= $optsetting['diachi'] ?>" title="Map">
                <img src="assets/images/fp-chiduong.png" alt="images"><br>
                <span>Chỉ Đường</span>
            </a>
        </li>
        <li>
            <a id="chatzalo" href="https://zalo.me/<?= preg_replace('/[^0-9]/', '', $optsetting['zalo']) ?>" title="title">
                <img src="assets/images/fp-zalo.png" alt="images"><br>
                <span>Chat zalo</span>
            </a>
        </li>
        <li>
            <a target="_blank" id="chatfb" href="<?= $optsetting['fanpage'] ?>" title="title">
                <img src="assets/images/fp-mess.png" alt="images"><br>
                <span>Chat facebook</span>
            </a>
        </li>
    </ul>
</div>