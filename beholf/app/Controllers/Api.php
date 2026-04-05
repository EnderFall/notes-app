<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Services\AiService;

class Api extends ResourceController
{
    protected $format = 'json';
    protected $aiService;

    public function __construct()
    {
        $this->aiService = new AiService();
    }

    public function index()
    {
        $request = $this->request->getJSON(true);
        $userMessage = $request['message'] ?? '';

        if (!$userMessage) {
            return $this->respond(['reply' => 'Pesan kosong, silakan tulis sesuatu.']);
        }

        // Get AI response with conversation memory
        $reply = $this->aiService->getResponse($userMessage);

        return $this->respond(['reply' => $reply]);
    }
}
