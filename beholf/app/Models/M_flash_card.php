<?php

namespace App\Models;

use CodeIgniter\Model;

class M_flash_card extends Model
{
    protected $table      = 'flash_cards';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'note_id', 'question', 'answer', 'difficulty', 'order_index',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function getByNote(int $noteId): array
    {
        return $this->where('note_id', $noteId)->orderBy('order_index', 'ASC')->findAll();
    }

    public function deleteByNote(int $noteId): bool
    {
        return $this->where('note_id', $noteId)->delete();
    }
}
