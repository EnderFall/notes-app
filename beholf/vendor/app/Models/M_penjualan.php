<?php

namespace App\Models;

use CodeIgniter\Model;

class M_penjualan extends Model
{
    protected $table = 'penjualan'; 
    protected $primaryKey = 'id_penjualan'; 
    protected $allowedFields = [
        'id_mobil', 'id_pelanggan', 'tanggal_jual', 'harga_jual', 'status_delete', 'status',
        'diskon', 'metode_bayar', 'status_bayar', 'catatan', 'created_at', 'updated_at', 'deleted_at'
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
        return $this->where('id_penjualan', $id)->delete();
    }

   public function getDeletedpenjualan()
    {
        return $this->db->table('penjualan')
            ->select('penjualan.*, mobil.nama_mobil, mobil.kode_mobil, mobil.plat_mobil, pelanggan.nama_pelanggan')
            ->join('mobil', 'mobil.id_mobil = penjualan.id_mobil')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan')
            ->where('penjualan.status_delete', 1)
            ->get()
            ->getResult();
    }

    public function getPenjualanById($id)
    {
        return $this->db->table('penjualan')
            ->select('penjualan.*, mobil.nama_mobil, mobil.kode_mobil, mobil.plat_mobil, pelanggan.nama_pelanggan')
            ->join('mobil', 'mobil.id_mobil = penjualan.id_mobil')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan')
            ->where('penjualan.id_penjualan', $id)
            ->get()
            ->getRow(); 
    }

}
