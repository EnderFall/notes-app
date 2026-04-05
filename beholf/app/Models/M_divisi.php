<?php

namespace App\Models;

use CodeIgniter\Model;

class M_divisi extends Model
{
    protected $table = 'el_divisi'; 
    protected $primaryKey = 'id_divisi'; 
    protected $allowedFields = [
        'nama_divisi','status_delete'];

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
        return $this->where('id_divisi', $id)->delete();
    }

    public function getDeletedDivisi()
    {
        return $this->db->table('el_divisi')->where('status_delete', 1)->get()->getResult();
    }

    public function getDivisiById($id)
    {
        return $this->asObject()->find($id);
    }

}
