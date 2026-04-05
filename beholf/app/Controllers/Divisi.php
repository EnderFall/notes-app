<?php

namespace App\Controllers;
use App\Models\M_divisi;

class Divisi extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
        $M_divisi = new M_divisi();
        $data = [
            'title' => 'Data Divisi',
            'a' => $M_divisi
                ->where('status_delete', 0)
                ->asObject()
                ->findAll()
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('data/v_divisi', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function tambah_divisi()
	{
        if (session()->get('id_user') > 0) {
		$model = new M_divisi();
		$data['title']='Tambah Data divisi';
		echo view('header', $data);
        echo view('menu');
        echo view('data/tambah_divisi', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
	}

    public function aksi_tambah_divisi()
    {
        $nama       = $this->request->getPost('nama');

        $data = [
            'nama_divisi'   => $nama
        ];

        $model = new M_divisi();
        $model->insert($data);
        $this->logActivity("Menambahkan divisi baru: $nama");

        return redirect()->to(base_url('divisi'))->with('success', 'divisi berhasil ditambahkan.');
    }

    public function edit_divisi($id)
    {
        if (session()->get('id_user') > 0) {
        $model = new M_divisi();
        $data['title'] = 'Edit divisi';
        $data['divisi'] = $model->asObject()->find($id);

        if (!$data['divisi']) {
            return redirect()->to('/divisi')->with('error', 'divisi tidak ditemukan.');
        }

        echo view('header', $data);
        echo view('menu');
        echo view('data/edit_divisi', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update_divisi($id)
    {
        $model = new \App\Models\M_divisi();
        $divisi = $model->find($id);

        if (!$divisi) {
            return redirect()->to('/divisi')->with('error', 'divisi tidak ditemukan.');
        }

        $nama       = $this->request->getPost('nama');

        $data = [
            'nama_divisi'   => $nama
        ];

        $model->update($id, $data);
        $this->logActivity("Mengupdate divisi ID: $id menjadi: $nama");

        return redirect()->to(base_url('divisi'))->with('success', 'Data divisi berhasil diupdate.');
    }

    public function dihapus_divisi()
    {
        if (session()->get('id_user') > 0) {
        $this->logActivity("Mengakses Tabel Data Data divisi yang Dihapus");

        $M_divisi = new M_divisi();
        $data = [
            'title' => 'Data divisi yang Dihapus',
            'deleted_divisi' => $M_divisi->getDeletedDivisi(),
            'showWelcome' => false
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('data/deleted_divisi', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function hapus_divisi($id)
    {
        $M_divisi = new M_divisi();
        if ($M_divisi->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen divisi ID: $id");

            return redirect()->to(base_url('divisi/dihapus_divisi'))->with('success', 'Data divisi berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('divisi/dihapus_divisi'))->with('error', 'Data divisi tidak ditemukan atau gagal dihapus');
    }

    public function delete_divisi($id)
    {
        $M_divisi = new M_divisi();
        if ($M_divisi->softDelete($id)) {
            $this->logActivity("Menghapus divisi ID: $id (soft delete)");

            return redirect()->to(base_url('divisi'))->with('success', 'Data divisi berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('divisi'))->with('error', 'Data divisi tidak ditemukan atau gagal dihapus');
    }

    public function restore_divisi($id)
    {
        $M_divisi = new M_divisi();

        if ($M_divisi->restore($id)) {
            $this->logActivity("Mengembalikan divisi ID: $id (soft delete)");
            return redirect()->to(base_url('divisi'))->with('success', 'Data divisi berhasil direstore');
        }
        return redirect()->to(base_url('divisi'))->with('error', 'Data divisi tidak ditemukan');
    }

    public function detail_divisi($id)
    {
        if (session()->get('id_user') > 0) {
        // $session = session();
        // $user_id = $session->get('id_user'); 
        // $user_level = $session->get('level'); 

        // $logModel = new \App\Models\M_log();
        $M_divisi = new M_divisi();
        $divisi = $M_divisi->getdivisiById($id);

        // $logModel->saveLog($divisi_id, "id_divisi={$divisi_id} berhasil melihat detail divisi ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail divisi',
            'divisi' => $divisi
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('data/detail_divisi', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

}