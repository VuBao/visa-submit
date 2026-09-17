<?php
	include "ajax_config.php";
	
	$id = (isset($_POST['id']) && $_POST['id'] > 0) ? htmlspecialchars($_POST['id']) : 0;
	$message = $d->rawQueryOne("select * from #_message where id = ? limit 0,1", array($id));
?>

<?php if($message['id']) { ?>
<?php if($message['type'] == 1) { ?>
<div class="message-right message">
    <div class="mw-75" title="đã gửi lúc <?= date("h:i d:m:Y", $message['ngaytao']) ?>">
        <?php if($message['message']) { ?>
        <?= nl2br($message['message']) ?>
        <?php } else { ?>
        <img src="<?= UPLOAD_FILE_L . $message['photo'] ?>" alt="">
        <?php } ?>
    </div>
</div>
<?php } else { ?>
<div class="message-left message">
    <div class="mw-75" title="đã gửi lúc <?= date("h:i d:m:Y", $message['ngaytao']) ?>">
        <?php if($message['message']) { ?>
        <?= nl2br($message['message']) ?>
        <?php } else { ?>
        <img src="<?= UPLOAD_FILE_L . $message['photo'] ?>" alt="">
        <?php } ?>
    </div>
</div>
<?php } ?>
<?php } ?>