<?php

namespace App\Models;

use CodeIgniter\Model;

class M_form extends Model
{
    protected $table = 'el_form';
    protected $primaryKey = 'id_form';
    protected $allowedFields = [
        'deskripsi', 'route', 'icon','jenis_form', 'status_delete'
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
        return $this->where('id_form', $id)->delete();
    }

    public function getDeletedForms()
{
    $result = $this->db->table('el_form')
                      ->where('status_delete', 1)
                      ->get()
                      ->getResultArray();
    
    return $result ?: []; // Return empty array if null
}
    public function getFormById($id)
    {
        return $this->asObject()->find($id);
    }
}
