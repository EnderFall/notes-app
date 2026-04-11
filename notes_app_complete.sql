-- ============================================
-- NOTES APP - COMPLETE DATABASE STRUCTURE
-- Version: 1.0
-- Date: April 10, 2026
-- Description: Complete fresh installation for Notes Taking App
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TABLE 1: el_category (Categories for notes)
-- ============================================
DROP TABLE IF EXISTS `el_category`;
CREATE TABLE `el_category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#007BFF',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'bi-folder',
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Default categories for students
INSERT INTO `el_category` VALUES 
(1, 'Personal', 'Personal notes and reminders', '#FF6B6B', 'bi-person-circle', 0),
(2, 'Study', 'Study materials and lecture notes', '#4ECDC4', 'bi-book', 0),
(3, 'Work', 'Work-related notes and tasks', '#45B7D1', 'bi-briefcase', 0),
(4, 'Projects', 'Project planning and ideas', '#FFA07A', 'bi-kanban', 0),
(5, 'Research', 'Research notes and references', '#98D8C8', 'bi-search', 0),
(6, 'Ideas', 'Creative ideas and brainstorming', '#F7DC6F', 'bi-lightbulb', 0),
(7, 'Meeting Notes', 'Meeting summaries and action items', '#BB8FCE', 'bi-calendar-range', 0),
(8, 'Quick Notes', 'Quick thoughts and reminders', '#85C1E2', 'bi-sticky', 0);

-- ============================================
-- TABLE 2: el_divisi (Division/Department)
-- ============================================
DROP TABLE IF EXISTS `el_divisi`;
CREATE TABLE `el_divisi` (
  `id_divisi` int NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_divisi`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Default divisions
INSERT INTO `el_divisi` VALUES 
(2, 'E.L.F', 0),
(3, 'K17', 0),
(4, 'Developer', 0),
(7, 'Customer Service', 0),
(8, 'Marketing', 0);

-- ============================================
-- TABLE 3: el_notes (Main notes table)
-- ============================================
DROP TABLE IF EXISTS `el_notes`;
CREATE TABLE `el_notes` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `category` int DEFAULT NULL,
  `tags` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_note`) USING BTREE,
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_at`),
  KEY `idx_status` (`status_delete`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ============================================
-- TABLE 4: el_form (Menu/Navigation)
-- ============================================
DROP TABLE IF EXISTS `el_form`;
CREATE TABLE `el_form` (
  `id_form` int NOT NULL AUTO_INCREMENT,
  `deskripsi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `route` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `jenis_form` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  `icon` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id_form`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Menu items
INSERT INTO `el_form` VALUES 
(1, 'Notes', 'rapat', 'table', 0, 'bi bi-journal-text'),
(2, 'Divisi', 'divisi', 'table', 0, 'bi bi-people-fill'),
(3, 'Category', 'category', 'table', 0, 'bi bi-tags-fill'),
(4, 'User', 'user', 'table', 0, 'bi bi-person-fill'),
(5, 'Hak Akses', 'hak_akses', 'table', 0, 'bi bi-shield-check'),
(6, 'Log Activity', 'log_activity', 'table', 0, 'bi bi-journal-text'),
(7, 'Settings', 'setting', 'input', 0, 'bi bi-gear');

-- ======5=====================================
-- TABLE 4: el_group_form (Permissions)
-- ============================================
DROP TABLE IF EXISTS `el_group_form`;
CREATE TABLE `el_group_form` (
  `id_form` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `can_read` bit(1) DEFAULT NULL,
  `can_create` bit(1) DEFAULT NULL,
  `can_approve` bit(1) DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_form`,`id_level`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Permissions setup
INSERT INTO `el_group_form` VALUES 
('1', '1', b'1', b'1', b'0', 0),
('1', '2', b'1', b'1', b'0', 0),
('1', '3', b'1', b'1', b'0', 0),
('1', '4', b'1', b'1', b'1', 0),
('2', '1', b'1', b'1', b'0', 0),
('2', '4', b'1', b'1', b'1', 0),
('3', '1', b'1', b'1', b'0', 0),
('3', '4', b'1', b'1', b'1', 0),
('4', '1', b'1', b'1', b'0', 0),
('4', '4', b'1', b'1', b'1', 0),
('5', '4', b'1', b'1', b'1', 0),
('6', '1', b'1', b'1', b'0', 0),
('6', '2', b'1', b'0', b'0', 0),
('6', '3', b'1', b'0', b'0', 0),
('6', '4', b'1', b'1', b'1', 0),
('7', '4', b'1', b'1', b'1', 0);

-- ============================================
-- TABLE 5: el_hak_akses_user (User Access Rights)
-- ============================================
DROP TABLE IF EXISTS `el_hak_akses_user`;
CREATE TABLE `el_hak_akses_user` (
  `id_hak_akses` int NOT NULL AUTO_INCREMENT,
  `id_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_hak_akses`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Default admin access
INSERT INTO `el_hak_akses_user` VALUES 
(1, '1', '4', 0);

-- ============================================
-- TABLE 7: el_level (User Levels/Roles)
-- ============================================
DROP TABLE IF EXISTS `el_level`;
CREATE TABLE `el_level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_level`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- User levels
INSERT INTO `el_level` VALUES 
(1, 'Admin', 0),
(2, 'Editor', 0),
(3, 'Student', 0),
(4, 'SuperAdmin', 0);

-- ============================================
-- TABLE 8: el_log_activity (Activity Logs)
-- ============================================
DROP TABLE IF EXISTS `el_log_activity`;
CREATE TABLE `el_log_activity` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `what_happens` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `happens_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- ============================================
-- TABLE 9: el_peserta (Note Participants/Shared With)
-- ============================================
DROP TABLE IF EXISTS `el_peserta`;
CREATE TABLE `el_peserta` (
  `id_peserta` int NOT NULL AUTO_INCREMENT,
  `id_note` int DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`id_peserta`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ============================================
-- TABLE 10: el_note_transcripts (Note Transcripts/Attachments)
-- ============================================
DROP TABLE IF EXISTS `el_note_transcripts`;
CREATE TABLE `el_note_transcripts` (
  `id_transkrip_rapat` int NOT NULL AUTO_INCREMENT,
  `id_note` int DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `hasil_transkrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `waktu_upload` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_transkrip_rapat`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ============================================
-- TABLE 11: el_user (Users)
-- ============================================
DROP TABLE IF EXISTS `el_user`;
CREATE TABLE `el_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `level` int DEFAULT NULL,
  `divisi` int DEFAULT NULL,
  `default_category` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expiry` datetime DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isApproved` tinyint DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `nomor_hp` (`nomor_hp`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Default admin user (password: 1)
INSERT INTO `el_user` VALUES 
(1, 'admin', '081234567890', 'admin@notesapp.com', 'c4ca4238a0b923820dcc509a6f75849b', 4, 1, 1, NOW(), NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0);

-- ============================================
-- TABLE 12: el_web_detail (Website Settings)
-- ============================================
DROP TABLE IF EXISTS `el_web_detail`;
CREATE TABLE `el_web_detail` (
  `id_wdetail` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_wdetail`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Default settings
INSERT INTO `el_web_detail` VALUES 
(1, 'Notes Assistant', NULL);

-- ============================================
-- COMPLETE!
-- ============================================
SET FOREIGN_KEY_CHECKS = 1;

-- Verification queries
SELECT 'Database setup complete!' AS Status;
SELECT COUNT(*) AS Total_Categories FROM el_category;
SELECT COUNT(*) AS Total_Users FROM el_user;
SELECT COUNT(*) AS Total_Menu_Items FROM el_form;
SELECT 'Default login: admin / Password: 1' AS Login_Info;
