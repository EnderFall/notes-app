<?php

namespace App\Models;

use CodeIgniter\Model;

class M_note_structure extends Model
{
    protected $table      = 'note_structures';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'note_id', 'main_idea', 'key_points', 'supporting_details', 'conclusion',
        'raw_result', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function getByNote(int $noteId): ?array
    {
        return $this->where('note_id', $noteId)->first();
    }

    public function upsert(int $noteId, array $data): bool
    {
        $existing = $this->getByNote($noteId);
        $data['note_id']    = $noteId;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing) {
            return $this->update($existing['id'], $data);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        return (bool) $this->insert($data);
    }
}
