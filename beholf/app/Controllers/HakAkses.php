<?php

namespace App\Controllers;
use App\Models\M_form;
use App\Models\M_group_form;
use App\Models\M_hak_akses_user;
use App\Models\M_level;
use App\Models\M_user;

class HakAkses extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $M_form = new M_form();
            $M_level = new M_level();
            $M_user = new M_user();
            $M_hak_akses_user = new M_hak_akses_user();

            $data = [
                'title' => 'Hak Akses',
                'forms' => $M_form->where('status_delete', 0)->findAll(),
                'levels' => $M_level->where('status_delete', 0)->findAll(),
                'users' => $M_user->where('status_delete', 0)->findAll(),
                'user_levels' => $M_hak_akses_user->where('status_delete', 0)->findAll(),
                'deleted_forms' => $M_form->getDeletedForms(),
                'deleted_levels' => $M_level->getDeletedLevels(),
                'deleted_user_levels' => $M_hak_akses_user->getDeletedUserAccess()
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/v_hak_akses', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    // Tab 1: Insert new menus (forms)
    public function tambah_form()
    {
        if (session()->get('id_user') > 0) {
            $data['title'] = 'Tambah Form Baru';

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/tambah_form', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_tambah_form()
    {
        $deskripsi = $this->request->getPost('deskripsi');
        $route = $this->request->getPost('route');
        $icon = $this->request->getPost('icon');
        $jenis_form = $this->request->getPost('jenis_form');

        $data = [
            'deskripsi' => $deskripsi,
            'route' => $route,
            'icon' => $icon,
            'jenis_form' => $jenis_form
        ];

        $model = new M_form();
        $model->insert($data);
        $this->logActivity("Menambahkan form baru: $deskripsi");

        return redirect()->to(base_url('hak_akses'))->with('success', 'Form berhasil ditambahkan.');
    }

    // Tab 2: Create level and assign permissions
    public function tambah_level_permission()
    {
        if (session()->get('id_user') > 0) {
            $M_level = new M_level();
            $M_form = new M_form();

            $data = [
                'title' => 'Buat Level dan Assign Permission',
                'levels' => $M_level->where('status_delete', 0)->findAll(),
                'forms' => $M_form->where('status_delete', 0)->findAll()
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/tambah_level_permission', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_tambah_level()
    {
        $nama_level = $this->request->getPost('nama_level');
        $permissions = $this->request->getPost('permissions'); // array of form_id => ['can_read', 'can_approve']

        // Insert new level
        $level_model = new M_level();
        $level_id = $level_model->insert(['nama_level' => $nama_level]);

        // Insert permissions
        if ($permissions && is_array($permissions)) {
            $group_form_model = new M_group_form();
            $form_model = new M_form();
            foreach ($permissions as $form_id => $perms) {
                $form = $form_model->find($form_id);
                $can_read = 0;
                $can_create = 0;
                $can_approve = 0;
                if ($form && $form['jenis_form'] == 'table') {
                    $can_read = !empty($perms['can_read']) ? 1 : 0;
                    $can_create = !empty($perms['can_create']) ? 1 : 0;
                    $can_approve = !empty($perms['can_approve']) ? 1 : 0;
                }
                $data = [
                    'id_form' => $form_id,
                    'id_level' => $level_id,
                    'can_read' => $can_read,
                    'can_create' => $can_create,
                    'can_approve' => $can_approve
                ];
                $group_form_model->insert($data);
            }
        }

        $this->logActivity("Membuat level baru: $nama_level dengan permissions");

        return redirect()->to(base_url('hak_akses'))->with('success', 'Level dan permission berhasil dibuat.');
    }

    // Tab 3: Assign levels to users
    public function assign_user_level()
    {
        if (session()->get('id_user') > 0) {
            $M_user = new M_user();
            $M_level = new M_level();

            $data = [
                'title' => 'Assign Level ke User',
                'users' => $M_user->where('status_delete', 0)->findAll(),
                'levels' => $M_level->where('status_delete', 0)->findAll()
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/assign_user_level', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_assign_user_level()
    {
        $user_id = $this->request->getPost('id_user');
        $level_id = $this->request->getPost('id_level');

        $model = new M_hak_akses_user();

        // Check if user already has a level
        $existing = $model->getUserLevel($user_id);
        if ($existing) {
            $model->update($existing['id_hak_akses'], ['id_level' => $level_id]);
        } else {
            $model->insert([
                'id_user' => $user_id,
                'id_level' => $level_id
            ]);
        }

        // Update the level column in el_user table
        $user_model = new M_user();
        $user_model->update($user_id, ['level' => $level_id]);

        $this->logActivity("Assign level $level_id ke user $user_id");

        return redirect()->to(base_url('hak_akses'))->with('success', 'Level berhasil diassign ke user.');
    }

    // Edit level and permissions
    public function edit_level_permission($level_id)
    {
        if (session()->get('id_user') > 0) {
            $M_level = new M_level();
            $M_form = new M_form();
            $M_group_form = new M_group_form();

            $level = $M_level->find($level_id);
            $forms = $M_form->where('status_delete', 0)->findAll();
            $existing_permissions = $M_group_form->getPermissionsByLevel($level_id);

            $data = [
                'title' => 'Edit Level & Permission',
                'level' => $level,
                'forms' => $forms,
                'existing_permissions' => $existing_permissions
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/edit_level_permission', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_edit_level()
    {
        $level_id = $this->request->getPost('id_level');
        $nama_level = $this->request->getPost('nama_level');
        $permissions = $this->request->getPost('permissions');

        // Update level name
        $level_model = new M_level();
        $level_model->update($level_id, ['nama_level' => $nama_level]);

        // Delete existing permissions
        $group_form_model = new M_group_form();
        $group_form_model->where('id_level', $level_id)->delete();

        // Insert new permissions
        if ($permissions && is_array($permissions)) {
            $form_model = new M_form();
            foreach ($permissions as $form_id => $perms) {
                $form = $form_model->find($form_id);
                $can_read = 0;
                $can_create = 0;
                $can_approve = 0;
                if ($form && $form['jenis_form'] == 'table') {
                    $can_read = !empty($perms['can_read']) ? 1 : 0;
                    $can_create = !empty($perms['can_create']) ? 1 : 0;
                    $can_approve = !empty($perms['can_approve']) ? 1 : 0;
                }
                $data = [
                    'id_form' => $form_id,
                    'id_level' => $level_id,
                    'can_read' => $can_read,
                    'can_create' => $can_create,
                    'can_approve' => $can_approve
                ];
                $group_form_model->insert($data);
            }
        }

        $this->logActivity("Edit level $level_id: $nama_level dengan permissions");

        return redirect()->to(base_url('hak_akses'))->with('success', 'Level dan permission berhasil diupdate.');
    }

    public function aksi_delete_level($level_id)
{
    $model = new M_level();
    $level = $model->find($level_id);
    
    if ($level && $level['status_delete'] == 0) {
        // Soft delete the level itself
        $model->update($level_id, ['status_delete' => 1]);
        
        // Only soft delete user level assignments (not forms)
        $hak_akses_user_model = new M_hak_akses_user();
        $hak_akses_user_model->where('id_level', $level_id)
                            ->where('status_delete', 0)
                            ->set(['status_delete' => 1])
                            ->update();
        
        $this->logActivity("Hapus level: " . $level['nama_level']);
        return redirect()->to(base_url('hak_akses'))->with('success', 'Level berhasil dihapus.');
    } elseif ($level && $level['status_delete'] == 1) {
        return redirect()->to(base_url('hak_akses'))->with('error', 'Level sudah dihapus sebelumnya.');
    } else {
        return redirect()->to(base_url('hak_akses'))->with('error', 'Level tidak ditemukan.');
    }
}

    // Edit form
    public function edit_form($form_id)
    {
        if (session()->get('id_user') > 0) {
            $M_form = new M_form();
            $form = $M_form->find($form_id);

            $data = [
                'title' => 'Edit Form',
                'form' => $form
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('hak_akses/edit_form', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_edit_form()
    {
        $form_id = $this->request->getPost('id_form');
        $deskripsi = $this->request->getPost('deskripsi');
        $route = $this->request->getPost('route');
        $icon = $this->request->getPost('icon');
        $jenis_form = $this->request->getPost('jenis_form');

        $data = [
            'deskripsi' => $deskripsi,
            'route' => $route,
            'icon' => $icon,
            'jenis_form' => $jenis_form
        ];

        $model = new M_form();
        $model->update($form_id, $data);
        $this->logActivity("Edit form $form_id: $deskripsi");

        return redirect()->to(base_url('hak_akses'))->with('success', 'Form berhasil diupdate.');
    }

    public function aksi_delete_form($form_id)
    {
        $model = new M_form();
        $form = $model->find($form_id);
        if ($form) {
            $model->update($form_id, ['status_delete' => 1]);
            $this->logActivity("Hapus form: " . $form['deskripsi']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'Form berhasil dihapus.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'Form tidak ditemukan.');
        }
    }

    // AJAX endpoints for dynamic content
    public function get_permissions_by_level($level_id)
    {
        $M_group_form = new M_group_form();
        $M_level = new M_level();
        $permissions = $M_group_form->getFormsByLevel($level_id);
        $level = $M_level->find($level_id);

        return $this->response->setJSON([
            'level_name' => $level ? $level['nama_level'] : 'Unknown Level',
            'permissions' => $permissions
        ]);
    }

    public function get_user_level($user_id)
    {
        $M_hak_akses_user = new M_hak_akses_user();
        $user_level = $M_hak_akses_user->getUserLevel($user_id);

        return $this->response->setJSON($user_level);
    }

    // // View deleted items
    // public function deleted_items()
    // {
    //     if (session()->get('id_user') > 0) {
    //         $M_form = new M_form();
    //         $M_level = new M_level();
    //         $M_user = new M_user();
    //         $M_hak_akses_user = new M_hak_akses_user();

    //         $data = [
    //             'title' => 'Deleted Items - Hak Akses',
    //             'deleted_forms' => $M_form->where('status_delete', 1)->findAll(),
    //             'deleted_levels' => $M_level->where('status_delete', 1)->findAll(),
    //             'deleted_user_levels' => $M_hak_akses_user->where('status_delete', 1)->findAll(),
    //             'users' => $M_user->where('status_delete', 0)->findAll(),
    //             'levels' => $M_level->where('status_delete', 0)->findAll()
    //         ];

    //         echo view('header', $data);
    //         echo view('menu');
    //         echo view('hak_akses', $data);
    //         echo view('footer');
    //     } else {
    //         return redirect()->to('login/logout');
    //     }
    // }

    // Restore methods
    public function aksi_restore_form($form_id)
    {
        $model = new M_form();
        $form = $model->find($form_id);
        if ($form && $form['status_delete'] == 1) {
            $model->update($form_id, ['status_delete' => 0]);
            $this->logActivity("Restore form: " . $form['deskripsi']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'Form berhasil direstore.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'Form tidak ditemukan atau sudah aktif.');
        }
    }
    public function aksi_restore_level($level_id)
{
    $model = new M_level();
    $level = $model->find($level_id);
    
    if ($level && $level['status_delete'] == 1) {
        // Restore the level
        $model->update($level_id, ['status_delete' => 0]);
        
        // Only restore user level assignments (not forms)
        $hak_akses_user_model = new M_hak_akses_user();
        $hak_akses_user_model->where('id_level', $level_id)
                            ->where('status_delete', 1)
                            ->set(['status_delete' => 0])
                            ->update();
        
        $this->logActivity("Restore level: " . $level['nama_level']);
        return redirect()->to(base_url('hak_akses'))->with('success', 'Level berhasil direstore.');
    } else {
        return redirect()->to(base_url('hak_akses'))->with('error', 'Level tidak ditemukan atau sudah aktif.');
    }
}

    public function aksi_restore_user_level($id_hak_akses)
    {
        $model = new M_hak_akses_user();
        $user_level = $model->find($id_hak_akses);
        if ($user_level && $user_level['status_delete'] == 1) {
            $model->update($id_hak_akses, ['status_delete' => 0]);
            $this->logActivity("Restore user level assignment: " . $user_level['id_user'] . " - " . $user_level['id_level']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'User level assignment berhasil direstore.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'User level assignment tidak ditemukan atau sudah aktif.');
        }
    }

    // Permanent delete methods
    public function aksi_permanent_delete_form($form_id)
    {
        $model = new M_form();
        $form = $model->find($form_id);
        if ($form && $form['status_delete'] == 1) {
            $model->delete($form_id);
            $this->logActivity("Permanent delete form: " . $form['deskripsi']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'Form berhasil dihapus permanen.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'Form tidak ditemukan atau sudah aktif.');
        }
    }

    public function aksi_permanent_delete_level($level_id)
    {
        $model = new M_level();
        $level = $model->find($level_id);
        if ($level && $level['status_delete'] == 1) {
            // Permanently delete related group_form records
            $group_form_model = new M_group_form();
            $group_form_model->where('id_level', $level_id)->delete();

            // Permanently delete the level
            $model->delete($level_id);
            $this->logActivity("Permanent delete level: " . $level['nama_level']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'Level berhasil dihapus permanen.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'Level tidak ditemukan atau sudah aktif.');
        }
    }

    public function aksi_permanent_delete_user_level($id_hak_akses)
    {
        $model = new M_hak_akses_user();
        $user_level = $model->find($id_hak_akses);
        if ($user_level && $user_level['status_delete'] == 1) {
            $model->delete($id_hak_akses);
            $this->logActivity("Permanent delete user level assignment: " . $user_level['id_user'] . " - " . $user_level['id_level']);
            return redirect()->to(base_url('hak_akses'))->with('success', 'User level assignment berhasil dihapus permanen.');
        } else {
            return redirect()->to(base_url('hak_akses'))->with('error', 'User level assignment tidak ditemukan atau sudah aktif.');
        }
    }
}
