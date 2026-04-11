<?php

namespace App\Controllers;
use App\Models\M_rapat;
use App\Models\M_peserta;
use App\Models\M_transkrip;
use App\Models\M_form;
use App\Models\M_group_form;


class Rapat extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $M_rapat = new M_rapat();
            $M_peserta = new M_peserta();
            $M_form = new M_form();
            $M_group_form = new M_group_form();

            $user_id = session()->get('id_user');
            $user_level = session()->get('level');
            $can_create = 0;

            // 🔍 Cek hak akses pada form "rapat"
            $form = $M_form->where('route', 'rapat')->where('status_delete', 0)->first();
            if ($form) {
                $perm = $M_group_form
                    ->where('id_form', $form['id_form'])
                    ->where('id_level', $user_level)
                    ->where('status_delete', 0)
                    ->first();

                if ($perm && $perm['can_create'] == 1) {
                    $can_create = 1;
                }
            }

            // 🔸 Ambil data rapat sesuai hak akses
            if ($can_create) {
                // Jika punya hak akses penuh (lihat semua)
                $rapat = $M_rapat->select('el_notes.*, el_category.name AS category_name, el_category.color AS category_color, el_category.icon AS category_icon')
                    ->join('el_category', 'el_notes.category = el_category.id_category', 'left')
                    ->where('el_notes.status_delete', 0)
                    ->asObject()
                    ->findAll();
            } else {
                // Jika tidak punya hak akses penuh, hanya rapat yang diikuti user atau dalam divisinya
                $user_id = session()->get('id_user');
                $user_divisi = session()->get('divisi'); // pastikan divisi disimpan di session saat login

                $rapat = $M_rapat->select('el_notes.*, el_category.name AS category_name, el_category.color AS category_color, el_category.icon AS category_icon')
                    ->join('el_category', 'el_notes.category = el_category.id_category', 'left')
                    ->join('el_peserta', 'el_peserta.id_note = el_notes.id_note', 'left')
                    ->groupStart()
                    ->where('el_peserta.id_user', $user_id)
                    ->orWhere('el_notes.category', $user_divisi)
                    ->groupEnd()
                    ->where('el_notes.status_delete', 0)
                    ->groupBy('el_notes.id_note')
                    ->asObject()
                    ->findAll();
            } 


            $data = [
                'title' => 'Data Rapat',
                'rapat' => $rapat,
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
            $M_category = new \App\Models\M_category();
            $data = [
                'title' => 'Tambah Data Note',
                'divisi' => $M_category->getAllCategories(), // Get all categories
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
        $category = $this->request->getPost('category');
        $content = $this->request->getPost('content');
        $tags = $this->request->getPost('tags');
        
        // Set current date/time if not provided
        if (empty($tanggal)) {
            $tanggal = date('Y-m-d H:i:s');
        } else {
            $tanggal = date('Y-m-d H:i:s', strtotime($tanggal));
        }

        $data = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'lokasi' => $lokasi,
            'keterangan' => $keterangan ?: null,
            'content' => $content,
            'category' => $category,
            'tags' => $tags,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('id_user'),
        ];

        $model = new M_rapat();
        $model->insert($data);
        $this->logActivity("Menambahkan note baru: $judul");

        return redirect()->to(base_url('rapat'))->with('success', 'Note berhasil ditambahkan.');
    }

    public function edit_rapat($id)
    {
        if (session()->get('id_user') > 0) {
            $model = new M_rapat();
            $M_category = new \App\Models\M_category();
            $data = [
                'title' => 'Edit Note',
                'divisi' => $M_category->getAllCategories(),
            ];
            $data['rapat'] = $model->asObject()->find($id);

            if (!$data['rapat']) {
                return redirect()->to('/rapat')->with('error', 'Note tidak ditemukan.');
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
        $category = $this->request->getPost('category');
        $keterangan = $this->request->getPost('keterangan');
        $content = $this->request->getPost('content');
        $tags = $this->request->getPost('tags');
        $tanggal = date('Y-m-d H:i:s', strtotime($tanggal));

        $data = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'lokasi' => $lokasi,
            'keterangan' => $keterangan,
            'content' => $content,
            'tags' => $tags,
            'category' => $category,
            'updated_at' => date('Y-m-d H:i:s'),
        ];


        $model->update($id, $data);
        $this->logActivity("Mengupdate rapat: $judul (ID: $id)");

        return redirect()->to(base_url('rapat'))->with('success', 'Data rapat berhasil diupdate.');
    }

    public function get_peserta($id_note)
    {
        $userModel = new \App\Models\M_user();
        $pesertaModel = new \App\Models\M_peserta();

        // Pakai fungsi model untuk dapat user + divisi
        $users = $userModel->getUsersWithDivisi();

        $pesertaDipilih = $pesertaModel
            ->where('id_note', $id_note)
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
        $id_note = $this->request->getPost('id_rapat');
        $id_user = $this->request->getPost('id_user');
        $status = $this->request->getPost('status'); // 1 = centang, 0 = hapus

        $pesertaModel = new \App\Models\M_peserta();

        if ($status == 1) {
            // Insert jika belum ada
            $exists = $pesertaModel->where('id_note', $id_note)
                ->where('id_user', $id_user)
                ->first();
            if (!$exists) {
                $pesertaModel->insert([
                    'id_note' => $id_note,
                    'id_user' => $id_user
                ]);
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Peserta ditambahkan']);
        } else {
            // Hapus peserta
            $pesertaModel->where('id_note', $id_note)
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
        $id_note = $this->request->getPost('id_rapat');
        $peserta = $this->request->getPost('peserta') ?? [];

        $pesertaModel = new \App\Models\M_peserta();
        $pesertaModel->where('id_note', $id_note)->delete();

        foreach ($peserta as $id_user) {
            $pesertaModel->insert([
                'id_note' => $id_note,
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
            $this->logActivity("Mengakses Tabel Data Data rapat yang Dihapus");

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

    // public function hapus_rapat($id)
    // {
    //     $M_rapat = new M_rapat();
    //     if ($M_rapat->deletePermanen($id)) {
    //         $this->logActivity("Menghapus permanen rapat ID: $id");

    //         return redirect()->to(base_url('rapat/dihapus_rapat'))->with('success', 'Data rapat berhasil dihapus secara permanen');
    //     }
    //     return redirect()->to(base_url('rapat/dihapus_rapat'))->with('error', 'Data rapat tidak ditemukan atau gagal dihapus');
    // }

    public function hapus_rapat($id)
    {
        $M_rapat = new M_rapat();
        $M_peserta = new M_peserta();
        $M_transkrip = new M_transkrip();

        // Ambil semua transkrip untuk hapus file audio-nya dulu
        $transkripList = $M_transkrip->where('id_note', $id)->findAll();

        if ($transkripList) {
            foreach ($transkripList as $t) {
                if (!empty($t['file_name'])) {
                    $path = FCPATH . 'uploads/' . $t['file_name']; // sesuaikan path folder kamu
                    if (file_exists($path)) {
                        unlink($path); // hapus file audio
                    }
                }
            }
        }

        // Hapus peserta dan transkrip
        $M_peserta->RapatdiDelete($id);
        $M_transkrip->RapatdiDelete($id);

        // Hapus rapat utama
        if ($M_rapat->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen rapat ID: $id dan semua data terkait (peserta, transkrip, audio)");
            return redirect()
                ->to(base_url('rapat/dihapus_rapat'))
                ->with('success', 'Data rapat dan seluruh data terkait berhasil dihapus secara permanen');
        }

        return redirect()
            ->to(base_url('rapat/dihapus_rapat'))
            ->with('error', 'Data rapat tidak ditemukan atau gagal dihapus');
    }


    public function delete_rapat($id)
    {
        $M_rapat = new M_rapat();
        if ($M_rapat->softDelete($id)) {
            $this->logActivity("Menghapus rapat ID: $id (soft delete)");

            return redirect()->to(base_url('rapat'))->with('success', 'Data rapat berhasil dihapus (soft delete)');
        }
        return redirect()->to(base_url('rapat'))->with('error', 'Data rapat tidak ditemukan atau gagal dihapus');
    }

    public function restore_rapat($id)
    {
        $M_rapat = new M_rapat();

        if ($M_rapat->restore($id)) {
            $this->logActivity("Mengembalikan rapat ID: $id (soft delete)");
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
                ->select('el_note_transcripts.*, el_user.username')
                ->join('el_user', 'el_user.id_user = el_note_transcripts.id_user', 'left')
                ->where('id_note', $id)
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

    /**
     * Summarize a note/meeting using AI
     */
    public function summarize($id)
    {
        if (session()->get('id_user') > 0) {
            $M_rapat = new M_rapat();
            $rapat = $M_rapat->find($id);

            if (!$rapat) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Catatan tidak ditemukan'
                ]);
            }

            // Combine title and description for summarization
            $textToSummarize = $rapat['judul'] . '. ' . ($rapat['keterangan'] ?? '');

            if (empty(trim($textToSummarize))) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada teks untuk diringkas'
                ]);
            }

            try {
                $summarizationService = new \App\Services\SummarizationService();
                $result = $summarizationService->summarizeNote($textToSummarize);

                $this->logActivity("Meringkas catatan ID: $id menggunakan AI");

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $result
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Summarization error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat meringkas catatan'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }
    }

    /**
     * Get highlighted version of note content
     */
    public function highlightKeywords($id)
    {
        if (session()->get('id_user') > 0) {
            $M_rapat = new M_rapat();
            $rapat = $M_rapat->find($id);

            if (!$rapat) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Catatan tidak ditemukan'
                ]);
            }

            $keywords = $this->request->getJSON(true)['keywords'] ?? [];

            if (empty($keywords)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada kata kunci'
                ]);
            }

            $summarizationService = new \App\Services\SummarizationService();
            $highlightedText = $summarizationService->highlightKeywords(
                $rapat['content'] ?? nl2br(htmlspecialchars($rapat['keterangan'] ?? '')),
                $keywords
            );

            return $this->response->setJSON([
                'status' => 'success',
                'highlighted_text' => $highlightedText
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }
    }

    /**
     * Extract text from uploaded document (AJAX endpoint)
     * Does not save the file, only extracts and returns text
     */
    public function extract_document_text()
    {
        if (session()->get('id_user') <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }

        $file = $this->request->getFile('document');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No valid file uploaded'
            ]);
        }

        $allowedExtensions = ['pdf', 'doc', 'docx'];
        $fileExtension = strtolower($file->getClientExtension());
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid file type. Only PDF and Word documents are allowed.'
            ]);
        }

        try {
            // Move to temporary location
            $tempPath = WRITEPATH . 'uploads/temp/';
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0777, true);
            }
            
            $tempFileName = 'temp_' . uniqid() . '.' . $fileExtension;
            $file->move($tempPath, $tempFileName);
            $filePath = $tempPath . $tempFileName;

            // Extract text
            $extractedText = $this->extractTextFromDocument($filePath, $fileExtension);

            // Delete temporary file
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if (empty($extractedText)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Could not extract text from document'
                ]);
            }

            $this->logActivity("Extracted text from uploaded document ({$fileExtension})");

            return $this->response->setJSON([
                'status' => 'success',
                'text' => $extractedText,
                'length' => strlen($extractedText)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Document text extraction error: ' . $e->getMessage());
            
            // Clean up temp file if it exists
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error extracting text: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Extract text content from uploaded PDF or Word document
     * @param string $filePath Path to the uploaded file
     * @param string $fileExtension File extension (pdf, doc, docx)
     * @return string Extracted text content
     */
    private function extractTextFromDocument($filePath, $fileExtension)
    {
        $extractedText = '';

        try {
            if ($fileExtension === 'pdf') {
                // Extract text from PDF using smalot/pdfparser
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filePath);
                $extractedText = $pdf->getText();
            } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                // Extract text from Word document using PHPWord
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $extractedText .= $element->getText() . "\n";
                        } elseif (method_exists($element, 'getElements')) {
                            foreach ($element->getElements() as $childElement) {
                                if (method_exists($childElement, 'getText')) {
                                    $extractedText .= $childElement->getText() . "\n";
                                }
                            }
                        }
                    }
                }
            }

            // Clean up the extracted text
            $extractedText = trim($extractedText);
            
            // Convert to HTML paragraphs for better display
            if (!empty($extractedText)) {
                $paragraphs = explode("\n\n", $extractedText);
                $htmlContent = '';
                foreach ($paragraphs as $paragraph) {
                    $paragraph = trim($paragraph);
                    if (!empty($paragraph)) {
                        $htmlContent .= '<p>' . nl2br(htmlspecialchars($paragraph)) . '</p>';
                    }
                }
                return $htmlContent;
            }

            return $extractedText;
        } catch (\Exception $e) {
            log_message('error', 'Document extraction error: ' . $e->getMessage());
            return '';
        }
    }




}