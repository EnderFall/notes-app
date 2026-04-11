<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoteFeaturesTables extends Migration
{
    public function up()
    {
        // ─── note_summaries ───────────────────────────────────────────────
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `note_summaries` (
                `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `note_id`      INT UNSIGNED NOT NULL,
                `summary_type` ENUM('short','bullets','detailed') NOT NULL DEFAULT 'short',
                `source_text`  MEDIUMTEXT NULL,
                `summary_text` MEDIUMTEXT NOT NULL,
                `created_by`   INT UNSIGNED NULL,
                `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`   DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_ns_note` (`note_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // ─── flash_cards ──────────────────────────────────────────────────
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `flash_cards` (
                `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `note_id`     INT UNSIGNED NOT NULL,
                `question`    TEXT NOT NULL,
                `answer`      TEXT NOT NULL,
                `difficulty`  ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium',
                `order_index` INT UNSIGNED NOT NULL DEFAULT 0,
                `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_fc_note` (`note_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // ─── note_highlights ─────────────────────────────────────────────
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `note_highlights` (
                `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `note_id`        INT UNSIGNED NOT NULL,
                `selected_text`  TEXT NOT NULL,
                `color`          VARCHAR(20) NOT NULL DEFAULT '#fef08a',
                `context_before` VARCHAR(100) NOT NULL DEFAULT '',
                `context_after`  VARCHAR(100) NOT NULL DEFAULT '',
                `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`     DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_nh_note` (`note_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // ─── note_structures ─────────────────────────────────────────────
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `note_structures` (
                `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `note_id`           INT UNSIGNED NOT NULL,
                `main_idea`         TEXT NULL,
                `key_points`        TEXT NULL,
                `supporting_details` TEXT NULL,
                `conclusion`        TEXT NULL,
                `raw_result`        MEDIUMTEXT NULL,
                `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`        DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY `uq_ns_note` (`note_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // ─── note_term_definitions ───────────────────────────────────────
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `note_term_definitions` (
                `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `note_id`              INT UNSIGNED NULL,
                `term`                 VARCHAR(200) NOT NULL,
                `simple_definition`    TEXT NULL,
                `technical_definition` TEXT NULL,
                `source_type`          VARCHAR(50) NOT NULL DEFAULT 'ai',
                `created_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`           DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_ntd_note` (`note_id`),
                INDEX `idx_ntd_term` (`term`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `note_term_definitions`");
        $this->db->query("DROP TABLE IF EXISTS `note_structures`");
        $this->db->query("DROP TABLE IF EXISTS `note_highlights`");
        $this->db->query("DROP TABLE IF EXISTS `flash_cards`");
        $this->db->query("DROP TABLE IF EXISTS `note_summaries`");
    }
}
