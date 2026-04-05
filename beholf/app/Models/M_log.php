<?php

namespace App\Models;

use CodeIgniter\Model;

class M_log extends Model
{
    protected $table = 'el_log_activity';
    protected $primaryKey = 'id_log';
    protected $allowedFields = [
        'id_log',
        'id_user',
        'what_happens',
        'happens_at',
        'ip_address'
    ];

    public function getAllLogs()
    {
        return $this->select('el_log_activity.*, el_user.username')
                    ->join('el_user', 'el_user.id_user = el_log_activity.id_user', 'left')
                    ->orderBy('happens_at', 'DESC')
                    ->findAll();
    }

    public function getLogsByUser($user_id)
    {
        return $this->select('el_log_activity.*, el_user.username')
                    ->join('el_user', 'el_user.id_user = el_log_activity.id_user', 'left')
                    ->where('el_log_activity.id_user', $user_id)
                    ->orderBy('happens_at', 'DESC')
                    ->findAll();
    }

    public function saveLog($id_user, $what_happens, $ip_address)
    {
        $data = [
            'id_user' => $id_user,
            'what_happens' => $what_happens,
            'happens_at' => date('Y-m-d H:i:s'),
            'ip_address' => $ip_address
        ];

        return $this->insert($data);
    }
}
