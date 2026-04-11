<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoteChatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'note_id'    => ['type' => 'INT', 'null' => false],
            'role'       => ['type' => "ENUM('user','assistant')", 'null' => false],
            'message'    => ['type' => 'TEXT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('note_id');
        $this->forge->createTable('note_chats');
    }

    public function down()
    {
        $this->forge->dropTable('note_chats', true);
    }
}
