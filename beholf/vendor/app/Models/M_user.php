<?php

namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table = 'el_user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = [
        'id_user',
        'username',
        'nomor_hp',
        'email',
        'password',
        'level',
        'divisi',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'token',
        'expiry',
        'foto',
        'isApproved',
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
        return $this->where('id_user', $id)->delete();
    }

    public function getDeleteduser()
    {
        return $this->db->table('el_user')->where('status_delete', 1)->get()->getResult();
    }

    public function getUserById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllLevel()
    {
        return $this->db->table('el_level')->get()->getResult();
    }

    public function getUserLevel()
    {
        return $this->select('el_level.nama_level')
                    ->join('el_level', 'el_level.id_level = el_user.level', 'left')
                    ->findAll();
    }

    public function getAllDivisi()
    {
        return $this->db->table('el_divisi')->get()->getResult();
    }

    public function getUsersWithDivisi()
    {
        return $this->select('el_user.id_user, el_user.username, el_user.email, el_divisi.nama_divisi')
                    ->join('el_divisi', 'el_divisi.id_divisi = el_user.divisi', 'left')
                    ->findAll();
    }


}
