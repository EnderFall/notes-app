<?php

namespace App\Models;

use CodeIgniter\Model;

class M_peserta extends Model
{
    protected $table = 'el_peserta';
    protected $primaryKey = 'id_peserta';
    protected $allowedFields = [
        'id_peserta',
        'id_rapat',
        'status',
        'catatan',
        'id_user',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
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
        return $this->where('id_peserta', $id)->delete();
    }

    public function RapatdiDelete($id)
    {
        return $this->where('id_rapat', $id)->delete();
    }

    public function getDeletedpeserta()
    {
        return $this->db->table('el_peserta')->where('status_delete', 1)->get()->getResult();
    }

    public function getpesertaById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllDivisi()
    {
        return $this->db->table('el_divisi')->get()->getResult();
    }

    public function getPesertaByRapat($id_rapat)
    {
        return $this->select('el_user.username, el_divisi.nama_divisi')
                    ->join('el_user', 'el_user.id_user = el_peserta.id_user')
                    ->join('el_divisi', 'el_divisi.id_divisi = el_user.divisi', 'left')
                    ->where('el_peserta.id_rapat', $id_rapat)
                    ->findAll();
    }
}
