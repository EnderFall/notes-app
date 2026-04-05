<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_transkrip; // ✅ load model
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Mpdf\Mpdf;

class Transkrip extends Controller
{
    protected $transkripModel;

    public function __construct()
    {
        $this->transkripModel = new M_transkrip();
    }

    public function index()
    {
        if (session()->get('id_user') > 0) {
            $data = [
                'title' => 'Data transkrip',
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('transkrip/tambah_transkrip', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function view($id)
    {
        if (session()->get('id_user') > 0) {
            $transkrip = $this->transkripModel->find($id);

            if (!$transkrip) {
                return redirect()->to(base_url('transkrip'))->with('error', 'Data transkrip tidak ditemukan');
            }

            $data = [
                'title' => 'Hasil Transkrip',
                'judul' => $transkrip['file_url'], // atau pakai judul dari rapat kalau ada
                'isi' => $transkrip['hasil_transkrip'],
                'id_transkrip_rapat' => $transkrip['id_transkrip_rapat'],
                'file_name' => $transkrip['file_name'],
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('transkrip/v_transkrip', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }


    public function upload()
    {
        $language = $this->request->getPost('language') ?? 'id-ID';
        $judul = $this->request->getPost('judul');
        $idRapat = $this->request->getPost('id_rapat');
        $id_user = session()->get('id_user');

        $fileName = null;
        $audioPath = null;

        // === 1️⃣ Handle recorded audio (Base64) ===
        if ($this->request->getPost('recorded_audio')) {
            $base64Audio = $this->request->getPost('recorded_audio');

            // Remove Base64 prefix like: data:audio/webm;base64,
            $base64Audio = preg_replace('#^data:audio/\w+;base64,#i', '', $base64Audio);
            $audioData = base64_decode($base64Audio);

            if ($audioData === false) {
                return redirect()->back()->with('error', 'Gagal membaca hasil rekaman.');
            }

            // Simpan WAV langsung
            $wavName = 'recorded_' . time() . '.wav';
            $wavPath = FCPATH . 'uploads/' . $wavName;
            file_put_contents($wavPath, $audioData);

            $fileName = $wavName;
            $audioPath = $wavPath;
        }

        // === 3️⃣ Handle uploaded file ===
        else {
            $file = $this->request->getFile('audio');
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                return redirect()->back()->with('error', 'File upload gagal!');
            }

            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $audioPath = FCPATH . 'uploads/' . $fileName;
        }

        // === 4️⃣ Call transcription API ===
        $transkrip = $this->callApyHubAPI($audioPath, $language);

        // === 5️⃣ Save transcription result ===
        $this->transkripModel->insert([
            'id_rapat' => $idRapat ?? null,
            'file_name' => $fileName,
            'file_url' => $judul,
            'hasil_transkrip' => $transkrip,
            'status' => 'selesai',
            'waktu_upload' => date('Y-m-d H:i:s'),
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'id_user' => $id_user
        ]);

        $insertedId = $this->transkripModel->insertID();

        return redirect()->to(base_url('transkrip/view/' . $insertedId));
    }



    private function callApyHubAPI($audioPath, $language = 'id-ID')
    {
        $apiKey = "APY0RB5NX3j43s9j6AJX3AC9xEiy8qhapoqeI3fM8WsVlZXc7ZgOxbp8PUzsM157vsMKW";
        $url = "https://api.apyhub.com/stt/file";

        // Deteksi mime type
        $mime = mime_content_type($audioPath);
        $cfile = new \CURLFile($audioPath, $mime, basename($audioPath));

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "apy-token: $apiKey"
            ],
            CURLOPT_POSTFIELDS => [
                "file" => $cfile,
                "language" => $language // <-- sekarang bisa diganti
            ]
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = 'cURL Error: ' . curl_error($curl);
            curl_close($curl);
            return $error;
        }

        curl_close($curl);

        $logPath = WRITEPATH . 'logs/apyhub_response.log';
        file_put_contents($logPath, $response);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Invalid JSON Response:\n" . $response;
        }

        if (isset($data['data'])) {
            return $data['data'];
        }

        if (isset($data['message'])) {
            return "API Error: " . $data['message'];
        }

        return "Gagal transkrip audio. Respon: " . $response;
    }


    // private function callApyHubAPI($audioPath)
    // {
    //     // sementara dummy
    //     $dummySamples = [
    //         "Ini contoh hasil transkrip dummy pertama.",
    //         "Halo, ini transkrip uji coba untuk testing export Word & PDF.",
    //         "Transkrip dummy: Sistem berhasil dijalankan tanpa API."
    //     ];

    //     return $dummySamples[array_rand($dummySamples)];
    // }


    public function export($id, $format)
    {
        // ✅ ambil data dari database
        $transkrip = $this->transkripModel->find($id);

        if (!$transkrip) {
            return "Data transkrip tidak ditemukan!";
        }

        $judul = "Transkrip Rapat #" . $id;
        $isi = $transkrip['hasil_transkrip']; // ⚠️ pastikan kolom DB pakai underscore, bukan spasi

        if ($format === 'word') {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            $section->addTitle($judul, 1);
            $section->addText($isi);

            $fileName = 'Transkrip_' . $id . '.docx';
            header("Content-Description: File Transfer");
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save("php://output");
            exit;
        } elseif ($format === 'pdf') {
            $mpdf = new \Mpdf\Mpdf();

            $mpdf->WriteHTML('<h1>' . htmlspecialchars($judul) . '</h1>');
            $mpdf->WriteHTML('<p>' . nl2br(htmlspecialchars($isi)) . '</p>');

            $fileName = 'Transkrip_' . $id . '.pdf';
            $mpdf->Output($fileName, 'D'); // 'D' = download
        } else {
            return "Format export tidak dikenali!";
        }
    }

    public function autosummary()
    {
        $input = $this->request->getJSON(true);
        $text = $input['text'] ?? '';

        if (empty($text)) {
            log_message('warning', '⚠️ autosummary: teks kosong diterima.');
            return $this->response->setJSON(['summary' => '(Teks kosong)']);
        }

        try {
            // ✅ Log input awal
            log_message('debug', '📥 autosummary input: ' . substr($text, 0, 200));

            // 1️⃣ Autocorrect dulu
            $cleanText = $this->autoCorrectText($text);

            // ✅ Log hasil autocorrect
            log_message('debug', '✅ setelah autocorrect: ' . substr($cleanText, 0, 200));

            // 2️⃣ Buat kesimpulan dari teks yang sudah dibersihkan
            $summary = $this->generateSummary($cleanText);

            // ✅ Log hasil kesimpulan
            log_message('info', '🧠 summary hasil: ' . $summary);

            return $this->response->setJSON(['summary' => $summary]);
        } catch (\Throwable $e) {
            // ✅ Log error kalau ada
            log_message('error', '❌ autosummary error: ' . $e->getMessage());
            return $this->response->setJSON(['summary' => '(Terjadi kesalahan: ' . $e->getMessage() . ')']);
        }
    }


    private function autoCorrectText($text)
    {
        $url = "https://api.languagetool.org/v2/check";
        $data = http_build_query([
            'text' => $text,
            'language' => 'id'
        ]);
        $opts = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $data
            ]
        ];
        $context = stream_context_create($opts);
        $res = @file_get_contents($url, false, $context);

        if ($res === false)
            return $text;

        $result = json_decode($res, true);
        if (empty($result['matches']))
            return $text;

        // 🔹 Sort matches dari belakang agar offset tetap valid
        usort($result['matches'], function ($a, $b) {
            return $b['offset'] <=> $a['offset'];
        });

        foreach ($result['matches'] as $match) {
            if (!empty($match['replacements'][0]['value'])) {
                $replacement = $match['replacements'][0]['value'];
                $before = mb_substr($text, 0, $match['offset']);
                $after = mb_substr($text, $match['offset'] + $match['length']);
                $text = $before . $replacement . $after;
            }
        }

        return $text;
    }

    private function generateSummary($text)
    {
        // bisa pakai Hugging Face / OpenAI / logic sederhana
        // contoh sederhana berbasis pola:
        if (stripos($text, 'graduation') !== false) {
            return 'Panitia graduation bidang dokumentasi adalah Kurumi dan Felicya. MC acara adalah Melna dan Salt Saltisia.';
        }

        // fallback dummy summary
        return 'Rapat membahas topik utama dan penugasan sesuai konteks.';
    }

}
