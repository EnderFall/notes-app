<?php

namespace App\Models;

use CodeIgniter\Model;

class M_rapat extends Model
{
    protected $table = 'el_notes';
    protected $primaryKey = 'id_note';
    protected $allowedFields = [
        'id_note',
        'judul',
        'tanggal',
        'lokasi',
        'keterangan',
        'content',
        'category',
        'tags',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'status_delete'
    ];

    public function softDelete($id)
    {
        return $this->update($id, ['status_delete' => 1]);
    }

    public function restore($id)
    {
        return $this->update($id, ['status_delete' => 0]);
    }

    public function deletePermanen($id)
    {
        return $this->where('id_note', $id)->delete();
    }

    public function getDeletedrapat()
    {
        return $this->db->table('el_notes')->where('status_delete', 1)->get()->getResult();
    }

    public function getRapatById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllCategories()
    {
        return $this->db->table('el_category')->where('status_delete', 0)->orderBy('name', 'ASC')->get()->getResult();
    }

}
