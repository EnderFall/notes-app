<?php

namespace App\Models;

use CodeIgniter\Model;

class M_note_highlight extends Model
{
    protected $table      = 'note_highlights';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'note_id', 'selected_text', 'color', 'context_before', 'context_after',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function getByNote(int $noteId): array
    {
        return $this->where('note_id', $noteId)->orderBy('created_at', 'ASC')->findAll();
    }
}
