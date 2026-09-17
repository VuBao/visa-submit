<?php
	include "ajax_config.php";
	
	$id = (isset($_POST['id']) && $_POST['id'] > 0) ? htmlspecialchars($_POST['id']) : 0;
	$message = $d->rawQueryOne("select * from #_message where id = ? limit 0,1", array($id));
    if($message['id']) {
        if($message['type'] == 2) {
            $response['message'] = '<div class="message-right message"><div class="mw-75" title="đã gửi lúc '. date("h:i d:m:Y", $message["ngaytao"]) .'">';
            if($message['message']) {
                $response['message'] .= nl2br($message['message']);
            } else {
                $response['message'] .= '<img src="../'. UPLOAD_FILE_L . $message['photo'] .'" alt="">';
            }
            $response['message'] .= '</div></div>';
        } else {
            $response['message'] = '<div class="message-left message"><div class="mw-75" title="đã gửi lúc '. date("h:i d:m:Y", $message["ngaytao"]) .'">';
            if($message['message']) {
                $response['message'] .= nl2br($message['message']);
            } else {
                $response['message'] .= '<img src="../'. UPLOAD_FILE_L . $message['photo'] .'" alt="">';
            }
            $response['message'] .= '</div></div>';
        }
    }
    $room = $d->rawQueryOne("select * from #_room where id = ?", array($message['id_room']));
    $member = $d->rawQueryOne("select * from #_member where id = ?", array($room['id_member']));

    $response['items_user'] = '<a class="items-user room-'.$member['id'].'" href="index.php?com=chat&act=man&id='.$member['id'].'"><div class="avatar-user"><img src="../'. UPLOAD_FILE_L.$member['avatar'] .'" alt="'.$member['username'].'"></div><div class="text-user"><h2>'. ($member['ten'] ? $member['ten'] : $member['username']) .'</h2><h3> <span class="text-sp-1">'. ($message['type'] == 1 ? '' : 'Bạn: ') .''. ($message['message'] ? $message['message'] : 'đã gửi 1 ảnh') .' </span> - <span>'. $func->khoangcach($message['ngaytao']) .'</span> </h3></div><div class="read-user '. ($message['trangthai'] == 1 && $message['type'] == 1 ? 'active' : '') .'"></div></a>';

    echo json_encode($response);
    die();
?>

