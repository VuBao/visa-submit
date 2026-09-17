<div id="footer">
    <div class="footer-top">
        <div class="center custom-footer">
            <div class="footer-1">
                <div class="footer-tit">
                    <div class="logo sss1 d-flex align-items-center">
						<a href="<?= $config_base ?>"><img onerror="this.src='<?= THUMBS ?>/200x70x2/assets/images/noimage.png';" src="<?= THUMBS ?>/200x70x2/<?= UPLOAD_PHOTO_L . $logo['photo'] ?>" /></a>
					</div>
                </div>
                <div class="footer-content"><?= htmlspecialchars_decode($footer['noidung']) ?></div>
                <?php if (count($mxh) > 0) { ?>
                    <ul class="mxh footer-mxh">
                        <?php foreach ($mxh as $mf) { ?>
                            <li>
                                <a href="<?= $mf['link'] ?>" target="_blank">
                                    <img onerror="this.src='<?= THUMBS ?>/30x30x2/assets/images/noimage.png';" src="<?= THUMBS ?>/30x30x1/<?= UPLOAD_PHOTO_L . $mf['photo'] ?>" alt="<?= $mf['ten'] ?>">
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div class="footer-2">
                
                <?php if (isset($chinhsach)) { ?>
                    <div class="footer-tit"><?= dieukhoanvachinhsach ?></div>
                    <ul class="footer-list">
                        <?php for ($i = 0, $count = count($chinhsach); $i < $count; $i++) { ?>
                            <li><a class="text-decoration-none" href="<?= $chinhsach[$i][$sluglang] ?>" title="<?= $chinhsach[$i]['ten'] ?>"><?= $chinhsach[$i]['ten'] ?></a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div class="footer-3">
                <div class="footer-tit">Google map</div>
                <?php /* $addons->setAddons('fanpage-facebook', 'fanpage-facebook', 10); */ ?>
                <div id="footer-map">
                <?= htmlspecialchars_decode($optsetting['toado_iframe']) ?>
                </div>
                
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="center">
            <ul class="statistic d-flex flex-wrap align-items-center justify-content-center">
                <li><?= dangonline ?>: <?= $online ?></li>
                <span>|</span>
                <li><?= trongtuan ?>: <?= $counter['week'] ?></li>
                <span>|</span>
                <li><?= trongthang ?>: <?= $counter['month'] ?></li>
                <span>|</span>
                <li><?= tongtruycap ?>: <?= $counter['total'] ?></li>
            </ul>
        </div>
    </div>
    <?php /*
    <div class="footer-map"></div>
        <div class="center">
            <div class="title-map">
                <?php foreach ($chinhanh as $q => $w): ?>
                    <h2 class="click-map <?php if($q==0) echo 'active';?>" data-id='<?=$w['id']?>' ><?=$w['ten']?></h2>
                <?php endforeach ?>
            </div>
        </div>
        <div class="load-map"></div>
    </div>
    */ ?>
    <?php /* if($source=='index'){ 
        <?=$addons->setAddons('footer-map', 'footer-map', 10);?>
    } */ ?>
</div>