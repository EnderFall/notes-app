<?php

namespace App\Models;

use CodeIgniter\Model;

class M_term_definition extends Model
{
    protected $table      = 'note_term_definitions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'note_id', 'term', 'simple_definition', 'technical_definition', 'source_type',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    /**
     * Look up a cached definition (global, not note-specific).
     */
    public function findCached(string $term): ?array
    {
        return $this->where('term', $term)->orderBy('created_at', 'DESC')->first();
    }

    public function getByNote(int $noteId): array
    {
        return $this->where('note_id', $noteId)->orderBy('term', 'ASC')->findAll();
    }

    public function saveDefinition(array $data): int|string
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data, true);
    }
}
