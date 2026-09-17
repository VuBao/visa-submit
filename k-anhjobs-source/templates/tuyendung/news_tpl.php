<div class="w-clear">
    <div class=" center">

        <div>
            <div class="content-primary">
                <div class="job-box">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-6 mt-3 mt-lg-0">
                            <h3><?= danhsachcongviec ?> ( <?= $total ?> <?= ketqua ?> )</h3>
                        </div>
                        <div class="col-lg-3 d-md-flex justify-content-end ml-md-auto mt-2 mt-md-0">
                            
                            <select class="form-control" id="filter-vitri" data-url="<?= $type ?>">
                                <option value="0" <?= !isset($_GET['vitri']) ? 'selected' : '' ?>><?= tatca ?></option>
                                <?php foreach($vitri as $vt) { ?> 
                                    <option value="<?= $vt['id'] ?>" <?= isset($_GET['vitri']) && $_GET['vitri'] == $vt['id'] ? 'selected' : '' ?>><?= $vt['ten'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <?php foreach ($news as $n) { ?>
                    <div class="job">
                        <div class="grid-job">
                            <div class="job-img">
                                <a href="<?= $n[$sluglang] ?>" class="scale-img">
                                    <img onerror="this.src='<?= THUMBS ?>/500x500x2/assets/images/noimage.png';"
                                        src="<?= THUMBS ?>/500x500x1/<?= UPLOAD_NEWS_L . $n['photo'] ?>"
                                        alt="<?= $n['ten'.$lang] ?>">
                                </a>
                            </div>
                            <div class="flex-grow-1 job-detail">
                                <div class="job-head d-lg-flex justify-content-between mb-3">
                                    <div class="job-left">
                                        <a class="d-flex" href="<?= $n[$sluglang] ?>" title="<?= $n['ten'.$lang] ?>">
                                            #<?= $n['id'] ?>
                                            <div class="job-meta ml-3">
                                                <div>
                                                    <i class="far fa-calendar-alt"></i>
                                                    <span class="d-block mx-2">
                                                        <?php $category = $d->rawQueryOne("select ten$lang as ten from #_news_list where id = ?", array($n['id_list'])); ?>
                                                        <?= $category['ten'] ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span class="d-block mx-2"><?= $n['diadiem'] ?></span>
                                                </div>


                                            </div>
                                        </a>
                                        <h3> <a href="<?= $n[$sluglang] ?>" title="<?= $n['ten'.$lang] ?>"
                                                class="text-split text-split-2"><?= $n['ten'.$lang] ?></a> </h3>
                                    </div>
                                    <div class="job-right">
                                        <div class="d-flex">
                                            <?php if(isset($_SESSION[$login_member]) && $_SESSION[$login_member]['active']) { ?>
                                            <?php $check = $d->rawQuery("select * from #_ungtuyen where id_news = ? and id_member = ?", array($n['id'], $_SESSION[$login_member]['id']) ) ?>
                                            <?php if($check) { ?>
                                            <a class="btn-ungtuyen mr-2"
                                                href="<?= $n[$sluglang] ?>"><?= daungtuyen ?></a>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2"
                                                href="account/ung-tuyen-ngay?id_tuyendung=<?= $n['id'] ?>"><?= ungtuyenngay ?></a>
                                            <?php } ?>

                                            <?php $checkwl = $d->rawQueryOne("select * from #_uuthich where id_news = ? and id_member = ?", array($n['id'], $_SESSION[$login_member]['id'])) ?>
                                            <?php if($checkwl) { ?>
                                            <a class="btn-whistlist active wishlist" data-news="<?= $n['id'] ?>"
                                                href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } else { ?>
                                            <a class="btn-whistlist wishlist" data-news="<?= $n['id'] ?>"
                                                href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <a class="btn-ungtuyen mr-2"
                                                href="account/ung-tuyen-ngay?id_tuyendung=<?= $n['id'] ?>"><?= ungtuyenngay ?></a>
                                            <a class="btn-whistlist" href="account/dang-nhap">
                                                <i class="far fa-heart"></i>
                                            </a>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-rows justify-content-between align-items-center  mt-2">
                                    <div class="motangan text-split text-split-2">
                                        <?= nl2br($n['mota'.$lang]) ?>
                                    </div>
                                  
                                </div>
                                <div class="job-info d-lg-flex justify-content-between align-items-baseline">
                                    <div class="d-lg-flex justify-content-between">
                                        <div class="job-price d-flex align-items-baseline mb-2 mb-md-0">
                                            <img src="assets/images/money.png" alt="Tiền thưởng">
                                            <strong class="ml-2"><?= $n['luong'] ?></strong>
                                           
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="job-time d-lg-flex align-items-start">
                                    <span class="d-flex align-items-center mr-2 time-left">
                                        <?= thoigianlamviec ?>
                                    </span>
                                    <span class="d-flex flex-column cl-accent time-center">
                                        <strong><?= $n['thoigianlamviec'] ?></strong>
                                    </span>
                                    <div class="d-flex hr-jobs__icon ml-auto pl-lg-2">
                                        <?php if($n['tienthuong'] == 1) { ?>
                                        <img src="assets/images/money.png" alt="<?= tienthuong ?>" title="<?= tienthuong ?>">
                                        <?php } ?>
                                        <?php if($n['1phong'] == 1) { ?>
                                        <img src="assets/images/1phong.png" alt="<?= nguoi1phong ?>"  title="<?= nguoi1phong ?>">
                                        <?php } ?>
                                        <?php if($n['lamthem'] == 1) { ?>
                                        <img src="assets/images/tangca.png" alt="<?= colamthem ?>" title="<?= colamthem ?>">
                                        <?php } ?>
                                        <?php if($n['trocap'] == 1) { ?>
                                        <img src="assets/images/trocap.png" alt="<?= trocap ?>" title="<?= trocap ?>">
                                        <?php } ?>
                                        <?php if($n['tangluong'] == 1) { ?>
                                        <img src="assets/images/tangluong.png" alt="<?= tangluong ?>" title="<?= tangluong ?>">
                                        <?php } ?>

                                    </div>
                                </div>
                               
                                
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="pagination-home"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
                </div>
            </div>
        </div>
    </div>
</div>