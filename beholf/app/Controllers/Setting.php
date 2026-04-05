<?php

namespace App\Controllers;
use App\Models\M_web_detail;

class Setting extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $M_web_detail = new M_web_detail();
            $data = [
                'title' => 'Website Settings',
                'web_detail' => $M_web_detail->getWebDetail(),
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('setting/v_setting', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update()
    {
        $title = $this->request->getPost('title');

        $logoData = $this->request->getPost('logo');
        $logoName = null;

        $M_web_detail = new M_web_detail();
        $currentDetail = $M_web_detail->getWebDetail();

        if ($logoData) {
            // Hapus prefix data:image/*;base64,
            $logoData = preg_replace('/^data:image\/\w+;base64,/', '', $logoData);
            $logoData = str_replace(' ', '+', $logoData);
            $logoData = str_replace(["\r", "\n"], '', $logoData);

            $logoDecoded = base64_decode($logoData);

            if ($logoDecoded === false) {
                return redirect()->back()->with('error', 'Upload logo gagal, data tidak valid.');
            }

            $logoName = time() . '_' . uniqid() . '.png';
            file_put_contents(FCPATH . 'assets/dash/img/' . $logoName, $logoDecoded);
        } else {
            $logoName = $currentDetail['logo'] ?? null;
        }

        $data = [
            'title' => $title,
            'logo' => $logoName,
        ];

        if ($M_web_detail->updateWebDetail($data)) {
            $this->logActivity("Mengupdate pengaturan website: title=$title");
            return redirect()->to(base_url('setting'))->with('success', 'Pengaturan website berhasil diupdate.');
        } else {
            return redirect()->to(base_url('setting'))->with('error', 'Gagal mengupdate pengaturan website.');
        }
    }
}
