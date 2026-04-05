<?php

namespace App\Controllers;
use App\Models\M_penjualan;
use App\Models\M_mobil;
use App\Models\M_pelanggan;

class Penjualan extends BaseController
{
    // public function index()
    // {
    //     $penjualan = new M_penjualan();

    //     $data = [
    //         'title' => 'Data Penjualan',
    //         'penjualan' => $penjualan
    //             ->select('penjualan.*, mobil.nama_mobil, mobil.kode_mobil, mobil.plat_mobil, pelanggan.nama_pelanggan')
    //             ->join('mobil', 'mobil.id_mobil = penjualan.id_mobil')
    //             ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan')
    //             ->where('penjualan.status_delete', 0)
    //             ->asObject()
    //             ->findAll()
    //     ];

    //     echo view('header', $data);
    //     echo view('menu');
    //     echo view('penjualan/v_penjualan', $data);
    //     echo view('footer');
    // }
    public function index()
    {
        $bulan = $this->request->getGet('bulan');
        $penjualan = new \App\Models\M_penjualan();

        $builder = $penjualan
            ->select('penjualan.*, mobil.nama_mobil, mobil.kode_mobil, mobil.plat_mobil, pelanggan.nama_pelanggan')
            ->join('mobil', 'mobil.id_mobil = penjualan.id_mobil')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan')
            ->where('penjualan.status_delete', 0);

        // Tambahkan filter bulan jika dipilih
        if (!empty($bulan)) {
            $builder->where('MONTH(penjualan.tanggal_jual)', $bulan);
        }

        $data = [
            'title' => 'Mobil Terjual',
            'penjualan' => $builder->asObject()->findAll()
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('penjualan/v_penjualan', $data); 
        echo view('footer');
    }

    public function tambah_penjualan()
    {
        $mobil = new M_mobil();
        $pelanggan = new M_pelanggan();

        $data = [
            'title' => 'Tambah Penjualan',
            'mobil' => $mobil
                ->where('status', 'Tersedia')
                ->where('status_delete', 0)
                ->asObject()
                ->findAll(),
            'pelanggan' => $pelanggan
                ->where('status_delete', 0)
                ->asObject()
                ->findAll(),
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('penjualan/tambah_penjualan', $data);
        echo view('footer');
    }

   public function aksi_tambah_penjualan()
    {
        $id_mobil       = $this->request->getPost('id_mobil');
        $id_pelanggan   = $this->request->getPost('id_pelanggan');
        $harga_jual     = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga'));
        $tanggal_jual   = $this->request->getPost('tanggal_jual');
        $catatan        = $this->request->getPost('catatan');

        $data = [
            'id_mobil'      => $id_mobil,
            'id_pelanggan'  => $id_pelanggan,
            'harga_jual'    => $harga_jual,
            'tanggal_jual'  => $tanggal_jual,
            'catatan'       => $catatan,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $penjualanModel = new \App\Models\M_penjualan(); 
        $penjualanModel->insert($data);

        $mobilModel = new \App\Models\M_mobil();
        $mobilModel->update($id_mobil, [
            'status' => 'Terjual',
            'tanggal_keluar' => $tanggal_jual,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('penjualan'))->with('success', 'Data penjualan berhasil ditambahkan.');
    }

    public function edit_penjualan($id)
    {
        $model = new M_penjualan();
        $mobil = new M_mobil();
        $pelanggan = new M_pelanggan();

        $penjualan = $model->asObject()->find($id);
        $idMobilDipakai = $penjualan->id_mobil;

        $data = [
            'title' => 'Edit Penjualan',
            'penjualan' => $penjualan,
            'mobil' => $mobil
                ->where('status_delete', 0)
                ->orWhere('id_mobil', $idMobilDipakai) 
                ->asObject()
                ->findAll(),
            'pelanggan' => $pelanggan
                ->where('status_delete', 0)
                ->asObject()
                ->findAll(),
        ];

        if (!$data['penjualan']) {
            return redirect()->to('/penjualan')->with('error', 'penjualan tidak ditemukan.');
        }

        echo view('header', $data);
        echo view('menu');
        echo view('penjualan/edit_penjualan', $data);
        echo view('footer');
    }

    public function update_penjualan($id)
    {
        $model = new \App\Models\M_penjualan();
        $penjualan = $model->find($id);

        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'penjualan tidak ditemukan.');
        }

        $id_mobil       = $this->request->getPost('id_mobil');
        $id_pelanggan   = $this->request->getPost('id_pelanggan');
        $harga_jual     = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga'));
        $tanggal_jual   = $this->request->getPost('tanggal_jual');
        $catatan        = $this->request->getPost('catatan');

        $data = [
            'id_mobil'      => $id_mobil,
            'id_pelanggan'  => $id_pelanggan,
            'harga_jual'    => $harga_jual,
            'tanggal_jual'  => $tanggal_jual,
            'catatan'       => $catatan,
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $model->update($id, $data);

        $mobilModel = new \App\Models\M_mobil();
        $mobilModel->update($id_mobil, [
            'tanggal_keluar' => $tanggal_jual,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('penjualan'))->with('success', 'Data penjualan berhasil diupdate.');
    }

    public function dihapus_penjualan()
    {
        // $this->logActivity("Mengakses Tabel Data Data penjualan yang Dihapus");

        // if (!session()->has('id_user')) { 
        //     return redirect()->to('login/halaman_login');
        // }

        // if (!in_array(session()->get('level'), [1])) {
        //     return redirect()->to('home/dashboard'); 
        // }

        $M_penjualan = new M_penjualan();

        $data = [
            'title' => 'Data penjualan yang Dihapus',
            'deleted_penjualan' => $M_penjualan->getDeletedpenjualan(),
            'showWelcome' => false 
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('penjualan/deleted_penjualan', $data);
        echo view('footer');
    }

    public function hapus_penjualan($id)
    {
        $M_penjualan = new M_penjualan();
        if ($M_penjualan->deletePermanen($id)) {
            // $this->logActivity("Menghapus permanen penjualan ID: $id");

            return redirect()->to(base_url('penjualan/dihapus_penjualan'))->with('success', 'Data penjualan berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('penjualan/dihapus_penjualan'))->with('error', 'Data penjualan tidak ditemukan atau gagal dihapus');
    }

    public function delete_penjualan($id)
    {
        $M_penjualan = new M_penjualan();
        $M_mobil = new M_mobil();

        $penjualan = $M_penjualan->find($id);

        if ($penjualan && $M_penjualan->softDelete($id)) {
            $M_mobil->update($penjualan['id_mobil'], [
                'status' => 'Tersedia',
                'tanggal_keluar' => null
            ]);

            return redirect()->to(base_url('penjualan/dihapus_penjualan'))
                ->with('success', 'Data penjualan berhasil dihapus dan status mobil menjadi Tersedia');
        }

        return redirect()->to(base_url('penjualan'))
            ->with('error', 'Data penjualan tidak ditemukan atau gagal dihapus');
    }

    public function restore_penjualan($id)
    {
        $M_penjualan = new M_penjualan();
        $M_mobil = new M_mobil();

        $penjualan = $M_penjualan->find($id);

        if ($penjualan) {
            $M_penjualan->update($id, ['status_delete' => 0]);

            $M_mobil->update($penjualan['id_mobil'], [
                'status' => 'Terjual',
                'tanggal_keluar' => $penjualan['tanggal_jual']
            ]);

            return redirect()->to(base_url('penjualan'))
                ->with('success', 'Data penjualan berhasil direstore dan status mobil kembali Terjual');
        }

        return redirect()->to(base_url('penjualan/dihapus_penjualan'))
            ->with('error', 'Data penjualan tidak ditemukan');
    }


    // public function delete_penjualan($id)
    // {
    //     $M_penjualan = new M_penjualan();
    //     if ($M_penjualan->softDelete($id)) {
    //         // $this->logActivity("Menghapus penjualan ID: $id (soft delete)");

    //         return redirect()->to(base_url('penjualan/dihapus_penjualan'))->with('success', 'Data penjualan berhasil dihapus (soft delete)');
    //     }
    //     return redirect()->to(base_url('penjualan'))->with('error', 'Data penjualan tidak ditemukan atau gagal dihapus');
    // }

    // public function restore_penjualan($id)
    // {
    //     $M_penjualan = new M_penjualan();

    //     if ($M_penjualan->restore($id)) {
    //         // $this->logActivity("Mengembalikan penjualan ID: $id (soft delete)");
    //         return redirect()->to(base_url('penjualan'))->with('success', 'Data penjualan berhasil direstore');
    //     }
    //     return redirect()->to(base_url('penjualan/dihapus_penjualan'))->with('error', 'Data penjualan tidak ditemukan');
    // }

    public function detail_penjualan($id)
    {
        // $session = session();
        // $user_id = $session->get('id_user'); 
        // $user_level = $session->get('level'); 

        // $logModel = new \App\Models\M_log();
        $M_penjualan = new M_penjualan();
        $penjualan = $M_penjualan->getPenjualanById($id);

        // $logModel->saveLog($penjualan_id, "id_penjualan={$penjualan_id} berhasil melihat detail penjualan ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail penjualan',
            'penjualan' => $penjualan
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('penjualan/detail_penjualan', $data);
        echo view('footer');
    }

}