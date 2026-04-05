<?php

namespace App\Models;

use CodeIgniter\Model;

class M_rapat extends Model
{
    protected $table = 'el_rapat';
    protected $primaryKey = 'id_rapat';
    protected $allowedFields = [
        'id_rapat',
        'judul',
        'tanggal',
        'lokasi',
        'keterangan',
        'divisi',
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
        return $this->where('id_rapat', $id)->delete();
    }

    public function getDeletedrapat()
    {
        return $this->db->table('el_rapat')->where('status_delete', 1)->get()->getResult();
    }

    public function getRapatById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllDivisi()
    {
        return $this->db->table('el_divisi')->get()->getResult();
    }


}
