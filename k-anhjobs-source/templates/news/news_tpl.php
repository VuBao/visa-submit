<div class="center">
<div class="title-main d-none">
    <h1><?=(@$title_cat!='')?$title_cat:@$title_crumb?></h1>
    <p><?=$slogan['ten']?></p>
</div>

<div class="w-clear">
    <div class="container__tin-tuc">
    <?php if(count($news)>0) { ?>
        <?php foreach($news as $n) { ?>
            <?php $category = $d->rawQuery("select ten$lang as ten, tenkhongdauvi, tenkhongdauen, id from #_news_cat where id_list = ? and hienthi > 0 order by stt,id desc",array($n['id'])); ?>
            <div class="item-news">
                <div class="pics-news">
                    <a class="text-decoration-none scale-img" href="<?=$n[$sluglang]?>" title="<?=$n['ten'.$lang]?>">
                        <img onerror="this.src='<?=THUMBS?>/599x360x2/assets/images/noimage.png';" src="<?=THUMBS?>/599x360x1/<?=UPLOAD_NEWS_L.$n['photo']?>" alt="<?=$n['ten'.$lang]?>">
                    </a>
                </div>
                <div class="content-news">
                    <div class="category-news">
                        <a href="<?= $category ? $category[$sluglang] : 'javascript:window.location.reload(true);' ?>" title="<?= $category ? $category['ten'] : $title_crumb ?>"><?= $category ? $category['ten'] : $title_crumb ?></a>
                        <span> &nbsp; &#8226; &nbsp; <?=date("d/m/Y h:i A",$n['ngaytao'])?></span>
                    </div>
                    <div class="name-news">
                        <a class="text-decoration-none scale-img" href="<?=$n[$sluglang]?>" title="<?=$n['ten'.$lang]?>">
                            <?=$n['ten'.$lang]?>
                        </a>
                    </div>
                    
                    <div class="description-news">
                        <p><?=$n['mota'.$lang]?></p>
                    </div>
                    <div class="link-news">
                        <a href="<?=$n[$sluglang]?>" title="<?=$n['ten'.$lang]?>">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?> 
        <div class="alert alert-danger-custom" role="alert">
            <strong><?=khongtimthayketqua?></strong>
        </div>
    <?php } ?>
    </div>
    <div class="clear"></div>
    <div class="pagination-home"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
</div>
</div>

