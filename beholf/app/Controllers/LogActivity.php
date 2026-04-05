<?php

namespace App\Controllers;

use App\Models\M_log;
use App\Models\M_form;
use App\Models\M_group_form;

class LogActivity extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $M_log = new M_log();
            $user_level = session()->get('level');

            // Check permissions for log_activity
            $M_form = new M_form();
            $M_group_form = new M_group_form();
            $form = $M_form->where('route', 'log_activity')->where('status_delete', 0)->first();
            $can_create = 0;
            if ($form) {
                $perm = $M_group_form->where('id_form', $form['id_form'])->where('id_level', $user_level)->where('status_delete', 0)->first();
                if ($perm && $perm['can_create'] == 1) {
                    $can_create = 1;
                }
            }

            if ($can_create) {
                // Can see all logs
                $logs = $M_log->getAllLogs();
            } else {
                // Can see their own logs
                $user_id = session()->get('id_user');
                $logs = $M_log->getLogsByUser($user_id);
            }

            $data = [
                'title' => 'Log Activity',
                'logs' => $logs,
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('log_activity/v_log_activity', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }

    public function delete()
    {
        if (session()->get('id_user') > 0) {
            $user_level = session()->get('level');

            // Check permissions for log_activity
            $M_form = new M_form();
            $M_group_form = new M_group_form();
            $form = $M_form->where('route', 'log_activity')->where('status_delete', 0)->first();
            $can_approve = 0;
            if ($form) {
                $perm = $M_group_form->where('id_form', $form['id_form'])->where('id_level', $user_level)->where('status_delete', 0)->first();
                if ($perm && $perm['can_approve'] == 1) {
                    $can_approve = 1;
                }
            }

            if (!$can_approve) {
                session()->setFlashdata('error', 'You do not have permission to delete logs.');
                return redirect()->to('log_activity');
            }

            $M_log = new M_log();
            $selected_logs = $this->request->getPost('selected_logs');

            if (!empty($selected_logs)) {
                // Permanently delete selected logs
                $M_log->whereIn('id_log', $selected_logs)->delete();

                // Log the delete action
                $user_id = session()->get('id_user');
                $ip_address = $this->request->getIPAddress();
                $count = count($selected_logs);
                $M_log->saveLog($user_id, "Deleted $count log activity record(s)", $ip_address);

                session()->setFlashdata('success', "$count log(s) have been permanently deleted.");
            } else {
                session()->setFlashdata('error', 'No logs selected for deletion.');
            }
        } else {
            return redirect()->to('login/logout');
        }

        return redirect()->to('log_activity');
    }
}
