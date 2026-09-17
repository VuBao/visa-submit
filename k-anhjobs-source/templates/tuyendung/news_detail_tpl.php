<div class="w-clear">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom">
            <div class="content-primary">
                <div class="job-head d-md-flex justify-content-between align-items-center mb-3">
                    <h3>
                        <a href="<?= $row_detail[$sluglang] ?>" class="head-title">
                            <?= $row_detail['ten'.$lang] ?>
                        </a>
                    </h3>
                    <div class="d-flex mt-2 mt-md-0">
                        <div class="d-flex">
                            <div class="job-share">
                                <a class="btn-whistlist" href="javascript:history.back()">
                                    <i class="fas fa-undo-alt"></i>
                                </a>
                                <?php if(isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active']) { ?> 
                                    <?php $checkwl = $d->rawQueryOne("select * from #_uuthich where id_news = ? and id_member = ?", array($row_detail['id'], $_SESSION[$login_member]['id'])) ?>
                                    <?php if($checkwl) { ?> 
                                        <a class="btn-whistlist active wishlist" data-news="<?= $row_detail['id'] ?>" href="account/dang-nhap">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    <?php } else { ?> 
                                        <a class="btn-whistlist wishlist" data-news="<?= $row_detail['id'] ?>" href="account/dang-nhap">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    <?php } ?>
                                <?php } else { ?> 
                                   
                                    <a class="btn-whistlist" href="account/dang-nhap">
                                        <i class="far fa-heart"></i>
                                    </a>
                                <?php } ?>
                               
                            </div>
                        </div>
                    </div>
                </div>


                <div class="job-info mb-4 job-info-box">
                    <p class="mb-4 motatuyendung">
                        <?= nl2br($row_detail['mota'.$lang]) ?>
                    </p>
                    <div class="d-md-flex mb-4 w-100">
                        <div class="job-price mb-2 mb-md-0">
                            <strong><?= $row_detail['luong'] ?></strong> 
                            
                        </div>
                        <?php if($row_detail['luongcoban']) { ?> 
                        <div class="job-price-base ml-3">
                            <span><?= luongcoban ?>: </span> <strong><?= $row_detail['luongcoban'] ?></strong>
                            <span><?= yen ?></span>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if(count($hinhanhtt)) { ?> 
                    <div class="mb-3 w-100">
                        <h5 class="title-hinhanhcon"><?= hinhanhmieuta ?></h5>
                        <div class="flex-hinhanhtuyendung" id="lightgallery">
                            <?php foreach($hinhanhtt as $ha) { ?>
                            <div class="job-img d-flex">
                                <a class="gallery w-100" href="<?= UPLOAD_NEWS_L . $ha['photo'] ?>">
                                <img class="w-100" onerror="this.src='<?= THUMBS ?>/120x96x2/assets/images/noimage.png';"
                                    src="<?= THUMBS ?>/120x96x1/<?= UPLOAD_NEWS_L . $ha['photo'] ?>"
                                    alt="<?= $row_detail['ten'.$lang] ?>">
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="job-list">
                        <div class="job-list--grid mb-4">
                            <?php if($row_detail['quoctich']) { ?> 
                            <div class="job-item">
                                <label class="d-block"><strong><?= quoctich ?></strong> </label>
                                <span class="d-block"><?= $row_detail['quoctich'] ?></span>
                            </div>
                            <?php } ?>
                            <?php if($row_detail['diadiem']) { ?> 
                            <div class="job-item">
                                <label class="d-block"><strong><?= diadiemlamviec ?></strong> </label>
                                <span class="d-block"><?= $row_detail['diadiem'] ?></span>
                            </div>
                            <?php } ?>
                            <?php if($row_detail['ngayvaolam']) { ?> 
                            <div class="job-item">
                                <label class="d-block"><strong><?= ngaylamviec ?></strong> </label>
                                <span class="d-block"><?= date('d/m/Y', $row_detail['ngayvaolam']) ?></span>
                            </div>
                            <?php } ?>
                            <?php if($row_detail['tinhtrangcutru']) { ?>
                            <div class="job-item">
                                <label class="d-block"><strong><?= tinhtrangcutru ?></strong> </label>
                                <span class="d-block"><?= $row_detail['tinhtrangcutru'] ?></span>
                            </div>
                            <?php } ?>
                            <?php if($row_detail['soluong']) { ?>
                            <div class="job-item">
                                <label class="d-block"><strong><?= soluong ?></strong> </label>
                                <span class="d-block"><?= $row_detail['soluong'] ?></span>
                            </div>
                            <?php } ?>
                            <?php if($row_detail['handangtuyen']) { ?>
                            <div class="job-item">
                                <label class="d-block"><strong><?= handangtuyen ?></strong> </label>
                                <span class="d-block"><?= date('d/m/Y', $row_detail['handangtuyen']) ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="job-sumary d-flex align-items-center justify-content-between">
                        <div class="text-truncate mr-5" id="">
                            <a href="">#</a>
                        </div>
                        <div class="job-meta">
                            <div>
                                <i class="far fa-calendar-alt"></i>
                                <span class="d-block mx-2">
                                    <?php $category = $d->rawQueryOne("select ten$lang as ten from #_news_list where id = ?", array($row_detail['id_list'])); ?>
                                    <?= $category['ten'] ?>
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="d-block mx-2"><?= $row_detail['diadiem'] ?></span>
                            </div>
                            <div>
                                <i class="fas fa-eye"></i>
                                <span class="d-block ml-2"><?= $row_detail['luotxem'] ?> <?= luotxem ?></span>
                            </div>
                           
                        </div>
                    </div>
                </div>

                <div class="foot-title">
                    <h3><?= thongtinlamviecyeucau ?></h3>
                </div>
                <div class="my-3">
                    <?= htmlspecialchars_decode($row_detail['noidung'.$lang]) ?>
                </div>

                <div class="d-flex mt-5">
                    <?php if($_SESSION[$login_member]['active']) { ?>
                    <?php 
                    $kiemtra = $d->rawQueryOne("select id, ngaytao from #_ungtuyen where id_member = ? and id_news = ?", array($_SESSION[$login_member]['id'], $row_detail['id']));
                   
                    ?>
                   
                    <form id="form-ungtuyen" action="" method="post">
                        <?php if($kiemtra['id']) { ?>
                        <p class="mb-2"><?= daungtuyenngay ?> <?= date('d/m/Y', $kiemtra['ngaytao']) ?></p>
                        <input type="hidden" name="id_gioithieu" id="id_gioithieu">
                        <input class="btn-ungtuyen mr-2 ungtuyen-submit"  value="<?= ungtuyenlai ?>" type="submit">
                        <?php } else { ?>
                            <?php if($row_detail['trangthai'] == 1) { ?> 
                                <input class="btn-ungtuyen mr-2 ungtuyen-submit"  value="<?= ungtuyenngay ?>" type="submit">
                            <?php } else { ?> 
                                <input class="btn-ungtuyen mr-2 text-center" value="<?= dahethan ?>" type="text" readonly>
                            <?php } ?>
                            <input type="hidden" name="id_gioithieu" id="id_gioithieu">
                        <?php } ?>
                        <button class="submit-form" name="ungtuyen" style="display:none">Gửi</button>
                    </form>

                    <?php if($_SESSION[$login_member]['role']) { ?>
                    <a class="btn-share" data-toggle="modal" data-target=".exampleModal" href="#">
                        <i class="fas fa-share-alt"></i>
                        <?= chiase ?>
                    </a>
  
                    <?php } ?>
                    <?php } else { ?>
                    <form id="form-ungtuyen" action="" method="post">
                            <?php if($row_detail['trangthai'] == 1) { ?> 
                                <input class="btn-ungtuyen mr-2 ungtuyen-submit" value="<?= ungtuyenngay ?>" type="submit">
                            <?php } else { ?> 
                                <input class="btn-ungtuyen mr-2 text-center" value="<?= dahethan ?>" type="text" readonly>
                            <?php } ?>
                            <input type="hidden" name="id_gioithieu" id="id_gioithieu">
                            <input type="hidden" name="ungtuyen">
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>