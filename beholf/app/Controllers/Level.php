<?php

namespace App\Controllers;
use App\Models\M_level;

class Level extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
        $M_level = new M_level();
        $data = [
            'title' => 'Data Level',
            'a' => $M_level
                ->where('status_delete', 0)
                ->asObject()
                ->findAll()
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('level/v_level', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function tambah_level()
	{
        if (session()->get('id_user') > 0) {
		$model = new M_level();
		$data['title']='Tambah Data level';

		echo view('header', $data);
        echo view('menu');
        echo view('level/tambah_level', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
	}

    public function aksi_tambah_level()
    {
        $nama       = $this->request->getPost('nama');

        $data = [
            'nama_level'   => $nama
        ];

        $model = new M_level();
        $model->insert($data);
        $this->logActivity("Menambahkan level baru: $nama");

        return redirect()->to(base_url('level'))->with('success', 'level berhasil ditambahkan.');
    }

    public function edit_level($id)
    {
        if (session()->get('id_user') > 0) {
        $model = new M_level();
        $data['title'] = 'Edit level';
        $data['level'] = $model->asObject()->find($id);

        if (!$data['level']) {
            return redirect()->to('/level')->with('error', 'level tidak ditemukan.');
        }

        echo view('header', $data);
        echo view('menu');
        echo view('level/edit_level', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update_level($id)
    {
        $model = new \App\Models\M_level();
        $level = $model->find($id);

        if (!$level) {
            return redirect()->to('/level')->with('error', 'level tidak ditemukan.');
        }

        $nama       = $this->request->getPost('nama');

        $data = [
            'nama_level'   => $nama
        ];

        $model->update($id, $data);
        $this->logActivity("Mengupdate level ID: $id menjadi: $nama");

        return redirect()->to(base_url('level'))->with('success', 'Data level berhasil diupdate.');
    }

    public function dihapus_level()
    {
        if (session()->get('id_user') > 0) {
        $this->logActivity("Mengakses Tabel Data Data level yang Dihapus");

        $M_level = new M_level();
        $data = [
            'title' => 'Data level yang Dihapus',
            'deleted_level' => $M_level->getDeletedLevel(),
            'showWelcome' => false
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('level/deleted_level', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function hapus_level($id)
    {
        $M_level = new M_level();
        if ($M_level->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen level ID: $id");

            return redirect()->to(base_url('level/dihapus_level'))->with('success', 'Data level berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('level/dihapus_level'))->with('error', 'Data level tidak ditemukan atau gagal dihapus');
    }

    public function delete_level($id)
    {
        $M_level = new M_level();
        if ($M_level->softDelete($id)) {
            $this->logActivity("Menghapus level ID: $id (soft delete)");

            return redirect()->to(base_url('level'))->with('success', 'Data level berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('level'))->with('error', 'Data level tidak ditemukan atau gagal dihapus');
    }

    public function restore_level($id)
    {
        $M_level = new M_level();

        if ($M_level->restore($id)) {
            $this->logActivity("Mengembalikan level ID: $id (soft delete)");
            return redirect()->to(base_url('level'))->with('success', 'Data level berhasil direstore');
        }
        return redirect()->to(base_url('level/dihapus_level'))->with('error', 'Data level tidak ditemukan');
    }

    public function detail_level($id)
    {
        if (session()->get('id_user') > 0) {
        // $session = session();
        // $user_id = $session->get('id_user'); 
        // $user_level = $session->get('level'); 

        // $logModel = new \App\Models\M_log();
        $M_level = new M_level();
        $level = $M_level->getlevelById($id);

        // $logModel->saveLog($level_id, "id_level={$level_id} berhasil melihat detail level ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail level',
            'level' => $level
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('level/detail_level', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

}