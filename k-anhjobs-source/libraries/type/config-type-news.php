<?php

    /* Dịch vụ */
    $nametype = "tin-tuyen-dung";
    $config['news'][$nametype]['title_main'] = "Tin tuyển dụng";
    $config['news'][$nametype]['dropdown'] = true;
    $config['news'][$nametype]['list'] = true;
    $config['news'][$nametype]['cat'] = false;
    $config['news'][$nametype]['item'] = false;
    $config['news'][$nametype]['sub'] = false;
    $config['news'][$nametype]['tags'] = false;
    $config['news'][$nametype]['view'] = true;
    $config['news'][$nametype]['copy'] = false;
    $config['news'][$nametype]['copy_image'] = false;
    $config['news'][$nametype]['slug'] = true;
    $config['news'][$nametype]['check'] = array("noibat" => "Nổi bật");
    $config['news'][$nametype]['images'] = true;
    $config['news'][$nametype]['show_images'] = true;
    $config['news'][$nametype]['gallery'] = array();
    $config['news'][$nametype]['mota'] = true;
    $config['news'][$nametype]['quoctich'] = true;
    $config['news'][$nametype]['diadiem'] = true;
    $config['news'][$nametype]['ngayvaolam'] = false;
    $config['news'][$nametype]['tinhtrangcutru'] = true;
    $config['news'][$nametype]['tinhthanh'] = true;
    $config['news'][$nametype]['soluong'] = true;
    $config['news'][$nametype]['handangtuyen'] = false;
    $config['news'][$nametype]['luong'] = true;
    $config['news'][$nametype]['thoigianlamviec'] = true;
    $config['news'][$nametype]['congty'] = true;
    $config['news'][$nametype]['luongcoban'] = true;
    $config['news'][$nametype]['noidung'] = true;
    $config['news'][$nametype]['noidung_cke'] = true;
    $config['news'][$nametype]['check1'] = true;
    $config['news'][$nametype]['trangthai'] = true;
    $config['news'][$nametype]['gallery'] = array
    (
        $nametype => array
        (
            "title_main_photo" => "Hình ảnh mô tả",
            "title_sub_photo" => "Hình ảnh",
            "number_photo" => 3,
            "images_photo" => true,
            "cart_photo" => false,
            "avatar_photo" => true,
            "tieude_photo" => true,
            "width_photo" => 540,
            "height_photo" => 540,
            "thumb_photo" => '540x540x1',
            "img_type_photo" => '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF'
        ),
    );
    $config['news'][$nametype]['seo'] = true;
    $config['news'][$nametype]['width'] = 280;
    $config['news'][$nametype]['height'] = 180;
    $config['news'][$nametype]['thumb'] = '100x80x1';
    $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';


    /* Dịch vụ (List) */
    $config['news'][$nametype]['title_main_list'] = "Ngành nghề";
    $config['news'][$nametype]['images_list'] = true;
    $config['news'][$nametype]['show_images_list'] = true;
    $config['news'][$nametype]['slug_list'] = true;
    $config['news'][$nametype]['check_list'] = array("noibat" => "Nổi bật", "gim" => "gim");
    $config['news'][$nametype]['gallery_list'] = array();
    $config['news'][$nametype]['mota_list'] = false;
    $config['news'][$nametype]['mota_cke_list'] = false;
    $config['news'][$nametype]['noidung_list'] = false;
    $config['news'][$nametype]['noidung_cke_list'] = false;
    $config['news'][$nametype]['seo_list'] = true;
    $config['news'][$nametype]['width_list'] = 100;
    $config['news'][$nametype]['height_list'] = 100;
    $config['news'][$nametype]['thumb_list'] = '100x100x1';
    $config['news'][$nametype]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';


    /* Tin tức */
    $nametype = "tin-tuc";
    // $config['news'][$nametype]['title_main'] = "Tin tức";
    // $config['news'][$nametype]['dropdown'] = false;
    // $config['news'][$nametype]['list'] = false;
    // $config['news'][$nametype]['cat'] = false;
    // $config['news'][$nametype]['item'] = false;
    // $config['news'][$nametype]['sub'] = false;
    // $config['news'][$nametype]['tags'] = false;
    // $config['news'][$nametype]['view'] = true;
    // $config['news'][$nametype]['copy'] = false;
    // $config['news'][$nametype]['copy_image'] = false;
    // $config['news'][$nametype]['slug'] = true;
    // $config['news'][$nametype]['check'] = array("noibat" => "Nổi bật");
    // $config['news'][$nametype]['images'] = true;
    // $config['news'][$nametype]['show_images'] = true;
    // $config['news'][$nametype]['gallery'] = array();
    // $config['news'][$nametype]['mota'] = true;
    // $config['news'][$nametype]['noidung'] = true;
    // $config['news'][$nametype]['noidung_cke'] = true;
    // $config['news'][$nametype]['seo'] = true;
    // $config['news'][$nametype]['width'] = 480;
    // $config['news'][$nametype]['height'] = 320;
    // $config['news'][$nametype]['thumb'] = '100x80x1';
    // $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';

    /* Tạm ẩn Thông báo
    $nametype = "thong-bao";
    $config['news'][$nametype]['title_main'] = "Thông báo";
    $config['news'][$nametype]['dropdown'] = true;
    $config['news'][$nametype]['list'] = false;
    $config['news'][$nametype]['cat'] = false;
    $config['news'][$nametype]['item'] = false;
    $config['news'][$nametype]['sub'] = false;
    $config['news'][$nametype]['tags'] = false;
    $config['news'][$nametype]['view'] = false;
    $config['news'][$nametype]['copy'] = false;
    $config['news'][$nametype]['copy_image'] = false;
    $config['news'][$nametype]['slug'] = false;
    $config['news'][$nametype]['check'] = array();
    $config['news'][$nametype]['images'] = false;
    $config['news'][$nametype]['show_images'] = false;
    $config['news'][$nametype]['gallery'] = array();
    $config['news'][$nametype]['mota'] = false;
    $config['news'][$nametype]['noidung'] = false;
    $config['news'][$nametype]['noidung_cke'] = false;
    $config['news'][$nametype]['seo'] = false;
    $config['news'][$nametype]['width'] = 480;
    $config['news'][$nametype]['height'] = 320;
    $config['news'][$nametype]['thumb'] = '100x80x1';
    $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';
    */

    /* Tạm ẩn Tin tức
    $nametype = "tin-tuc-va-su-kien";
    $config['news'][$nametype]['title_main'] = "Tin tức";
    $config['news'][$nametype]['dropdown'] = true;
    $config['news'][$nametype]['list'] = false;
    $config['news'][$nametype]['cat'] = false;
    $config['news'][$nametype]['item'] = false;
    $config['news'][$nametype]['sub'] = false;
    $config['news'][$nametype]['tags'] = false;
    $config['news'][$nametype]['view'] = false;
    $config['news'][$nametype]['copy'] = false;
    $config['news'][$nametype]['copy_image'] = false;
    $config['news'][$nametype]['slug'] = false;
    $config['news'][$nametype]['check'] = array();
    $config['news'][$nametype]['images'] = true;
    $config['news'][$nametype]['show_images'] = true;
    $config['news'][$nametype]['gallery'] = array();
    $config['news'][$nametype]['mota'] = true;
    $config['news'][$nametype]['noidung'] = true;
    $config['news'][$nametype]['noidung_cke'] = true;
    $config['news'][$nametype]['seo'] = true;
    $config['news'][$nametype]['width'] = 480;
    $config['news'][$nametype]['height'] = 320;
    $config['news'][$nametype]['thumb'] = '100x80x1';
    $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';
    */

    /* Tạm ẩn Chính sách
    $nametype = "chinh-sach";
    $config['news'][$nametype]['title_main'] = "Chính sách";
    $config['news'][$nametype]['check'] = array();
    $config['news'][$nametype]['dropdown'] = true;
    $config['news'][$nametype]['view'] = true;
    $config['news'][$nametype]['slug'] = true;
    $config['news'][$nametype]['copy'] = false;
    $config['news'][$nametype]['images'] = true;
    $config['news'][$nametype]['show_images'] = true;
    $config['news'][$nametype]['noidung'] = true;
    $config['news'][$nametype]['noidung_cke'] = true;
    $config['news'][$nametype]['seo'] = true;
    $config['news'][$nametype]['width'] = 480;
    $config['news'][$nametype]['height'] = 320;
    $config['news'][$nametype]['thumb'] = '100x80x1';
    $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';
    */

    /* Hình thức thanh toán */
    $nametype = "hinh-thuc-thanh-toan";
    // $config['news']['hinh-thuc-thanh-toan']['title_main'] = "Hình thức thanh toán";
    // $config['news']['hinh-thuc-thanh-toan']['check'] = array();
    // $config['news']['hinh-thuc-thanh-toan']['mota'] = true;

    /* Dịch vụ */
    $nametype = "dich-vu";
    // $config['news'][$nametype]['title_main'] = "Dịch vụ";
    // $config['news'][$nametype]['dropdown'] = true;
    // $config['news'][$nametype]['list'] = true;
    // $config['news'][$nametype]['cat'] = true;
    // $config['news'][$nametype]['item'] = false;
    // $config['news'][$nametype]['sub'] = false;
    // $config['news'][$nametype]['tags'] = false;
    // $config['news'][$nametype]['view'] = true;
    // $config['news'][$nametype]['copy'] = false;
    // $config['news'][$nametype]['copy_image'] = false;
    // $config['news'][$nametype]['slug'] = true;
    // $config['news'][$nametype]['check'] = array("noibat" => "Nổi bật");
    // $config['news'][$nametype]['images'] = true;
    // $config['news'][$nametype]['show_images'] = true;
    // $config['news'][$nametype]['gallery'] = array();
    // $config['news'][$nametype]['mota'] = true;
    // $config['news'][$nametype]['noidung'] = true;
    // $config['news'][$nametype]['noidung_cke'] = true;
    // $config['news'][$nametype]['seo'] = true;
    // $config['news'][$nametype]['width'] = 480;
    // $config['news'][$nametype]['height'] = 320;
    // $config['news'][$nametype]['thumb'] = '100x80x1';
    // $config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';

    /* Dịch vụ (List) */
    // $config['news'][$nametype]['title_main_list'] = "Dịch vụ cấp 1";
    // $config['news'][$nametype]['images_list'] = true;
    // $config['news'][$nametype]['show_images_list'] = true;
    // $config['news'][$nametype]['slug_list'] = true;
    // $config['news'][$nametype]['check_list'] = array();
    // $config['news'][$nametype]['gallery_list'] = array();
    // $config['news'][$nametype]['mota_list'] = false;
    // $config['news'][$nametype]['mota_cke_list'] = false;
    // $config['news'][$nametype]['noidung_list'] = false;
    // $config['news'][$nametype]['noidung_cke_list'] = false;
    // $config['news'][$nametype]['seo_list'] = true;
    // $config['news'][$nametype]['width_list'] = 320;
    // $config['news'][$nametype]['height_list'] = 240;
    // $config['news'][$nametype]['thumb_list'] = '100x100x1';
    // $config['news'][$nametype]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';

    /* Dịch vụ (Cat) */
    // $config['news'][$nametype]['title_main_cat'] = "Dịch vụ cấp 2";
    // $config['news'][$nametype]['images_cat'] = true;
    // $config['news'][$nametype]['show_images_cat'] = true;
    // $config['news'][$nametype]['slug_cat'] = true;
    // $config['news'][$nametype]['check_cat'] = array();
    // $config['news'][$nametype]['mota_cat'] = false;
    // $config['news'][$nametype]['mota_cke_cat'] = false;
    // $config['news'][$nametype]['noidung_cat'] = false;
    // $config['news'][$nametype]['noidung_cke_cat'] = false;
    // $config['news'][$nametype]['seo_cat'] = true;
    // $config['news'][$nametype]['width_cat'] = 320;
    // $config['news'][$nametype]['height_cat'] = 240;
    // $config['news'][$nametype]['thumb_cat'] = '100x100x1';
    // $config['news'][$nametype]['img_type_cat'] = '.jpg|.gif|.png|.jpeg|.gif|.JPG|.PNG|.JPEG|.Png|.GIF';

    /* Quản lý mục (Không cấp) */
    if(isset($config['news']))
    {
        foreach($config['news'] as $key => $value)
        {
            if(!isset($value['dropdown']) || (isset($value['dropdown']) && $value['dropdown'] == false))
            { 
                $config['shownews'] = 1;
                break;
            }
        }
    }
?>