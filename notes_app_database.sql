-- ============================================
-- NOTES APP DATABASE SETUP
-- Version: 1.0
-- Date: April 10, 2026
-- Description: Complete database structure for Notes Taking App
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. CATEGORY TABLE
-- ============================================

DROP TABLE IF EXISTS `el_category`;

CREATE TABLE `el_category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#007BFF',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'bi-folder',
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default categories
INSERT INTO `el_category` (`id_category`, `name`, `description`, `color`, `icon`, `status_delete`) VALUES
(1, 'Personal', 'Personal notes and reminders', '#FF6B6B', 'bi-person-circle', 0),
(2, 'Study', 'Study materials and lecture notes', '#4ECDC4', 'bi-book', 0),
(3, 'Work', 'Work-related notes and tasks', '#45B7D1', 'bi-briefcase', 0),
(4, 'Projects', 'Project planning and ideas', '#FFA07A', 'bi-kanban', 0),
(5, 'Research', 'Research notes and references', '#98D8C8', 'bi-search', 0),
(6, 'Ideas', 'Creative ideas and brainstorming', '#F7DC6F', 'bi-lightbulb', 0),
(7, 'Meeting Notes', 'Meeting summaries and action items', '#BB8FCE', 'bi-calendar-range', 0),
(8, 'Quick Notes', 'Quick thoughts and reminders', '#85C1E2', 'bi-sticky', 0);

-- ============================================
-- 2. NOTES TABLE
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
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_note`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_at`),
  KEY `idx_status` (`status_delete`),
  FULLTEXT KEY `ft_search` (`judul`,`keterangan`,`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- 3. UPDATE FORM MENU
-- ============================================

-- Remove old entries
DELETE FROM `el_form` WHERE `route` IN ('divisi', 'rapat', 'category', 'notes');

-- Insert Notes and Category menu items
INSERT INTO `el_form` (`id_form`, `deskripsi`, `route`, `jenis_form`, `status_delete`, `icon`) VALUES
(1, 'Notes', 'rapat', 'table', 0, 'bi bi-journal-text'),
(2, 'Category', 'category', 'table', 0, 'bi bi-tags-fill');

-- ============================================
-- 4. UPDATE PERMISSIONS
-- ============================================

-- Remove old permissions for these forms
DELETE FROM `el_group_form` WHERE `id_form` IN (1, 2);

-- Add permissions (adjust based on your user levels)
-- Level 1 = Super Admin, Level 2 = Admin, Level 3 = User, Level 4 = Manager
INSERT INTO `el_group_form` (`id_form`, `id_level`, `can_read`, `can_create`, `can_approve`, `status_delete`) VALUES
(1, 1, b'1', b'1', b'1', 0),
(1, 2, b'1', b'1', b'0', 0),
(1, 3, b'1', b'1', b'0', 0),
(1, 4, b'1', b'1', b'1', 0),
(2, 1, b'1', b'1', b'1', 0),
(2, 2, b'1', b'1', b'0', 0),
(2, 4, b'1', b'1', b'1', 0);

-- ============================================
-- COMPLETE!
-- ============================================

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Notes App database setup complete!' AS Status;
SELECT COUNT(*) AS 'Categories Created' FROM el_category;
SELECT 'Table el_notes created successfully' AS Notes_Table;
