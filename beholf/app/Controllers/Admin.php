<?php

namespace App\Controllers;

use App\Models\M_transkrip;
use App\Models\M_rapat;
use App\Models\M_user;
use App\Controllers\Reminder;

class Admin extends BaseController
{

    public function index()
    {
        if (session()->get('id_user') > 0) {
            $userModel = new M_user();
            $rapatModel = new M_rapat();
            $transkripModel = new M_transkrip();

            $totalUser = $userModel->countAllResults();
            $totalRapat = $rapatModel->countAllResults();
            $totalTranskrip = $transkripModel->countAllResults();

            $idUser = session()->get('id_user');
            $username = session()->get('username');

            // ambil level user berdasarkan id_user
            $userData = $userModel->select('el_user.username, el_level.nama_level')
                ->join('el_level', 'el_level.id_level = el_user.level', 'left')
                ->where('el_user.id_user', $idUser)
                ->first();

            // Send meeting reminders on dashboard load (only if not already sent in this session)
            if (!session()->get('reminders_sent_' . $idUser)) {
                $reminderController = new Reminder();
                $reminderController->sendMeetingReminders($idUser);
                session()->set('reminders_sent_' . $idUser, true);
            }

            // Get calendar data
            $currentYear = date('Y');
            $currentMonth = date('m');
            $calendarNotes = $rapatModel->getNotesForCalendar($currentYear, $currentMonth);

            $data = [
                'title' => 'Dashboard Admin',
                'totalUser' => $totalUser,
                'totalRapat' => $totalRapat,
                'totalTranskrip' => $totalTranskrip,
                'levelUser' => $userData['nama_level'] ?? '-',
                'weeklyStats' => $transkripModel->getWeeklyStatsFull(),
                'monthlyStats' => $transkripModel->getMonthlyStatsFull(),
                'username' => $userData['username'] ?? $username,
                'calendarNotes' => $calendarNotes,
                'currentYear' => $currentYear,
                'currentMonth' => $currentMonth,
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('dashboard', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }
    public function api()
    {
        echo view('menu');
    }
}
