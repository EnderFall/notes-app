<?php

namespace App\Controllers;

class Translate extends BaseController
{
    public function index()
    {
        if (session()->get('id_user') > 0) {
            $data = [
                'title' => 'Translate',
            ];

            echo view('header', $data);
            echo view('menu');
            echo view('translate/v_translate', $data);
            echo view('footer');
        } else {
            return redirect()->to('login/logout');
        }
    }
}
