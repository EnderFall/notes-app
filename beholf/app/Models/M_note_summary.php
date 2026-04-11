<?php

namespace App\Models;

use CodeIgniter\Model;

class M_note_summary extends Model
{
    protected $table      = 'note_summaries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'note_id', 'summary_type', 'source_text', 'summary_text', 'created_by',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function getByNote(int $noteId): array
    {
        return $this->where('note_id', $noteId)->orderBy('created_at', 'DESC')->findAll();
    }
}
