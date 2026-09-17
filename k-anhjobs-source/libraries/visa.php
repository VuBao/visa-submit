<?php
if (!defined('LIBRARIES')) die('Error');

function visa_escape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function visa_document_definitions()
{
    return array(
        'residence_card_front' => array('ja'=>'在留カード・表面','vi'=>'Thẻ ngoại kiều - mặt trước','required'=>true),
        'residence_card_back' => array('ja'=>'在留カード・裏面','vi'=>'Thẻ ngoại kiều - mặt sau','required'=>true),
        'passport_vietnam' => array('ja'=>'パスポート・身分事項ページ','vi'=>'Hộ chiếu Việt Nam - trang thông tin','required'=>true),
        'passport_residence_status' => array('ja'=>'パスポート・在留資格ページ','vi'=>'Hộ chiếu - trang tư cách lưu trú Nhật Bản','required'=>true),
        'insurance_front' => array('ja'=>'保険証・表面','vi'=>'Thẻ bảo hiểm - mặt trước','required'=>true),
        'insurance_back' => array('ja'=>'保険証・裏面','vi'=>'Thẻ bảo hiểm - mặt sau','required'=>true),
        'sankyu_senmonkyu' => array('ja'=>'三級・専門級','vi'=>'Chứng chỉ SANKYU hoặc SENMONKYU','required'=>false),
        'tokutei_certificate' => array('ja'=>'特定技能合格証','vi'=>'Chứng chỉ Tokutei chuyên ngành','required'=>true),
        'gensen' => array('ja'=>'源泉徴収票','vi'=>'Phiếu khấu trừ thuế Gensen','required'=>true),
        'tax_certificate' => array('ja'=>'課税証明書','vi'=>'Giấy chứng nhận thuế năm gần nhất','required'=>true),
        'tax_payment_certificate' => array('ja'=>'納税証明書','vi'=>'Giấy chứng nhận đã đóng thuế','required'=>true),
        'juminhyo_mynumber' => array('ja'=>'マイナンバー付の住民票','vi'=>'Juminhyo có MyNumber','required'=>true),
        'nenkin_record' => array('ja'=>'年金記録照会','vi'=>'Giấy tra cứu lịch sử Nenkin','required'=>true),
        'insured_record_nofu2' => array('ja'=>'被保険者記録照会（納付II）','vi'=>'Lịch sử đóng Nenkin','required'=>true),
        'health_check' => array('ja'=>'健康診断書','vi'=>'Giấy khám sức khỏe','required'=>true),
        'photo_3x4' => array('ja'=>'証明写真 3×4','vi'=>'Ảnh thẻ 3×4','required'=>true),
        'kokumin_payment' => array('ja'=>'国民健康保険料納付証明書','vi'=>'Giấy đóng bảo hiểm quốc dân','required'=>false),
        'student_documents' => array('ja'=>'留学生の追加書類','vi'=>'Giấy tờ bổ sung cho du học sinh','required'=>false)
    );
}

function visa_csrf_token($scope)
{
    if (empty($_SESSION['visa_csrf'][$scope])) $_SESSION['visa_csrf'][$scope] = bin2hex(random_bytes(32));
    return $_SESSION['visa_csrf'][$scope];
}

function visa_verify_csrf($scope, $token)
{
    return !empty($_SESSION['visa_csrf'][$scope]) && is_string($token) && hash_equals($_SESSION['visa_csrf'][$scope], $token);
}

function visa_rotate_csrf($scope)
{
    unset($_SESSION['visa_csrf'][$scope]);
}

function visa_storage_root()
{
    global $config;
    return rtrim($config['visa']['storage_path'], DIRECTORY_SEPARATOR);
}

function visa_ensure_storage_root()
{
    $root = visa_storage_root();
    if (!is_dir($root) && !mkdir($root, 0700, true)) throw new RuntimeException('Không thể tạo nơi lưu hồ sơ.');
    if (!is_writable($root)) throw new RuntimeException('Nơi lưu hồ sơ không thể ghi.');
    return $root;
}

function visa_safe_original_name($name)
{
    $name = str_replace(array("\0", '/', '\\'), '', (string)$name);
    $name = preg_replace('/[\x00-\x1F\x7F]+/u', '', $name);
    return mb_substr(trim($name), 0, 255, 'UTF-8');
}

function visa_validate_upload($file)
{
    global $config;
    if (!isset($file['error']) || is_array($file['error'])) throw new RuntimeException('Upload file không hợp lệ.');
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) throw new RuntimeException('File vượt quá giới hạn dung lượng máy chủ.');
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload file không hợp lệ.');
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) throw new RuntimeException('File upload không hợp lệ.');
    if ((int)$file['size'] <= 0 || (int)$file['size'] > (int)$config['visa']['max_file_size']) throw new RuntimeException('File vượt quá 10 MB.');

    $original = visa_safe_original_name($file['name']);
    if ($original === '' || preg_match('/(^|\.)(php\d*|phtml|phar|htaccess)(\.|$)/i', strtolower($original))) throw new RuntimeException('Tên file không được phép.');
    $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($extension, $config['visa']['allowed_extensions'], true)) throw new RuntimeException('Phần mở rộng không được hỗ trợ.');

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $mimeExtensions = array('image/jpeg'=>array('jpg','jpeg'),'image/png'=>array('png'),'image/webp'=>array('webp'),'application/pdf'=>array('pdf'));
    if (!in_array($mime, $config['visa']['allowed_mime_types'], true) || !isset($mimeExtensions[$mime]) || !in_array($extension, $mimeExtensions[$mime], true)) throw new RuntimeException('MIME và phần mở rộng không hợp lệ.');
    return array('original_name'=>$original,'extension'=>$extension,'mime_type'=>$mime,'file_size'=>(int)$file['size']);
}

function visa_store_upload($file, $applicationId, $documentType)
{
    $definitions = visa_document_definitions();
    if (!isset($definitions[$documentType])) throw new RuntimeException('Loại giấy tờ không hợp lệ.');
    $meta = visa_validate_upload($file);
    $root = visa_ensure_storage_root();
    $relativeDirectory = (int)$applicationId . DIRECTORY_SEPARATOR . $documentType;
    $directory = $root . DIRECTORY_SEPARATOR . $relativeDirectory;
    if (!is_dir($directory) && !mkdir($directory, 0700, true)) throw new RuntimeException('Không thể tạo thư mục hồ sơ.');
    $storedName = bin2hex(random_bytes(24)) . '.' . $meta['extension'];
    $absolutePath = $directory . DIRECTORY_SEPARATOR . $storedName;
    if (!move_uploaded_file($file['tmp_name'], $absolutePath)) throw new RuntimeException('Không thể lưu file.');
    chmod($absolutePath, 0600);
    $meta['stored_name'] = $storedName;
    $meta['storage_path'] = str_replace(DIRECTORY_SEPARATOR, '/', $relativeDirectory . DIRECTORY_SEPARATOR . $storedName);
    $meta['absolute_path'] = $absolutePath;
    return $meta;
}

function visa_resolve_storage_path($relativePath)
{
    $normalized = str_replace('\\', '/', (string)$relativePath);
    if ($normalized === '' || $normalized[0] === '/' || strpos($normalized, '../') !== false || strpos($normalized, "\0") !== false) return false;
    $root = visa_ensure_storage_root();
    $realRoot = realpath($root);
    $realFile = realpath($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalized));
    if ($realRoot === false || $realFile === false || strpos($realFile, $realRoot . DIRECTORY_SEPARATOR) !== 0 || !is_file($realFile)) return false;
    return $realFile;
}

function visa_normalize_document_files($input)
{
    $files = array();
    if (empty($input['name']) || !is_array($input['name'])) return $files;
    foreach ($input['name'] as $type=>$name) {
        $files[$type] = array('name'=>$name,'type'=>isset($input['type'][$type])?$input['type'][$type]:'','tmp_name'=>isset($input['tmp_name'][$type])?$input['tmp_name'][$type]:'','error'=>isset($input['error'][$type])?$input['error'][$type]:UPLOAD_ERR_NO_FILE,'size'=>isset($input['size'][$type])?$input['size'][$type]:0);
    }
    return $files;
}

function visa_generate_application_code($d)
{
    for ($attempt=0; $attempt<8; $attempt++) {
        $code = 'EH-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $exists = $d->rawQueryOne('select id from #_visa_applications where application_code = ? limit 1', array($code));
        if (empty($exists['id'])) return $code;
    }
    throw new RuntimeException('Không thể tạo mã hồ sơ.');
}

function visa_require_admin_permission()
{
    global $func, $config;
    if (!isset($config['permission']) || !$config['permission'] || !$func->check_permission()) return;
    if (empty($_SESSION['list_quyen']) || !in_array('visa_man', $_SESSION['list_quyen'], true)) {
        http_response_code(403);
        $func->transfer('Bạn không có quyền truy cập', 'index.php', false);
        exit;
    }
}
