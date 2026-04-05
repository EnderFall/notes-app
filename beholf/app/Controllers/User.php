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

        $fotoData = $this->request->getPost('foto');
        $fotoName = null;
        if ($fotoData) {
            // Hapus prefix data:image/*;base64,
            $fotoData = preg_replace('/^data:image\/\w+;base64,/', '', $fotoData);
            $fotoData = str_replace(' ', '+', $fotoData);
            $fotoData = str_replace(["\r", "\n"], '', $fotoData);

            $fotoDecoded = base64_decode($fotoData);

            if ($fotoDecoded === false) {
                return redirect()->back()->with('error', 'Upload foto gagal, data tidak valid.');
            }

            $fotoName = time() . '_' . uniqid() . '.png';
            file_put_contents(FCPATH . 'assets/img/' . $fotoName, $fotoDecoded);
        }


        $data = [
            'username' => $nama,
            'password' => md5($password),
            'nomor_hp' => $nohp,
            'email' => $email ?: null,
            'level' => $level,
            'divisi' => $divisi,
            'foto' => $fotoName,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $model = new M_user();
        $insertedId = $model->insert($data);

        if ($insertedId) {
            $this->logActivity("Menambahkan user baru: $nama");
        }

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
        $user = $model->asObject()->find($id);

        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
        }

        $nama = $this->request->getPost('nama');
        $nohp = $this->request->getPost('nohp');
        $email = $this->request->getPost('email') ?: null;
        $level = $this->request->getPost('level');
        $divisi = $this->request->getPost('divisi');
        $password = $this->request->getPost('password');

        $fotoData = $this->request->getPost('foto');
        $fotoName = $user->foto; // Keep existing photo by default

        // Only process new photo if it's different from current one and not empty
        if ($fotoData && !empty($fotoData) && $fotoData !== $user->foto) {
            // Check if it's a base64 image data (new upload) or just a filename (existing)
            if (strpos($fotoData, 'data:image') === 0) {
                // It's a new base64 image - save to gallery first
                $fotoData = preg_replace('/^data:image\/\w+;base64,/', '', $fotoData);
                $fotoData = str_replace(' ', '+', $fotoData);
                $fotoData = str_replace("\n", '', $fotoData);
                $fotoData = str_replace("\r", '', $fotoData);

                $fotoDecoded = base64_decode($fotoData);

                if ($fotoDecoded === false) {
                    return redirect()->back()->with('error', 'Upload foto gagal, data tidak valid.');
                }

                // Generate unique filename
                $fotoName = time() . '_' . uniqid() . '.png';

                // Save to gallery folder first
                $galleryPath = FCPATH . 'assets/img/' . $fotoName;
                file_put_contents($galleryPath, $fotoDecoded);
                
                // Delete old photo if it's not the default and not in gallery
                if (!empty($user->foto) && $user->foto !== 'default-avatar.jpg') {
                    $oldPhotoPath = FCPATH . 'assets/img/' . $user->foto;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }

                    // Also check if old photo exists in gallery and delete if it's not used by others
                    $oldGalleryPath = FCPATH . 'assets/img/gallery/' . $user->foto;
                    if (file_exists($oldGalleryPath)) {
                        // Check if this photo is being used by other users
                        $otherUsers = $model->where('foto', $user->foto)->where('id_user !=', $id)->countAllResults();
                        if ($otherUsers === 0) {
                            unlink($oldGalleryPath);
                        }
                    }
                }
            } else {
                // It's just a filename (existing image from gallery)
                $fotoName = $fotoData;

                // Copy from gallery to main img folder if not exists
                $galleryPath = FCPATH . 'assets/img/gallery/' . $fotoName;
                $profilePath = FCPATH . 'assets/img/' . $fotoName;

                if (file_exists($galleryPath) && !file_exists($profilePath)) {
                    copy($galleryPath, $profilePath);
                }

                // Delete old photo if it's not the default and not the same as new one
                if (!empty($user->foto) && $user->foto !== 'default-avatar.jpg' && $user->foto !== $fotoName) {
                    $oldPhotoPath = FCPATH . 'assets/img/' . $user->foto;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
            }
        }

        $data = [
            'username' => $nama,
            'nomor_hp' => $nohp,
            'email' => $email,
            'level' => $level,
            'divisi' => $divisi,
            'foto' => $fotoName,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($password)) {
            $data['password'] = md5($password);
        }

        $updated = $model->update($id, $data);

        if ($updated) {
            $this->logActivity("Mengupdate user: $nama (ID: $id)");
            return redirect()->to(base_url('user'))->with('success', 'Data user berhasil diupdate.');
        } else {
            return redirect()->to(base_url('user'))->with('error', 'Gagal mengupdate data user.');
        }
    }

    public function upload_to_gallery()
    {
        $file = $this->request->getFile('image');

        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid']);
        }

        // Validate image
        $validationRule = [
            'image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
                    . '|max_size[image,2048]' // 2MB max
            ]
        ];

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON(['success' => false, 'message' => $this->validator->getErrors()]);
        }

        // Generate unique filename
        $newName = $file->getRandomName();

        // Move file to gallery folder
        if ($file->move(FCPATH . 'assets/img/gallery/', $newName)) {
            return $this->response->setJSON([
                'success' => true,
                'filename' => $newName,
                'url' => base_url('assets/img/gallery/' . $newName)
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupload gambar']);
        }
    }

    public function get_image_library()
    {
        $imagesFolder = FCPATH . 'assets/img/gallery/';

        // Check if folder exists, create if not
        if (!is_dir($imagesFolder)) {
            mkdir($imagesFolder, 0755, true);
        }

        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = [];

        foreach ($extensions as $ext) {
            $found = glob($imagesFolder . '*.' . $ext);
            if ($found) {
                $images = array_merge($images, $found);
            }
        }

        $html = '';
        if (!empty($images)) {
            foreach ($images as $img) {
                $filename = basename($img);
                $url = base_url('assets/img/gallery/' . $filename);
                $html .= '<div class="col-3 mb-3 image-item" data-filename="' . $filename . '">
                <img src="' . $url . '" alt="' . $filename . '" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
            </div>';
            }
        } else {
            $html = '<div class="col-12"><p class="text-muted text-center">Tidak ada gambar ditemukan.</p></div>';
        }

        echo $html;
    }

    // public function update_user($id)
    // {
    //     $model = new \App\Models\M_user();
    //     $user = $model->asObject()->find($id);

    //     if (!$user) {
    //         return redirect()->to('/user')->with('error', 'User tidak ditemukan.');
    //     }

    //     $nama = $this->request->getPost('nama');
    //     $nohp = $this->request->getPost('nohp');
    //     $email = $this->request->getPost('email') ?: null;
    //     $level = $this->request->getPost('level');
    //     $divisi = $this->request->getPost('divisi');
    //     $password = $this->request->getPost('password');

    //     $fotoData = $this->request->getPost('foto');
    //     $fotoName = $user->foto; // Keep existing photo if no new one

    //     if ($fotoData) {
    //         // // Hapus foto lama
    //         // if (!empty($user->foto) && file_exists(FCPATH . 'assets/img/' . $user->foto)) {
    //         //     unlink(FCPATH . 'assets/img/' . $user->foto);
    //         // }

    //         // Hapus prefix dan whitespace
    //         $fotoData = preg_replace('/^data:image\/\w+;base64,/', '', $fotoData);
    //         $fotoData = str_replace(' ', '+', $fotoData);
    //         $fotoData = str_replace("\n", '', $fotoData);
    //         $fotoData = str_replace("\r", '', $fotoData);

    //         $fotoDecoded = base64_decode($fotoData);

    //         if ($fotoDecoded === false) {
    //             return redirect()->back()->with('error', 'Upload foto gagal, data tidak valid.');
    //         }

    //         $fotoName = time() . '_' . uniqid() . '.png';
    //         file_put_contents(FCPATH . 'assets/img/' . $fotoName, $fotoDecoded);
    //     }


    //     $data = [
    //         'username' => $nama,
    //         'nomor_hp' => $nohp,
    //         'email' => $email,
    //         'level' => $level,
    //         'divisi' => $divisi,
    //         'foto' => $fotoName,
    //         'updated_at' => date('Y-m-d H:i:s'),
    //     ];

    //     if (!empty($password)) {
    //         $data['password'] = md5($password); // atau password_hash($password, PASSWORD_DEFAULT)
    //     }

    //     $updated = $model->update($id, $data);

    //     if ($updated) {
    //         $this->logActivity("Mengupdate user: $nama (ID: $id)");
    //     }

    //     return redirect()->to(base_url('user'))->with('success', 'Data user berhasil diupdate.');
    // }


    public function dihapus_user()
    {
        if (session()->get('id_user') > 0) {
            $this->logActivity("Mengakses Tabel Data Data user yang Dihapus");

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
            $this->logActivity("Menghapus permanen user ID: $id");

            return redirect()->to(base_url('user/dihapus_user'))->with('success', 'Data user berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('user/dihapus_user'))->with('error', 'Data user tidak ditemukan atau gagal dihapus');
    }

    public function delete_user($id)
    {
        $M_user = new M_user();
        if ($M_user->softDelete($id)) {
            $this->logActivity("Menghapus user ID: $id (soft delete)");

            return redirect()->to(base_url('user'))->with('success', 'Data user berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('user'))->with('error', 'Data user tidak ditemukan atau gagal dihapus');
    }

    public function restore_user($id)
    {
        $M_user = new M_user();

        if ($M_user->restore($id)) {
            $this->logActivity("Mengembalikan user ID: $id (soft delete)");
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
