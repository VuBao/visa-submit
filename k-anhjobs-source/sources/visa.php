<?php
if (!defined('SOURCES')) die('Error');

require_once LIBRARIES . 'visa.php';

$visa_errors = array();
$visa_old = array();
$visa_documents = visa_document_definitions();
$visa_csrf = visa_csrf_token('public_apply');
$visa_success_code = isset($_SESSION['visa_success_code']) ? $_SESSION['visa_success_code'] : '';
unset($_SESSION['visa_success_code']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : 0;
    if ($contentLength > 0 && empty($_POST) && empty($_FILES)) {
        $visa_errors[] = 'Tổng dung lượng hồ sơ vượt giới hạn máy chủ. Vui lòng giảm dung lượng ảnh/PDF rồi gửi lại.';
    } else {
    foreach ($_POST as $key=>$value) $visa_old[$key] = is_string($value) ? trim($value) : $value;
    if (!visa_verify_csrf('public_apply', isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) $visa_errors[] = 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.';
    if (!empty($_POST['website'])) $visa_errors[] = 'Không thể gửi hồ sơ.';

    foreach (array('full_name','company_name') as $field) if (empty($visa_old[$field])) $visa_errors[] = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
    if (empty($_POST['consent'])) $visa_errors[] = 'Vui lòng đồng ý cung cấp thông tin.';

    $files = isset($_FILES['documents']) ? visa_normalize_document_files($_FILES['documents']) : array();
    foreach ($visa_documents as $type=>$definition) {
        if (!empty($definition['required']) && (!isset($files[$type]) || $files[$type]['error'] === UPLOAD_ERR_NO_FILE)) $visa_errors[] = $definition['vi'] . ': thiếu file bắt buộc.';
    }
    foreach ($files as $type=>$file) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) continue;
        if (!isset($visa_documents[$type])) { $visa_errors[] = 'Loại giấy tờ không hợp lệ.'; continue; }
        try { visa_validate_upload($file); }
        catch (RuntimeException $exception) { $visa_errors[] = $visa_documents[$type]['vi'] . ': ' . $exception->getMessage(); }
    }

    $visa_errors = array_values(array_unique($visa_errors));
    if (empty($visa_errors)) {
        $storedFiles = array();
        try {
            $d->startTransaction();
            $now = date('Y-m-d H:i:s');
            $applicationCode = visa_generate_application_code($d);
            if (!$d->insert('visa_applications', array(
                'application_code'=>$applicationCode,
                'full_name'=>mb_substr($visa_old['full_name'],0,191,'UTF-8'),
                'phone'=>'',
                'email'=>'',
                'company_name'=>mb_substr($visa_old['company_name'],0,191,'UTF-8'),
                'visa_type'=>'',
                'status'=>'submitted','admin_note'=>null,'submitted_at'=>$now,'updated_at'=>$now
            ))) throw new RuntimeException('Không thể lưu hồ sơ.');
            $applicationId = (int)$d->getLastInsertId();

            foreach ($files as $type=>$file) {
                if ($file['error'] === UPLOAD_ERR_NO_FILE) continue;
                $meta = visa_store_upload($file, $applicationId, $type);
                $storedFiles[] = $meta['absolute_path'];
                if (!$d->insert('visa_documents', array(
                    'visa_application_id'=>$applicationId,'document_type'=>$type,
                    'original_name'=>$meta['original_name'],'stored_name'=>$meta['stored_name'],
                    'storage_path'=>$meta['storage_path'],'mime_type'=>$meta['mime_type'],
                    'file_size'=>$meta['file_size'],'uploaded_at'=>$now
                ))) throw new RuntimeException('Không thể lưu metadata file.');
            }
            $d->commit();
            visa_rotate_csrf('public_apply');
            $_SESSION['visa_success_code'] = $applicationCode;
            header('Location: ' . $config_base . 'apply-visa');
            exit;
        } catch (Throwable $exception) {
            try { $d->rollback(); } catch (Throwable $ignored) {}
            foreach ($storedFiles as $path) if (is_file($path)) @unlink($path);
            $visa_errors[] = 'Không thể gửi hồ sơ. Vui lòng thử lại sau.';
        }
    }
    }
}

$seo->setSeo('h1','ビザ申請書類の提出');
$seo->setSeo('title','ビザ申請書類の提出 | K-ANH');
$seo->setSeo('description','Nộp hồ sơ xin visa kỹ năng đặc định số 1');
$seo->setSeo('url',$config_base.'apply-visa');
