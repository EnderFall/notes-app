<?php

namespace App\Models;

use CodeIgniter\Model;

class M_hak_akses_user extends Model
{
    protected $table = 'el_hak_akses_user';
    protected $primaryKey = 'id_hak_akses'; // Assuming auto-increment primary key
    protected $allowedFields = [
        'id_user', 'id_level', 'status_delete'
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
        return $this->where('id_hak_akses', $id)->delete();
    }

    public function getDeletedUserAccess()
{
    $result = $this->db->table('el_hak_akses_user')
                      ->where('status_delete', 1)
                      ->get()
                      ->getResultArray();
    
    return $result ?: []; // Return empty array if null
}

    public function getUserAccessById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getUserLevel($id_user)
    {
        return $this->where('id_user', $id_user)->where('status_delete', 0)->first();
    }

    public function getUsersByLevel($id_level)
    {
        return $this->select('el_hak_akses_user.*, el_user.nama_user, el_user.email')
                    ->join('el_user', 'el_user.id_user = el_hak_akses_user.id_user')
                    ->where('el_hak_akses_user.id_level', $id_level)
                    ->where('el_hak_akses_user.status_delete', 0)
                    ->where('el_user.status_delete', 0)
                    ->findAll();
    }
}
