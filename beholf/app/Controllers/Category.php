<?php

namespace App\Controllers;
use App\Models\M_category;

class Category extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $M_category = new M_category();
            $data = [
                'title' => 'Data Category',
                'category' => $M_category
                    ->where('status_delete', 0)
                    ->orderBy('name', 'ASC')
                    ->asObject()
                    ->findAll()
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('category/v_category', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function tambah_category()
    {
        if (session()->get('id_user') > 0) {
            $data['title'] = 'Tambah Category';
            echo view('header', $data);
            echo view('menu');
            echo view('category/tambah_category', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function aksi_tambah_category()
    {
        $name        = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $color       = $this->request->getPost('color') ?? '#007BFF';
        $icon        = $this->request->getPost('icon') ?? 'bi-folder';

        $data = [
            'name'        => $name,
            'description' => $description,
            'color'       => $color,
            'icon'        => $icon
        ];

        $model = new M_category();
        $model->insert($data);
        $this->logActivity("Menambahkan category baru: $name");

        return redirect()->to(base_url('category'))->with('success', 'Category berhasil ditambahkan.');
    }

    public function edit_category($id)
    {
        if (session()->get('id_user') > 0) {
            $model = new M_category();
            $data['title'] = 'Edit Category';
            $data['category'] = $model->asObject()->find($id);

            if (!$data['category']) {
                return redirect()->to('/category')->with('error', 'Category tidak ditemukan.');
            }

            echo view('header', $data);
            echo view('menu');
            echo view('category/edit_category', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function update_category($id = null)
    {
        // Get ID from URL parameter or POST data
        $id = $id ?? $this->request->getPost('id_category');
        
        $model = new M_category();
        $category = $model->find($id);

        if (!$category) {
            return redirect()->to('/category')->with('error', 'Category tidak ditemukan.');
        }

        $name        = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $color       = $this->request->getPost('color');
        $icon        = $this->request->getPost('icon');

        $data = [
            'name'        => $name,
            'description' => $description,
            'color'       => $color,
            'icon'        => $icon
        ];

        $model->update($id, $data);
        $this->logActivity("Mengupdate category ID: $id menjadi: $name");

        return redirect()->to(base_url('category'))->with('success', 'Data category berhasil diupdate.');
    }

    public function dihapus_category()
    {
        if (session()->get('id_user') > 0) {
            $this->logActivity("Mengakses Tabel Data Category yang Dihapus");

            $M_category = new M_category();
            $data = [
                'title' => 'Data Category yang Dihapus',
                'deleted_category' => $M_category->getDeletedCategory(),
                'showWelcome' => false
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('category/deleted_category', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function hapus_category($id)
    {
        $M_category = new M_category();
        if ($M_category->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen category ID: $id");
            return redirect()->to(base_url('category/dihapus_category'))->with('success', 'Data category berhasil dihapus secara permanen');
        }
        return redirect()->to(base_url('category/dihapus_category'))->with('error', 'Data category tidak ditemukan atau gagal dihapus');
    }

    public function delete_category($id)
    {
        $M_category = new M_category();
        if ($M_category->softDelete($id)) {
            $this->logActivity("Menghapus category ID: $id (soft delete)");
            return redirect()->to(base_url('category'))->with('success', 'Data category berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('category'))->with('error', 'Data category tidak ditemukan atau gagal dihapus');
    }

    public function restore_category($id)
    {
        $M_category = new M_category();

        if ($M_category->restore($id)) {
            $this->logActivity("Mengembalikan category ID: $id");
            return redirect()->to(base_url('category'))->with('success', 'Data category berhasil direstore');
        }
        return redirect()->to(base_url('category'))->with('error', 'Data category tidak ditemukan');
    }

    public function detail_category($id)
    {
        if (session()->get('id_user') > 0) {
            $M_category = new M_category();
            $category = $M_category->getCategoryById($id);

            $data = [
                'title' => 'Detail Category',
                'category' => $category
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('category/detail_category', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }
}
