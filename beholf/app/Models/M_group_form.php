<?php

namespace App\Models;

use CodeIgniter\Model;

class M_group_form extends Model
{
    protected $table = 'el_group_form';
    protected $primaryKey = 'id_group_form'; // Assuming auto-increment primary key
    protected $allowedFields = [
        'id_form', 'id_level', 'can_read','can_create', 'can_approve', 'status_delete'
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
        return $this->where('id_group_form', $id)->delete();
    }

    public function getDeletedGroupForms()
    {
        return $this->db->table('el_group_form')->where('status_delete', 1)->get()->getResult();
    }

    public function getGroupFormById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getPermissionsByLevel($id_level)
    {
        return $this->where('id_level', $id_level)->where('status_delete', 0)->findAll();
    }

    public function getFormsByLevel($id_level)
    {
        return $this->select('el_group_form.*, el_form.deskripsi, el_form.route, el_form.jenis_form, el_form.icon')
                    ->join('el_form', 'el_form.id_form = el_group_form.id_form')
                    ->where('el_group_form.id_level', $id_level)
                    ->where('el_group_form.status_delete', 0)
                    ->where('el_form.status_delete', 0)
                    ->findAll();
    }
}
