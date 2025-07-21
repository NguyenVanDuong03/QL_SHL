-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               11.5.2-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ql_shl
CREATE
DATABASE IF NOT EXISTS `ql_shl` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci */;
USE
`ql_shl`;

-- Dumping structure for table ql_shl.academic_warnings
CREATE TABLE IF NOT EXISTS `academic_warnings`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `student_id` bigint
(
    20
) unsigned NOT NULL,
    `semester_id` bigint
(
    20
) unsigned NOT NULL,
    `credits` varchar
(
    255
) NOT NULL,
    `gpa_10` decimal
(
    4,
    2
) NOT NULL COMMENT 'Điểm trung bình học kỳ theo thang điểm 10',
    `gpa_4` decimal
(
    3,
    2
) NOT NULL COMMENT 'Điểm trung bình học kỳ theo thang điểm 4',
    `academic_status` varchar
(
    255
) NOT NULL COMMENT 'Mức xử lý học vụ',
    `note` text DEFAULT NULL COMMENT 'Ghi chú',
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `academic_warnings_student_id_foreign`
(
    `student_id`
),
    KEY `academic_warnings_semester_id_foreign`
(
    `semester_id`
),
    CONSTRAINT `academic_warnings_semester_id_foreign` FOREIGN KEY
(
    `semester_id`
) REFERENCES `semesters`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `academic_warnings_student_id_foreign` FOREIGN KEY
(
    `student_id`
) REFERENCES `students`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.academic_warnings: ~2 rows (approximately)
INSERT INTO `academic_warnings` (`id`, `student_id`, `semester_id`, `credits`, `gpa_10`, `gpa_4`, `academic_status`,
                                 `note`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 19, 1, '12', 3.50, 0.70, 'Cảnh báo', 'Sinh viên cần gặp GVCN để trao đổi', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (2, 16, 2, '18', 4.00, 0.90, 'Cảnh báo', 'Đã có cải thiện nhưng cần tiếp tục phấn đấu', NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.attendances
CREATE TABLE IF NOT EXISTS `attendances`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `student_id` bigint
(
    20
) unsigned NOT NULL,
    `class_session_request_id` bigint
(
    20
) unsigned NOT NULL,
    `status` tinyint
(
    4
) NOT NULL DEFAULT 0 COMMENT '0:present(có mặt); 1:absent(Vắng mặt); 2:late(Muộn); 3:excused(Vắng mặt có phép)',
    `reason` text DEFAULT NULL COMMENT 'Lý do vắng mặt, muộn, có phép',
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `attendances_student_id_foreign`
(
    `student_id`
),
    KEY `attendances_class_session_request_id_foreign`
(
    `class_session_request_id`
),
    CONSTRAINT `attendances_class_session_request_id_foreign` FOREIGN KEY
(
    `class_session_request_id`
) REFERENCES `class_session_requests`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY
(
    `student_id`
) REFERENCES `students`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.attendances: ~21 rows (approximately)
INSERT INTO `attendances` (`id`, `student_id`, `class_session_request_id`, `status`, `reason`, `deleted_at`,
                           `created_at`, `updated_at`)
VALUES (1, 1, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 2, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 3, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 4, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 5, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 6, 1, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 1, 2, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 2, 2, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 3, 2, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 4, 2, 1, 'Vắng mặt do ốm', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 5, 2, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 6, 2, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 7, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 8, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 9, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 10, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 11, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 12, 3, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 16, 5, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 17, 5, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 18, 5, 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.cache
CREATE TABLE IF NOT EXISTS `cache`
(
    `key`
    varchar
(
    255
) NOT NULL,
    `value` mediumtext NOT NULL,
    `expiration` int
(
    11
) NOT NULL,
    PRIMARY KEY
(
    `key`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.cache: ~0 rows (approximately)

-- Dumping structure for table ql_shl.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks`
(
    `key`
    varchar
(
    255
) NOT NULL,
    `owner` varchar
(
    255
) NOT NULL,
    `expiration` int
(
    11
) NOT NULL,
    PRIMARY KEY
(
    `key`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.cache_locks: ~0 rows (approximately)

-- Dumping structure for table ql_shl.class_session_registrations
CREATE TABLE IF NOT EXISTS `class_session_registrations`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `semester_id` bigint
(
    20
) unsigned NOT NULL,
    `open_date` datetime NOT NULL,
    `end_date` datetime NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `class_session_registrations_semester_id_foreign`
(
    `semester_id`
),
    CONSTRAINT `class_session_registrations_semester_id_foreign` FOREIGN KEY
(
    `semester_id`
) REFERENCES `semesters`
(
    `id`
) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.class_session_registrations: ~3 rows (approximately)
INSERT INTO `class_session_registrations` (`id`, `semester_id`, `open_date`, `end_date`, `deleted_at`, `created_at`,
                                           `updated_at`)
VALUES (1, 1, '2023-12-31 08:00:00', '2024-01-14 17:00:00', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 2, '2024-05-31 08:00:00', '2024-06-14 17:00:00', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 5, '2024-12-29 08:00:00', '2025-01-12 17:00:00', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.class_session_reports
CREATE TABLE IF NOT EXISTS `class_session_reports`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `class_session_request_id` bigint
(
    20
) unsigned NOT NULL,
    `reporter_id` bigint
(
    20
) unsigned NOT NULL,
    `attending_students` int
(
    11
) NOT NULL COMMENT 'Số sinh viên tham dự họp lớp/Tổng số sinh viên trong lớp',
    `teacher_attendance` tinyint
(
    4
) NOT NULL COMMENT 'GVCN có tham gia buổi Sinh hoạt lớp',
    `politics_ethics_lifestyle` text NOT NULL COMMENT 'Tình hình chính trị, tư tưởng, đạo đức, lối sống',
    `academic_training_status` text NOT NULL COMMENT 'Tình hình học tập, rèn luyện',
    `on_campus_student_status` text NOT NULL COMMENT 'Tình hình sinh viên nội trú',
    `off_campus_student_status` text NOT NULL COMMENT 'Tình hình sinh viên ngoại trú',
    `other_activities` text NOT NULL COMMENT 'Các hoạt động khác',
    `suggestions_to_faculty_university` text NOT NULL COMMENT 'Đề xuất, kiến nghị với Khoa, Nhà trường',
    `path` varchar
(
    255
) NOT NULL COMMENT 'Minh chứng, hình ảnh buổi sinh hoạt lớp',
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `class_session_reports_class_session_request_id_foreign`
(
    `class_session_request_id`
),
    KEY `class_session_reports_reporter_id_foreign`
(
    `reporter_id`
),
    CONSTRAINT `class_session_reports_class_session_request_id_foreign` FOREIGN KEY
(
    `class_session_request_id`
) REFERENCES `class_session_requests`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `class_session_reports_reporter_id_foreign` FOREIGN KEY
(
    `reporter_id`
) REFERENCES `students`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.class_session_reports: ~0 rows (approximately)
INSERT INTO `class_session_reports` (`id`, `class_session_request_id`, `reporter_id`, `attending_students`,
                                     `teacher_attendance`, `politics_ethics_lifestyle`, `academic_training_status`,
                                     `on_campus_student_status`, `off_campus_student_status`, `other_activities`,
                                     `suggestions_to_faculty_university`, `path`, `deleted_at`, `created_at`,
                                     `updated_at`)
VALUES (1, 1, 2, 10, 1, 'Không có vấn đề gì về đạo đức và lối sống', 'Lớp thống nhất phương pháp học tập nhóm',
        'Sinh viên ở ký túc xá ổn định', 'Không có trường hợp đặc biệt',
        'Sinh hoạt đầu năm, phổ biến nội quy và kế hoạch học tập',
        'Đề xuất tổ chức thêm buổi học nhóm và mời chuyên gia chia sẻ kỹ năng học tập', '/class-session-reports/tes',
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 2, 3, 8, 1, 'Có 2 sinh viên vắng không phép, cần nhắc nhở', 'Lớp đề xuất ôn tập thêm môn Cấu trúc dữ liệu',
        'Sinh viên đi học đầy đủ, tinh thần tốt', 'Một vài sinh viên ở trọ cần hỗ trợ tài liệu ôn tập',
        'Hướng dẫn ôn tập và phổ biến quy chế thi học kỳ',
        'GVCN cần hỗ trợ thêm tài liệu ôn tập và tổ chức giải đáp thắc mắc', '/class-session-reports/tes', NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 5, 16, 4, 1, '1 sinh viên vắng không lý do, đề nghị kiểm tra',
        'Lớp đề xuất giảm tải một số bài tập lý thuyết', 'Sinh viên nội trú tham gia đầy đủ',
        'Một số sinh viên ngoại trú gặp khó khăn về mạng',
        'Thảo luận về tình hình học tập và khó khăn khi học trực tuyến',
        'Đề xuất nhà trường hỗ trợ đường truyền Internet cho SV học online', '/class-session-reports/tes', NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.class_session_requests
CREATE TABLE IF NOT EXISTS `class_session_requests`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `study_class_id` bigint
(
    20
) unsigned NOT NULL,
    `lecturer_id` bigint
(
    20
) unsigned NOT NULL,
    `class_session_registration_id` bigint
(
    20
) unsigned DEFAULT NULL,
    `room_id` bigint
(
    20
) unsigned DEFAULT NULL,
    `type` tinyint
(
    4
) NOT NULL COMMENT '0:SHL cố định; 1:SHL linh hoạt',
    `position` tinyint
(
    4
) NOT NULL COMMENT '0: Trực tiếp tại trường; 1: Trực tuyến; 2: Dã ngoại',
    `proposed_at` datetime NOT NULL,
    `location` varchar
(
    255
) DEFAULT NULL,
    `meeting_type` varchar
(
    255
) DEFAULT NULL COMMENT '0:Google Meet; 1:Zoom; 2:Microsoft Teams',
    `meeting_id` varchar
(
    255
) DEFAULT NULL,
    `meeting_password` varchar
(
    255
) DEFAULT NULL,
    `meeting_url` varchar
(
    255
) DEFAULT NULL,
    `title` varchar
(
    255
) NOT NULL,
    `content` text NOT NULL,
    `note` text DEFAULT NULL,
    `status` tinyint
(
    4
) NOT NULL DEFAULT 0 COMMENT '0:pending; 1:approved; 2:rejected',
    `rejection_reason` varchar
(
    255
) DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `class_session_requests_study_class_id_foreign`
(
    `study_class_id`
),
    KEY `class_session_requests_lecturer_id_foreign`
(
    `lecturer_id`
),
    KEY `class_session_requests_class_session_registration_id_foreign`
(
    `class_session_registration_id`
),
    KEY `class_session_requests_room_id_foreign`
(
    `room_id`
),
    CONSTRAINT `class_session_requests_class_session_registration_id_foreign` FOREIGN KEY
(
    `class_session_registration_id`
) REFERENCES `class_session_registrations`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `class_session_requests_lecturer_id_foreign` FOREIGN KEY
(
    `lecturer_id`
) REFERENCES `lecturers`
(
    `id`
)
  ON DELETE CASCADE,
    CONSTRAINT `class_session_requests_room_id_foreign` FOREIGN KEY
(
    `room_id`
) REFERENCES `rooms`
(
    `id`
)
  ON DELETE CASCADE,
    CONSTRAINT `class_session_requests_study_class_id_foreign` FOREIGN KEY
(
    `study_class_id`
) REFERENCES `study_classes`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.class_session_requests: ~10 rows (approximately)
INSERT INTO `class_session_requests` (`id`, `study_class_id`, `lecturer_id`, `class_session_registration_id`, `room_id`,
                                      `type`, `position`, `proposed_at`, `location`, `meeting_type`, `meeting_id`,
                                      `meeting_password`, `meeting_url`, `title`, `content`, `note`, `status`,
                                      `rejection_reason`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 1, 1, 1, 1, 0, 0, '2022-09-01 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL đầu năm học',
        'Hoàn thành sinh hoạt đầu năm học mới, phổ biến nội quy và kế hoạch học tập', NULL, 3, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 1, 2, 2, 0, 0, '2023-02-01 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL chuẩn bị thi cuối kỳ',
        'Hoàn thành hướng dẫn ôn tập và quy chế thi', NULL, 3, NULL, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (3, 2, 2, 1, 3, 0, 0, '2022-09-02 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL đầu năm',
        'Hoàn thành giới thiệu chương trình học và các hoạt động', NULL, 3, NULL, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (4, 1, 1, 3, NULL, 1, 1, '2024-09-01 09:00:00', NULL, 'Google Meet', 'meet123', 'pass123',
        'https://meet.google.com/abc123', 'SHL trực tuyến đầu kỳ',
        'Hoàn thành trao đổi về kế hoạch học kỳ mới qua Google Meet', NULL, 3, NULL, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (5, 12, 6, 3, NULL, 1, 1, '2024-09-02 09:00:00', NULL, 'Zoom', 'zoom456', 'pass456', 'https://zoom.us/j/def456',
        'SHL trực tuyến', 'Hoàn thành thảo luận về tình hình học tập của lớp', NULL, 3, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 1, 1, 3, NULL, 1, 2, '2024-05-15 07:30:00', 'Công viên Lê Nin', NULL, NULL, NULL, NULL, 'SHL dã ngoại',
        'Hoàn thành hoạt động teambuilding và gắn kết lớp', NULL, 3, NULL, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (7, 1, 1, 3, 4, 0, 0, '2024-12-18 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL chuẩn bị thi',
        'Hoàn thành chuẩn bị cho kỳ thi cuối kỳ', NULL, 3, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 2, 2, 3, 5, 0, 0, '2024-12-19 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL tổng kết',
        'Hoàn thành tổng kết hoạt động học kỳ', NULL, 3, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 1, 1, 1, 1, 0, 0, '2022-09-03 08:00:00', NULL, NULL, NULL, NULL, NULL, 'SHL bổ sung',
        'Hoàn thành yêu cầu không hợp lệ do trùng lịch', NULL, 3, 'Yêu cầu bị từ chối do trùng lịch với lớp khác', NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 2, 2, 2, 2, 1, 1, '2023-02-02 09:00:00', NULL, 'Microsoft Teams', 'teams789', 'pass789',
        'https://teams.microsoft.com/l/meetup', 'SHL đột xuất', 'Hoàn thành thảo luận vấn đề kỷ luật lớp', NULL, 3,
        'Không đủ lý do chính đáng để tổ chức SHL đột xuất', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.cohorts
CREATE TABLE IF NOT EXISTS `cohorts`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar
(
    255
) NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `cohorts_name_unique`
(
    `name`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.cohorts: ~0 rows (approximately)
INSERT INTO `cohorts` (`id`, `name`, `start_date`, `end_date`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 'K62', '2020-09-01', '2024-07-15', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 'K63', '2021-09-01', '2025-07-15', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 'K64', '2022-09-01', '2026-07-15', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 'K65', '2023-09-01', '2027-07-15', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 'K66', '2024-09-01', '2028-07-15', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.conduct_criterias
CREATE TABLE IF NOT EXISTS `conduct_criterias`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `content` text NOT NULL,
    `max_score` int
(
    11
) NOT NULL DEFAULT 0 COMMENT 'Điểm tối đa cho tiêu chí này',
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.conduct_criterias: ~0 rows (approximately)
INSERT INTO `conduct_criterias` (`id`, `content`, `max_score`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 'CHẤP HÀNH ĐẦY ĐỦ CÁC QUY ĐỊNH HỌC TẬP TRÊN GIẢNG ĐƯỜNG', 2, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (2,
        'THÀNH VIÊN CÂU LẠC BỘ, THAM GIA THI OLYMPIC, NGHIÊN CỨU KHOA HỌC, THÀNH VIÊN CÂU LẠC BỘ: 1 ĐIỂM, CẤP KHOA: ĐẠT GIẢI 2 ĐIỂM, THAM GIA 1 ĐIỂM, CẤP TRƯỜNG: ĐẠT GIẢI 3 ĐIỂM, THAM GIA 2 ĐIỂM, CẤP TRÊN TRƯỜNG: ĐẠT GIẢI 5 ĐIỂM, THAM GIA 3 ĐIỂM',
        5, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3,
        'CHẤP HÀNH TỐT NỘI QUY VỀ HỌC TẬP, BỊ CBCT LẬP BIÊN BẢN KHIỂN TRÁCH: TRỪ 1 ĐIỂM/LẦN, BỊ CBCT LẬP BIÊN BẢN CẢNH BÁO: TRỪ 2 ĐIỂM/LẦN, BỊ CBCT LẬP BIÊN BẢN ĐÌNH CHỈ THI: TRỪ 3 ĐIỂM/LẦN',
        3, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4,
        'ĐIỂM HỌC TẬP KỲ SAU CAO HƠN KỲ TRƯỚC HOẶC XẾP LOẠI HỌC TẬP TỪ KHÁ TRỞ LÊN (ĐIỂM ĐƯỢC TÍNH TỰ ĐỘNG DỰA TRÊN ĐIỂM HỌC TẬP CỦA SINH VIÊN)',
        2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5,
        'KẾT QUẢ HỌC TẬP TRONG HỌC KỲ, ĐẠT HỌC LỰC GIỎI, XUẤT SẮC: 8 ĐIỂM; HỌC LỰC KHÁ: 5 ĐIỂM; HỌC LỰC TRUNG BÌNH & TBK: 3 ĐIỂM; HỌC LỰC DƯỚI TRUNG BÌNH: 0 ĐIỂM (ĐIỂM ĐƯỢC TÍNH TỰ ĐỘNG DỰA TRÊN ĐIỂM HỌC TẬP CỦA SINH VIÊN)',
        8, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 'CHẤP HÀNH TỐT CÁC NỘI QUY, QUY CHẾ VÀ CÁC QUY ĐỊNH ĐƯỢC THỰC HIỆN TRONG NHÀ TRƯỜNG, VI PHẠM: 0 ĐIỂM', 5,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 'THAM GIA ĐẦY ĐỦ CÁC BUỔI SINH HOẠT LỚP HÀNG THÁNG, VẮNG KHÔNG CÓ LÝ DO CHÍNH ĐÁNG: TRỪ 2 ĐIỂM/LẦN', 10,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 'CHẤP HÀNH TỐT QUY ĐỊNH VỀ ĐÓNG HỌC PHÍ. ĐÓNG CHẬM HỌC PHÍ SO VỚI QUY ĐỊNH: 0 ĐIỂM', 5, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9,
        'CHẤP HÀNH ĐẦY ĐỦ CÁC QUY ĐỊNH KHÁC CỦA NHÀ TRƯỜNG NHƯ THỰC HIỆN TỐT QUY ĐỊNH VỀ NỘI, NGOẠI TRÚ; NỘI QUY THƯ VIỆN, NẾP SỐNG VĂN HÓA HỌC ĐƯỜNG. VI PHẠM MỘT TRONG CÁC QUY ĐỊNH: 0 ĐIỂM',
        5, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10,
        'THAM GIA CÁC HOẠT ĐỘNG VĂN HÓA, VĂN NGHỆ, THỂ THAO, THAM GIA TRỰC TIẾP HOẶC THAM GIA TỔ CHỨC: 6 ĐIỂM, THAM GIA HỖ TRỢ, CỔ VŨ: 3 ĐIỂM, KHÔNG THAM GIA HOẶC TỪ CHỐI THAM GIA KHI TỔ CHỨC PHÂN CÔNG: 0 ĐIỂM',
        6, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11,
        'THAM GIA CÔNG TÁC XÃ HỘI (HIẾN MÁU NHÂN ĐẠO, ỦNG HỘ NGƯỜI NGHÈO & THIÊN TAI, TÌNH NGUYỆN ...); KHÔNG THAM GIA HOẶC TỪ CHỐI THAM GIA KHI TỔ CHỨC PHÂN CÔNG: 0 ĐIỂM, NẾU CÔNG TÁC XÃ HỘI PHÂN BỔ CHỈ TIÊU THEO TỪNG LỚP THÌ LỚP HOÀN THÀNH CHỈ TIÊU MỖI SINH VIÊN: 6 ĐIỂM, LỚP KHÔNG HOÀN THÀNH CHỈ TIÊU THÌ CÁC SINH VIÊN THAM GIA: 6 ĐIỂM',
        6, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 'THAM GIA TUYÊN TRUYỀN, PHÒNG CHỐNG MA TÚY VÀ CÁC TỆ NẠN XÃ HỘI KHÁC', 6, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (13,
        'LÀ THÀNH VIÊN ĐỘI TUYỂN DỰ THI HOẠT ĐỘNG CHÍNH TRỊ - XÃ HỘI, VĂN HÓA, THỂ THAO TỪ CẤP TRƯỜNG TRỞ LÊN, HOẶC ĐƯỢC NHÀ TRƯỜNG KHEN THƯỞNG HOẶC ĐẠT GIẢI TRONG CÁC HOẠT ĐỘNG CHÍNH TRỊ, XÃ HỘI, VĂN HÓA, THỂ THAO',
        2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 'THAM GIA TUYÊN TRUYỀN VÀ PHẤN ĐẤU TỐT CÁC CHỦ TRƯƠNG CỦA ĐẢNG, CHÍNH SÁCH PHÁP LUẬT CỦA NHÀ NƯỚC', 10,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15,
        'THAM GIA CÁC HOẠT ĐỘNG XÃ HỘI CÓ THÀNH TÍCH ĐƯỢC GHI NHẬN, BIỂU DƯƠNG, KHEN THƯỞNG CẤP KHOA: 3 ĐIỂM, CẤP TRƯỜNG: 4 ĐIỂM, TRÊN CẤP TRƯỜNG: 5 ĐIỂM',
        5, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 'CÓ TÌNH THẦN CHIA SẺ, GIÚP ĐỠ NGƯỜI THÂN, NGƯỜI CÓ KHÓ KHĂN, HOẠN NẠN', 10, NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (17,
        'SINH VIÊN ĐƯỢC PHÂN CÔNG QUẢN LÝ LỚP, CÁC TỔ CHỨC ĐOÀN, HỘI VÀ CÁC TỔ CHỨC KHÁC TRONG NHÀ TRƯỜNG (BCS LỚP, BCH ĐOÀN, BCH HỘI SV, THÀNH VIÊN CÂU LẠC BỘ, TRƯỞNG PHÒNG Ở KÝ TÚC XÁ...), HOÀN THÀNH XUẤT SẮC NHIỆM VỤ: 10 ĐIỂM, HOÀN THÀNH TỐT NHIỆM VỤ: 8 ĐIỂM, KHÔNG HOÀN THÀNH NHIỆM VỤ: 0 ĐIỂM',
        10, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18,
        'SINH VIÊN LÀ THÀNH VIÊN TỔ CHỨC CÁC CHƯƠNG TRÌNH, CỘNG TÁC VIÊN THAM GIA TÍCH CỰC VÀO CÁC HOẠT ĐỘNG CHUNG CỦA LỚP, KHOA, TRƯỜNG',
        10, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19,
        'SINH VIÊN TÍCH CỰC TRONG CÔNG TÁC PHÁT TRIỂN ĐẢNG, ĐẠT YÊU CẦU KHI THAM GIA LỚP BỒI DƯỠNG NÂNG CAO NHẬN THỨC VỀ ĐẢNG: 5 ĐIỂM, ĐƯỢC KẾT NẠP ĐẢNG HOẶC ĐƯỢC CHUYỂN ĐẢNG CHÍNH THỨC ĐÚNG HẠN: 10 ĐIỂM',
        10, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20,
        'SINH VIÊN ĐẠT THÀNH TÍCH ĐẶC BIỆT TRONG HỌC TẬP, RÈN LUYỆN, ĐẠT GIẢI THƯỞNG TRONG NGHIÊN CỨU KHOA HỌC, THI OLYMPIC CÁC CẤP, ĐẠT HUY CHƯƠNG, GIẤY KHEN, GIẢI THƯỞNG CÁC CẤP VỀ VĂN HÓA, VĂN NGHỆ, THỂ THAO, PHÒNG CHỐNG CÁC TỆ NẠN XÃ HỘI; HOẠT ĐỘNG VÌ CỘNG ĐỒNG…',
        10, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.conduct_evaluation_periods
CREATE TABLE IF NOT EXISTS `conduct_evaluation_periods`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `semester_id` bigint
(
    20
) unsigned NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `conduct_evaluation_periods_semester_id_foreign`
(
    `semester_id`
),
    CONSTRAINT `conduct_evaluation_periods_semester_id_foreign` FOREIGN KEY
(
    `semester_id`
) REFERENCES `semesters`
(
    `id`
) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.conduct_evaluation_periods: ~0 rows (approximately)
INSERT INTO `conduct_evaluation_periods` (`id`, `semester_id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 1, 'Đợt 1 HK1 2022-2023', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 2, 'Đợt 1 HK2 2022-2023', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 3, 'Đợt 1 HK1 2023-2024', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 4, 'Đợt 1 HK2 2023-2024', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 5, 'Đợt 1 HK1 2024-2025', NULL, '2025-07-09 21:04:08', '2025-07-09 21:04:08');

-- Dumping structure for table ql_shl.conduct_evaluation_phases
CREATE TABLE IF NOT EXISTS `conduct_evaluation_phases`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `conduct_evaluation_period_id` bigint
(
    20
) unsigned NOT NULL,
    `role` tinyint
(
    4
) NOT NULL COMMENT '0: SV, 1: GVCN, 2: VPK',
    `open_date` datetime NOT NULL,
    `end_date` datetime NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `conduct_evaluation_phases_conduct_evaluation_period_id_foreign`
(
    `conduct_evaluation_period_id`
),
    CONSTRAINT `conduct_evaluation_phases_conduct_evaluation_period_id_foreign` FOREIGN KEY
(
    `conduct_evaluation_period_id`
) REFERENCES `conduct_evaluation_periods`
(
    `id`
) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.conduct_evaluation_phases: ~0 rows (approximately)
INSERT INTO `conduct_evaluation_phases` (`id`, `conduct_evaluation_period_id`, `role`, `open_date`, `end_date`,
                                         `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 1, 0, '2023-12-01 00:00:00', '2023-12-07 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 1, '2023-12-08 00:00:00', '2023-12-14 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 1, 2, '2023-12-15 00:00:00', '2023-12-21 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 2, 0, '2024-05-01 00:00:00', '2024-05-07 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 2, 1, '2024-05-08 00:00:00', '2024-05-14 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 2, 2, '2024-05-15 00:00:00', '2024-05-21 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 3, 0, '2024-12-01 00:00:00', '2024-12-07 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 3, 1, '2024-12-08 00:00:00', '2024-12-14 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 3, 2, '2024-12-15 00:00:00', '2024-12-21 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 4, 0, '2025-05-01 00:00:00', '2025-05-07 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 4, 1, '2025-05-08 00:00:00', '2025-05-14 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 4, 2, '2025-05-15 00:00:00', '2025-05-21 23:59:59', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 5, 0, '2024-12-01 00:00:00', '2024-12-07 23:59:59', NULL, '2025-07-09 21:04:08', '2025-07-09 21:04:08'),
       (14, 5, 1, '2024-12-08 00:00:00', '2024-12-14 23:59:59', NULL, '2025-07-09 21:04:08', '2025-07-09 21:04:08'),
       (15, 5, 2, '2024-12-15 00:00:00', '2024-12-21 23:59:59', NULL, '2025-07-09 21:04:08', '2025-07-09 21:04:08');

-- Dumping structure for table ql_shl.departments
CREATE TABLE IF NOT EXISTS `departments`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.departments
INSERT INTO `departments` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 'Khoa Công nghệ thông tin', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 'Khoa Điện - Điện tử', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 'Khoa Cơ khí', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 'Khoa Kinh tế và Quản lý', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 'Khoa Công trình', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 'Khoa Hóa và Môi trường', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 'Khoa Kỹ thuật tài nguyên nước', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 'TT Đào tạo quốc tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 'Khoa Kế toán và Kinh doanh', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 'Khoa Luật và Lý luận chính trị', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.detail_conduct_scores
CREATE TABLE IF NOT EXISTS `detail_conduct_scores`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `conduct_criteria_id` bigint
(
    20
) unsigned NOT NULL,
    `student_conduct_score_id` bigint
(
    20
) unsigned NOT NULL,
    `self_score` int
(
    11
) DEFAULT 0,
    `class_score` int
(
    11
) DEFAULT 0,
    `final_score` int
(
    11
) DEFAULT 0,
    `path` varchar
(
    255
) DEFAULT NULL,
    `note` text DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `detail_conduct_scores_conduct_criteria_id_foreign`
(
    `conduct_criteria_id`
),
    KEY `detail_conduct_scores_student_conduct_score_id_foreign`
(
    `student_conduct_score_id`
),
    CONSTRAINT `detail_conduct_scores_conduct_criteria_id_foreign` FOREIGN KEY
(
    `conduct_criteria_id`
) REFERENCES `conduct_criterias`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `detail_conduct_scores_student_conduct_score_id_foreign` FOREIGN KEY
(
    `student_conduct_score_id`
) REFERENCES `student_conduct_scores`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.detail_conduct_scores: ~0 rows (approximately)
INSERT INTO `detail_conduct_scores` (`id`, `conduct_criteria_id`, `student_conduct_score_id`, `self_score`,
                                     `class_score`, `final_score`, `path`, `note`, `deleted_at`, `created_at`,
                                     `updated_at`)
VALUES (1, 1, 1, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 2, 1, 5, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 3, 1, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 4, 1, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 5, 1, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 6, 1, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 7, 1, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 8, 1, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 9, 1, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 10, 1, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 11, 1, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 12, 1, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 13, 1, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 14, 1, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 15, 1, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 16, 1, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 17, 1, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 18, 1, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 19, 1, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 20, 1, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 1, 2, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 2, 2, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 3, 2, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 4, 2, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 5, 2, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 6, 2, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, 7, 2, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, 8, 2, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, 9, 2, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, 10, 2, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (31, 11, 2, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (32, 12, 2, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (33, 13, 2, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (34, 14, 2, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (35, 15, 2, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (36, 16, 2, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (37, 17, 2, 0, 0, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (38, 18, 2, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (39, 19, 2, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (40, 20, 2, 10, 10, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (41, 1, 3, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (42, 2, 3, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (43, 3, 3, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (44, 4, 3, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (45, 5, 3, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (46, 6, 3, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (47, 7, 3, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (48, 8, 3, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (49, 9, 3, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (50, 10, 3, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (51, 11, 3, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (52, 12, 3, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (53, 13, 3, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (54, 14, 3, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (55, 15, 3, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (56, 16, 3, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (57, 17, 3, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (58, 18, 3, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (59, 19, 3, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (60, 20, 3, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (61, 1, 4, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (62, 2, 4, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (63, 3, 4, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (64, 4, 4, 1, 1, 1, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (65, 5, 4, 7, 7, 7, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (66, 6, 4, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (67, 7, 4, 9, 9, 9, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (68, 8, 4, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (69, 9, 4, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (70, 10, 4, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (71, 11, 4, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (72, 12, 4, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (73, 13, 4, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (74, 14, 4, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (75, 15, 4, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (76, 16, 4, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (77, 17, 4, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (78, 18, 4, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (79, 19, 4, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (80, 20, 4, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (81, 1, 5, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (82, 2, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (83, 3, 5, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (84, 4, 5, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (85, 5, 5, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (86, 6, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (87, 7, 5, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (88, 8, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (89, 9, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (90, 10, 5, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (91, 11, 5, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (92, 12, 5, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (93, 13, 5, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (94, 14, 5, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (95, 15, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (96, 16, 5, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (97, 17, 5, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (98, 18, 5, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (99, 19, 5, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (100, 20, 5, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (101, 1, 6, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (102, 2, 6, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (103, 3, 6, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (104, 4, 6, 1, 1, 1, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (105, 5, 6, 7, 7, 7, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (106, 6, 6, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (107, 7, 6, 9, 9, 9, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (108, 8, 6, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (109, 9, 6, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (110, 10, 6, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (111, 11, 6, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (112, 12, 6, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (113, 13, 6, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (114, 14, 6, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (115, 15, 6, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (116, 16, 6, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (117, 17, 6, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (118, 18, 6, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (119, 19, 6, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (120, 20, 6, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (121, 1, 7, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (122, 2, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (123, 3, 7, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (124, 4, 7, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (125, 5, 7, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (126, 6, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (127, 7, 7, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (128, 8, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (129, 9, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (130, 10, 7, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (131, 11, 7, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (132, 12, 7, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (133, 13, 7, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (134, 14, 7, 10, 10, 10, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (135, 15, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (136, 16, 7, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (137, 17, 7, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (138, 18, 7, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (139, 19, 7, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (140, 20, 7, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (141, 1, 8, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (142, 2, 8, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (143, 3, 8, 2, 2, 2, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (144, 4, 8, 1, 1, 1, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (145, 5, 8, 7, 7, 7, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (146, 6, 8, 6, 6, 6, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (147, 7, 8, 9, 9, 9, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (148, 8, 8, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (149, 9, 8, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (150, 10, 8, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (151, 11, 8, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (152, 12, 8, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (153, 13, 8, 3, 3, 3, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (154, 14, 8, 8, 8, 8, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (155, 15, 8, 4, 4, 4, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (156, 16, 8, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (157, 17, 8, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (158, 18, 8, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (159, 19, 8, 5, 5, 5, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (160, 20, 8, 0, 0, 0, NULL, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.faculties
CREATE TABLE IF NOT EXISTS `faculties`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `department_id` bigint
(
    20
) unsigned NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `faculties_department_id_foreign`
(
    `department_id`
),
    CONSTRAINT `faculties_department_id_foreign` FOREIGN KEY
(
    `department_id`
) REFERENCES `departments`
(
    `id`
) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.faculties
INSERT INTO `faculties` (`id`, `department_id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 1, 'Bộ môn Công nghệ phần mềm', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 'Bộ môn Hệ thống thông tin', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 1, 'Bộ môn Khoa học máy tính', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 2, 'Bộ môn Điện tử viễn thông', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 2, 'Bộ môn Điện công nghiệp', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 2, 'Bộ môn Kỹ thuật điều khiển và tự động hóa', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 3, 'Bộ môn Cơ khí chế tạo', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 3, 'Bộ môn Kỹ thuật ô tô', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 4, 'Bộ môn Quản trị kinh doanh', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 4, 'Bộ môn Kinh tế xây dựng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 5, 'Bộ môn Kỹ thuật xây dựng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 5, 'Bộ môn Kỹ thuật xây dựng công trình giao thông', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 6, 'Bộ môn Kỹ thuật môi trường', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 6, 'Bộ môn Hóa học ứng dụng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 7, 'Bộ môn Kỹ thuật tài nguyên nước', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 7, 'Bộ môn Quản lý tài nguyên nước', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 8, 'Bộ môn Đào tạo quốc tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 9, 'Bộ môn Kế toán', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 9, 'Bộ môn Kinh doanh quốc tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 10, 'Bộ môn Luật học', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.faculty_offices
CREATE TABLE IF NOT EXISTS `faculty_offices`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint
(
    20
) unsigned NOT NULL,
    `department_id` bigint
(
    20
) unsigned NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `faculty_offices_user_id_unique`
(
    `user_id`
),
    UNIQUE KEY `faculty_offices_department_id_unique`
(
    `department_id`
),
    CONSTRAINT `faculty_offices_department_id_foreign` FOREIGN KEY
(
    `department_id`
) REFERENCES `departments`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `faculty_offices_user_id_foreign` FOREIGN KEY
(
    `user_id`
) REFERENCES `users`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.faculty_offices: ~0 rows (approximately)
INSERT INTO `faculty_offices` (`id`, `user_id`, `department_id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 55, 1, 'Văn phòng Khoa Công nghệ thông tin', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 56, 3, 'Văn phòng Khoa Cơ khí', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 59, 4, 'Văn phòng Khoa Kinh tế và Quản lý', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 57, 6, 'Văn phòng Khoa Công trình', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 58, 9, 'Văn phòng Khoa Môi trường', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `uuid` varchar
(
    255
) NOT NULL,
    `connection` text NOT NULL,
    `queue` text NOT NULL,
    `payload` longtext NOT NULL,
    `exception` longtext NOT NULL,
    `failed_at` timestamp NOT NULL DEFAULT current_timestamp
(
),
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `failed_jobs_uuid_unique`
(
    `uuid`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table ql_shl.jobs
CREATE TABLE IF NOT EXISTS `jobs`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar
(
    255
) NOT NULL,
    `payload` longtext NOT NULL,
    `attempts` tinyint
(
    3
) unsigned NOT NULL,
    `reserved_at` int
(
    10
) unsigned DEFAULT NULL,
    `available_at` int
(
    10
) unsigned NOT NULL,
    `created_at` int
(
    10
) unsigned NOT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `jobs_queue_index`
(
    `queue`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.jobs: ~0 rows (approximately)

-- Dumping structure for table ql_shl.job_batches
CREATE TABLE IF NOT EXISTS `job_batches`
(
    `id`
    varchar
(
    255
) NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `total_jobs` int
(
    11
) NOT NULL,
    `pending_jobs` int
(
    11
) NOT NULL,
    `failed_jobs` int
(
    11
) NOT NULL,
    `failed_job_ids` longtext NOT NULL,
    `options` mediumtext DEFAULT NULL,
    `cancelled_at` int
(
    11
) DEFAULT NULL,
    `created_at` int
(
    11
) NOT NULL,
    `finished_at` int
(
    11
) DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.job_batches: ~0 rows (approximately)

-- Dumping structure for table ql_shl.lecturers
CREATE TABLE IF NOT EXISTS `lecturers`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint
(
    20
) unsigned NOT NULL,
    `faculty_id` bigint
(
    20
) unsigned NOT NULL,
    `title` varchar
(
    255
) NOT NULL,
    `position` varchar
(
    255
) NOT NULL COMMENT 'Giảng viên; Phó trưởng bộ môn; Trưởng bộ môn; Phó trưởng khoa; Trưởng khoa',
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `lecturers_user_id_unique`
(
    `user_id`
),
    KEY `lecturers_faculty_id_foreign`
(
    `faculty_id`
),
    CONSTRAINT `lecturers_faculty_id_foreign` FOREIGN KEY
(
    `faculty_id`
) REFERENCES `faculties`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `lecturers_user_id_foreign` FOREIGN KEY
(
    `user_id`
) REFERENCES `users`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.lecturers: ~8 rows (approximately)
INSERT INTO `lecturers` (`id`, `user_id`, `faculty_id`, `title`, `position`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 3, 1, 'Thạc sĩ', 'Giảng viên', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 4, 2, 'Phó Giáo sư', 'Phó trưởng bộ môn', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 5, 3, 'Tiến sĩ', 'Trưởng bộ môn', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 6, 4, 'Thạc sĩ', 'Giảng viên', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 7, 5, 'Thạc sĩ', 'Giảng viên', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 8, 6, 'Giảng viên', 'Giảng viên', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 9, 7, 'Tiến sĩ', 'Phó trưởng bộ môn', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 10, 8, 'Giáo sư', 'Trưởng khoa', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.majors
CREATE TABLE IF NOT EXISTS `majors`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `faculty_id` bigint
(
    20
) unsigned NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `majors_faculty_id_foreign`
(
    `faculty_id`
),
    CONSTRAINT `majors_faculty_id_foreign` FOREIGN KEY
(
    `faculty_id`
) REFERENCES `faculties`
(
    `id`
) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.majors
INSERT INTO `majors` (`id`, `faculty_id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 1, 'Kỹ thuật phần mềm', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 'An toàn thông tin', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 2, 'Hệ thống thông tin', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 3, 'Khoa học dữ liệu', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 3, 'Trí tuệ nhân tạo', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 4, 'Điện tử viễn thông', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 5, 'Điện công nghiệp', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 6, 'Kỹ thuật điều khiển và tự động hóa', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 6, 'Kỹ thuật robot', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 7, 'Công nghệ chế tạo máy', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 8, 'Kỹ thuật ô tô', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 9, 'Quản trị kinh doanh', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 9, 'Thương mại điện tử', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 10, 'Kinh tế xây dựng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 11, 'Kỹ thuật xây dựng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 12, 'Kỹ thuật xây dựng công trình giao thông', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 13, 'Kỹ thuật môi trường', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 14, 'Hóa học ứng dụng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 15, 'Kỹ thuật tài nguyên nước', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 16, 'Quản lý tài nguyên nước', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 17, 'Chương trình đào tạo quốc tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 18, 'Kế toán', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 19, 'Kinh doanh quốc tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 20, 'Luật kinh tế', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 20, 'Lý luận chính trị', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 9, 'Logistics và Quản lý chuỗi cung ứng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.migrations
CREATE TABLE IF NOT EXISTS `migrations`
(
    `id`
    int
(
    10
) unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar
(
    255
) NOT NULL,
    `batch` int
(
    11
) NOT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=597 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.migrations: ~23 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES (574, '0001_01_01_000000_create_users_table', 1),
       (575, '0001_01_01_000001_create_cache_table', 1),
       (576, '0001_01_01_000002_create_jobs_table', 1),
       (577, '2025_05_12_163128_create_cohorts_table', 1),
       (578, '2025_05_12_163256_create_departments_table', 1),
       (579, '2025_05_12_163508_create_semesters_table', 1),
       (580, '2025_05_12_163858_create_class_session_registrations_table', 1),
       (581, '2025_05_12_164208_create_rooms_table', 1),
       (582, '2025_05_12_164258_create_faculties_table', 1),
       (583, '2025_05_12_164420_create_majors_table', 1),
       (584, '2025_05_12_164532_create_lecturers_table', 1),
       (585, '2025_05_12_165044_create_study_classes_table', 1),
       (586, '2025_05_12_165342_create_students_table', 1),
       (587, '2025_05_12_165902_create_class_session_requests_table', 1),
       (588, '2025_05_12_170857_create_attendances_table', 1),
       (589, '2025_05_12_171135_create_conduct_criterias_table', 1),
       (590, '2025_05_12_172216_create_conduct_evaluation_periods_table', 1),
       (591, '2025_05_12_172335_create_academic_warnings_table', 1),
       (592, '2025_05_12_173038_create_class_session_reports_table', 1),
       (593, '2025_05_27_161749_student_conduct_scores', 1),
       (594, '2025_06_12_041308_create_detail_conduct_scores_table', 1),
       (595, '2025_06_15_124851_create_faculty_offices_table', 1),
       (596, '2025_06_20_161834_create_conduct_evaluation_phases_table', 1);

-- Dumping structure for table ql_shl.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens`
(
    `email`
    varchar
(
    255
) NOT NULL,
    `token` varchar
(
    255
) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `email`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table ql_shl.rooms
CREATE TABLE IF NOT EXISTS `rooms`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar
(
    255
) NOT NULL,
    `description` varchar
(
    255
) DEFAULT NULL,
    `quantity` int
(
    11
) NOT NULL,
    `status` tinyint
(
    4
) NOT NULL DEFAULT 0,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.rooms: ~0 rows (approximately)
INSERT INTO `rooms` (`id`, `name`, `description`, `quantity`, `status`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, '101A2', 'Phòng 101A2 tòa A2', 50, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, '102A2', 'Phòng 102A2 tòa A2', 51, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, '103A2', 'Phòng 103A2 tòa A2', 52, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, '104A2', 'Phòng 104A2 tòa A2', 53, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, '105A2', 'Phòng 105A2 tòa A2', 54, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, '106A2', 'Phòng 106A2 tòa A2', 55, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, '107A2', 'Phòng 107A2 tòa A2', 56, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, '108A2', 'Phòng 108A2 tòa A2', 57, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, '109A2', 'Phòng 109A2 tòa A2', 58, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, '110A2', 'Phòng 110A2 tòa A2', 59, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, '111A2', 'Phòng 111A2 tòa A2', 60, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, '112A2', 'Phòng 112A2 tòa A2', 61, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, '113A2', 'Phòng 113A2 tòa A2', 62, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, '114A2', 'Phòng 114A2 tòa A2', 63, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, '115A2', 'Phòng 115A2 tòa A2', 64, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, '116A2', 'Phòng 116A2 tòa A2', 65, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, '117A2', 'Phòng 117A2 tòa A2', 66, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, '118A2', 'Phòng 118A2 tòa A2', 67, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, '119A2', 'Phòng 119A2 tòa A2', 68, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, '120A2', 'Phòng 120A2 tòa A2', 69, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, '121A2', 'Phòng 121A2 tòa A2', 70, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, '122A2', 'Phòng 122A2 tòa A2', 71, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, '123A2', 'Phòng 123A2 tòa A2', 72, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, '124A2', 'Phòng 124A2 tòa A2', 73, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, '125A2', 'Phòng 125A2 tòa A2', 74, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, '126A2', 'Phòng 126A2 tòa A2', 75, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, '127A2', 'Phòng 127A2 tòa A2', 50, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, '128A2', 'Phòng 128A2 tòa A2', 51, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, '129A2', 'Phòng 129A2 tòa A2', 52, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, '130A2', 'Phòng 130A2 tòa A2', 53, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (31, '101A3', 'Phòng 101A3 tòa A3', 54, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (32, '102A3', 'Phòng 102A3 tòa A3', 55, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (33, '103A3', 'Phòng 103A3 tòa A3', 56, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (34, '104A3', 'Phòng 104A3 tòa A3', 57, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (35, '105A3', 'Phòng 105A3 tòa A3', 58, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (36, '106A3', 'Phòng 106A3 tòa A3', 59, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (37, '107A3', 'Phòng 107A3 tòa A3', 60, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (38, '108A3', 'Phòng 108A3 tòa A3', 61, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (39, '109A3', 'Phòng 109A3 tòa A3', 62, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (40, '110A3', 'Phòng 110A3 tòa A3', 63, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (41, '111A3', 'Phòng 111A3 tòa A3', 64, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (42, '112A3', 'Phòng 112A3 tòa A3', 65, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (43, '113A3', 'Phòng 113A3 tòa A3', 66, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (44, '114A3', 'Phòng 114A3 tòa A3', 67, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (45, '115A3', 'Phòng 115A3 tòa A3', 68, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (46, '116A3', 'Phòng 116A3 tòa A3', 69, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (47, '117A3', 'Phòng 117A3 tòa A3', 70, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (48, '118A3', 'Phòng 118A3 tòa A3', 71, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (49, '119A3', 'Phòng 119A3 tòa A3', 72, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (50, '120A3', 'Phòng 120A3 tòa A3', 73, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (51, '121A3', 'Phòng 121A3 tòa A3', 74, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (52, '122A3', 'Phòng 122A3 tòa A3', 75, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (53, '123A3', 'Phòng 123A3 tòa A3', 50, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (54, '124A3', 'Phòng 124A3 tòa A3', 51, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (55, '125A3', 'Phòng 125A3 tòa A3', 52, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (56, '126A3', 'Phòng 126A3 tòa A3', 53, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (57, '127A3', 'Phòng 127A3 tòa A3', 54, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (58, '128A3', 'Phòng 128A3 tòa A3', 55, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (59, '129A3', 'Phòng 129A3 tòa A3', 56, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (60, '130A3', 'Phòng 130A3 tòa A3', 57, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (61, '101A4', 'Phòng 101A4 tòa A4', 58, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (62, '102A4', 'Phòng 102A4 tòa A4', 59, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (63, '103A4', 'Phòng 103A4 tòa A4', 60, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (64, '104A4', 'Phòng 104A4 tòa A4', 61, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (65, '105A4', 'Phòng 105A4 tòa A4', 62, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (66, '106A4', 'Phòng 106A4 tòa A4', 63, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (67, '107A4', 'Phòng 107A4 tòa A4', 64, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (68, '108A4', 'Phòng 108A4 tòa A4', 65, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (69, '109A4', 'Phòng 109A4 tòa A4', 66, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (70, '110A4', 'Phòng 110A4 tòa A4', 67, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (71, '111A4', 'Phòng 111A4 tòa A4', 68, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (72, '112A4', 'Phòng 112A4 tòa A4', 69, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (73, '113A4', 'Phòng 113A4 tòa A4', 70, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (74, '114A4', 'Phòng 114A4 tòa A4', 71, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (75, '115A4', 'Phòng 115A4 tòa A4', 72, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (76, '116A4', 'Phòng 116A4 tòa A4', 73, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (77, '117A4', 'Phòng 117A4 tòa A4', 74, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (78, '118A4', 'Phòng 118A4 tòa A4', 75, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (79, '119A4', 'Phòng 119A4 tòa A4', 50, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (80, '120A4', 'Phòng 120A4 tòa A4', 51, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (81, '121A4', 'Phòng 121A4 tòa A4', 52, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (82, '122A4', 'Phòng 122A4 tòa A4', 53, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (83, '123A4', 'Phòng 123A4 tòa A4', 54, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (84, '124A4', 'Phòng 124A4 tòa A4', 55, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (85, '125A4', 'Phòng 125A4 tòa A4', 56, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (86, '126A4', 'Phòng 126A4 tòa A4', 57, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (87, '127A4', 'Phòng 127A4 tòa A4', 58, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (88, '128A4', 'Phòng 128A4 tòa A4', 59, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (89, '129A4', 'Phòng 129A4 tòa A4', 60, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (90, '130A4', 'Phòng 130A4 tòa A4', 61, 0, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.semesters
CREATE TABLE IF NOT EXISTS `semesters`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar
(
    255
) NOT NULL,
    `school_year` varchar
(
    255
) NOT NULL,
    `start_date` datetime NOT NULL,
    `end_date` datetime NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.semesters: ~5 rows (approximately)
INSERT INTO `semesters` (`id`, `name`, `school_year`, `start_date`, `end_date`, `deleted_at`, `created_at`,
                         `updated_at`)
VALUES (1, 'Học kỳ 1', '2022-2023', '2022-09-05 00:00:00', '2022-12-31 23:59:59', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (2, 'Học kỳ 2', '2022-2023', '2023-02-06 00:00:00', '2023-06-03 23:59:59', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (3, 'Học kỳ 1', '2023-2024', '2023-09-04 00:00:00', '2023-12-30 23:59:59', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (4, 'Học kỳ 2', '2023-2024', '2024-02-05 00:00:00', '2024-06-01 23:59:59', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08'),
       (5, 'Học kỳ 1', '2024-2025', '2024-09-02 00:00:00', '2024-12-28 23:59:59', NULL, '2025-07-07 14:29:08',
        '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.sessions
CREATE TABLE IF NOT EXISTS `sessions`
(
    `id`
    varchar
(
    255
) NOT NULL,
    `user_id` bigint
(
    20
) unsigned DEFAULT NULL,
    `ip_address` varchar
(
    45
) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `payload` longtext NOT NULL,
    `last_activity` int
(
    11
) NOT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `sessions_user_id_index`
(
    `user_id`
),
    KEY `sessions_last_activity_index`
(
    `last_activity`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.sessions: ~0 rows (approximately)

-- Dumping structure for table ql_shl.students
CREATE TABLE IF NOT EXISTS `students`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint
(
    20
) unsigned NOT NULL,
    `cohort_id` bigint
(
    20
) unsigned NOT NULL,
    `study_class_id` bigint
(
    20
) unsigned NOT NULL,
    `student_code` varchar
(
    255
) NOT NULL,
    `position` tinyint
(
    4
) NOT NULL COMMENT '0: sinh viên; 1: Lớp trưởng; 2: Lớp Phó; 3: Bí thư',
    `note` text DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `students_user_id_unique`
(
    `user_id`
),
    UNIQUE KEY `students_student_code_unique`
(
    `student_code`
),
    KEY `students_cohort_id_foreign`
(
    `cohort_id`
),
    KEY `students_study_class_id_foreign`
(
    `study_class_id`
),
    CONSTRAINT `students_cohort_id_foreign` FOREIGN KEY
(
    `cohort_id`
) REFERENCES `cohorts`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `students_study_class_id_foreign` FOREIGN KEY
(
    `study_class_id`
) REFERENCES `study_classes`
(
    `id`
)
  ON DELETE CASCADE,
    CONSTRAINT `students_user_id_foreign` FOREIGN KEY
(
    `user_id`
) REFERENCES `users`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.students: ~40 rows (approximately)
INSERT INTO `students` (`id`, `user_id`, `cohort_id`, `study_class_id`, `student_code`, `position`, `note`,
                        `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 11, 2, 1, '1123581321', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 12, 2, 1, '3141592653', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 13, 2, 1, '2718281828', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 14, 2, 1, '1618033988', 0, 'Sinh viên thường', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 15, 2, 1, '1234567890', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 16, 2, 1, '0987654321', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 19, 2, 2, '1414213562', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 20, 2, 2, '1732050807', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 21, 2, 2, '2109876543', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 22, 2, 2, '2468101214', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 23, 2, 2, '1357924680', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 24, 2, 2, '1597534681', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 26, 3, 3, '2581473693', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 27, 3, 3, '3692581470', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 28, 3, 3, '1472583691', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 29, 3, 3, '3216549872', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 30, 3, 3, '4567891234', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 31, 3, 3, '6543219876', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 32, 4, 4, '7894561230', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 33, 4, 4, '9871236543', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 34, 4, 4, '1234567809', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 35, 4, 4, '5432109876', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 36, 4, 4, '6547893210', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 37, 4, 4, '9876541232', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 39, 1, 5, '5432109877', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 40, 1, 5, '6789012345', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, 41, 1, 5, '7890123456', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, 42, 1, 5, '8901234567', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, 43, 1, 5, '9012345678', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, 44, 1, 5, '0123456789', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (31, 45, 2, 6, '2345678901', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (32, 46, 2, 6, '3456789012', 2, 'Lớp phó', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (33, 47, 2, 6, '4567890123', 3, 'Bí thư', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (34, 48, 2, 6, '5678901234', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (35, 49, 2, 6, '6789012346', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (36, 50, 2, 6, '7890123467', 0, NULL, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (37, 51, 3, 7, '8901234678', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (38, 52, 3, 8, '9012346789', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (39, 53, 1, 9, '0123467890', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (40, 54, 4, 10, '1234678901', 1, 'Lớp trưởng', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.student_conduct_scores
CREATE TABLE IF NOT EXISTS `student_conduct_scores`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `conduct_evaluation_period_id` bigint
(
    20
) unsigned NOT NULL COMMENT 'Mã kỳ đánh giá hạnh kiểm',
    `student_id` bigint
(
    20
) unsigned NOT NULL,
    `status` tinyint
(
    4
) NOT NULL DEFAULT 0,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `student_conduct_scores_conduct_evaluation_period_id_foreign`
(
    `conduct_evaluation_period_id`
),
    KEY `student_conduct_scores_student_id_foreign`
(
    `student_id`
),
    CONSTRAINT `student_conduct_scores_conduct_evaluation_period_id_foreign` FOREIGN KEY
(
    `conduct_evaluation_period_id`
) REFERENCES `conduct_evaluation_periods`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `student_conduct_scores_student_id_foreign` FOREIGN KEY
(
    `student_id`
) REFERENCES `students`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.student_conduct_scores: ~160 rows (approximately)
INSERT INTO `student_conduct_scores` (`id`, `conduct_evaluation_period_id`, `student_id`, `status`, `deleted_at`,
                                      `created_at`, `updated_at`)
VALUES (1, 1, 1, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 2, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 1, 3, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 1, 4, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 1, 5, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 1, 6, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 1, 7, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 1, 8, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 1, 9, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 1, 10, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 1, 11, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 1, 12, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 1, 13, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 1, 14, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 1, 15, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 1, 16, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 1, 17, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 1, 18, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 1, 19, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 1, 20, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 1, 21, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 1, 22, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 1, 23, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 1, 24, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 1, 25, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 1, 26, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, 1, 27, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, 1, 28, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, 1, 29, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, 1, 30, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (31, 1, 31, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (32, 1, 32, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (33, 1, 33, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (34, 1, 34, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (35, 1, 35, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (36, 1, 36, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (37, 1, 37, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (38, 1, 38, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (39, 1, 39, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (40, 1, 40, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (41, 2, 1, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (42, 2, 2, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (43, 2, 3, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (44, 2, 4, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (45, 2, 5, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (46, 2, 6, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (47, 2, 7, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (48, 2, 8, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (49, 2, 9, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (50, 2, 10, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (51, 2, 11, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (52, 2, 12, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (53, 2, 13, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (54, 2, 14, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (55, 2, 15, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (56, 2, 16, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (57, 2, 17, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (58, 2, 18, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (59, 2, 19, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (60, 2, 20, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (61, 2, 21, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (62, 2, 22, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (63, 2, 23, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (64, 2, 24, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (65, 2, 25, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (66, 2, 26, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (67, 2, 27, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (68, 2, 28, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (69, 2, 29, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (70, 2, 30, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (71, 2, 31, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (72, 2, 32, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (73, 2, 33, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (74, 2, 34, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (75, 2, 35, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (76, 2, 36, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (77, 2, 37, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (78, 2, 38, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (79, 2, 39, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (80, 2, 40, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (81, 3, 1, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (82, 3, 2, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (83, 3, 3, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (84, 3, 4, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (85, 3, 5, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (86, 3, 6, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (87, 3, 7, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (88, 3, 8, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (89, 3, 9, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (90, 3, 10, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (91, 3, 11, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (92, 3, 12, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (93, 3, 13, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (94, 3, 14, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (95, 3, 15, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (96, 3, 16, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (97, 3, 17, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (98, 3, 18, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (99, 3, 19, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (100, 3, 20, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (101, 3, 21, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (102, 3, 22, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (103, 3, 23, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (104, 3, 24, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (105, 3, 25, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (106, 3, 26, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (107, 3, 27, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (108, 3, 28, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (109, 3, 29, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (110, 3, 30, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (111, 3, 31, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (112, 3, 32, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (113, 3, 33, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (114, 3, 34, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (115, 3, 35, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (116, 3, 36, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (117, 3, 37, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (118, 3, 38, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (119, 3, 39, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (120, 3, 40, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (121, 4, 1, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (122, 4, 2, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (123, 4, 3, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (124, 4, 4, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (125, 4, 5, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (126, 4, 6, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (127, 4, 7, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (128, 4, 8, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (129, 4, 9, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (130, 4, 10, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (131, 4, 11, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (132, 4, 12, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (133, 4, 13, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (134, 4, 14, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (135, 4, 15, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (136, 4, 16, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (137, 4, 17, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (138, 4, 18, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (139, 4, 19, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (140, 4, 20, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (141, 4, 21, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (142, 4, 22, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (143, 4, 23, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (144, 4, 24, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (145, 4, 25, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (146, 4, 26, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (147, 4, 27, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (148, 4, 28, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (149, 4, 29, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (150, 4, 30, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (151, 4, 31, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (152, 4, 32, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (153, 4, 33, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (154, 4, 34, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (155, 4, 35, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (156, 4, 36, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (157, 4, 37, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (158, 4, 38, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (159, 4, 39, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (160, 4, 40, 2, NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.study_classes
CREATE TABLE IF NOT EXISTS `study_classes`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `major_id` bigint
(
    20
) unsigned NOT NULL,
    `cohort_id` bigint
(
    20
) unsigned NOT NULL,
    `lecturer_id` bigint
(
    20
) unsigned NOT NULL,
    `name` varchar
(
    255
) NOT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `study_classes_major_id_foreign`
(
    `major_id`
),
    KEY `study_classes_lecturer_id_foreign`
(
    `lecturer_id`
),
    KEY `study_classes_cohort_id_foreign`
(
    `cohort_id`
),
    CONSTRAINT `study_classes_cohort_id_foreign` FOREIGN KEY
(
    `cohort_id`
) REFERENCES `cohorts`
(
    `id`
) ON DELETE CASCADE,
    CONSTRAINT `study_classes_lecturer_id_foreign` FOREIGN KEY
(
    `lecturer_id`
) REFERENCES `lecturers`
(
    `id`
)
  ON DELETE CASCADE,
    CONSTRAINT `study_classes_major_id_foreign` FOREIGN KEY
(
    `major_id`
) REFERENCES `majors`
(
    `id`
)
  ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.study_classes
INSERT INTO `study_classes` (`id`, `major_id`, `cohort_id`, `lecturer_id`, `name`, `deleted_at`, `created_at`,
                             `updated_at`)
VALUES (1, 1, 2, 1, '63KTPM1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (2, 1, 2, 1, '63KTPM2', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 2, 3, 1, '64ATTT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 2, 4, 1, '65ATTT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 3, 1, 2, '62HTTT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 3, 2, 2, '63HTTT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 4, 4, 3, '65KHDL1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 5, 5, 3, '66TTNT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 6, 3, 4, '64DTVT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 6, 5, 4, '66DTVT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 7, 2, 5, '63DCN1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 8, 4, 6, '65KTDK1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 9, 5, 6, '66KTRB1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 10, 1, 7, '62CKCT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 11, 3, 8, '64KTO1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 12, 2, 1, '63QTKD1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 13, 4, 2, '65TMĐT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 14, 5, 2, '66KTXD1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 15, 3, 2, '64KX1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 16, 1, 3, '62KXGT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 17, 2, 3, '63KMT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 18, 4, 4, '65HHUD1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 19, 5, 5, '66KTTN1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 20, 3, 6, '64QLTN1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 21, 2, 6, '63DTQT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 22, 1, 7, '62KT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, 23, 4, 7, '65KDQT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, 24, 5, 8, '66LKT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, 25, 3, 8, '64LLCT1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, 26, 2, 8, '63LOG1', NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08');

-- Dumping structure for table ql_shl.users
CREATE TABLE IF NOT EXISTS `users`
(
    `id`
    bigint
(
    20
) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar
(
    255
) DEFAULT NULL,
    `email` varchar
(
    255
) NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar
(
    255
) NOT NULL,
    `date_of_birth` date DEFAULT NULL,
    `gender` varchar
(
    255
) DEFAULT NULL,
    `phone` varchar
(
    255
) DEFAULT NULL,
    `role` tinyint
(
    4
) NOT NULL DEFAULT 0,
    `remember_token` varchar
(
    100
) DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY
(
    `id`
),
    UNIQUE KEY `users_email_unique`
(
    `email`
)
    ) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

-- Dumping data for table ql_shl.users: ~58 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `date_of_birth`, `gender`, `phone`, `role`,
                     `remember_token`, `deleted_at`, `created_at`, `updated_at`)
VALUES (1, 'Phòng Chính trị và Công tác Sinh viên', 'p7@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345678', 3, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (3, 'Phạm Văn Giảng', 'phamvangiang@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1975-03-10', 'Nam', '0912345680', 1, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (4, 'Lê Thị Hương', 'lethihuong@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1982-11-25', 'Nữ', '0912345681', 1, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (5, 'Hoàng Văn Khoa', 'hoangvankhoa@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1978-07-30', 'Nam', '0912345682', 1, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (6, 'Vũ Thị Lan', 'vuthilan@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1983-04-05', 'Nữ', '0912345683', 1, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (7, 'Đặng Văn Minh', 'dangvanminh@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1970-09-12', 'Nam', '0912345684', 1, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (8, 'Bùi Thị Ngọc', 'buithingoc@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1987-12-18', 'Nữ', '0912345685', 1, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (9, 'Ngô Văn Phúc', 'ngovanphuc@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1973-06-22', 'Nam', '0912345686', 1, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (10, 'Đỗ Thị Quỳnh', 'dothiquynh@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '1984-02-28', 'Nữ', '0912345687', 1, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (11, 'Trần Văn An', '1123581321@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-01-15', 'Nam', '0912345688', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (12, 'Lê Thị Bình', '3141592653@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2001-03-20', 'Nữ', '0912345689', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (13, 'Phạm Văn Cường', '2718281828@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-05-10', 'Nam', '0912345690', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (14, 'Hoàng Thị Dung', '1618033988@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2001-07-25', 'Nữ', '0912345691', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (15, 'Nguyễn Văn Hùng', '1234567890@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-02-14', 'Nam', '0912345717', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (16, 'Trần Thị Mai', '0987654321@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2001-09-05', 'Nữ', '0912345718', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (17, 'Lê Văn Tâm', '1122334455@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-06-20', 'Nam', '0912345719', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (18, 'Phạm Thị Ngọc', '5566778899@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2001-11-12', 'Nữ', '0912345720', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (19, 'Nguyễn Văn Đạt', '1414213562@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-04-12', 'Nam', '0912345692', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (20, 'Trần Thị Em', '1732050807@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-08-30', 'Nữ', '0912345693', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (21, 'Lê Văn Phúc', '2109876543@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-11-05', 'Nam', '0912345694', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (22, 'Phạm Thị Giang', '2468101214@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-02-18', 'Nữ', '0912345695', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (23, 'Hoàng Văn Hải', '1357924680@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-06-22', 'Nam', '0912345696', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (24, 'Vũ Thị Kim', '1597534681@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-09-14', 'Nữ', '0912345697', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (25, 'Đặng Văn Long', '3579514682@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-12-08', 'Nam', '0912345698', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (26, 'Bùi Thị Mai', '2581473693@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-01-25', 'Nữ', '0912345699', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (27, 'Ngô Văn Nam', '3692581470@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-07-19', 'Nam', '0912345700', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (28, 'Đỗ Thị Oanh', '1472583691@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-10-11', 'Nữ', '0912345701', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (29, 'Trịnh Văn Phú', '3216549872@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-03-28', 'Nam', '0912345702', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (30, 'Lý Thị Quyên', '4567891234@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-05-15', 'Nữ', '0912345703', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (31, 'Vương Văn Sơn', '6543219876@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-08-07', 'Nam', '0912345704', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (32, 'Đinh Thị Tuyết', '7894561230@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-04-30', 'Nữ', '0912345705', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (33, 'Mai Văn Uy', '9871236543@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-11-22', 'Nam', '0912345706', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (34, 'Hồ Thị Vân', '1237894567@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-02-14', 'Nữ', '0912345707', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (35, 'Chu Văn Xuân', '3219876541@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-06-05', 'Nam', '0912345708', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (36, 'Lưu Thị Yến', '6547893210@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-09-28', 'Nữ', '0912345709', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (37, 'Trương Văn Anh', '9876541232@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-01-17', 'Nam', '0912345710', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (38, 'Phan Thị Bích', '1234567809@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-07-10', 'Nữ', '0912345711', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (39, 'Nguyễn Văn Bình', '5432109876@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2004-01-10', 'Nam', '0912345721', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (40, 'Trần Thị Hồng', '6789012345@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-03-15', 'Nữ', '0912345722', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (41, 'Lê Văn Đức', '7890123456@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-08-22', 'Nam', '0912345723', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (42, 'Phạm Thị Lan', '8901234567@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-05-30', 'Nữ', '0912345724', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (43, 'Hoàng Văn Nam', '9012345678@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-12-12', 'Nam', '0912345725', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (44, 'Vũ Thị Thanh', '0123456789@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-07-18', 'Nữ', '0912345726', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (45, 'Đặng Văn Quang', '2345678901@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-02-25', 'Nam', '0912345727', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (46, 'Bùi Thị Hoa', '3456789012@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-10-05', 'Nữ', '0912345728', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (47, 'Ngô Văn Khánh', '4567890123@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-04-15', 'Nam', '0912345729', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (48, 'Đỗ Thị Minh', '5678901234@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-06-20', 'Nữ', '0912345730', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (49, 'Trịnh Văn Tuấn', '6789012346@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-11-30', 'Nam', '0912345731', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (50, 'Lý Thị Ngọc', '7890123467@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-01-22', 'Nữ', '0912345732', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (51, 'Vương Văn Hùng', '8901234678@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-09-10', 'Nam', '0912345733', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (52, 'Đinh Thị Hương', '9012346789@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-04-28', 'Nữ', '0912345734', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (53, 'Mai Văn Thành', '0123467890@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2002-07-15', 'Nam', '0912345735', 0, NULL,
        NULL, '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (54, 'Hồ Thị Linh', '1234678901@e.tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', '2003-12-05', 'Nữ', '0912345736', 0, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (55, 'Văn phòng Khoa Công nghệ thông tin', 'vpkcntt@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345712', 2, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (56, 'Văn phòng Khoa Cơ khí', 'khoak@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345713', 2, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (57, 'Văn phòng Khoa Công trình', 'vpkhoacongtrinh@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345714', 2, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (58, 'Văn phòng Khoa Môi trường', 'vpkhoamt@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345715', 2, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08'),
       (59, 'Văn phòng Khoa Kinh tế và Quản lý', 'khoam@tlu.edu.vn', '2025-07-07 14:29:08',
        '$2y$12$dvG2kMymTMJYYY.cjI5B6unPnk/SwM1iFQF9li9/v14mXd3ODDmcO', NULL, NULL, '0912345716', 2, NULL, NULL,
        '2025-07-07 14:29:08', '2025-07-07 14:29:08');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
