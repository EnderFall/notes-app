<?php

namespace App\Models;

use CodeIgniter\Model;

class M_rapat extends Model
{
    protected $table = 'el_notes';
    protected $primaryKey = 'id_note';
    protected $allowedFields = [
        'id_note',
        'judul',
        'tanggal',
        'lokasi',
        'keterangan',
        'content',
        'category',
        'tags',
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
        return $this->where('id_note', $id)->delete();
    }

    public function getDeletedrapat()
    {
        return $this->db->table('el_notes')->where('status_delete', 1)->get()->getResult();
    }

    public function getRapatById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getAllCategories()
    {
        return $this->db->table('el_category')->where('status_delete', 0)->orderBy('name', 'ASC')->get()->getResult();
    }

    public function getNotesForCalendar($year = null, $month = null)
    {
        $year = $year ?: date('Y');
        $month = $month ?: date('m');
        
        return $this->select('el_notes.id_note, el_notes.judul, DATE(el_notes.tanggal) as note_date, el_notes.keterangan, el_notes.category, el_notes.tags, el_category.icon as category_icon, el_category.name as category_name, el_category.color as category_color')
                    ->join('el_category', 'el_category.id_category = el_notes.category', 'left')
                    ->where('el_notes.status_delete', 0)
                    ->where('YEAR(el_notes.tanggal)', $year)
                    ->where('MONTH(el_notes.tanggal)', $month)
                    ->orderBy('el_notes.tanggal', 'ASC')
                    ->findAll();
    }

    public function getNotesByDate($date)
    {
        return $this->select('id_note, judul, tanggal, keterangan, category, tags')
                    ->where('status_delete', 0)
                    ->where('DATE(tanggal)', $date)
                    ->orderBy('tanggal', 'ASC')
                    ->findAll();
    }

}
