<?php
if(!defined('SOURCES')) die("Error");

/* Cấu hình đường dẫn trả về */
$strUrl = "";
$strUrl .= (isset($_REQUEST['keyword'])) ? "&keyword=".htmlspecialchars($_REQUEST['keyword']) : "";
$strUrl .= (isset($_REQUEST['duyetnguyenvong'])) ? "&duyetnguyenvong=".htmlspecialchars($_REQUEST['duyetnguyenvong']) : "";

switch($act)
{
    case "man":
        get_items();
        $template = "duyetnguyenvong/man/items";
        break;

    case "duyet":
        duyet();
        break;

    case "boduyet":
        boduyet();
        break;

    case "delete":
        delete_item();
        break;

    default:
        $template = "404";
}

/* Get items */
function get_items()
{
    global $d, $func, $strUrl, $curPage, $items, $paging;
    
    $where = "role = 0 and trinhdotiengnhat is not null and trinhdotiengnhat != ''";
    $params = array();

    if(isset($_REQUEST['keyword']))
    {
        $keyword = htmlspecialchars($_REQUEST['keyword']);
        $where .= " and (username LIKE ? or ten LIKE ? or email LIKE ? or tinhmongmuon LIKE ? or nganhnghemongmuon LIKE ?)";
        array_push($params, "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%");
    }

    $duyetnguyenvong = (isset($_REQUEST['duyetnguyenvong'])) ? (int)$_REQUEST['duyetnguyenvong'] : 0; // default 0: pending
    if ($duyetnguyenvong == 1) {
        $where .= " and duyetnguyenvong = 1";
    } else if ($duyetnguyenvong == 2) {
        // all
    } else {
        // pending (0)
        $where .= " and duyetnguyenvong = 0";
    }

    $per_page = 10;
    $startpoint = ($curPage * $per_page) - $per_page;
    $limit = " limit ".$startpoint.",".$per_page;
    
    $sql = "select * from #_member where $where order by stt,id desc $limit";
    $items = $d->rawQuery($sql, $params);
    
    $sqlNum = "select count(*) as 'num' from #_member where $where order by stt,id desc";
    $count = $d->rawQueryOne($sqlNum, $params);
    $total = $count['num'];
    
    $url = "index.php?com=duyetnguyenvong&act=man";
    if(isset($_REQUEST['keyword'])) $url .= "&keyword=".htmlspecialchars($_REQUEST['keyword']);
    if(isset($_REQUEST['duyetnguyenvong'])) $url .= "&duyetnguyenvong=".htmlspecialchars($_REQUEST['duyetnguyenvong']);
    
    $paging = $func->pagination($total,$per_page,$curPage,$url);
}

function duyet()
{
    global $d, $func, $curPage;
    
    $id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
    $duyetnguyenvong = (isset($_REQUEST['duyetnguyenvong'])) ? (int)$_REQUEST['duyetnguyenvong'] : 0;
    
    if($id) {
        $data['duyetnguyenvong'] = 1;
        $d->where('id', $id);
        $d->update("member", $data);
        $func->transfer("Duyệt nguyện vọng thành công", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage);
    } else {
        $func->transfer("Không nhận được dữ liệu", "index.php?com=duyetnguyenvong&act=man&p=".$curPage, false);
    }
}

function boduyet()
{
    global $d, $func, $curPage;
    
    $id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
    $duyetnguyenvong = (isset($_REQUEST['duyetnguyenvong'])) ? (int)$_REQUEST['duyetnguyenvong'] : 0;
    
    if($id) {
        $data['duyetnguyenvong'] = 0;
        $d->where('id', $id);
        $d->update("member", $data);
        $func->transfer("Bỏ duyệt nguyện vọng thành công", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage);
    } else {
        $func->transfer("Không nhận được dữ liệu", "index.php?com=duyetnguyenvong&act=man&p=".$curPage, false);
    }
}

/* Delete expectation fields */
function delete_item()
{
    global $d, $func, $curPage;

    $id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
    $duyetnguyenvong = (isset($_REQUEST['duyetnguyenvong'])) ? (int)$_REQUEST['duyetnguyenvong'] : 0;

    if($id)
    {
        $data['duyetnguyenvong'] = 0;
        $data['trinhdotiengnhat'] = null;
        $data['tinhmongmuon'] = null;
        $data['nganhnghemongmuon'] = null;
        $data['noidungmongmuon'] = null;
        $data['mucluongmongmuon'] = null;
        $data['thoigianchuyenviec'] = null;
        $data['zalo_fb_line'] = null;
        $data['zalo_fb_line_id'] = null;
        $data['mongmuonkhac'] = null;
        
        $d->where('id', $id);
        if($d->update('member', $data)) {
            $func->transfer("Xóa nguyện vọng thành công", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage);
        } else {
            $func->transfer("Xóa nguyện vọng bị lỗi", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage, false);
        }
    }
    elseif(isset($_GET['listid']))
    {
        $listid = explode(",",$_GET['listid']);
        
        for($i=0;$i<count($listid);$i++)
        {
            $id = htmlspecialchars($listid[$i]);
            $data['duyetnguyenvong'] = 0;
            $data['trinhdotiengnhat'] = null;
            $data['tinhmongmuon'] = null;
            $data['nganhnghemongmuon'] = null;
            $data['noidungmongmuon'] = null;
            $data['mucluongmongmuon'] = null;
            $data['thoigianchuyenviec'] = null;
            $data['zalo_fb_line'] = null;
            $data['zalo_fb_line_id'] = null;
            $data['mongmuonkhac'] = null;
            
            $d->where('id', $id);
            $d->update('member', $data);
        }
        $func->transfer("Xóa nguyện vọng thành công", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage);
    }
    else {
        $func->transfer("Không nhận được dữ liệu", "index.php?com=duyetnguyenvong&act=man&duyetnguyenvong=".$duyetnguyenvong."&p=".$curPage, false);
    }
}
?>
