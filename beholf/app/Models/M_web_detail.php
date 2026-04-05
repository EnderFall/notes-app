<?php

namespace App\Models;

use CodeIgniter\Model;

class M_web_detail extends Model
{
    protected $table = 'el_web_detail';
    protected $primaryKey = 'id_wdetail';
    protected $allowedFields = [
        'id_wdetail',
        'title',
        'logo'
    ];

    public function getWebDetail()
    {
        return $this->first();
    }

    public function updateWebDetail($data)
    {
        $webDetail = $this->first();
        if ($webDetail) {
            return $this->update($webDetail['id_wdetail'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
