<?php

namespace App\Controllers;
use App\Models\M_rapat;
use App\Models\M_peserta;
use App\Models\M_transkrip;

class Rapat extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
        $M_rapat = new M_rapat();
        $data = [
            'title' => 'Data Rapat',
            'rapat' => $M_rapat->select('el_rapat.*, el_divisi.nama_divisi AS divisi')
                ->join('el_divisi', 'el_rapat.divisi = el_divisi.id_divisi', 'left')
                ->where('el_rapat.status_delete', 0)
                ->asObject()
                ->findAll(),
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('rapat/v_rapat', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function tambah_rapat()
    {
        if (session()->get('id_user') > 0) {
        $rapat = new M_rapat();
        $data = [
            'title' => 'Tambah Data Rapat',
            'divisi' => $rapat->getAllDivisi(), // ambil daftar divisi dari tabel el_divisi
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('rapat/tambah_rapat', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_tambah_rapat()
    {
        $judul = $this->request->getPost('judul');
        $lokasi = $this->request->getPost('lokasi');
        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $divisi = $this->request->getPost('divisi');
        $tanggal = date('Y-m-d H:i:s', strtotime($tanggal));

        $data = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'lokasi' => $lokasi,
            'keterangan' => $keterangan ?: null,
            'divisi' => $divisi,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $model = new M_rapat();
        $model->insert($data);

        return redirect()->to(base_url('rapat'))->with('success', 'rapat berhasil ditambahkan.');
    }

    public function edit_rapat($id)
    {
        if (session()->get('id_user') > 0) {
        $model = new M_rapat();
        $data = [
            'title' => 'Edit Rapat',
            'divisi' => $model->getAllDivisi(), // ambil daftar divisi dari tabel el_divisi
        ];
        $data['rapat'] = $model->asObject()->find($id);

        if (!$data['rapat']) {
            return redirect()->to('/rapat')->with('error', 'rapat tidak ditemukan.');
        }

        echo view('header', $data);
        echo view('menu');
        echo view('rapat/edit_rapat', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update_rapat($id)
    {
        $model = new \App\Models\M_rapat();
        $rapat = $model->find($id);

        if (!$rapat) {
            return redirect()->to('/rapat')->with('error', 'Rapat tidak ditemukan.');
        }

        $judul = $this->request->getPost('judul');
        $tanggal = $this->request->getPost('tanggal');
        $lokasi = $this->request->getPost('lokasi') ?: null;
        $divisi = $this->request->getPost('divisi');
        $keterangan = $this->request->getPost('keterangan');
        $tanggal = date('Y-m-d H:i:s', strtotime($tanggal));

        $data = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'lokasi' => $lokasi,
            'keterangan' => $keterangan,
            'divisi' => $divisi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];


        $model->update($id, $data);

        return redirect()->to(base_url('rapat'))->with('success', 'Data rapat berhasil diupdate.');
    }

    public function get_peserta($id_rapat)
    {
        $userModel = new \App\Models\M_user();
        $pesertaModel = new \App\Models\M_peserta();

        // Pakai fungsi model untuk dapat user + divisi
        $users = $userModel->getUsersWithDivisi();

        $pesertaDipilih = $pesertaModel
            ->where('id_rapat', $id_rapat)
            ->findColumn('id_user') ?? [];

        $divisiList = array_unique(array_column($users, 'nama_divisi'));

        $html = '
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filterDivisi" class="form-label">Filter Divisi</label>
            <select id="filterDivisi" class="form-select form-select-sm">
                <option value="">Semua Divisi</option>';
        foreach ($divisiList as $div) {
            $html .= "<option value='{$div}'>{$div}</option>";
        }
        $html .= '</select>
        </div>
        <div class="col-md-4 offset-md-4">
            <label for="searchNama" class="form-label">Cari Nama / Email</label>
            <input type="text" id="searchNama" class="form-control form-control-sm" placeholder="Ketik untuk mencari...">
        </div>
    </div>';

        $html .= '<table class="table table-bordered table-sm" id="tablePeserta">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Divisi</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($users as $u) {
            $checked = in_array($u['id_user'], $pesertaDipilih) ? 'checked' : '';
            $html .= "<tr>
            <td><input type='checkbox' name='peserta[]' value='{$u['id_user']}' {$checked}></td>
            <td>{$u['username']}</td>
            <td>{$u['email']}</td>
            <td>{$u['nama_divisi']}</td>
        </tr>";
        }
        $html .= '</tbody></table>';

        return $this->response->setBody($html);
    }


    public function simpan_peserta_single()
    {
        $id_rapat = $this->request->getPost('id_rapat');
        $id_user = $this->request->getPost('id_user');
        $status = $this->request->getPost('status'); // 1 = centang, 0 = hapus

        $pesertaModel = new \App\Models\M_peserta();

        if ($status == 1) {
            // Insert jika belum ada
            $exists = $pesertaModel->where('id_rapat', $id_rapat)
                ->where('id_user', $id_user)
                ->first();
            if (!$exists) {
                $pesertaModel->insert([
                    'id_rapat' => $id_rapat,
                    'id_user' => $id_user
                ]);
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Peserta ditambahkan']);
        } else {
            // Hapus peserta
            $pesertaModel->where('id_rapat', $id_rapat)
                ->where('id_user', $id_user)
                ->delete();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Peserta dihapus']);
        }
    }


    // public function get_peserta($id_rapat)
    // {
    //     $userModel = new \App\Models\M_user();
    //     $pesertaModel = new \App\Models\M_peserta();

    //     $users = $userModel->findAll();
    //     $pesertaDipilih = $pesertaModel
    //         ->where('id_rapat', $id_rapat)
    //         ->findColumn('id_user') ?? [];

    //     // Kirim HTML checkbox ke modal
    //     $html = '<table class="table table-bordered">';
    //     $html .= '<thead><tr><th>Pilih</th><th>Nama</th><th>Email</th></tr></thead><tbody>';
    //     foreach ($users as $u) {
    //         $checked = in_array($u['id_user'], $pesertaDipilih) ? 'checked' : '';
    //         $html .= "<tr>
    //                 <td><input type='checkbox' name='peserta[]' value='{$u['id_user']}' {$checked}></td>
    //                 <td>{$u['username']}</td>
    //                 <td>{$u['email']}</td>
    //               </tr>";
    //     }
    //     $html .= '</tbody></table>';

    //     return $this->response->setBody($html);
    // }

    public function simpan_peserta()
    {
        $id_rapat = $this->request->getPost('id_rapat');
        $peserta = $this->request->getPost('peserta') ?? [];

        $pesertaModel = new \App\Models\M_peserta();
        $pesertaModel->where('id_rapat', $id_rapat)->delete();

        foreach ($peserta as $id_user) {
            $pesertaModel->insert([
                'id_rapat' => $id_rapat,
                'id_user' => $id_user
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Peserta rapat berhasil disimpan.'
        ]);
    }

    public function dihapus_rapat()
    {
        if (session()->get('id_user') > 0) {
        // $this->logActivity("Mengakses Tabel Data Data rapat yang Dihapus");

        // if (!session()->has('id_rapat')) { 
        //     return redirect()->to('login/halaman_login');
        // }

        // if (!in_array(session()->get('divisi'), [1])) {
        //     return redirect()->to('home/dashboard'); 
        // }

        $M_rapat = new M_rapat();
        $data = [
            'title' => 'Data rapat yang Dihapus',
            'deleted_rapat' => $M_rapat->getDeletedrapat(),
            'showWelcome' => false
        ];

        echo view('header', $data);
        echo view('menu');
        echo view('rapat/deleted_rapat', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function hapus_rapat($id)
    {
        $M_rapat = new M_rapat();
        if ($M_rapat->deletePermanen($id)) {
            // $this->logActivity("Menghapus permanen rapat ID: $id");

            return redirect()->to(base_url('rapat/dihapus_rapat'))->with('success', 'Data rapat berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('rapat/dihapus_rapat'))->with('error', 'Data rapat tidak ditemukan atau gagal dihapus');
    }

    public function delete_rapat($id)
    {
        $M_rapat = new M_rapat();
        if ($M_rapat->softDelete($id)) {
            // $this->logActivity("Menghapus rapat ID: $id (soft delete)");

            return redirect()->to(base_url('rapat/dihapus_rapat'))->with('success', 'Data rapat berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('rapat'))->with('error', 'Data rapat tidak ditemukan atau gagal dihapus');
    }

    public function restore_rapat($id)
    {
        $M_rapat = new M_rapat();

        if ($M_rapat->restore($id)) {
            // $this->logActivity("Mengembalikan rapat ID: $id (soft delete)");
            return redirect()->to(base_url('rapat'))->with('success', 'Data rapat berhasil direstore');
        }
        return redirect()->to(base_url('rapat/dihapus_rapat'))->with('error', 'Data rapat tidak ditemukan');
    }

    public function detail_rapat($id)
    {
        if (session()->get('id_user') > 0) {
        $M_transkrip = new M_transkrip();
        $M_rapat = new M_rapat();
        $M_peserta = new M_peserta();
        $rapat = $M_rapat->getRapatById($id);
        $peserta = $M_peserta->getPesertaByRapat($id);
        $transkrip = $M_transkrip
            ->select('el_transkrip_rapat.*, el_user.username')
            ->join('el_user', 'el_user.id_user = el_transkrip_rapat.id_user', 'left')
            ->where('id_rapat', $id)
             ->orderBy('waktu_upload', 'DESC')
            ->findAll();
            

        $data = [
            'title' => 'Detail rapat',
            'rapat' => $rapat,
            'peserta' => $peserta,
            'transkrip' => $transkrip
        ];
        

        echo view('header', $data);
        echo view('menu');
        echo view('rapat/detail_rapat', $data);
        echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

}