<?php

namespace App\Controllers;

use App\Models\M_user;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function aksi_login()
    {
        $username = $this->request->getPost('usn');
        $password = $this->request->getPost('pswd');
        $recaptcha_response = $this->request->getPost('g-recaptcha-response');
        $math_answer = $this->request->getPost('math_answer');
        $correct_answer = $this->request->getPost('correct_answer');

        $connected = @fsockopen("www.google.com", 80);

        if ($connected) {
            fclose($connected);

            $recaptcha_secret = "6LeZQekqAAAAAIk1nT3Xbz4KcKFyZ4Uk51w8m1b4";
            $verify_url = "https://www.google.com/recaptcha/api/siteverify";
            $response = file_get_contents($verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
            $response_keys = json_decode($response, true);

            if (!$response_keys["success"]) {
                return redirect()->back()->with('error', 'reCAPTCHA verification failed. Please try again.');
            }
        } else {
            if ($math_answer === null || $correct_answer === null || (int) $math_answer !== (int) $correct_answer) {
                return redirect()->back()->with('error', 'Verifikasi matematika salah. Coba lagi.');
            }
        }

        $M_user = new \App\Models\M_user();
        $user = $M_user->where('username', $username)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }

        if (md5($password) !== $user['password']) {
            return redirect()->back()->with('error', 'Password salah!');
        }

        // ✅ Cek apakah akun sudah diverifikasi (isApproved = 1)
        if (empty($user['isApproved']) || $user['isApproved'] != 1) {
            return redirect()->back()->with('error', 'Akun belum diverifikasi. Silakan cek email Anda.');
        }

        session()->regenerate();
        $sessionData = [
            'id_user' => $user['id_user'],
            'username' => $user['username'],
            'foto' => $user['foto'],
            'level' => $user['level'],
            'divisi' => $user['divisi'],
            'isLoggedIn' => true,
            'last_activity' => time()
        ];
        session()->set($sessionData);

        // Send meeting reminders on login
        $reminderController = new \App\Controllers\Reminder();
        $reminderController->sendMeetingReminders($user['id_user']);

        return redirect()->to('Admin');
    }


    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $userModel = new M_user();

            $username = $this->request->getPost('username');
            $nomor_hp = $this->request->getPost('nomor_hp');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Cek apakah username sudah ada
            if ($userModel->where('username', $username)->first()) {
                return redirect()->back()->withInput()->with('error', 'Username sudah terdaftar.');
            }

            // Cek apakah nomor HP sudah ada
            if ($userModel->where('nomor_hp', $nomor_hp)->first()) {
                return redirect()->back()->withInput()->with('error', 'Nomor HP sudah terdaftar.');
            }

            // Cek apakah email sudah ada
            if ($userModel->where('email', $email)->first()) {
                return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar.');
            }


            // Buat kode verifikasi 6 digit
            $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $data = [
                'username' => $username,
                'nomor_hp' => $nomor_hp,
                'email' => $email,
                'password' => md5($password),
                'level' => 3,
                'divisi' => null, // Tambahkan ini agar tidak error
                'token' => $token,
                'expiry' => $expiry,
                'isApproved' => false,
                'created_by' => 2,
            ];


            $inserted = $userModel->insert($data);

            if ($inserted) {
                $registeredUser = $userModel->where('email', $email)->first();
                $registeredUser['otp'] = $token;

                // Simpan ke session
                session()->set('registered_user', $registeredUser);
                // Kirim email verifikasi
                if (!empty($email)) {
                    $subject = "Kode Verifikasi Akun Anda";
                    $verify_link = base_url('verifikasi');
                    $message = "
                    <p>Halo <b>$username</b>,</p>
                    <p>Terima kasih telah mendaftar di sistem kami.</p>
                    <p>Kode verifikasi Anda adalah:</p>
                    <h2 style='color:#fc7eaf;'>$token</h2>
                    <p>Klik tombol berikut untuk verifikasi akun:</p>
                    <p><a href='$verify_link' style='background:#fc7eaf; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>Verifikasi Akun</a></p>
                    <p>Masukkan kode ini di halaman verifikasi. Berlaku selama 10 menit.</p>
                ";
                    $this->send_verification_email($email, $subject, $message);
                }

                return redirect()->to('/verifikasi')->with('success', 'Registrasi berhasil. Silakan cek email untuk kode verifikasi.');
            } else {
                // Jika gagal insert
                return redirect()->back()->withInput()->with('error', 'Registrasi gagal. Silakan coba lagi.');
            }
        }

        return view('register');
    }



    public function verify()
    {
        $kode = $this->request->getPost('kode');
        $userModel = new M_user();

        // Cari user berdasarkan token (bukan username lagi karena username dihapus dari form)
        $user = $userModel->where('token', $kode)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Kode verifikasi tidak ditemukan.');
        }

        if ($user['token'] !== $kode) {
            return redirect()->back()->withInput()->with('error', 'Kode verifikasi salah.');
        }

        if (strtotime($user['expiry']) < time()) {
            return redirect()->back()->withInput()->with('error', 'Kode verifikasi sudah kedaluwarsa.');
        }

        // Update status akun
        $userModel->update($user['id_user'], [
            'token' => null,
            'expiry' => null,
            'isApproved' => true
        ]);

        // Login otomatis (opsional)
        session()->set([
            'id_user' => $user['id_user'],
            'username' => $user['username'],
            'isLoggedIn' => true
        ]);

        // Redirect ke halaman utama/dashboard
        return redirect()->to(base_url('Admin'));
    }


    private function send_verification_email($to, $subject, $message)
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kurumidafox@gmail.com';
            $mail->Password = 'lzufykieovjeveaj'; // app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kurumidafox@gmail.com', 'Ellie - Meeting Assistant');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            log_message('error', 'Email gagal dikirim: ' . $mail->ErrorInfo);
        }
    }

    public function form_verifikasi()
    {
        return view('form_verifikasi');
    }


    public function sendWhatsAppCode()
    {
        $user = session()->get('registered_user'); // atau ambil dari DB kalau disimpan
        if (!$user) {
            return redirect()->to('register')->with('error', 'Data user tidak ditemukan.');
        }

        $token = 'VvwhbUpXqshWMnrdLDiU'; // Ganti dengan token dari Fonnte
        $rawPhone = $user['nomor_hp']; // dari DB atau input user
        if (str_starts_with($rawPhone, '0')) {
            $phone = '62' . substr($rawPhone, 1);
        } else {
            $phone = $rawPhone;
        }
        $verify_link = base_url('verifikasi');
        $message = "Selamat Datang di Ellie - Meeting Assistant! Kode verifikasi akun Anda adalah: {$user['otp']}\n\nKlik link berikut untuk verifikasi: $verify_link";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $phone,
                'message' => $message,
                'delay' => 2,
                'schedule' => 0,
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token" // Masukkan token API kamu di sini
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return redirect()->back()->with('success', 'Kode verifikasi telah dikirim ke WhatsApp Anda.');
    }



    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    public function forgot_password()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $userModel = new M_user();

            $user = $userModel->where('email', $email)->first();

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan.');
            }

            if (empty($user['isApproved']) || $user['isApproved'] != 1) {
                return redirect()->back()->withInput()->with('error', 'Akun belum diverifikasi. Silakan verifikasi akun terlebih dahulu.');
            }

            // Generate reset token
            $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            $userModel->update($user['id_user'], [
                'token' => $token,
                'expiry' => $expiry
            ]);

            // Send reset email
            $subject = "Reset Password Akun Anda";
            $reset_link = base_url('reset-password');
            $message = "
            <p>Halo <b>{$user['username']}</b>,</p>
            <p>Anda telah meminta reset password.</p>
            <p>Token reset password Anda adalah:</p>
            <h2 style='color:#fc7eaf;'>$token</h2>
            <p>Klik tombol berikut untuk reset password:</p>
            <p><a href='$reset_link' style='background:#fc7eaf; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>Reset Password</a></p>
            <p>Masukkan token ini di halaman reset password. Berlaku selama 15 menit.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            ";
            $this->send_verification_email($email, $subject, $message);

            return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        return view('forgot_password');
    }

    public function reset_password()
    {
        if ($this->request->getMethod() === 'POST') {
            $token = $this->request->getPost('token');
            $password = $this->request->getPost('password');
            $confirm_password = $this->request->getPost('confirm_password');
            $userModel = new M_user();

            if ($password !== $confirm_password) {
                return redirect()->back()->withInput()->with('error', 'Password dan konfirmasi password tidak cocok.');
            }

            $user = $userModel->where('token', $token)->first();

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Token reset tidak valid.');
            }

            if (strtotime($user['expiry']) < time()) {
                return redirect()->back()->withInput()->with('error', 'Token reset sudah kedaluwarsa.');
            }

            // Update password and clear token/expiry
            $userModel->update($user['id_user'], [
                'password' => md5($password),
                'token' => null,
                'expiry' => null
            ]);

            return redirect()->to('/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return view('reset_password');
    }

}
