<div class="center bg-white py-3 my-2">
<div class="title-main d-none">
	<h1><?= $static['ten'] ?></h1>
	<p><?= $slogan['ten'] ?></p>
</div>
<div class="content w-clear"><?= (isset($static['noidung']) && $static['noidung'] != '') ? htmlspecialchars_decode($static['noidung']) : '' ?></div>
<div class="share">
	<b><?= chiase ?>:</b>
	<div class="social-plugin w-clear">
		<div class="addthis_inline_share_toolbox_qj48"></div>
		<div class="zalo-share-button" data-href="<?= $func->getCurrentPageURL() ?>" data-oaid="<?= ($optsetting['oaidzalo'] != '') ? $optsetting['oaidzalo'] : '579745863508352884' ?>" data-layout="1" data-color="blue" data-customize=false></div>
	</div>
</div>
</div>
