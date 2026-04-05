/*
 Navicat Premium Data Transfer

 Source Server         : Elysian Realm
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : permat34_cici

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 05/04/2026 14:42:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for el_divisi
-- ----------------------------
DROP TABLE IF EXISTS `el_divisi`;
CREATE TABLE `el_divisi`  (
  `id_divisi` int NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_divisi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_divisi
-- ----------------------------
INSERT INTO `el_divisi` VALUES (2, 'E.L.F', 0);
INSERT INTO `el_divisi` VALUES (3, 'K17', 0);
INSERT INTO `el_divisi` VALUES (4, 'Developer', 0);
INSERT INTO `el_divisi` VALUES (7, 'Customer Service', 0);
INSERT INTO `el_divisi` VALUES (8, 'Marketing', 0);

-- ----------------------------
-- Table structure for el_form
-- ----------------------------
DROP TABLE IF EXISTS `el_form`;
CREATE TABLE `el_form`  (
  `id_form` int NOT NULL AUTO_INCREMENT,
  `deskripsi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `route` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_form` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  `icon` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_form`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_form
-- ----------------------------
INSERT INTO `el_form` VALUES (2, 'Divisi', 'divisi', 'table', 0, 'bi bi-people-fill');
INSERT INTO `el_form` VALUES (3, 'User', 'user', 'table', 0, 'bi bi-person-fill');
INSERT INTO `el_form` VALUES (4, 'Hak Akses', 'hak_akses', 'table', 0, 'bi bi-shield-check');
INSERT INTO `el_form` VALUES (6, 'Log Activity', 'log_activity', 'table', 0, 'bi bi-journal-text');
INSERT INTO `el_form` VALUES (7, 'Settings', 'setting', 'input', 0, 'bi bi-gear');
INSERT INTO `el_form` VALUES (8, 'Rapat', 'rapat', 'table', 0, 'bi bi-calendar-range');

-- ----------------------------
-- Table structure for el_group_form
-- ----------------------------
DROP TABLE IF EXISTS `el_group_form`;
CREATE TABLE `el_group_form`  (
  `id_form` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `can_read` bit(1) NULL DEFAULT NULL,
  `can_create` bit(1) NULL DEFAULT NULL,
  `can_approve` bit(1) NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_form`, `id_level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_group_form
-- ----------------------------
INSERT INTO `el_group_form` VALUES ('2', '1', b'1', b'1', b'0', 0);
INSERT INTO `el_group_form` VALUES ('2', '4', b'1', b'1', b'1', 0);
INSERT INTO `el_group_form` VALUES ('3', '1', b'1', b'1', b'0', 0);
INSERT INTO `el_group_form` VALUES ('3', '4', b'1', b'1', b'1', 0);
INSERT INTO `el_group_form` VALUES ('4', '4', b'1', b'1', b'1', 0);
INSERT INTO `el_group_form` VALUES ('6', '1', b'1', b'1', b'0', 0);
INSERT INTO `el_group_form` VALUES ('6', '2', b'1', b'0', b'0', 0);
INSERT INTO `el_group_form` VALUES ('6', '3', b'1', b'0', b'0', 0);
INSERT INTO `el_group_form` VALUES ('6', '4', b'1', b'1', b'1', 0);
INSERT INTO `el_group_form` VALUES ('7', '4', b'0', b'0', b'0', 0);
INSERT INTO `el_group_form` VALUES ('8', '1', b'1', b'1', b'0', 0);
INSERT INTO `el_group_form` VALUES ('8', '2', b'1', b'1', b'0', 0);
INSERT INTO `el_group_form` VALUES ('8', '3', b'1', b'0', b'0', 0);
INSERT INTO `el_group_form` VALUES ('8', '4', b'1', b'1', b'1', 0);

-- ----------------------------
-- Table structure for el_hak_akses_user
-- ----------------------------
DROP TABLE IF EXISTS `el_hak_akses_user`;
CREATE TABLE `el_hak_akses_user`  (
  `id_hak_akses` int NOT NULL AUTO_INCREMENT,
  `id_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_hak_akses`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_hak_akses_user
-- ----------------------------
INSERT INTO `el_hak_akses_user` VALUES (1, '31', '1', 0);
INSERT INTO `el_hak_akses_user` VALUES (2, '36', '4', 0);
INSERT INTO `el_hak_akses_user` VALUES (3, '41', '3', 0);
INSERT INTO `el_hak_akses_user` VALUES (4, '50', '3', 0);

-- ----------------------------
-- Table structure for el_level
-- ----------------------------
DROP TABLE IF EXISTS `el_level`;
CREATE TABLE `el_level`  (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_level
-- ----------------------------
INSERT INTO `el_level` VALUES (1, 'Admin', 0);
INSERT INTO `el_level` VALUES (2, 'Sekretaris', 0);
INSERT INTO `el_level` VALUES (3, 'Anggota', 0);
INSERT INTO `el_level` VALUES (4, 'SuperAdmin', 0);
INSERT INTO `el_level` VALUES (56, 'test', 1);

-- ----------------------------
-- Table structure for el_log_activity
-- ----------------------------
DROP TABLE IF EXISTS `el_log_activity`;
CREATE TABLE `el_log_activity`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int NULL DEFAULT NULL,
  `what_happens` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `happens_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 184 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_log_activity
-- ----------------------------
INSERT INTO `el_log_activity` VALUES (120, 36, 'Deleted 87 log activity record(s)', '2025-11-11 09:34:01', '::1');
INSERT INTO `el_log_activity` VALUES (121, 36, 'Edit level 1: Admin dengan permissions', '2025-11-11 09:34:40', '::1');
INSERT INTO `el_log_activity` VALUES (122, 36, 'Edit level 2: Sekretaris dengan permissions', '2025-11-11 09:35:03', '::1');
INSERT INTO `el_log_activity` VALUES (123, 36, 'Edit level 3: Anggota dengan permissions', '2025-11-11 09:35:12', '::1');
INSERT INTO `el_log_activity` VALUES (124, 31, 'Mengupdate divisi ID: 2 menjadi: E.L.Fs', '2025-11-11 09:42:12', '::1');
INSERT INTO `el_log_activity` VALUES (125, 31, 'Mengupdate divisi ID: 2 menjadi: E.L.F', '2025-11-11 09:42:20', '::1');
INSERT INTO `el_log_activity` VALUES (126, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 02:18:27', '::1');
INSERT INTO `el_log_activity` VALUES (127, 36, 'Menghapus rapat ID: 7 (soft delete)', '2025-11-12 02:39:52', '::1');
INSERT INTO `el_log_activity` VALUES (128, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 02:39:54', '::1');
INSERT INTO `el_log_activity` VALUES (129, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 02:39:55', '::1');
INSERT INTO `el_log_activity` VALUES (130, 36, 'Menghapus permanen rapat ID: 7 dan semua data terkait (peserta, transkrip, audio)', '2025-11-12 02:40:01', '::1');
INSERT INTO `el_log_activity` VALUES (131, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 02:40:01', '::1');
INSERT INTO `el_log_activity` VALUES (132, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 02:48:08', '::1');
INSERT INTO `el_log_activity` VALUES (133, 36, 'Menghapus beberapa transkrip rapat: 21, 20', '2025-11-12 03:04:18', '::1');
INSERT INTO `el_log_activity` VALUES (134, 36, 'Menghapus beberapa transkrip rapat: 22', '2025-11-12 03:04:27', '::1');
INSERT INTO `el_log_activity` VALUES (135, 36, 'Menghapus beberapa transkrip rapat: 33', '2025-11-12 03:07:08', '::1');
INSERT INTO `el_log_activity` VALUES (136, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 03:07:40', '::1');
INSERT INTO `el_log_activity` VALUES (137, 36, 'Mengakses Tabel Data Data rapat yang Dihapus', '2025-11-12 03:07:40', '::1');
INSERT INTO `el_log_activity` VALUES (138, 36, 'Mengupdate user: Elysia (ID: 36)', '2025-11-12 03:21:02', '::1');
INSERT INTO `el_log_activity` VALUES (139, 36, 'Mengupdate user: Kevin Kaslana (ID: 48)', '2025-11-12 03:44:01', '::1');
INSERT INTO `el_log_activity` VALUES (140, 36, 'Mengupdate user: Kevin Kaslana (ID: 48)', '2025-11-12 03:44:01', '::1');
INSERT INTO `el_log_activity` VALUES (141, 36, 'Mengupdate user: Kevin Kaslana (ID: 48)', '2025-11-12 03:44:19', '::1');
INSERT INTO `el_log_activity` VALUES (142, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 04:18:32', '::1');
INSERT INTO `el_log_activity` VALUES (143, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 04:18:32', '::1');
INSERT INTO `el_log_activity` VALUES (144, 50, 'Mengupdate profil: Raiden Mei (ID: 50)', '2025-11-12 04:20:43', '::1');
INSERT INTO `el_log_activity` VALUES (145, 36, 'Mengupdate user: Raiden Mei (ID: 50)', '2025-11-12 04:29:28', '::1');
INSERT INTO `el_log_activity` VALUES (146, 36, 'Mengupdate user: Raiden Mei (ID: 50)', '2025-11-12 04:29:42', '::1');
INSERT INTO `el_log_activity` VALUES (147, 31, 'Mengupdate profil: Ely (ID: 31)', '2025-11-12 04:57:33', '::1');
INSERT INTO `el_log_activity` VALUES (148, 31, 'Mengupdate profil: Ely (ID: 31)', '2025-11-12 04:57:38', '::1');
INSERT INTO `el_log_activity` VALUES (149, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:20:23', '::1');
INSERT INTO `el_log_activity` VALUES (150, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:21:05', '::1');
INSERT INTO `el_log_activity` VALUES (151, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:26:48', '::1');
INSERT INTO `el_log_activity` VALUES (152, 36, 'Reset password user: Elysia (ID: 36)', '2025-11-12 06:27:05', '::1');
INSERT INTO `el_log_activity` VALUES (153, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:27:52', '::1');
INSERT INTO `el_log_activity` VALUES (154, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:28:01', '::1');
INSERT INTO `el_log_activity` VALUES (155, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:28:04', '::1');
INSERT INTO `el_log_activity` VALUES (156, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:28:14', '::1');
INSERT INTO `el_log_activity` VALUES (157, 36, 'Mengupdate user: Raiden Mei (ID: 50)', '2025-11-12 06:36:27', '::1');
INSERT INTO `el_log_activity` VALUES (158, 36, 'Mengupdate user: Raiden Mei (ID: 50)', '2025-11-12 06:36:35', '::1');
INSERT INTO `el_log_activity` VALUES (159, 36, 'Mengupdate user: Ely (ID: 31)', '2025-11-12 06:38:05', '::1');
INSERT INTO `el_log_activity` VALUES (160, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:50:37', '::1');
INSERT INTO `el_log_activity` VALUES (161, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:50:52', '::1');
INSERT INTO `el_log_activity` VALUES (162, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 06:50:53', '::1');
INSERT INTO `el_log_activity` VALUES (163, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 07:05:41', '::1');
INSERT INTO `el_log_activity` VALUES (164, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 07:06:09', '::1');
INSERT INTO `el_log_activity` VALUES (165, 36, 'Mengupdate user: Raiden Mei (ID: 50)', '2025-11-12 07:17:06', '::1');
INSERT INTO `el_log_activity` VALUES (166, 36, 'Mengupdate user: Ely (ID: 31)', '2025-11-12 07:20:26', '::1');
INSERT INTO `el_log_activity` VALUES (167, 36, 'Mengupdate user: Kevin Kaslana (ID: 48)', '2025-11-12 07:20:56', '::1');
INSERT INTO `el_log_activity` VALUES (168, 36, 'Mengupdate profil: Elysia (ID: 36)', '2025-11-12 07:50:39', '::1');
INSERT INTO `el_log_activity` VALUES (179, 36, 'Deleted 10 log activity record(s)', '2025-12-01 02:35:59', '36.68.178.159');
INSERT INTO `el_log_activity` VALUES (180, 36, 'Mengupdate pengaturan website: title=Panpan', '2025-12-01 02:36:11', '36.68.178.159');
INSERT INTO `el_log_activity` VALUES (181, 36, 'Mengupdate pengaturan website: title=Ellie Secretary', '2025-12-01 02:36:24', '36.68.178.159');
INSERT INTO `el_log_activity` VALUES (182, 36, 'Menambahkan rapat baru: Persiapan SIdang', '2025-12-01 02:37:08', '36.68.178.159');
INSERT INTO `el_log_activity` VALUES (183, 36, 'Mengupdate rapat: Persiapan SIdang (ID: 12)', '2025-12-01 02:38:45', '36.68.178.159');

-- ----------------------------
-- Table structure for el_peserta
-- ----------------------------
DROP TABLE IF EXISTS `el_peserta`;
CREATE TABLE `el_peserta`  (
  `id_peserta` int NOT NULL AUTO_INCREMENT,
  `id_rapat` int NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_user` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_peserta`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_peserta
-- ----------------------------
INSERT INTO `el_peserta` VALUES (1, 1, NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (3, 1, NULL, NULL, 25, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (4, 3, NULL, NULL, 26, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (7, 4, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (9, 6, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (10, 6, NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (11, 6, NULL, NULL, 35, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (12, 6, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (17, 4, NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (18, 6, NULL, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (19, 6, NULL, NULL, 43, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (20, 9, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (21, 9, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (22, 10, NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (23, 10, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (24, 10, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (26, 11, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (27, 11, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (29, 4, NULL, NULL, 50, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (30, 4, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (31, 12, NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `el_peserta` VALUES (32, 12, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for el_rapat
-- ----------------------------
DROP TABLE IF EXISTS `el_rapat`;
CREATE TABLE `el_rapat`  (
  `id_rapat` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tanggal` datetime NULL DEFAULT NULL,
  `lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `divisi` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_rapat`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_rapat
-- ----------------------------
INSERT INTO `el_rapat` VALUES (4, 'Kegiatan 17 Agustus', '2025-08-25 17:46:00', 'Lantai 3', 'Membicarakan tentang kegiatan untuk memperingati kemerdekaan yippie', 2, '2025-08-23 10:47:25', NULL, '2025-11-10 08:40:35', NULL, NULL, NULL, 0);
INSERT INTO `el_rapat` VALUES (6, 'Graduation', '2025-09-14 16:58:00', 'Office', 'Pembagian tugas panitia', 3, '2025-09-14 09:58:28', NULL, '2025-09-14 09:58:42', NULL, NULL, NULL, 0);
INSERT INTO `el_rapat` VALUES (9, 'Test Early Warning System', '2025-11-10 10:38:00', 'Kantor utama', 'test', 4, '2025-11-10 02:38:05', NULL, NULL, NULL, NULL, NULL, 0);
INSERT INTO `el_rapat` VALUES (10, 'Test Early Warning System 2', '2025-11-10 11:00:00', 'Kantor utama', 'Test2', 3, '2025-11-10 03:01:15', NULL, NULL, NULL, NULL, NULL, 0);
INSERT INTO `el_rapat` VALUES (11, 'Breefing', '2025-11-10 12:00:00', 'Kantin', 'Melihat kembali kesalahan dan perbaikan', 4, '2025-11-10 04:15:14', NULL, NULL, NULL, NULL, NULL, 0);
INSERT INTO `el_rapat` VALUES (12, 'Persiapan SIdang', '2025-12-01 12:36:00', 'Lab Komputer', 'Membicarakan tentang apa saja yang perlu dipersiapkan', 4, '2025-12-01 02:37:08', NULL, '2025-12-01 02:38:44', NULL, NULL, NULL, 0);

-- ----------------------------
-- Table structure for el_transkrip_rapat
-- ----------------------------
DROP TABLE IF EXISTS `el_transkrip_rapat`;
CREATE TABLE `el_transkrip_rapat`  (
  `id_transkrip_rapat` int NOT NULL AUTO_INCREMENT,
  `id_rapat` int NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `hasil_transkrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `waktu_upload` datetime NULL DEFAULT NULL,
  `waktu_selesai` datetime NULL DEFAULT NULL,
  `id_user` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_transkrip_rapat`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_transkrip_rapat
-- ----------------------------
INSERT INTO `el_transkrip_rapat` VALUES (23, 4, '1757490800_3bf08a214acd3d71930b.wav', 'Sebuah Judul', 'Selamat sore rapat hari ini akan membahas mengenai penggeledahan rumah. Masukkan nama random sebelum kita mulai. Silahkan dilihat ppt berikut.', '', '2025-09-10 07:53:25', '2025-09-10 07:53:25', 36);
INSERT INTO `el_transkrip_rapat` VALUES (24, 6, '1757844568_95c2f858ab5f27f13150.wav', 'test', 'Selamat sore rapat hari ini akan membahas mengenai penggeledahan rumah. Masukkan nama random sebelum kita mulai. Silahkan dilihat ppt berikut.', '', '2025-09-14 10:09:34', '2025-09-14 10:09:34', 36);
INSERT INTO `el_transkrip_rapat` VALUES (25, 4, '1757844863_1cf95ce392319873dd45.ogg', 'shut', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-09-14 10:14:24', '2025-09-14 10:14:24', 36);
INSERT INTO `el_transkrip_rapat` VALUES (26, 4, '1757846099_2479a399222df96c480d.wav', 'test', 'Selamat pagi ini adalah audio testim.', '', '2025-09-14 10:35:03', '2025-09-14 10:35:03', 36);
INSERT INTO `el_transkrip_rapat` VALUES (27, 6, '1757846166_ca2db5791fecb3948440.wav', 'Kesimpulan', 'Selamat pagi ini adalah audio testim.', '', '2025-09-14 10:36:09', '2025-09-14 10:36:09', 36);
INSERT INTO `el_transkrip_rapat` VALUES (28, 6, '1757846785_876fc40911f294cfefaa.wav', 'for Riyal', 'Selamat pagi untuk penugasan panitia graduation di bidang dokumentasi pada kurumi dan felicya dan untuk mc nya melna dan salt saltisia. Terima kasih.', '', '2025-09-14 10:46:32', '2025-09-14 10:46:32', 39);
INSERT INTO `el_transkrip_rapat` VALUES (30, 4, '1761033561_e8b7df6e1f3b4d2dbe26.wav', 'dawg', 'The style of old bear lingers. It takes he bring out the owter a cold, deep restore health and zest a salt pick taste find with hamples all pastore are my favorite. A zestful food is the hot crass bund.', '', '2025-10-21 07:59:30', '2025-10-21 07:59:30', 31);
INSERT INTO `el_transkrip_rapat` VALUES (31, 6, '1761033939_eee5ffc58464a10328cc.wav', 'lets see', 'Anywaym detail yang you have any hobby of close do anything who you food iya. Bak perlu allah amit my friends. Eits eits its um its um bustil yang. Hmmm ans of course some subcamp. Eits Android amp. Helai om them ya its. Musty im gine.', '', '2025-10-21 08:05:55', '2025-10-21 08:05:55', 31);
INSERT INTO `el_transkrip_rapat` VALUES (32, 6, '1761034181_6fa76f421b4ba6676840.wav', 'test language', 'Anywaym detail yang you have any hobby of close do anything who you food iya. Bak perlu allah amit my friends. Eits eits its um its um bustil yang. Hmmm ans of course some subcamp. Eits Android amp. Helai om them ya its. Musty im gine.', '', '2025-10-21 08:09:57', '2025-10-21 08:09:57', 31);
INSERT INTO `el_transkrip_rapat` VALUES (34, 4, 'recorded_1761535994.wav', 'testnumero1', '', '', '2025-10-27 03:33:15', '2025-10-27 03:33:15', 31);
INSERT INTO `el_transkrip_rapat` VALUES (35, 4, '1761536215_fe0f108ea614147e9ba8.webm', 'what if upload?', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-10-27 03:36:56', '2025-10-27 03:36:56', 31);
INSERT INTO `el_transkrip_rapat` VALUES (37, 4, '1761537351_a052a47e38dfb65aff4e.wav', 'sugh', 'I can\'t read this actually anyway. Can you tell me, I mean, do you have any hobbies or pastimes or anything which you? Yeah, uh. Play football a lot. With my friends. Do you do you play at school or is there a bigger? It\'s it\'s it\'s mostly in the in in the park. And I\'ve got some. Subcomp. Ayutthayas and I am. Play on them. Yeah, it\'s. It\'s mostly games.', '', '2025-10-27 03:56:08', '2025-10-27 03:56:08', 31);
INSERT INTO `el_transkrip_rapat` VALUES (38, 6, 'recorded_1761579002.wav', 'okcobareacoerd', '', '', '2025-10-27 15:30:18', '2025-10-27 15:30:18', 31);
INSERT INTO `el_transkrip_rapat` VALUES (39, 6, '1761656585_7a9c81d6c99c484d7e98.webm', 'tessssssssssss', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-10-28 13:03:06', '2025-10-28 13:03:06', 31);
INSERT INTO `el_transkrip_rapat` VALUES (40, 6, '1761656716_89ef3d34cce21413d139.webm', 'testsetsett', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-10-28 13:05:17', '2025-10-28 13:05:17', 31);
INSERT INTO `el_transkrip_rapat` VALUES (41, 6, '1761657111_783ac95c6cc457cec753.webm', 'blegh', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-10-28 13:11:52', '2025-10-28 13:11:52', 31);
INSERT INTO `el_transkrip_rapat` VALUES (42, 6, '1761657444_3d1089f1bb17cac9b215.wav', 'tstestestesmarikita coba', 'Tes tes.', '', '2025-10-28 13:17:29', '2025-10-28 13:17:29', 31);
INSERT INTO `el_transkrip_rapat` VALUES (43, 6, '1761657523_c34dfe0868a1471b50fe.webm', 'gimana kalau uplaod?', 'Gagal transkrip audio. Respon: {\"error\":{\"message\":\"The file is either missing or the format of submitted file is invalid\",\"code\":104}}\n', '', '2025-10-28 13:18:44', '2025-10-28 13:18:44', 31);
INSERT INTO `el_transkrip_rapat` VALUES (44, 6, '1761658585_3c47e9d2e2379028b8b8.wav', 'kurumi', 'Romi. Cukup yang ngomong. Apalah.', '', '2025-10-28 13:36:41', '2025-10-28 13:36:41', 31);
INSERT INTO `el_transkrip_rapat` VALUES (49, 4, '1761799343_a14c8a91f2abc325cf66.wav', 'Mari kita coba', 'Kamu locknya bikinnya ngomong lebih suka sama kamu locek jadi jangan diulangin jadi suka sama kamu.', '', '2025-10-30 04:42:27', '2025-10-30 04:42:27', 31);
INSERT INTO `el_transkrip_rapat` VALUES (50, 6, '1762093393_80d890060a01fb29b142.wav', 'Helloo', 'Di perguruan, menurut. Dia belajar. Ngomong c ngomong. Alhamdulillah kalian lagi ingat asya. Hello hello. Cici. Please.', '', '2025-11-02 14:23:21', '2025-11-02 14:23:21', 31);
INSERT INTO `el_transkrip_rapat` VALUES (52, 4, '1762269431_684392cbdba94c1037a3.wav', 'LOL', 'Hello hello hello hello. Kita mau makan high tea law.', '', '2025-11-04 15:17:14', '2025-11-04 15:17:14', 43);
INSERT INTO `el_transkrip_rapat` VALUES (53, 4, '1767537680_83d81cd51e348550baae.wav', 'testing if still working for debugging purpose', 'Tes tes masih bisakah?', '', '2026-01-04 14:41:23', '2026-01-04 14:41:23', 31);

-- ----------------------------
-- Table structure for el_user
-- ----------------------------
DROP TABLE IF EXISTS `el_user`;
CREATE TABLE `el_user`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nomor_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `level` int NULL DEFAULT NULL,
  `divisi` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `expiry` datetime NULL DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isApproved` tinyint NULL DEFAULT NULL,
  `status_delete` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `nomor_hp`(`nomor_hp` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_user
-- ----------------------------
INSERT INTO `el_user` VALUES (31, 'Ely', '081212121212', 'nagisaryuu404@gmail.com', 'c81e728d9d4c2f636f067f89cc14862c', 1, 2, '2025-12-01 09:16:36', NULL, '2025-12-01 02:16:36', NULL, NULL, NULL, NULL, NULL, '1764555154_692cf992290bc.png', 1, 0);
INSERT INTO `el_user` VALUES (36, 'Elysia', '081234567892', 'kurumidafox@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 4, 2, '2025-12-01 09:34:13', 2, '2025-12-01 02:34:13', NULL, NULL, NULL, NULL, NULL, '1764556453_692cfea523347.png', 1, 0);
INSERT INTO `el_user` VALUES (41, 'anggota', '085668499103', 'ad@min', 'c4ca4238a0b923820dcc509a6f75849b', 3, NULL, '2025-09-14 20:59:54', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0);
INSERT INTO `el_user` VALUES (43, 'ToRRIIDeR96', '097365443', 'sensen13575757@gmail.com', '5d1c163444859b57f2d06276b2190b18', 3, 2, '2025-11-10 12:29:56', 2, '2025-11-10 05:28:26', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0);
INSERT INTO `el_user` VALUES (48, 'Kevin Kaslana', '44235235', 'wfsdef@vf5degf', 'c4ca4238a0b923820dcc509a6f75849b', 3, 3, '2025-11-12 14:22:27', NULL, '2025-11-12 07:20:56', NULL, NULL, NULL, NULL, NULL, '1762932056_69143558c498f.png', NULL, 0);
INSERT INTO `el_user` VALUES (50, 'Raiden Mei', '069485733352', 'ryukusune@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 3, 3, '2025-11-12 14:18:36', 2, '2025-11-12 07:17:05', NULL, NULL, NULL, NULL, NULL, '1762931825_69143471b299b.png', 1, 0);

-- ----------------------------
-- Table structure for el_web_detail
-- ----------------------------
DROP TABLE IF EXISTS `el_web_detail`;
CREATE TABLE `el_web_detail`  (
  `id_wdetail` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_wdetail`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of el_web_detail
-- ----------------------------
INSERT INTO `el_web_detail` VALUES (1, 'Ellie Secretary', '1764556583_692cff27a1c91.png');

SET FOREIGN_KEY_CHECKS = 1;
