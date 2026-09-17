<?php
$countNotify = 0;
$contactNotify = $d->rawQuery("select id from #_contact where hienthi = 0");

$countNotify += count($contactNotify);
if(isset($config['newsletter']))
{
    foreach($config['newsletter'] as $k => $v) 
    {
        $emailNotify = $d->rawQuery("select id from #_newsletter where type = ? and hienthi = 0",array($k));
        $countNotify += count($emailNotify);
    }
}

if(isset($config['order']['active']) && $config['order']['active'] == true)
{
    $orderNotify = $d->rawQuery("select id from #_order where tinhtrang=1");
    $countNotify += count($orderNotify);
}

$chat_access = true;
if (isset($kiemtra) && $kiemtra == true) {
    if ($func->check_access('chat', 'man', '', null, 'phrase-1')) {
        $chat_access = false;
    }
}
$countChatNotify = 0;
if ($chat_access) {
    $chatNotify = $d->rawQuery("select distinct id_room from #_message where id_send_type = 0 and id_receive = 0 and trangthai = 1");
    $countChatNotify = count($chatNotify);
}
?>
<!-- Header -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item nav-item-hello d-sm-inline-block">
            <a class="nav-link"><span class="text-split">Xin chào, <?=$_SESSION[$login_admin]['username']?>!</span></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications -->
        <li class="nav-item d-sm-inline-block">
            <a href="../" target="_blank" class="nav-link"><i class="fas fa-reply"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a id="dropdownSubMenu-info" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-cogs"></i></a>
            <ul aria-labelledby="dropdownSubMenu-info" class="dropdown-menu dropdown-menu-right border-0 shadow">
                <?php if($config['website']['debug-developer'] && count($config['website']['lang']) >= 2) { ?>
                    <li>
                        <a href="index.php?com=lang&act=man" class="dropdown-item">
                            <i class="fas fa-language"></i>
                            <span>Quản lý ngôn ngữ</span>
                        </a>
                    </li>
                    <div class="dropdown-divider"></div>
                <?php } ?>
                <li>
                    <a href="index.php?com=user&act=admin_edit" class="dropdown-item">
                        <i class="fas fa-user-cog"></i>
                        <span>Thông tin admin</span>
                    </a>
                </li>
                <div class="dropdown-divider"></div>
                <li>
                    <a href="index.php?com=user&act=admin_edit&changepass=1" class="dropdown-item">
                        <i class="fas fa-key"></i>
                        <span>Đổi mật khẩu</span>
                    </a>
                </li>
                <div class="dropdown-divider"></div>
                <li>
                    <a href="index.php?com=cache&act=delete" class="dropdown-item">
                        <i class="far fa-trash-alt"></i>
                        <span>Xóa bộ nhớ tạm</span>
                    </a>
                </li>
            </ul>
        </li>
        <?php if ($chat_access) { ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?com=chat&act=man" title="Tin nhắn">
                    <i class="fab fa-facebook-messenger" style="font-size: 1.15rem;"></i>
                    <?php if ($countChatNotify > 0) { ?>
                        <span class="badge badge-danger navbar-badge"><?=$countChatNotify?></span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger"><?=$countNotify?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <span class="dropdown-item dropdown-header p-0">Thông báo</span>
                <div class="dropdown-divider"></div>
                <a href="index.php?com=contact&act=man" class="dropdown-item"><i class="fas fa-envelope mr-2"></i><span class="badge badge-danger mr-1"><?=count($contactNotify)?></span> Liên hệ</a>
                <?php if(isset($config['order']['active']) && $config['order']['active'] == true) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="index.php?com=order&act=man" class="dropdown-item"><i class="fas fa-shopping-bag mr-2"></i><span class="badge badge-danger mr-1"><?=count($orderNotify)?></span> Đơn hàng</a>
                <?php } ?>
                <?php if(isset($config['newsletter'])) { ?>
                    <div class="dropdown-divider"></div>
                    <?php foreach($config['newsletter'] as $k => $v) { 
                        $emailNotify = $d->rawQuery("select id from #_newsletter where type = ? and hienthi = 0",array($k)); ?>
                        <a href="index.php?com=newsletter&act=man&type=<?=$k?>" class="dropdown-item"><i class="fas fa-mail-bulk mr-2"></i></i><span class="badge badge-danger mr-1"><?=count($emailNotify)?></span> <?=$v['title_main']?></a>
                        <div class="dropdown-divider"></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </li>
        <li class="nav-item d-sm-inline-block">
            <a href="index.php?com=user&act=logout" class="nav-link"><i class="fas fa-sign-out-alt mr-1"></i>Đăng xuất</a>
        </li>
    </ul>
</nav>