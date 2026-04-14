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
        $query = "SELECT 
                    c.*, 
                    COALESCE(COUNT(n.id_note), 0) as note_count
                  FROM el_category c
                  LEFT JOIN el_notes n ON n.category = c.id_category AND n.status_delete = 0
                  WHERE c.status_delete = 0
                  GROUP BY c.id_category
                  ORDER BY c.name ASC";
        
        return $this->db->query($query)->getResult();
    }
}
