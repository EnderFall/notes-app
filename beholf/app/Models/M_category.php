<?php

namespace App\Models;

use CodeIgniter\Model;

class M_category extends Model
{
    protected $table = 'el_category'; 
    protected $primaryKey = 'id_category'; 
    protected $allowedFields = [
        'name',
        'description',
        'color',
        'icon',
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
        return $this->where('id_category', $id)->delete();
    }

    public function getDeletedCategory()
    {
        return $this->db->table('el_category')->where('status_delete', 1)->get()->getResult();
    }

    public function getCategoryById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllCategories()
    {
        return $this->where('status_delete', 0)->orderBy('name', 'ASC')->asObject()->findAll();
    }

    public function getCategoryWithCount()
    {
        return $this->select('el_category.*, COUNT(el_notes.id_note) as note_count')
            ->join('el_notes', 'el_notes.category = el_category.id_category', 'left')
            ->where('el_category.status_delete', 0)
            ->where('el_notes.status_delete', 0)
            ->groupBy('el_category.id_category')
            ->findAll();
    }
}
