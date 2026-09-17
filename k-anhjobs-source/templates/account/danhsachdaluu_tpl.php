<div class="w-clear cover">
    <div class="row small-gutters center">
        <div class="col-lg-3 col-3--custom">
            <?php include TEMPLATE . LAYOUT . "sidebar.php"; ?>
        </div>
        <div class="right-sibar col-lg-9 col-9--custom profile">
            <div class="content-primary">
                <div class="job-box">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-6 mt-3 mt-lg-0">
                            <h3><?= danhsachcongviecdaluu ?> ( <?= $total ?> <?= ketqua ?> )</h3>
                        </div>
                       
                    </div>

                    <?php if(count($news)) { ?>
                    <?php foreach ($news as $n) { ?>
                    <?php $new_detail = $d->rawQueryOne("select * from #_news where id = ?", array($n['id_news'])) ?>
                    <div class="job">
                        <div class="grid-job">
                            <div class="job-img">
                                <a href="<?= $new_detail[$sluglang] ?>" class="scale-img">
                                    <img onerror="this.src='<?= THUMBS ?>/200x200x2/assets/images/noimage.png';"
                                        src="<?= THUMBS ?>/200x200x1/<?= UPLOAD_NEWS_L . $new_detail['photo'] ?>"
                                        alt="<?= $new_detail['ten'.$lang] ?>">
                                </a>
                            </div>
                            <div class="flex-grow-1 job-detail">
                                <div class="job-head d-lg-flex justify-content-between mb-3">
                                    <div class="job-left">
                                        <a href="<?= $new_detail[$sluglang] ?>"
                                            title="<?= $new_detail['ten'.$lang] ?>">#<?= $new_detail['id'] ?></a>
                                        <h3> <a href="<?= $new_detail[$sluglang] ?>"
                                                title="<?= $new_detail['ten'.$lang] ?>"
                                                class="text-split text-split-2"><?= $new_detail['ten'.$lang] ?></a>
                                        </h3>
                                    </div>
                                    <div class="job-right">
                                        <div class="d-flex">
                                            <?php if(isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active']) { ?>
                                            <?php $check = $d->rawQuery("select * from #_ungtuyen where id_news = ? and id_member = ?", array($new_detail['id'], $_SESSION[$login_member]['id']) ) ?>
                                            <?php if($check) { ?>
                                            <a class="btn-ungtuyen mr-2" href="<?= $new_detail[$sluglang] ?>"><?= daungtuyen ?></a>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2" href="<?= $new_detail[$sluglang] ?>"><?= xemchitiet ?></a>
                                            <?php } ?>

                                            <?php $checkwl = $d->rawQueryOne("select * from #_uuthich where id_news = ? and id_member = ?", array($new_detail['id'], $_SESSION[$login_member]['id'])) ?>
                                            <?php if($checkwl) { ?>
                                            <a class="btn-whistlist active wishlist"
                                                data-news="<?= $new_detail['id'] ?>" href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } else { ?>
                                            <a class="btn-whistlist wishlist" data-news="<?= $new_detail['id'] ?>"
                                                href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2" href="<?= $new_detail[$sluglang] ?>"><?= xemchitiet ?></a>
                                            <a class="btn-whistlist" href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="job-info d-lg-flex justify-content-between align-items-baseline">
                                    <div class="d-lg-flex justify-content-between">
                                        <div class="job-price d-flex align-items-baseline mb-2 mb-md-0">
                                            <img src="assets/images/money.png" alt="Tiền thưởng">
                                            <strong><?= $new_detail['luong'] ?></strong>
                                            <span> <?= yen ?> / <?= thang ?></span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="job-time d-lg-flex align-items-start">
                                    <span class="d-flex align-items-center mr-2 time-left">
                                        <?= thoigianlamviec ?>
                                    </span>
                                    <span class="d-flex flex-column cl-accent time-center">
                                        <strong><?= $new_detail['thoigianlamviec'] ?></strong>
                                    </span>
                                    <div class="d-flex hr-jobs__icon ml-auto pl-lg-2">
                                        <?php if($new_detail['tienthuong'] == 1) { ?>
                                        <img src="assets/images/money.png" alt="<?= tienthuong ?>">
                                        <?php } ?>
                                        <?php if($new_detail['1phong'] == 1) { ?>
                                        <img src="assets/images/1phong.png" alt="<?= nguoi1phong ?>">
                                        <?php } ?>
                                        <?php if($new_detail['lamthem'] == 1) { ?>
                                        <img src="assets/images/tangca.png" alt="<?= colamthem ?>">
                                        <?php } ?>
                                        <?php if($new_detail['trocap'] == 1) { ?>
                                        <img src="assets/images/trocap.png" alt="<?= trocap ?>">
                                        <?php } ?>
                                        <?php if($new_detail['tangluong'] == 1) { ?>
                                        <img src="assets/images/tangluong.png" alt="<?= tangluong ?>">
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="job-sumary d-flex align-items-center justify-content-between">
                            <div class="text-truncate mr-5" id="">
                                #
                            </div>
                            <div class="job-meta">
                                <div>
                                <i class="far fa-calendar-alt"></i>
                                <span class="d-block mx-2"> 
                                <?php $category = $d->rawQueryOne("select ten$lang as ten from #_news_list where id = ?", array($new_detail['id_list'])); ?>
                                    <?= $category['ten'] ?>    
                                </span>
                                </div>
                                <div>
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="d-block mx-2"><?= $new_detail['diadiem'] ?></span>
                                </div>
                              <div>
                              <i class="fas fa-eye"></i>
                                <span class="d-block ml-2"><?= $new_detail['luotxem'] ?> <?= luotxem ?></span>
                              </div>
                           
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="pagination-home"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
                    <?php } else { ?>
                    <p><?= khongcodulieudehienthi ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>