<?php if(isset($popup) && $popup['hienthi'] == 1) { ?>
	<!-- Modal popup -->
	<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<a href="<?=$popup['link']?>"><img src="<?=THUMBS?>/800x530x1/<?=UPLOAD_PHOTO_L.$popup['photo']?>" alt="Popup"></a>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Modal notify -->
<div class="modal modal-custom fade" id="popup-notify" tabindex="-1" role="dialog" aria-labelledby="popup-notify-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-top modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="popup-notify-label"><?=thongbao?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?=thoat?></button>
			</div>
		</div>
	</div>
</div>

<?php if(isset($config['cart'])){ ?>
<!-- Modal cart -->
<div class="modal fade" id="popup-cart" tabindex="-1" role="dialog" aria-labelledby="popup-cart-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-top modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="popup-cart-label"></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>
<?php } ?>


<?php if($_SESSION[$login_member]['role']) { ?>
<div class="modal fade exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-share" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title-share" id="exampleModalLabel"><?= linkgioithieu ?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="p-modals-share mb-4"> <?= sub1linkgioithieu ?> </p>
				<div class="form-group">
                
                <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">
                        <label for="link" id="copy"><i class="far fa-copy"></i></label>
                        <input type="text" id="link" name="link" value="<?= $config_base ?>share/<?= $func->mahoa_share($_SESSION[$login_member]['id'].','.$row_detail['id']) ?>" readonly/>
                    </div>
                </div>
				<p  class="p-modals-share mb-3">
					<?= sub2linkgioithieu ?>
				</p>
				<p  class="p-modals-share"><?= sub3linkgioithieu ?></p>
            </div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modals-share" data-dismiss="modal">OK</button>
				
			</div>
		</div>
	</div>
</div>
<?php } ?>

<div class="loadding hide">
	<img src="assets/images/images/loadding.gif" alt="">
</div>