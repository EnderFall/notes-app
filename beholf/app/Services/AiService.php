<?php

namespace App\Services;

use CodeIgniter\Session\Session;

class AiService
{
    protected $session;
    protected $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    protected $apiKey;
    protected $model = 'microsoft/wizardlm-2-8x22b'; // Free model, change if needed
    protected $maxHistory = 20; // Limit history to last 20 messages

    public function __construct()
    {
        $this->session = session();
        $this->apiKey = getenv('OPENROUTER_API_KEY') ?: null;
    }

    public function getResponse(string $userMessage): string
    {
        // Get or initialize conversation history
        $history = $this->session->get('chat_history') ?? [];

        // Append user message to history
        $history[] = ['role' => 'user', 'content' => $userMessage];

        // Limit history to prevent token overflow
        if (count($history) > $this->maxHistory) {
            $history = array_slice($history, -$this->maxHistory);
        }

        // Prepare payload for OpenRouter (OpenAI-compatible)
        $payload = [
            'model' => $this->model,
            'messages' => $history,
            'max_tokens' => 300,
            'temperature' => 0.7
        ];

        // Make API call
        $response = $this->callOpenRouter($payload);

        if ($response === false) {
            log_message('error', 'OpenRouter API call failed for message: ' . $userMessage);
            return 'Maaf, saya tidak bisa menjawab sekarang. Coba lagi nanti.';
        }

        // Parse response
        $data = json_decode($response, true);
        if (isset($data['choices'][0]['message']['content'])) {
            $assistantReply = trim($data['choices'][0]['message']['content']);

            // Append assistant response to history
            $history[] = ['role' => 'assistant', 'content' => $assistantReply];

            // Save updated history to session
            $this->session->set('chat_history', $history);

            return $assistantReply;
        } else {
            log_message('error', 'OpenRouter API error: ' . $response);
            return 'Maaf, terjadi kesalahan dalam pemrosesan respons AI.';
        }
    }

    protected function callOpenRouter(array $payload): string|false
    {
        if (!$this->apiKey) {
            log_message('error', 'OPENROUTER_API_KEY is missing from environment.');
            return false;
        }

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: ' . base_url(), // Optional, for OpenRouter analytics
                'X-Title: Ellie Notes Assistant' // Optional
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30 // 30 seconds timeout
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            log_message('error', 'OpenRouter cURL error: ' . $error);
            return false;
        }

        if ($statusCode !== 200) {
            log_message('error', 'OpenRouter HTTP error: ' . $statusCode . ' - ' . $response);
            return false;
        }

        return $response;
    }

    public function clearHistory(): void
    {
        $this->session->remove('chat_history');
    }
}
