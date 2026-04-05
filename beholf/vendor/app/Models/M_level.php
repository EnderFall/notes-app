<?php

namespace App\Models;

use CodeIgniter\Model;

class M_level extends Model
{
    protected $table = 'el_level'; 
    protected $primaryKey = 'id_level'; 
    protected $allowedFields = [
        'nama_level','status_delete'];

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
        return $this->where('id_level', $id)->delete();
    }

    public function getDeletedLevel()
    {
        return $this->db->table('el_level')->where('status_delete', 1)->get()->getResult();
    }

    public function getLevelById($id)
    {
        return $this->asObject()->find($id);
    }

}
