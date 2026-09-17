<div id="banner">
	<div class="center">
		<div class="flex-banner">
			<div class="language-box">
				<div class="switch-toggle switch-3 switch-candy">

					<input id="on" class="language-toggle" name="state-d" value="vn" type="radio" <?= $lang == 'vi' ? 'checked' : '' ?> />
					<label for="on" onclick="">VN</label>

					<input id="na" class="language-toggle" name="state-d" value="en" type="radio" <?= $lang == 'en' ? 'checked' : '' ?> />
					<label for="na" onclick="">EN</label>

					<input id="off" class="language-toggle" name="state-d" value="jp" type="radio" <?= $lang == 'jp' ? 'checked' : '' ?> />
					<label for="off" onclick="">JP</label>

				</div>
			</div>
			<div class="d-flex justify-content-center">
				<?php if ($logo) { ?>
					<div class="logo sss d-flex align-items-center">
						<a href="<?= $config_base ?>"><img onerror="this.src='<?= THUMBS ?>/200x70x2/assets/images/noimage.png';" src="<?= THUMBS ?>/200x70x2/<?= UPLOAD_PHOTO_L . $logo['photo'] ?>" /></a>
					</div>
				<?php } ?>
			</div>
			<div class="d-flex justify-content-end mxh-header123">
				<?php if (count($mxh1) > 0) { ?>
					<ul class="mxh banner-mxh">
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

		</div>
	</div>
</div>