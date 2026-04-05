<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_rapat;
use App\Models\M_peserta;
use App\Models\M_user;

class Cron extends Controller
{
    public function sendMeetingReminders()
    {
        $rapatModel = new M_rapat();
        $pesertaModel = new M_peserta();
        $userModel = new M_user();

        // Get current time and time 12 hours from now
        $now = date('Y-m-d H:i:s');
        $twelveHoursLater = date('Y-m-d H:i:s', strtotime('+12 hours'));
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));

        // Find meetings where tanggal is between now and 12 hours later
        // OR meetings that were created within the last hour and scheduled within 12 hours
        $upcomingMeetings = $rapatModel
            ->where('tanggal >=', $now)
            ->where('tanggal <=', $twelveHoursLater)
            ->where('status_delete', 0)
            ->where('created_at >=', $oneHourAgo)
            ->findAll();

        if (empty($upcomingMeetings)) {
            log_message('info', 'No meetings found that require reminders in the next 12 hours.');
            return;
        }

        $email = \Config\Services::email();
        $sentCount = 0;

        foreach ($upcomingMeetings as $meeting) {
            // Get participants for this meeting
            $participants = $pesertaModel
                ->select('el_user.email, el_user.username')
                ->join('el_user', 'el_user.id_user = el_peserta.id_user')
                ->where('el_peserta.id_rapat', $meeting['id_rapat'])
                ->findAll();

            if (empty($participants)) {
                log_message('info', "No participants found for meeting: {$meeting['judul']}");
                continue;
            }

            // Calculate hours until meeting
            $meetingTime = strtotime($meeting['tanggal']);
            $currentTime = time();
            $hoursUntil = round(($meetingTime - $currentTime) / 3600, 1);

            // Prepare email content
            $meetingTimeFormatted = date('d M Y, H:i', strtotime($meeting['tanggal']));
            $subject = "Reminder: Meeting in {$hoursUntil} Hours - {$meeting['judul']}";
            $message = "
                <h2>Meeting Reminder</h2>
                <p>Dear participant,</p>
                <p>This is a reminder that you have a meeting scheduled in approximately {$hoursUntil} hours:</p>
                <ul>
                    <li><strong>Title:</strong> {$meeting['judul']}</li>
                    <li><strong>Date & Time:</strong> {$meetingTimeFormatted}</li>
                    <li><strong>Location:</strong> {$meeting['lokasi']}</li>
                </ul>
                <p>Please make sure to attend the meeting on time.</p>
                <p>Best regards,<br>Meeting Management System</p>
            ";

            // Send email to each participant
            foreach ($participants as $participant) {
                $email->setTo($participant['email']);
                $email->setSubject($subject);
                $email->setMessage($message);
                $email->setMailType('html');

                if ($email->send()) {
                    log_message('info', "Reminder sent to: {$participant['email']} for meeting: {$meeting['judul']}");
                    $sentCount++;
                } else {
                    log_message('error', "Failed to send reminder to: {$participant['email']} - " . $email->printDebugger());
                }

                $email->clear();
            }
        }

        log_message('info', "Total reminders sent: {$sentCount}");
    }
}
