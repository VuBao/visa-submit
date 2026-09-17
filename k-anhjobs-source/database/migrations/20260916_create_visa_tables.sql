-- Review and run manually. Runtime PHP never creates or alters tables.

CREATE TABLE `table_visa_applications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_code` VARCHAR(32) NOT NULL,
  `full_name` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(40) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `company_name` VARCHAR(191) NOT NULL,
  `visa_type` VARCHAR(120) NOT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'submitted',
  `admin_note` TEXT DEFAULT NULL,
  `submitted_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_visa_application_code` (`application_code`),
  KEY `idx_visa_status` (`status`),
  KEY `idx_visa_submitted_at` (`submitted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `table_visa_documents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visa_application_id` BIGINT UNSIGNED NOT NULL,
  `document_type` VARCHAR(80) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `stored_name` VARCHAR(255) NOT NULL,
  `storage_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL,
  `uploaded_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_visa_document_application` (`visa_application_id`),
  KEY `idx_visa_document_type` (`document_type`),
  CONSTRAINT `fk_visa_document_application`
    FOREIGN KEY (`visa_application_id`) REFERENCES `table_visa_applications` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
