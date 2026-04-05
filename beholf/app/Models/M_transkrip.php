<?php

namespace App\Models;

use CodeIgniter\Model;

class M_transkrip extends Model
{
    protected $table = 'el_transkrip_rapat';
    protected $primaryKey = 'id_transkrip_rapat';
    protected $allowedFields = [
        'id_transkrip_rapat',
        'id_rapat',
        'file_name',
        'file_url',
        'hasil_transkrip',
        'status',
        'waktu_upload',
        'waktu_selesai',
        'id_user'
    ];

    // public function softDelete($id)
    // {
    //     return $this->update($id, ['status_delete' => 1]);
    // }

    // public function restore($id)
    // {
    //     return $this->update($id, ['status_delete' => 0]);
    // }

    public function deletePermanen($id)
    {
        return $this->where('id_transkrip_rapat', $id)->delete();
    }

    public function RapatdiDelete($id)
    {
        return $this->where('id_rapat', $id)->delete();
    }

    // public function getDeletedrapat()
    // {
    //     return $this->db->table('el_transkrip_rapat')->where('status_delete', 1)->get()->getResult();
    // }

    public function getTranskripRapatById($id)
    {
        return $this->asObject()->find($id);
    }

    public function getWeeklyStatsFull()
{
    // Ambil data yang ada
    $rows = $this->select("DATE(waktu_upload) AS tanggal, COUNT(*) AS jumlah")
                ->where("waktu_upload >=", date('Y-m-d', strtotime('-6 days')))
                ->groupBy("DATE(waktu_upload)")
                ->orderBy("tanggal", "ASC")
                ->findAll();

    // Index by tanggal
    $map = [];
    foreach ($rows as $r) {
        $map[$r['tanggal']] = $r['jumlah'];
    }

    // Generate 7 hari terakhir
    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $result[] = [
            'tanggal' => $date,
            'jumlah'  => $map[$date] ?? 0
        ];
    }

    return $result;
}

public function getMonthlyStatsFull()
{
    // Ambil data yang ada bulan ini
    $rows = $this->select("DATE(waktu_upload) AS tanggal, COUNT(*) AS jumlah")
                ->where("MONTH(waktu_upload)", date('m'))
                ->where("YEAR(waktu_upload)", date('Y'))
                ->groupBy("DATE(waktu_upload)")
                ->orderBy("tanggal", "ASC")
                ->findAll();

    // Index by tanggal
    $map = [];
    foreach ($rows as $r) {
        $map[$r['tanggal']] = $r['jumlah'];
    }

    // Generate dari tanggal 1 sampai hari ini
    $result = [];
    $daysInMonth = date('t'); // hari ini (angka)
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
        $result[] = [
            'tanggal' => $date,
            'jumlah'  => $map[$date] ?? 0
        ];
    }

    return $result;
}

    // public function getAllDivisi()
    // {
    //     return $this->db->table('el_divisi')->get()->getResult();
    // }


}
