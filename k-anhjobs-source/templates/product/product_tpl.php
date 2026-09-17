
<div class="title-main">
    <h1><?=(@$title_cat!='')?$title_cat:@$title_crumb?></h1>
    <p><?=$slogan['ten']?></p>
</div>

<div class="w-clear">
    <?php if(isset($product) && count($product) > 0) { ?>
        <?php for($i=0,$count=count($product); $i < $count; $i++) { ?>
            <div class="box-product">
                <div class="pic-product">
                    <a class="text-decoration-none scale-img" href="<?=$product[$i][$sluglang]?>" title="<?=$product[$i]['ten']?>">
                        <img onerror="this.src='<?=THUMBS?>/540x540x2/assets/images/noimage.png';" src="<?=THUMBS?>/540x540x1/<?=UPLOAD_PRODUCT_L.$product[$i]['photo']?>" alt="<?=$product[$i]['ten']?>"/>
                    </a>
                </div>
                <div class="content-product">
                    <h3 class="name-product">
                        <a class="text-decoration-none text-split text-split-2" href="<?=$product[$i][$sluglang]?>" title="<?=$product[$i]['ten']?>">
                            <?=$product[$i]['ten']?>
                        </a>
                    </h3>
                    <div class="price-product d-flex flex-wrap align-items-center justify-content-center">
                        <?php if($product[$i]['giakm']) { ?>
                            <span class="price-new"><?=$func->format_money($product[$i]['giamoi'])?></span>
                            <span class="price-old del"><?=$func->format_money($product[$i]['gia'])?></span>
                            <span class="price-per"><?='-'.$product[$i]['giakm'].'%'?></span>
                        <?php } else { ?>
                            <div><?=gia?>:&nbsp;</div><span class="price-new"><?=($product[$i]['gia']) ? $func->format_money($product[$i]['gia']) : lienhe?></span>
                        <?php } ?>
                    </div>
                    
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-danger-custom" role="alert">
            <strong><?=khongtimthayketqua?></strong>
        </div>
    <?php } ?>
    <div class="clear"></div>
    <div class="pagination-home mgt-25"><?=(isset($paging) && $paging != '') ? $paging : ''?></div>
</div>