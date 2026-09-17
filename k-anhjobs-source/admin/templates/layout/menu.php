    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
        <!-- Logo -->
        <a class="brand-link" href="index.php">
            <img class="logo-admin-img brand-image" src="assets/images/logo-admintrator.png" alt="Logo">
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview"
                    role="menu" data-accordion="false">
                    <!-- Bảng điều khiển -->
                    <?php
                $active = "";
                if($com=='index' || $com=='') $active = 'active';
                ?>
                    <li class="nav-item <?=$active?>">
                        <a class="nav-link <?=$active?>" href="index.php" title="Bảng thành tích">
                            <i class="nav-icon text-sm fas fa-trophy text-warning"></i>
                            <p>Bảng thành tích</p>
                        </a>
                    </li>

                    <!-- Group -->
                    <?php $disabled = array(); if(isset($config['group'])) { foreach($config['group'] as $key => $value) { ?>
                    <li class="nav-item has-treeview menu-group">
                        <a class="nav-link" href="#" title="Quản lý <?=$key?>">
                            <i class="nav-icon text-sm fas fa-layer-group"></i>
                            <p>
                                <?=$key?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(isset($value['product'])) { foreach($value['product'] as $k) { ?>
                            <?php
                                $disabled['product'][$k] = 1;
                                $v = $config['product'][$k];
                                $none = "";
                                $active = "";
                                $menuopen = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_list', $k, null, 'phrase-1') && $func->check_access('product', 'man_cat', $k, null, 'phrase-1') && $func->check_access('product', 'man_item', $k, null, 'phrase-1') && $func->check_access('product', 'man_sub', $k, null, 'phrase-1') && $func->check_access('product', 'man_brand', $k, null, 'phrase-1') && $func->check_access('product', 'man', $k, null, 'phrase-1') && $func->check_access('import', 'man', $k, null, 'phrase-1') && $func->check_access('export', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if((($com=='product') || ($com=='import') || ($com=='export')) && ($k==$_GET['type']))
                                {
                                    $active = 'active';
                                    $menuopen = 'menu-open';
                                }
                                ?>
                            <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                                <a class="nav-link <?=$active?>" href="#" title="Quản lý <?=$v['title_main']?>">
                                    <i class="nav-icon text-sm fas fa-boxes"></i>
                                    <p>
                                        Quản lý <?=$v['title_main']?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if(isset($v['list']) && $v['list'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_list', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_list' || $act=='add_list' || $act=='edit_list' || $kind=='man_list') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_list&type=<?=$k?>"
                                            title="Danh mục cấp 1"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 1</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['cat']) && $v['cat'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_cat', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_cat' || $act=='add_cat' || $act=='edit_cat' || $kind=='man_cat') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_cat&type=<?=$k?>"
                                            title="Danh mục cấp 2"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 2</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['item']) && $v['item'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_item', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_item' || $act=='add_item' || $act=='edit_item' || $kind=='man_item') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_item&type=<?=$k?>"
                                            title="Danh mục cấp 3"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 3</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['sub']) && $v['sub'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_sub', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_sub' || $act=='add_sub' || $act=='edit_sub' || $kind=='man_sub') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_sub&type=<?=$k?>"
                                            title="Danh mục cấp 4"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 4</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['brand']) && $v['brand'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_brand', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_brand' || $act=='add_brand' || $act=='edit_brand') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_brand&type=<?=$k?>"
                                            title="Danh mục hãng"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục hãng</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['mau']) && $v['mau'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_mau', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_mau' || $act=='add_mau' || $act=='edit_mau') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_mau&type=<?=$k?>"
                                            title="Danh mục màu sắc"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục màu sắc</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['size']) && $v['size'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_size', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='product' && ($act=='man_size' || $act=='add_size' || $act=='edit_size') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man_size&type=<?=$k?>"
                                            title="Danh mục kích thước"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục kích thước</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php
                                        $none = "";
                                        $active = "";
                                        if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                        if($com=='product' && ($act=='man' || $act=='add' || $act=='edit' || $act=='copy' || $kind=='man') && $k==$_GET['type']) $active = "active";
                                        ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=product&act=man&type=<?=$k?>"
                                            title="<?=$v['title_main']?>"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p><?=$v['title_main']?></p>
                                        </a></li>
                                    <?php if(isset($v['import']) && $v['import'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('import', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                            if(($com=='import') && ($k==$_GET['type'])) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>">
                                        <a class="nav-link <?=$active?>"
                                            href="index.php?com=import&act=man&type=<?=$k?>" title="Import"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Import</p>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if(isset($v['export']) && $v['export'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('export', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                            if(($com=='export') && ($act=='man') && ($k==$_GET['type'])) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>">
                                        <a class="nav-link <?=$active?>"
                                            href="index.php?com=export&act=man&type=<?=$k?>" title="Export"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Export</p>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['news'])) { foreach($value['news'] as $k) { ?>
                            <?php
                                $disabled['news'][$k] = 1;
                                $v = $config['news'][$k];
                                $none = "";
                                $active = "";
                                $menuopen = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_list', $k, null, 'phrase-1') && $func->check_access('news', 'man_cat', $k, null, 'phrase-1') && $func->check_access('news', 'man_item', $k, null, 'phrase-1') && $func->check_access('news', 'man_sub', $k, null, 'phrase-1') && $func->check_access('news', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if(($com=='news') && ($k==$_GET['type']))
                                {
                                    $active = 'active';
                                    $menuopen = 'menu-open';
                                }
                                ?>
                            <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                                <a class="nav-link <?=$active?>" href="#" title="Quản lý <?=$v['title_main']?>">
                                    <i class="nav-icon text-sm fas fa-book"></i>
                                    <p>
                                        Quản lý <?=$v['title_main']?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if(isset($v['list']) && $v['list'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_list', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='news' && ($act=='man_list' || $act=='add_list' || $act=='edit_list' || $kind=='man_list' || $kind=='man_list') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=news&act=man_list&type=<?=$k?>"
                                            title="Danh mục cấp 1"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p><?= $v['title_main_list'] ?></p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['cat']) && $v['cat'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_cat', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='news' && ($act=='man_cat' || $act=='add_cat' || $act=='edit_cat' || $kind=='man_cat' || $kind=='man_cat') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=news&act=man_cat&type=<?=$k?>" title="Danh mục cấp 2"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 2</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['item']) && $v['item'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_item', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='news' && ($act=='man_item' || $act=='add_item' || $act=='edit_item' || $kind=='man_item' || $kind=='man_item') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=news&act=man_item&type=<?=$k?>"
                                            title="Danh mục cấp 3"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 3</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php if(isset($v['sub']) && $v['sub'] == true) {
                                            $none = "";
                                            $active = "";
                                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_sub', $k, null, 'phrase-1')) $none = "d-none";
                                            if($com=='news' && ($act=='man_sub' || $act=='add_sub' || $act=='edit_sub' || $kind=='man_sub' || $kind=='man_sub') && $k==$_GET['type']) $active = "active"; ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=news&act=man_sub&type=<?=$k?>" title="Danh mục cấp 4"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p>Danh mục cấp 4</p>
                                        </a></li>
                                    <?php } ?>
                                    <?php
                                        $none = "";
                                        $active = "";
                                        if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                        if($com=='news' && ($act=='man' || $act=='add' || $act=='edit' || $act=='copy' || $kind=='man') && $k==$_GET['type']) $active = "active";
                                        ?>
                                    <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                            href="index.php?com=news&act=man&type=<?=$k?>"
                                            title="<?=$v['title_main']?>"><i
                                                class="nav-icon text-sm far fa-caret-square-right"></i>
                                            <p><?=$v['title_main']?></p>
                                        </a></li>
                                </ul>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['tags'])) { foreach($value['tags'] as $k) { ?>
                            <?php
                                $disabled['tags'][$k] = 1;
                                $v = $config['tags'][$k];
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('tags', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='tags' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=tags&act=man&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['static'])) { foreach($value['static'] as $k) { ?>
                            <?php
                                $disabled['static'][$k] = 1;
                                $v = $config['static'][$k];
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('static', 'capnhat', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='static' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=static&act=capnhat&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['newsletter'])) { foreach($value['newsletter'] as $k) { ?>
                            <?php
                                $disabled['newsletter'][$k] = 1;
                                $v = $config['newsletter'][$k];
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('newsletter', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='newsletter' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=newsletter&act=man&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['photo'])) { foreach($value['photo'] as $k) {
                                $disabled['photo'][$k] = 1;
                                $v = $config['photo']['man_photo'][$k];
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('photo', 'man_photo', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='photo' && $_GET['type']==$k && ($act=='man_photo' || $act=='add_photo' || $act=='edit_photo')) $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=photo&act=man_photo&type=<?=$k?>"
                                    title="<?=$v['title_main_photo']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main_photo']?></p>
                                </a>
                            </li>
                            <?php } } ?>

                            <?php if(isset($value['photo_static'])) { foreach($value['photo_static'] as $k) {
                                $disabled['photo_static'][$k] = 1;
                                $v = $config['photo']['photo_static'][$k];
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('photo', 'photo_static', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='photo' && $_GET['type']==$k && $act=='photo_static') $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>"
                                    href="index.php?com=photo&act=photo_static&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </li>
                    <?php } } ?>

                    <!-- Search -->
                    <?php if(isset($config['search'])) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('search', 'search_static', '', $config['search']['search_static'], 'phrase-2') && $func->check_access('search', 'man_search', '', $config['search']['man_search'], 'phrase-2')) $none = "d-none";
                    if($com=='search' && !isset($disabled['search'][$_GET['type']]) && !isset($disabled['search_static'][$_GET['type']]))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý bộ tìm kiếm">
                            <i class="nav-icon text-sm fas fa-share-alt"></i>
                            <p>
                                Quản lý bộ tìm kiếm
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(isset($config['search']['search_static'])) { ?>
                            <?php foreach($config['search']['search_static'] as $k => $v) { if(!isset($disabled['search_static'][$k])) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('search', 'search_static', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='search' && $_GET['type']==$k && $act=='search_static') $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>"
                                    href="index.php?com=search&act=search_static&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                            <?php } ?>
                            <?php if(isset($config['search']['man_search'])) { ?>
                            <?php foreach($config['search']['man_search'] as $k => $v) { if(!isset($disabled['search'][$k])) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('search', 'man_search', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='search' && $_GET['type']==$k && ($act=='man_search' || $act=='add_search' || $act=='edit_search')) $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=search&act=man_search&type=<?=$k?>"
                                    title="<?=$v['title_main_search']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main_search']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- Sản phẩm -->
                    <?php if(isset($config['product'])) { ?>
                    <?php foreach($config['product'] as $k => $v) { if(!isset($disabled['product'][$k])) { ?>
                    <?php
                        $none = "";
                        $active = "";
                        $menuopen = "";
                        if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_list', $k, null, 'phrase-1') && $func->check_access('product', 'man_cat', $k, null, 'phrase-1') && $func->check_access('product', 'man_item', $k, null, 'phrase-1') && $func->check_access('product', 'man_sub', $k, null, 'phrase-1') && $func->check_access('product', 'man_brand', $k, null, 'phrase-1') && $func->check_access('product', 'man', $k, null, 'phrase-1') && $func->check_access('import', 'man', $k, null, 'phrase-1') && $func->check_access('export', 'man', $k, null, 'phrase-1')) $none = "d-none";
                        if((($com=='product') || ($com=='import') || ($com=='export')) && ($k==$_GET['type']))
                        {
                            $active = 'active';
                            $menuopen = 'menu-open';
                        }
                        ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý <?=$v['title_main']?>">
                            <i class="nav-icon text-sm fas fa-boxes"></i>
                            <p>
                                Quản lý <?=$v['title_main']?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(isset($v['list']) && $v['list'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_list', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_list' || $act=='add_list' || $act=='edit_list' || $kind=='man_list') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_list&type=<?=$k?>" title="Danh mục cấp 1"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 1</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['cat']) && $v['cat'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_cat', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_cat' || $act=='add_cat' || $act=='edit_cat' || $kind=='man_cat') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_cat&type=<?=$k?>" title="Danh mục cấp 2"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 2</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['item']) && $v['item'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_item', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_item' || $act=='add_item' || $act=='edit_item' || $kind=='man_item') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_item&type=<?=$k?>" title="Danh mục cấp 3"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 3</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['sub']) && $v['sub'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_sub', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_sub' || $act=='add_sub' || $act=='edit_sub' || $kind=='man_sub') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_sub&type=<?=$k?>" title="Danh mục cấp 4"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 4</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['brand']) && $v['brand'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_brand', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_brand' || $act=='add_brand' || $act=='edit_brand') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_brand&type=<?=$k?>" title="Danh mục hãng"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục hãng</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['mau']) && $v['mau'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_mau', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_mau' || $act=='add_mau' || $act=='edit_mau') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_mau&type=<?=$k?>" title="Danh mục màu sắc"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục màu sắc</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['size']) && $v['size'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man_size', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='product' && ($act=='man_size' || $act=='add_size' || $act=='edit_size') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man_size&type=<?=$k?>"
                                    title="Danh mục kích thước"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục kích thước</p>
                                </a></li>
                            <?php } ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('product', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='product' && ($act=='man' || $act=='add' || $act=='edit' || $act=='copy' || $kind=='man') && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=product&act=man&type=<?=$k?>" title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a></li>
                            <?php if(isset($v['import']) && $v['import'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('import', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                    if(($com=='import') && ($k==$_GET['type'])) $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=import&act=man&type=<?=$k?>"
                                    title="Import"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Import</p>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if(isset($v['export']) && $v['export'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('export', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                    if(($com=='export') && ($act=='man') && ($k==$_GET['type'])) $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=export&act=man&type=<?=$k?>"
                                    title="Export"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Export</p>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } } ?>
                    <?php } ?>

                    <!-- Bài viết (Có cấp) -->
                    <?php if(isset($config['news'])) { ?>
                    <?php foreach($config['news'] as $k => $v) { if(!isset($disabled['news'][$k])) { if(isset($v['dropdown']) && $v['dropdown'] == true) { ?>
                    <?php
                        $none = "";
                        $active = "";
                        $menuopen = "";
                        if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_list', $k, null, 'phrase-1') && $func->check_access('news', 'man_cat', $k, null, 'phrase-1') && $func->check_access('news', 'man_item', $k, null, 'phrase-1') && $func->check_access('news', 'man_sub', $k, null, 'phrase-1') && $func->check_access('news', 'man', $k, null, 'phrase-1')) $none = "d-none";
                        if(($com=='news') && ($k==$_GET['type']))
                        {
                            $active = 'active';
                            $menuopen = 'menu-open';
                        }
                        ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý <?=$v['title_main']?>">
                            <i class="nav-icon text-sm fas fa-book"></i>
                            <p>
                                Quản lý <?=$v['title_main']?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(isset($v['list']) && $v['list'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_list', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='news' && ($act=='man_list' || $act=='add_list' || $act=='edit_list' || $kind=='man_list' || $kind=='man_list') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=news&act=man_list&type=<?=$k?>" title="Danh mục cấp 1"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?= $v['title_main_list'] ?></p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['cat']) && $v['cat'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_cat', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='news' && ($act=='man_cat' || $act=='add_cat' || $act=='edit_cat' || $kind=='man_cat' || $kind=='man_cat') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=news&act=man_cat&type=<?=$k?>" title="Danh mục cấp 2"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 2</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['item']) && $v['item'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_item', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='news' && ($act=='man_item' || $act=='add_item' || $act=='edit_item' || $kind=='man_item' || $kind=='man_item') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=news&act=man_item&type=<?=$k?>" title="Danh mục cấp 3"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 3</p>
                                </a></li>
                            <?php } ?>
                            <?php if(isset($v['sub']) && $v['sub'] == true) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man_sub', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='news' && ($act=='man_sub' || $act=='add_sub' || $act=='edit_sub' || $kind=='man_sub' || $kind=='man_sub') && $k==$_GET['type']) $active = "active"; ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=news&act=man_sub&type=<?=$k?>" title="Danh mục cấp 4"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Danh mục cấp 4</p>
                                </a></li>
                            <?php } ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='news' && ($act=='man' || $act=='add' || $act=='edit' || $act=='copy' || $kind=='man') && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>"><a class="nav-link <?=$active?>"
                                    href="index.php?com=news&act=man&type=<?=$k?>" title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a></li>
                        </ul>
                    </li>
                    <?php } } } ?>
                    <?php } ?>

                    <!-- Bài viết (Không cấp) -->
                    <?php if(isset($config['shownews']) && $config['shownews'] == true) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man', '', $config['news'], 'phrase-2', false)) $none = "d-none";
                    if(($com=='news') && !isset($disabled['news'][$_GET['type']]) && (!isset($config['news'][$_GET['type']]['dropdown']) || (isset($config['news'][$_GET['type']]['dropdown']) && $config['news'][$_GET['type']]['dropdown'] == false)))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý bài viết">
                            <i class="nav-icon text-sm far fa-newspaper"></i>
                            <p>
                                Quản lý bài viết
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach($config['news'] as $k => $v) { if(!isset($disabled['news'][$k]) && (!isset($v['dropdown']) || (isset($v['dropdown']) && $v['dropdown'] == false))) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('news', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='news' && ($act=='man' || $act=='add' || $act=='edit' || $act=='copy' || $kind=='man') && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=news&act=man&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </li>
                    <?php } ?>


                    <!-- ===================== QUẢN LÝ ===================== -->
                    <?php
                    $ql_menuopen = "";
                    $ql_active = "";
                    $ql_coms = array('user','congtacvien','ungtuyen','duyetnguyenvong','chat','order');
                    if(in_array($com, $ql_coms) && $act!='login' && $act!='logout') {
                        $ql_active = 'active';
                        $ql_menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$ql_menuopen?> menu-group">
                        <a class="nav-link <?=$ql_active?>" href="#" title="Quản lý">
                            <i class="nav-icon text-sm fas fa-tasks"></i>
                            <p>
                                QUẢN LÝ
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!-- Quản lý admin -->
                            <?php if(isset($config['user']['active']) && $config['user']['active'] == true && $func->is_super_admin()) { ?>
                            <?php if(isset($config['user']['admin']) && $config['user']['admin'] == true) {
                                $active = "";
                                if($com=='user' && ($act=='man_admin' || $act=='add_admin' || $act=='edit_admin' || $act=='permission_group' || $act=='add_permission_group' || $act=='edit_permission_group' || $act=='admin_edit')) $active = "active"; ?>
                            <li class="nav-item"><a class="nav-link <?=$active?>" href="index.php?com=user&act=man_admin"
                                    title="Quản lý admin"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý admin</p>
                                </a></li>
                            <?php } ?>
                            <?php } ?>

                            <!-- Quản lý CTV (Phê duyệt CTV) -->
                            <?php
                                $none = "";
                                $active = "";
                                if (isset($kiemtra) && $kiemtra == true) if ($func->check_access('congtacvien', 'man', '', null, 'phrase-1')) $none = "d-none";
                                if ($com == 'congtacvien') $active = 'active';
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=congtacvien&act=man"
                                    title="Quản lý CTV"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý CTV</p>
                                </a>
                            </li>

                            <!-- Quản lý ứng viên -->
                            <?php if(isset($config['user']['active']) && $config['user']['active'] == true && !$func->check_permission()) { ?>
                            <?php if(isset($config['user']['visitor']) && $config['user']['visitor'] == true) {
                                $active = "";
                                if($com=='user' && ($act=='man' || $act=='add' || $act=='edit')) $active = "active"; ?>
                            <li class="nav-item"><a class="nav-link <?=$active?>" href="index.php?com=user&act=man"
                                    title="Quản lý ứng viên"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý ứng viên</p>
                                </a></li>
                            <?php } ?>
                            <?php } ?>


                            <!-- Quản lý Tokutei Gino -->
                            <?php
                                $none = "";
                                $active = "";
                                if (isset($kiemtra) && $kiemtra == true) if ($func->check_access('ungtuyen', 'man', '', null, 'phrase-1')) $none = "d-none";
                                if ($com == 'ungtuyen') $active = 'active';
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=ungtuyen&act=man"
                                    title="Quản lý Tokutei Gino"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý Tokutei Gino</p>
                                </a>
                            </li>

                            <!-- Phê duyệt nguyện vọng -->
                            <?php
                                $none = "";
                                $active = "";
                                if (isset($kiemtra) && $kiemtra == true) if ($func->check_access('duyetnguyenvong', 'man', '', null, 'phrase-1')) $none = "d-none";
                                if ($com == 'duyetnguyenvong') $active = 'active';
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=duyetnguyenvong&act=man"
                                    title="Phê duyệt nguyện vọng"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Phê duyệt nguyện vọng</p>
                                </a>
                            </li>

                            <!-- Quản lý tin nhắn -->
                            <?php
                                $none = "";
                                $active = "";
                                if (isset($kiemtra) && $kiemtra == true) if ($func->check_access('chat', 'man', '', null, 'phrase-1')) $none = "d-none";
                                if ($com == 'chat') $active = 'active';
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=chat&act=man"
                                    title="Quản lý tin nhắn"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý tin nhắn</p>
                                </a>
                            </li>

                            <!-- Quản lý đơn hàng -->
                            <?php if(isset($config['order']['active']) && $config['order']['active'] == true) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('order', 'man', '', null, 'phrase-1')) $none = "d-none";
                                if($com=='order') $active = 'active';
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=order&act=man"
                                    title="Quản lý đơn hàng"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quản lý đơn hàng</p>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>



                    <!-- Tags -->
                    <?php if(isset($config['tags'])) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('tags', 'man', '', $config['tags'], 'phrase-2')) $none = "d-none";
                    if($com=='tags' && !isset($disabled['tags'][$_GET['type']]))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý tags">
                            <i class="nav-icon text-sm fas fa-tags"></i>
                            <p>
                                Quản lý tags
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach($config['tags'] as $k => $v) { if(!isset($disabled['tags'][$k])) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('tags', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='tags' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=tags&act=man&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- Newsletter -->
                    <?php if(isset($config['newsletter'])) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('newsletter', 'man', '', $config['newsletter'], 'phrase-2')) $none = "d-none";
                    if($com=='newsletter' && !isset($disabled['newsletter'][$_GET['type']]))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý nhận tin">
                            <i class="nav-icon text-sm fas fa-envelope"></i>
                            <p>
                                Quản lý nhận tin
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach($config['newsletter'] as $k => $v) { if(!isset($disabled['newsletter'][$k])) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('newsletter', 'man', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='newsletter' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=newsletter&act=man&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- Static (Ẩn tạm) -->
                    <?php /* Tạm ẩn Quản lý trang tĩnh
                    <?php if(isset($config['static'])) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('static', 'capnhat', '', $config['static'], 'phrase-2')) $none = "d-none";
                    if($com=='static' && !isset($disabled['static'][$_GET['type']]))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý trang tĩnh">
                            <i class="nav-icon text-sm fas fa-bookmark"></i>
                            <p>
                                Quản lý trang tĩnh
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach($config['static'] as $k => $v) { if(!isset($disabled['static'][$k])) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('static', 'capnhat', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='static' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=static&act=capnhat&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    */ ?>

                    <!-- Gallery -->
                    <?php if(isset($config['photo'])) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('photo', 'photo_static', '', $config['photo']['photo_static'], 'phrase-2') && $func->check_access('photo', 'man_photo', '', $config['photo']['man_photo'], 'phrase-2')) $none = "d-none";
                    if($com=='photo' && !isset($disabled['photo'][$_GET['type']]) && !isset($disabled['photo_static'][$_GET['type']]))
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý hình ảnh - video">
                            <i class="nav-icon text-sm fas fa-photo-video"></i>
                            <p>
                                Quản lý hình ảnh - video
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(isset($config['photo']['photo_static'])) { ?>
                            <?php foreach($config['photo']['photo_static'] as $k => $v) { if(!isset($disabled['photo_static'][$k])) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('photo', 'photo_static', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='photo' && $_GET['type']==$k && $act=='photo_static') $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>"
                                    href="index.php?com=photo&act=photo_static&type=<?=$k?>"
                                    title="<?=$v['title_main']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                            <?php } ?>
                            <?php if(isset($config['photo']['man_photo'])) { ?>
                            <?php foreach($config['photo']['man_photo'] as $k => $v) { if(!isset($disabled['photo'][$k])) {
                                    $none = "";
                                    $active = "";
                                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('photo', 'man_photo', $k, null, 'phrase-1')) $none = "d-none";
                                    if($com=='photo' && $_GET['type']==$k && ($act=='man_photo' || $act=='add_photo' || $act=='edit_photo')) $active = "active"; ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=photo&act=man_photo&type=<?=$k?>"
                                    title="<?=$v['title_main_photo']?>"><i
                                        class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v['title_main_photo']?></p>
                                </a>
                            </li>
                            <?php } } ?>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- Địa điểm -->
                    <?php if(isset($config['places']['active']) && $config['places']['active'] == true) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('places', 'man_city', '', null, 'phrase-1') && $func->check_access('places', 'man_district', '', null, 'phrase-1') && $func->check_access('places', 'man_wards', '', null, 'phrase-1') && $func->check_access('places', 'man_street', '', null, 'phrase-1')) $none = "d-none";
                    if($com=='places')
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý địa điểm">
                            <i class="nav-icon text-sm fas fa-building"></i>
                            <p>
                                Quản lý địa điểm
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            $none = "";
                            $active = "";
                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('places', 'man_city', '', null, 'phrase-1')) $none = "d-none";
                            if($com=='places' && ($act=='man_city' || $act=='add_city' || $act=='edit_city')) $active = "active";
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=places&act=man_city"
                                    title="Tỉnh thành"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Tỉnh thành</p>
                                </a>
                            </li>
                            <?php
                            $none = "";
                            $active = "";
                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('places', 'man_district', '', null, 'phrase-1')) $none = "d-none";
                            if($com=='places' && ($act=='man_district' || $act=='add_district' || $act=='edit_district')) $active = "active";
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=places&act=man_district"
                                    title="Quận huyện"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Quận huyện</p>
                                </a>
                            </li>
                            <?php
                            $none = "";
                            $active = "";
                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('places', 'man_wards', '', null, 'phrase-1')) $none = "d-none";
                            if($com=='places' && ($act=='man_wards' || $act=='add_wards' || $act=='edit_wards')) $active = "active";
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=places&act=man_wards"
                                    title="Phường xã"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Phường xã</p>
                                </a>
                            </li>
                            <?php
                            $none = "";
                            $active = "";
                            if(isset($kiemtra) && $kiemtra == true) if($func->check_access('places', 'man_street', '', null, 'phrase-1')) $none = "d-none";
                            if($com=='places' && ($act=='man_street' || $act=='add_street' || $act=='edit_street')) $active = "active";
                            ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=places&act=man_street"
                                    title="Đường"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p>Đường</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- User (Đã gộp vào QUẢN LÝ) -->

                    <!-- Onesignal (Ẩn tạm) -->
                    <?php /* Tạm ẩn Quản lý thông báo đẩy
                    <?php if(isset($config['onesignal']) && $config['onesignal'] == true) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('pushOnesignal', 'man', '', null, 'phrase-1')) $none = "d-none";
                    if($com=='pushOnesignal') $active = 'active';
                    ?>
                    <li class="nav-item <?=$active?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="index.php?com=pushOnesignal&act=man"
                            title="Quản lý thông báo đẩy">
                            <i class="nav-icon text-sm fas fa-bell"></i>
                            <p>Quản lý thông báo đẩy</p>
                        </a>
                    </li>
                    <?php } ?>
                    */ ?>

                    <!-- SEO page -->
                    <?php if(isset($config['seopage']) && count($config['seopage']['page']) > 0) { ?>
                    <?php
                    $none = "";
                    $active = "";
                    $menuopen = "";
                    if(isset($kiemtra) && $kiemtra == true) if($func->check_access('seopage', 'capnhat', '', $config['seopage']['page'], 'phrase-2')) $none = "d-none";
                    if($com=='seopage')
                    {
                        $active = 'active';
                        $menuopen = 'menu-open';
                    }
                    ?>
                    <li class="nav-item has-treeview <?=$menuopen?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="#" title="Quản lý SEO page">
                            <i class="nav-icon text-sm fas fa-share-alt"></i>
                            <p>
                                Quản lý SEO page
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach($config['seopage']['page'] as $k => $v) { ?>
                            <?php
                                $none = "";
                                $active = "";
                                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('seopage', 'capnhat', $k, null, 'phrase-1')) $none = "d-none";
                                if($com=='seopage' && $k==$_GET['type']) $active = "active";
                                ?>
                            <li class="nav-item <?=$none?>">
                                <a class="nav-link <?=$active?>" href="index.php?com=seopage&act=capnhat&type=<?=$k?>"
                                    title="<?=$v?>"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                                    <p><?=$v?></p>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <!-- Thiết lập thông tin -->
                    <?php
                $none = "";
                $active = "";
                if(isset($kiemtra) && $kiemtra == true) if($func->check_access('setting', 'capnhat', '', null, 'phrase-1')) $none = "d-none";
                if($com=='setting') $active = 'active';
                ?>
                    <li class="nav-item <?=$active?> <?=$none?>">
                        <a class="nav-link <?=$active?>" href="index.php?com=setting&act=capnhat"
                            title="Thiết lập thông tin">
                            <i class="nav-icon text-sm fas fa-cogs"></i>
                            <p>Thiết lập thông tin</p>
                        </a>
                    </li>

                    <!-- Visa: menu nghiệp vụ cuối cùng của sidebar -->
                    <?php
                    $none = "";
                    $active = ($com === 'visa') ? 'active' : '';
                    if(isset($kiemtra) && $kiemtra == true && $func->check_access('visa', 'man', '', null, 'phrase-1')) $none = "d-none";
                    ?>
                    <li class="nav-item <?=$none?>">
                        <a class="nav-link <?=$active?>" href="index.php?com=visa&act=man" title="ビザ申請書類">
                            <i class="nav-icon text-sm fas fa-passport"></i>
                            <p>ビザ申請書類</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <script type="text/javascript">
$(document).ready(function() {
    if ($(".menu-group").length) {
        var navlink = $(".menu-group").find(".nav-link.active").first();
        if (navlink.length) {
            var menugroup = navlink.parents(".menu-group");
            menugroup.addClass("menu-open");
            menugroup.find(">.nav-link").addClass("active");
        }
    }

    if ($(".nav-sidebar").find(">li.nav-item").not('.menu-group, .d-none').length) {
        var navitem = $(".nav-sidebar").find(">li.nav-item").not('.menu-group, .d-none');
        navitem.each(function(index) {
            var navtreeview = $(this).find(">ul.nav-treeview");
            if (navtreeview.length) {
                var navitemchild = $(this).find(">ul.nav-treeview").find(">li.nav-item");
                var navitemchildnone = $(this).find(">ul.nav-treeview").find(">li.nav-item.d-none");
                if (navitemchild.length) {
                    if (navitemchild.length == navitemchildnone.length) {
                        if (!$(this).hasClass("d-none")) {
                            $(this).addClass("d-none");
                        }
                    }
                } else if (navitemchild.length == 0 && navitemchildnone.length == 0) {
                    if (!$(this).hasClass("d-none")) {
                        $(this).addClass("d-none");
                    }
                }
            }
        });
    }
})
    </script>
