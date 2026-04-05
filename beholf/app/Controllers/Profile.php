<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_user;

class Profile extends BaseController
{
    public function index()
    {
        $session = session();
        $id = $session->get('id_user');

        if (!$id) {
            return redirect()->to(base_url('login'));
        }

        $userModel = new M_user();
        $data = [
            'title' => 'Profile',
            'user' => $userModel->getUserWithLevelById($id)
        ];


        echo view('header', $data);
        echo view('menu');
        echo view('Profile/profile_view', $data);
        echo view('footer');
    }

    public function update($id)
{
    $model = new M_user();
    $user = $model->asObject()->find($id);

    if (!$user) {
        return redirect()->to('/profile')->with('error', 'User tidak ditemukan.');
    }

    $nama = $this->request->getPost('nama');
    $nohp = $this->request->getPost('nohp');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $alamat = $this->request->getPost('alamat');

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
            $galleryPath = FCPATH . 'assets/img/gallery/' . $fotoName;
            file_put_contents($galleryPath, $fotoDecoded);
            
            // Then copy to main img folder for user profile
            $profilePath = FCPATH . 'assets/img/' . $fotoName;
            copy($galleryPath, $profilePath);

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
                    $otherUsers = $model->where('foto', $user->foto)->countAllResults();
                    if ($otherUsers === 0) {
                        unlink($oldGalleryPath);
                    }
                }
            }
        } else {
            // It's just a filename (existing image from gallery)
            $fotoName = $fotoData;
            
            // Copy from gallery to main img folder if not exists
            $profilePath = FCPATH . 'assets/img/' . $fotoName;
            
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
        'alamat' => $alamat,
        'foto' => $fotoName,
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    if (!empty($password)) {
        $data['password'] = md5($password);
    }

    $updated = $model->update($id, $data);

    if ($updated) {
        $this->logActivity("Mengupdate profil: $nama (ID: $id)");
        return redirect()->to(base_url('profile'))->with('success', 'Profil berhasil diperbarui.');
    } else {
        return redirect()->to(base_url('profile'))->with('error', 'Gagal memperbarui profil.');
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

    public function reset_password($id)
    {
        $model = new M_user();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('profile')->with('error', 'User tidak ditemukan.');
        }

        $defaultPassword = md5('123456'); // 🔒 default password
        $updated = $model->update($id, [
            'password' => $defaultPassword,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($updated) {
            $this->logActivity("Reset password user: {$user['username']} (ID: $id)");
            return redirect()->to('profile')->with('success', 'Password telah direset ke default (123456).');
        } else {
            return redirect()->to('profile')->with('error', 'Gagal mereset password.');
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

}
