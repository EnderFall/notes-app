<?php

namespace App\Controllers;
use App\Models\M_user;

class User extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
        $M_user = new M_user();
        $data = [
            'title' => 'Data User',
            'user' => $M_user->select('el_user.*, el_level.nama_level AS level, el_divisi.nama_divisi AS divisi')
                ->join('el_level', 'el_user.level = el_level.id_level', 'left')
                ->join('el_divisi', 'el_user.divisi = el_divisi.id_divisi', 'left')
                ->where('el_user.status_delete', 0)
                ->asObject()
                ->findAll(),
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('user/v_user', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function tambah_user()
    {
        if (session()->get('id_user') > 0) {
        $user = new M_user();
        $data = [
            'title' => 'Tambah Data User',
            'level' => $user->getAllLevel(),
            'divisi' => $user->getAllDivisi(), // ambil daftar level dari tabel el_level
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('user/tambah_user', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_tambah_user()
    {
        $nama = $this->request->getPost('nama');
        $nohp = $this->request->getPost('nohp');
        $password = $this->request->getPost('password');
        $email = $this->request->getPost('email');
        $level = $this->request->getPost('level');
        $divisi = $this->request->getPost('divisi');

        $data = [
            'username' => $nama,
            'password' => md5($password),
            'nomor_hp' => $nohp,
            'email' => $email ?: null,
            'level' => $level,
            'divisi' => $divisi,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $model = new M_user();
        $model->insert($data);

        return redirect()->to(base_url('user'))->with('success', 'user berhasil ditambahkan.');
    }

    public function edit_user($id)
    {
        if (session()->get('id_user') > 0) {
        $model = new M_user();
        $data = [
            'title' => 'Edit User',
            'level' => $model->getAllLevel(), 
            'divisi' => $model->getAllDivisi(),// ambil daftar level dari tabel el_level
        ];
        $data['user'] = $model->asObject()->find($id);

        if (!$data['user']) {
            return redirect()->to('/user')->with('error', 'user tidak ditemukan.');
        }

        echo view('header', $data);
        echo view('menu');
        echo view('user/edit_user', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update_user($id)
    {
        $model = new \App\Models\M_user();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
        }

        $nama = $this->request->getPost('nama');
        $nohp = $this->request->getPost('nohp');
        $email = $this->request->getPost('email') ?: null;
        $level = $this->request->getPost('level');
        $divisi = $this->request->getPost('divisi');
        $password = $this->request->getPost('password');

        $data = [
            'username' => $nama,
            'nomor_hp' => $nohp,
            'email' => $email,
            'level' => $level,
            'divisi' => $divisi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Hanya update password jika diisi
        if (!empty($password)) {
            $data['password'] = md5($password); // amankan dengan hash
        }

        $model->update($id, $data);

        return redirect()->to(base_url('user'))->with('success', 'Data user berhasil diupdate.');
    }


    public function dihapus_user()
    {
        if (session()->get('id_user') > 0) {
        // $this->logActivity("Mengakses Tabel Data Data user yang Dihapus");

        // if (!session()->has('id_user')) { 
        //     return redirect()->to('login/halaman_login');
        // }

        // if (!in_array(session()->get('level'), [1])) {
        //     return redirect()->to('home/dashboard'); 
        // }

        $M_user = new M_user();
        $data = [
            'title' => 'Data user yang Dihapus',
            'deleted_user' => $M_user->getDeleteduser(),
            'showWelcome' => false
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('user/deleted_user', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function hapus_user($id)
    {
        $M_user = new M_user();
        if ($M_user->deletePermanen($id)) {
            // $this->logActivity("Menghapus permanen user ID: $id");

            return redirect()->to(base_url('user/dihapus_user'))->with('success', 'Data user berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('user/dihapus_user'))->with('error', 'Data user tidak ditemukan atau gagal dihapus');
    }

    public function delete_user($id)
    {
        $M_user = new M_user();
        if ($M_user->softDelete($id)) {
            // $this->logActivity("Menghapus user ID: $id (soft delete)");

            return redirect()->to(base_url('user/dihapus_user'))->with('success', 'Data user berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('user'))->with('error', 'Data user tidak ditemukan atau gagal dihapus');
    }

    public function restore_user($id)
    {
        $M_user = new M_user();

        if ($M_user->restore($id)) {
            // $this->logActivity("Mengembalikan user ID: $id (soft delete)");
            return redirect()->to(base_url('user'))->with('success', 'Data user berhasil direstore');
        }
        return redirect()->to(base_url('user/dihapus_user'))->with('error', 'Data user tidak ditemukan');
    }

    public function detail_user($id)
    {
        if (session()->get('id_user') > 0) {
        // $session = session();
        // $user_id = $session->get('id_user'); 
        // $user_level = $session->get('level'); 

        // $logModel = new \App\Models\M_log();
        $M_user = new M_user();
        $user = $M_user->getUserById($id);

        // $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail user ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail user',
            'user' => $user
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('user/detail_user', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    

}