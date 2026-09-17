<div class="box-index-re">
<?php if (count($slider)) { ?>
    <div class="center mt-2">
    <div class="slideshow">
        <p class="control-slideshow prev-slideshow transition"><i class="fas fa-chevron-left"></i></p>
        <div class="owl-carousel owl-theme owl-slideshow">
            <?php for ($i = 0, $count = count($slider); $i < $count; $i++) { ?>
                <div>
                    <a href="<?= $slider[$i]['link'] ?>" target="_blank" title="<?= $slider[$i]['ten'] ?>"><img onerror="this.src='<?= THUMBS ?>/2000x750x1/assets/images/noimage.png';" src="<?= THUMBS ?>/2000x750x1/<?= UPLOAD_PHOTO_L . $slider[$i]['photo'] ?>" alt="<?= $slider[$i]['ten'] ?>" title="<?= $slider[$i]['ten'] ?>" /></a>
                </div>
            <?php } ?>
        </div>
        <p class="control-slideshow next-slideshow transition"><i class="fas fa-chevron-right"></i></p>
    </div>
    </div>
  
<?php } ?>