<?php

namespace App\Controllers;

use App\Models\M_rapat;
use App\Models\M_peserta;
use App\Models\M_user;

class Reminder extends BaseController
{
    public function sendMeetingReminders($userId = null)
    {
        // If no userId provided, get from session
        if (!$userId) {
            $userId = session()->get('id_user');
        }

        if (!$userId) {
            return false;
        }

        $M_rapat = new M_rapat();
        $M_peserta = new M_peserta();
        $M_user = new M_user();

        // Get current time in WIB (since database stores WIB time)
        $nowWIB = date('Y-m-d H:i:s', time() + (7 * 3600)); // Add 7 hours for WIB
        $twelveHoursFromNowWIB = date('Y-m-d H:i:s', time() + (7 * 3600) + (12 * 3600));
        $fiveMinutesFromNowWIB = date('Y-m-d H:i:s', time() + (7 * 3600) + (5 * 60));

        // Find meetings within 12 hours that this user is participating in AND haven't started yet
        // Only send reminders for meetings more than 5 minutes in the future
        $meetings = $M_rapat->select('el_rapat.*, el_peserta.id_user')
            ->join('el_peserta', 'el_peserta.id_rapat = el_rapat.id_rapat')
            ->where('el_peserta.id_user', $userId)
            ->where('el_rapat.tanggal >=', $fiveMinutesFromNowWIB)  // Meeting is at least 5 minutes in the future (WIB)
            ->where('el_rapat.tanggal <=', $twelveHoursFromNowWIB)  // Within 12 hours (WIB)
            ->where('el_rapat.status_delete', 0)
            ->findAll();

        if (empty($meetings)) {
            return true; // No meetings to remind about
        }

        // Get user email
        $user = $M_user->find($userId);
        if (!$user || empty($user['email'])) {
            return false;
        }

        $emailService = \Config\Services::email();

        foreach ($meetings as $meeting) {
            // Check if reminder already sent (you might want to add a reminder_log table for this)
            // For now, we'll send it every time they log in within the window

            $meetingTime = date('d M Y, H:i', strtotime($meeting['tanggal'])) . ' WIB';
            // Calculate time difference in seconds, then format as H:i
            $timeDiffSeconds = strtotime($meeting['tanggal']) - (7 * 3600) - time();
            $hours = floor(abs($timeDiffSeconds) / 3600);
            $minutes = floor((abs($timeDiffSeconds) % 3600) / 60);
            $timeUntil = sprintf('%02d:%02d', $hours, $minutes);

            $subject = "Meeting Reminder: {$meeting['judul']}";
            $message = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #fc7eaf;'>Meeting Reminder</h2>
                    <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3>{$meeting['judul']}</h3>
                        <p><strong>📅 Date & Time:</strong> {$meetingTime}</p>
                        <p><strong>⏰ Time until meeting:</strong> {$timeUntil}</p>
                        <p><strong>📍 Location:</strong> {$meeting['lokasi']}</p>
                        " . (!empty($meeting['keterangan']) ? "<p><strong>📝 Notes:</strong> {$meeting['keterangan']}</p>" : "") . "
                    </div>
                    <p style='color: #666; font-size: 14px;'>
                        This is an automated reminder from Ellie Meeting Assistant.
                    </p>
                </div>
            ";

            $emailService->setTo($user['email']);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                log_message('info', "Meeting reminder sent to {$user['email']} for meeting: {$meeting['judul']}");
            } else {
                log_message('error', "Failed to send meeting reminder to {$user['email']}: " . $emailService->printDebugger());
            }
        }

        return true;
    }
}
