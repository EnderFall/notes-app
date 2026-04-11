<?php

namespace App\Services;

class SummarizationService
{
    protected $apiKey;
    protected $summarizationModel = 'facebook/bart-large-cnn';
    protected $keywordModel = 'yanekyuk/bert-uncased-keyword-extractor';
    protected $definitionModel = 'microsoft/phi-2';
    // Llama 3.1 8B Instruct – proper conversational LLM via HF router API
    protected $chatModel = 'meta-llama/Llama-3.1-8B-Instruct';

    public function __construct()
    {
        $this->apiKey = getenv('ai.huggingface.apiKey') ?: getenv('AI_HUGGINGFACE_API_KEY') ?: null;
    }

    /**
     * Summarize text using Hugging Face API
     * 
     * @param string $text Text to summarize
     * @return array Returns ['summary' => string, 'keywords' => array, 'scientific_terms' => array]
     */
    public function summarizeNote(string $text): array
    {
        $summary = $this->getSummary($text);
        $keywords = $this->extractKeywords($summary);
        $scientificTerms = $this->findScientificTerms($text, $keywords);

        return [
            'summary' => $summary,
            'keywords' => $keywords,
            'scientific_terms' => $scientificTerms
        ];
    }

    /**
     * Get summary from Hugging Face
     */
    protected function getSummary(string $text): string
    {
        $url = "https://router.huggingface.co/models/{$this->summarizationModel}";
        
        $payload = [
            'inputs' => $text,
            'parameters' => [
                'max_length' => 150,
                'min_length' => 30,
                'do_sample' => false
            ]
        ];

        $response = $this->callHuggingFace($url, $payload);
        
        if ($response && isset($response[0]['summary_text'])) {
            return $response[0]['summary_text'];
        }

        // Fallback: use simple summarization if API fails
        return $this->simpleSummarization($text);
    }

    /**
     * Extract keywords from text
     */
    protected function extractKeywords(string $text): array
    {
        // Use simple keyword extraction as fallback
        $words = str_word_count(strtolower($text), 1);
        $wordFreq = array_count_values($words);
        
        // Remove common words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 
                      'of', 'with', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
                      'yang', 'dan', 'di', 'ke', 'dari', 'untuk', 'pada', 'adalah'];
        
        foreach ($stopWords as $stopWord) {
            unset($wordFreq[$stopWord]);
        }
        
        // Remove single character words
        $wordFreq = array_filter($wordFreq, function($word) {
            return strlen($word) > 2;
        }, ARRAY_FILTER_USE_KEY);
        
        // Sort by frequency and get top keywords
        arsort($wordFreq);
        $keywords = array_slice(array_keys($wordFreq), 0, 10);
        
        return $keywords;
    }

    /**
     * Find scientific terms and get their definitions
     */
    protected function findScientificTerms(string $text, array $keywords): array
    {
        $scientificTerms = [];
        
        // Patterns for scientific terms
        $patterns = [
            '/\b[A-Z][a-z]+ [a-z]+\b/', // Binomial nomenclature (e.g., "Homo sapiens")
            '/\b[A-Z]{2,}\b/',           // Acronyms (e.g., "DNA", "RNA")
        ];
        
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $text, $matches);
            foreach ($matches[0] as $term) {
                if (!in_array($term, $scientificTerms)) {
                    $definition = $this->getTermDefinition($term);
                    if ($definition) {
                        $scientificTerms[] = [
                            'term' => $term,
                            'definition' => $definition
                        ];
                    }
                }
            }
        }
        
        return $scientificTerms;
    }

    /**
     * Get definition for a scientific term
     */
    protected function getTermDefinition(string $term): ?string
    {
        // For now, use a simple Wikipedia-style query or predefined terms
        // In production, you could use Hugging Face question-answering model
        
        $commonTerms = [
            'DNA' => 'Deoxyribonucleic Acid - The molecule that carries genetic instructions',
            'RNA' => 'Ribonucleic Acid - A molecule essential in various biological roles',
            'ATP' => 'Adenosine Triphosphate - The energy currency of cells',
            'CO2' => 'Carbon Dioxide - A colorless gas produced by burning carbon and organic compounds',
            'H2O' => 'Water - A transparent, tasteless, odorless chemical substance',
            'pH' => 'Potential of Hydrogen - A scale used to specify acidity or basicity',
        ];
        
        if (isset($commonTerms[$term])) {
            return $commonTerms[$term];
        }
        
        // Try to get definition using AI
        return $this->getAIDefinition($term);
    }

    /**
     * Get AI-generated definition
     */
    protected function getAIDefinition(string $term): ?string
    {
        $url = "https://router.huggingface.co/models/facebook/bart-large-cnn";
        
        $prompt = "Define the scientific term '{$term}' in one sentence:";
        
        $payload = [
            'inputs' => $prompt,
            'parameters' => [
                'max_length' => 60,
                'min_length' => 20
            ]
        ];

        $response = $this->callHuggingFace($url, $payload);
        
        if ($response && isset($response[0]['summary_text'])) {
            return $response[0]['summary_text'];
        }
        
        return null;
    }

    /**
     * Make API call to Hugging Face
     */
    protected function callHuggingFace(string $url, array $payload): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Hugging Face cURL error: ' . $error);
            return null;
        }

        if ($statusCode !== 200) {
            log_message('error', 'Hugging Face HTTP error: ' . $statusCode . ' - ' . $response);
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Simple fallback summarization
     */
    protected function simpleSummarization(string $text): string
    {
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        if (count($sentences) <= 3) {
            return $text;
        }
        
        // Return first 2-3 sentences as summary
        $summaryLength = min(3, ceil(count($sentences) / 3));
        $summary = array_slice($sentences, 0, $summaryLength);
        
        return implode('. ', $summary) . '.';
    }

    /**
     * Highlight keywords in HTML text
     */
    public function highlightKeywords(string $html, array $keywords): string
    {
        foreach ($keywords as $keyword) {
            $pattern = '/\b(' . preg_quote($keyword, '/') . ')\b/i';
            $html = preg_replace($pattern, '<mark class="keyword-highlight">$1</mark>', $html);
        }
        
        return $html;
    }

    // ════════════════════════════════════════════════════════════════════
    // SUMMARY TYPES
    // ════════════════════════════════════════════════════════════════════

    /**
     * Generate a short 1-3 sentence summary via OpenRouter AI.
     */
    public function generateShortSummary(string $text): string
    {
        $plain = strip_tags($text);
        $messages = [
            ['role' => 'system', 'content' => 'You are a concise summarizer. Output only the summary, no preamble.'],
            ['role' => 'user',   'content' => "Summarize the following note in 1–3 concise sentences:\n\n" . mb_substr($plain, 0, 4000)],
        ];
        return $this->callHuggingFaceChatCompletions($messages, 200)
            ?? $this->callOpenRouterMessages($messages, 200)
            ?? $this->simpleSummarization($plain);
    }

    /**
     * Generate a bullet-point summary.
     */
    public function generateBulletSummary(string $text): string
    {
        $plain = strip_tags($text);
        $messages = [
            ['role' => 'system', 'content' => 'You are a concise summarizer. Output only a bullet-point list, no preamble. Use • as the bullet character.'],
            ['role' => 'user',   'content' => "Summarize the following note as a bullet-point list of 5–10 key points:\n\n" . mb_substr($plain, 0, 4000)],
        ];
        $result = $this->callHuggingFaceChatCompletions($messages, 400)
            ?? $this->callOpenRouterMessages($messages, 400);
        if (!$result) {
            $sentences = array_filter(array_map('trim', preg_split('/[.\n]+/', $plain)));
            $top = array_slice(array_values($sentences), 0, 7);
            return implode("\n", array_map(fn($s) => '• ' . $s, $top));
        }
        return $result;
    }

    /**
     * Generate a detailed paragraph summary.
     */
    public function generateDetailedSummary(string $text): string
    {
        $plain = strip_tags($text);
        $messages = [
            ['role' => 'system', 'content' => 'You are a detailed summarizer. Output only well-written paragraphs, no preamble.'],
            ['role' => 'user',   'content' => "Write a detailed summary of the following note in 3–5 paragraphs:\n\n" . mb_substr($plain, 0, 4000)],
        ];
        return $this->callHuggingFaceChatCompletions($messages, 600)
            ?? $this->callOpenRouterMessages($messages, 600)
            ?? $this->simpleSummarization($plain);
    }

    // ════════════════════════════════════════════════════════════════════
    // FLASH CARDS
    // ════════════════════════════════════════════════════════════════════

    /**
     * Generate flash cards from note content.
     * Returns array of ['question' => ..., 'answer' => ...]
     */
    public function generateFlashCards(string $text, int $count = 8): array
    {
        $plain = strip_tags($text);
        $prompt = "Generate exactly {$count} flash cards from the following note content.\n"
            . "Return ONLY a valid JSON array, no explanation. Format:\n"
            . "[{\"question\":\"...\",\"answer\":\"...\",\"difficulty\":\"easy|medium|hard\"}]\n\n"
            . mb_substr($plain, 0, 4000);

        $messages = [
            ['role' => 'system', 'content' => 'You generate flash cards. Respond ONLY with a valid JSON array, no explanation, no markdown fences.'],
            ['role' => 'user',   'content' => $prompt],
        ];
        $raw = $this->callHuggingFaceChatCompletions($messages, 800)
            ?? $this->callOpenRouterMessages($messages, 800);

        if ($raw) {
            // Extract JSON even if wrapped in markdown code blocks
            if (preg_match('/\[.*\]/s', $raw, $m)) {
                $cards = json_decode($m[0], true);
                if (is_array($cards)) {
                    return array_slice($cards, 0, $count);
                }
            }
        }

        // Fallback: split content into Q&A pairs from sentences
        return $this->fallbackFlashCards($plain, $count);
    }

    private function fallbackFlashCards(string $plain, int $count): array
    {
        $sentences = array_values(array_filter(
            array_map('trim', preg_split('/[.!?]+/', $plain)),
            fn($s) => strlen($s) > 20
        ));

        $cards = [];
        foreach (array_slice($sentences, 0, $count) as $i => $s) {
            // Convert statement to question
            $words  = explode(' ', $s);
            $answer = $s;
            $q      = 'What is described by: "' . mb_substr($s, 0, 60) . '..."?';
            $cards[] = ['question' => $q, 'answer' => $answer, 'difficulty' => 'medium'];
        }
        return $cards;
    }

    // ════════════════════════════════════════════════════════════════════
    // STRUCTURE EXTRACTION
    // ════════════════════════════════════════════════════════════════════

    /**
     * Extract the structure of a note.
     * Returns ['main_idea', 'key_points', 'supporting_details', 'conclusion']
     */
    public function extractStructure(string $text): array
    {
        $plain = strip_tags($text);
        $prompt = "Analyze the following note and extract its structure. "
            . "Return ONLY a valid JSON object (no explanation) with keys:\n"
            . "\"main_idea\" (1 sentence), \"key_points\" (bullet list as string), "
            . "\"supporting_details\" (paragraph), \"conclusion\" (1-2 sentences).\n\n"
            . mb_substr($plain, 0, 4000);

        $messages = [
            ['role' => 'system', 'content' => 'You extract note structure. Respond ONLY with a valid JSON object, no explanation, no markdown fences.'],
            ['role' => 'user',   'content' => $prompt],
        ];
        $raw = $this->callHuggingFaceChatCompletions($messages, 600)
            ?? $this->callOpenRouterMessages($messages, 600);

        if ($raw) {
            if (preg_match('/\{.*\}/s', $raw, $m)) {
                $struct = json_decode($m[0], true);
                if (is_array($struct)) {
                    return [
                        'main_idea'          => $struct['main_idea'] ?? '',
                        'key_points'         => $struct['key_points'] ?? '',
                        'supporting_details' => $struct['supporting_details'] ?? '',
                        'conclusion'         => $struct['conclusion'] ?? '',
                    ];
                }
            }
        }

        // Fallback
        $sentences = array_values(array_filter(
            array_map('trim', preg_split('/[.!?\n]+/', $plain)),
            fn($s) => strlen($s) > 15
        ));

        return [
            'main_idea'          => $sentences[0] ?? 'Unable to extract.',
            'key_points'         => implode("\n", array_map(fn($s) => '• ' . $s, array_slice($sentences, 1, 5))),
            'supporting_details' => implode('. ', array_slice($sentences, 6, 5)) . '.',
            'conclusion'         => end($sentences) ?: '',
        ];
    }

    // ════════════════════════════════════════════════════════════════════
    // SCIENTIFIC TERM LOOKUP
    // ════════════════════════════════════════════════════════════════════

    /**
     * Look up a term and return simple + technical definitions.
     */
    public function lookupTerm(string $term): array
    {
        // Check built-in dictionary first (instant, no API needed)
        $builtin = $this->getBuiltinDefinition($term);
        if ($builtin) {
            return ['simple' => $builtin, 'technical' => ''];
        }

        // Use Llama 3.1 via HF chat completions
        $messages = [
            [
                'role'    => 'system',
                'content' => 'You are a knowledgeable assistant. When given a term, respond ONLY with a valid JSON object with exactly two keys: "simple" (plain English, 1 sentence) and "technical" (academic/scientific, 1-2 sentences). No extra text, no markdown, no code fences.',
            ],
            [
                'role'    => 'user',
                'content' => "Define the term: \"{$term}\"",
            ],
        ];

        $raw = $this->callHuggingFaceChatCompletions($messages, 200)
            ?? $this->callOpenRouterMessages($messages, 200);

        if ($raw) {
            // Extract JSON from response (model may wrap in markdown)
            if (preg_match('/\{[^{}]*"simple"[^{}]*\}/s', $raw, $m)) {
                $def = json_decode($m[0], true);
                if (is_array($def) && !empty($def['simple'])) {
                    return [
                        'simple'    => trim($def['simple']),
                        'technical' => trim($def['technical'] ?? ''),
                    ];
                }
            }

            // If model didn't return JSON, use the raw text as the simple definition
            $cleaned = preg_replace('/^```[a-z]*\n?/i', '', trim($raw));
            $cleaned = rtrim($cleaned, '`');
            if (strlen($cleaned) > 10) {
                return ['simple' => $cleaned, 'technical' => ''];
            }
        }

        return ['simple' => 'Definition not available. Try a different term or check your connection.', 'technical' => ''];
    }

    private function getBuiltinDefinition(string $term): ?string
    {
        $dict = [
            'DNA'       => 'Deoxyribonucleic Acid – the molecule carrying genetic instructions.',
            'RNA'       => 'Ribonucleic Acid – essential in gene expression and protein synthesis.',
            'ATP'       => 'Adenosine Triphosphate – the primary energy currency of cells.',
            'CO2'       => 'Carbon Dioxide – a greenhouse gas produced by combustion and respiration.',
            'H2O'       => 'Water – a polar molecule essential to all known life.',
            'pH'        => 'Potential of Hydrogen – a scale measuring acidity or alkalinity (0–14).',
            'osmosis'   => 'The movement of water through a semi-permeable membrane from low to high solute concentration.',
            'mitosis'   => 'Cell division producing two genetically identical daughter cells.',
            'entropy'   => 'A measure of disorder or randomness in a thermodynamic system.',
            'catalyst'  => 'A substance that speeds up a chemical reaction without being consumed.',
            'photosynthesis' => 'The process by which plants convert sunlight, CO2, and water into glucose.',
            'diffusion' => 'The movement of particles from high to low concentration.',
            'algorithm' => 'A step-by-step procedure for solving a problem or accomplishing a task.',
            'hypothesis' => 'A proposed explanation for an observation, subject to testing.',
        ];

        $lower = strtolower(trim($term));
        foreach ($dict as $k => $v) {
            if (strtolower($k) === $lower) return $v;
        }
        return null;
    }

    // ════════════════════════════════════════════════════════════════════
    // SHARED: OPENROUTER AI CALL
    // ════════════════════════════════════════════════════════════════════

    // ════════════════════════════════════════════════════════════════════
    // CHAT WITH NOTE
    // ════════════════════════════════════════════════════════════════════

    /**
     * Answer a question about a note using its content as context.
     * Uses Llama 3.1 8B Instruct via HF chat completions API for GPT-like quality.
     * $history = [['role'=>'user'|'assistant', 'content'=>'...'], ...]
     */
    public function chatWithNote(string $noteContent, string $question, array $history = []): string
    {
        $plain    = mb_substr(strip_tags($noteContent), 0, 4000);
        $question = trim($question);

        if ($question === '') {
            return 'Please ask a question first.';
        }

        // System prompt: inject the entire note as context so the model has full awareness
        $systemPrompt = "You are a smart, helpful study assistant. The user is reviewing their personal note and wants to ask questions about it.\n\n"
            . "Here is the full note content:\n---\n{$plain}\n---\n\n"
            . "Guidelines:\n"
            . "- Answer questions based on the note content above.\n"
            . "- If the note doesn't fully cover the question, briefly say so and supplement with your general knowledge.\n"
            . "- Be conversational, clear, and accurate.\n"
            . "- Use **bold**, bullet lists, or numbered steps when they improve clarity.\n"
            . "- Keep answers concise unless detail is clearly needed.\n"
            . "- You can reference earlier parts of the conversation to give contextual follow-ups.\n"
            . "- Respond in the same language the user writes in (Indonesian or English).";

        // Build messages: system → conversation history (last 12 turns) → current question
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach (array_slice($history, -12) as $h) {
            if (!isset($h['role'], $h['content'])) {
                continue;
            }
            if (!in_array($h['role'], ['user', 'assistant'], true)) {
                continue;
            }
            $messages[] = ['role' => $h['role'], 'content' => (string) $h['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        // Primary: HF Llama 3.1 chat completions
        $answer = $this->callHuggingFaceChatCompletions($messages, 600);
        if ($answer) {
            return $this->normalizeChatAnswer($answer);
        }

        // Secondary: OpenRouter (if key is configured)
        $answer = $this->callOpenRouterMessages($messages, 600);
        if ($answer) {
            return $this->normalizeChatAnswer($answer);
        }

        // Local fallback (offline / quota exceeded)
        return $this->normalizeChatAnswer($this->fallbackChatAnswer($plain, $question));
    }

    protected function normalizeChatAnswer(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/^\s+/u', '', $text);
        $text = preg_replace('/\n{3,}/u', "\n\n", $text);

        // Remove model echo prefixes if present
        $text = preg_replace('/^(answer|assistant)\s*[:\-]\s*/iu', '', $text);

        return trim($text);
    }

    protected function getRelevantSentences(string $plain, string $question, int $limit = 3): array
    {
        $sentences = array_values(array_filter(
            array_map('trim', preg_split('/(?<=[.!?])\s+|\n+/', trim($plain))),
            fn ($s) => $s !== ''
        ));

        if (empty($sentences)) {
            return ['sentences' => [], 'scores' => []];
        }

        $qWords = str_word_count(strtolower($question), 1);
        $stopWords = [
            'the','a','an','and','or','but','in','on','at','to','for','of','with','is','are','was','were','be','been','being',
            'what','which','who','why','how','when','where','does','do','did','can','could','should','would',
            'yang','dan','di','ke','dari','untuk','pada','adalah','apa','siapa','kapan','dimana','bagaimana','mengapa','kenapa',
            'itu','ini','kah','nya','saya','aku','kami','kita','mereka','anda','tolong'
        ];
        $qWords = array_values(array_filter($qWords, function ($w) use ($stopWords) {
            return strlen($w) > 2 && !in_array($w, $stopWords, true);
        }));

        if (empty($qWords)) {
            return ['sentences' => array_slice($sentences, 0, $limit), 'scores' => []];
        }

        $scored = [];
        foreach ($sentences as $idx => $sentence) {
            $sLower = strtolower($sentence);
            $score = 0.0;
            foreach ($qWords as $w) {
                if (str_contains($sLower, $w)) {
                    $score += 2.0;
                } elseif (preg_match('/\b' . preg_quote($w, '/') . '\w*\b/u', $sLower)) {
                    $score += 1.0;
                }
            }
            $score += max(0.0, 0.2 - ($idx * 0.01));
            $scored[] = ['text' => $sentence, 'score' => $score];
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
        $top = array_slice($scored, 0, $limit);

        return [
            'sentences' => array_values(array_map(fn($x) => $x['text'], $top)),
            'scores'    => array_values(array_map(fn($x) => $x['score'], $top)),
        ];
    }

    protected function isGenericAnswer(string $answer, string $question): bool
    {
        $a = strtolower(trim($answer));
        if ($a === '') return true;

        $genericPatterns = [
            'ask a more specific question',
            'main keywords:',
            'i could not',
            'please try again',
            'not enough content',
        ];

        foreach ($genericPatterns as $p) {
            if (str_contains($a, $p)) {
                return true;
            }
        }

        $qWords = array_values(array_filter(
            str_word_count(strtolower($question), 1),
            fn($w) => strlen($w) > 3
        ));

        if (empty($qWords)) return false;

        $matchCount = 0;
        foreach ($qWords as $w) {
            if (str_contains($a, $w)) $matchCount++;
        }

        return $matchCount === 0;
    }

    protected function fallbackChatAnswer(string $plain, string $question): string
    {
        $question = trim($question);
        $questionLower = strtolower($question);
        $plain = trim($plain);

        if ($plain === '') {
            return 'This note does not have enough content yet. Add more note content and try again.';
        }

        $relevance = $this->getRelevantSentences($plain, $question, 3);
        $sentences = !empty($relevance['sentences']) ? $relevance['sentences'] : array_values(array_filter(
            array_map('trim', preg_split('/(?<=[.!?])\s+|\n+/', $plain)),
            fn ($s) => $s !== ''
        ));

        if (empty($sentences)) {
            return 'I could not find readable sentences in this note yet. Please add clearer note content and try again.';
        }

        $mainIdea = $this->simpleSummarization($plain);
        $intent   = $this->detectQuestionIntent($questionLower);

        // Keep explicit intent handlers for common study prompts
        if (str_contains($questionLower, 'main idea') || str_contains($questionLower, 'ide utama')) {
            return $mainIdea;
        }

        if (str_contains($questionLower, 'explain simply') || str_contains($questionLower, 'simple') || str_contains($questionLower, 'jelaskan') || str_contains($questionLower, 'sederhana')) {
            $top = array_slice($sentences, 0, 2);
            return 'In simple terms: ' . implode(' ', $top);
        }

        if (str_contains($questionLower, 'example') || str_contains($questionLower, 'contoh')) {
            // Look for sentences in the note that describe examples, instances, or applications
            $exampleKeywords = ['contoh', 'misalnya', 'seperti', 'example', 'instance', 'such as', 'for example',
                                'aplikasi', 'penerapan', 'kasus', 'case', 'scenario', 'illustrated', 'misal'];

            // Score sentences by example-related words AND general topic relevance
            $allSentences = array_values(array_filter(
                array_map('trim', preg_split('/(?<=[.!?])\s+|\n+/', $plain)),
                fn($s) => strlen($s) > 15
            ));

            $exampleSentences = [];
            foreach ($allSentences as $s) {
                $sl = strtolower($s);
                foreach ($exampleKeywords as $ek) {
                    if (str_contains($sl, $ek)) {
                        $exampleSentences[] = $s;
                        break;
                    }
                }
            }

            if (!empty($exampleSentences)) {
                $picked = array_slice($exampleSentences, 0, 3);
                if (count($picked) === 1) {
                    return 'Contoh dari catatan ini: ' . $picked[0];
                }
                $lines = array_map(fn($s) => '- ' . rtrim($s, ". \t\n\r\0\x0B") . '.', $picked);
                return "Beberapa contoh dari catatan ini:\n" . implode("\n", $lines);
            }

            // No explicit example sentences — use the most relevant sentences as the best answer
            $rel = $this->getRelevantSentences($plain, $question, 3)['sentences'] ?? [];
            if (!empty($rel)) {
                $lines = array_map(fn($s) => '- ' . rtrim($s, ". \t\n\r\0\x0B") . '.', $rel);
                return "Catatan ini tidak menyebutkan contoh eksplisit, tapi ini bagian yang paling relevan:\n" . implode("\n", $lines);
            }

            return 'Catatan ini tidak mengandung contoh eksplisit. Coba tambahkan contoh ke catatan, atau tanyakan hal yang lebih spesifik.';
        }

        if (preg_match('/^(apa itu|what is|what\'s)\s+(.+)$/iu', $question, $m)) {
            $term = trim($m[2], " ?!.,\t\n\r\0\x0B");
            $definitionSentence = $sentences[0] ?? $mainIdea;
            return $term . ' adalah ' . rtrim($definitionSentence, ". \t\n\r\0\x0B") . '.';
        }

        if (str_contains($questionLower, 'important') || str_contains($questionLower, 'remember') || str_contains($questionLower, 'key takeaway') || str_contains($questionLower, 'poin penting')) {
            $top = array_slice($sentences, 0, 4);
            $bullets = array_map(fn ($s) => '- ' . rtrim($s, ". \t\n\r\0\x0B") . '.', $top);
            return "Key takeaways:\n" . implode("\n", $bullets);
        }

        // General question answering by sentence relevance
        $topMatches = array_map(
            fn($s) => ['text' => $s],
            $this->getRelevantSentences($plain, $question, 3)['sentences'] ?? []
        );

        if (!empty($topMatches)) {
            $best = rtrim($topMatches[0]['text'], ". \t\n\r\0\x0B") . '.';
            $support = array_slice($topMatches, 1, 2);
            $supportText = implode(' ', array_map(
                fn($m) => rtrim($m['text'], ". \t\n\r\0\x0B") . '.',
                $support
            ));

            if ($intent === 'why') {
                return 'Alasannya: ' . $best . ($supportText ? ' ' . $supportText : '');
            }

            if ($intent === 'how') {
                return 'Cara/prosesnya: ' . $best . ($supportText ? ' ' . $supportText : '');
            }

            if ($intent === 'compare') {
                return 'Perbedaannya dapat dilihat dari konteks ini: ' . $best . ($supportText ? ' ' . $supportText : '');
            }

            if ($intent === 'list') {
                $lines = array_map(
                    fn($m) => '- ' . rtrim($m['text'], ". \t\n\r\0\x0B") . '.',
                    $topMatches
                );
                return "Poin yang relevan:\n" . implode("\n", $lines);
            }

            if (empty($support)) {
                return $best;
            }

            return $best . ' ' . $supportText;
        }

        // Last fallback: summarize + keywords, but no fixed canned sentence loop
        $keywords = $this->extractKeywords($plain);
        $keywordText = $keywords ? ('\nMain keywords: ' . implode(', ', array_slice($keywords, 0, 6)) . '.') : '';
        return "I could not find an exact sentence match for that question in the note. Here is the closest overview:\n"
            . $mainIdea
            . $keywordText;
    }

    protected function detectQuestionIntent(string $questionLower): string
    {
        if (preg_match('/\b(why|mengapa|kenapa)\b/u', $questionLower)) {
            return 'why';
        }

        if (preg_match('/\b(how|bagaimana|gimana|caranya)\b/u', $questionLower)) {
            return 'how';
        }

        if (preg_match('/\b(bedanya|perbedaan|difference|compare|comparison|vs|versus)\b/u', $questionLower)) {
            return 'compare';
        }

        if (preg_match('/\b(sebutkan|list|daftar|poin|points?)\b/u', $questionLower)) {
            return 'list';
        }

        return 'general';
    }

    // ════════════════════════════════════════════════════════════════════
    // EXPLAIN SELECTED TEXT
    // ════════════════════════════════════════════════════════════════════

    /**
     * Explain a selected piece of text, optionally in the context of its note.
     */
    public function explainText(string $selectedText, string $noteContext = ''): string
    {
        $ctx = $noteContext ? "\n\nNote context:\n" . mb_substr(strip_tags($noteContext), 0, 1500) : '';

        $messages = [
            [
                'role'    => 'system',
                'content' => 'You are a helpful study assistant. Explain text in clear, simple language.',
            ],
            [
                'role'    => 'user',
                'content' => "Explain the following text in simple, clear language (2–4 sentences). Be concise and direct.\n\nText: \"{$selectedText}\"{$ctx}",
            ],
        ];

        return $this->callHuggingFaceChatCompletions($messages, 300)
            ?? $this->callOpenRouterMessages($messages, 300)
            ?? 'Explanation not available. Please try again.';
    }

    protected function callHuggingFaceTextGeneration(string $prompt, int $maxTokens = 300): ?string
    {
        if (!$this->apiKey) {
            log_message('error', 'Hugging Face API key is missing from environment.');
            return null;
        }

        $url = 'https://router.huggingface.co/models/' . $this->chatModel;
        $payload = [
            'inputs' => $prompt,
            'parameters' => [
                'max_new_tokens' => min($maxTokens, 512),
                'temperature' => 0.4,
                'return_full_text' => false,
            ],
            'options' => [
                'wait_for_model' => true,
                'use_cache' => false,
            ],
        ];

        $response = $this->callHuggingFace($url, $payload);
        if (!$response) {
            return null;
        }

        if (isset($response[0]['generated_text']) && trim($response[0]['generated_text']) !== '') {
            return trim($response[0]['generated_text']);
        }

        if (isset($response['generated_text']) && trim($response['generated_text']) !== '') {
            return trim($response['generated_text']);
        }

        if (isset($response[0]['summary_text']) && trim($response[0]['summary_text']) !== '') {
            return trim($response[0]['summary_text']);
        }

        if (isset($response['error'])) {
            log_message('error', 'Hugging Face generation error: ' . (string) $response['error']);
        }

        return null;
    }

    // ════════════════════════════════════════════════════════════════════
    // HUGGING FACE CHAT COMPLETIONS (OpenAI-compatible, proper LLM)
    // ════════════════════════════════════════════════════════════════════

    /**
     * Call Hugging Face's OpenAI-compatible /v1/chat/completions endpoint.
     * Works with instruction-tuned models like Meta-Llama-3.1-8B-Instruct.
     */
    protected function callHuggingFaceChatCompletions(array $messages, int $maxTokens = 600): ?string
    {
        if (!$this->apiKey) {
            log_message('error', 'Hugging Face API key is missing from environment.');
            return null;
        }

        $url = 'https://router.huggingface.co/v1/chat/completions';

        $payload = [
            'model'       => $this->chatModel,
            'messages'    => $messages,
            'max_tokens'  => min($maxTokens, 800),
            'temperature' => 0.7,
            'top_p'       => 0.9,
            'stream'      => false,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            log_message('error', 'HF Chat cURL error: ' . $err);
            return null;
        }

        if ($status < 200 || $status >= 300) {
            log_message('error', 'HF Chat HTTP ' . $status . ': ' . $response);
            return null;
        }

        $data = json_decode($response, true);
        $text = $data['choices'][0]['message']['content'] ?? null;
        return $text ? trim($text) : null;
    }

    // ════════════════════════════════════════════════════════════════════
    // SHARED: OPENROUTER – MESSAGES ARRAY (supports system prompt)
    // ════════════════════════════════════════════════════════════════════

    protected function callOpenRouterMessages(array $messages, int $maxTokens = 300): ?string
    {
        $apiKey = getenv('OPENROUTER_API_KEY') ?: null;
        if (!$apiKey) {
            log_message('error', 'OPENROUTER_API_KEY is missing from environment.');
            return null;
        }
        $url    = 'https://openrouter.ai/api/v1/chat/completions';

        $payload = [
            'model'       => 'microsoft/wizardlm-2-8x22b',
            'messages'    => $messages,
            'max_tokens'  => $maxTokens,
            'temperature' => 0.6,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: ' . base_url(),
            ],
            CURLOPT_TIMEOUT => 45,
        ]);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            log_message('error', 'OpenRouter cURL error: ' . $err);
            return null;
        }

        if ($status < 200 || $status >= 300) {
            log_message('error', 'OpenRouter HTTP error: ' . $status . ' - ' . (string) $response);
            return null;
        }

        $data = json_decode($response, true);
        return $data['choices'][0]['message']['content'] ?? null;
    }

    protected function callOpenRouter(string $prompt, int $maxTokens = 300): ?string
    {
        $apiKey = getenv('OPENROUTER_API_KEY') ?: null;
        if (!$apiKey) {
            log_message('error', 'OPENROUTER_API_KEY is missing from environment.');
            return null;
        }
        $url    = 'https://openrouter.ai/api/v1/chat/completions';

        $payload = [
            'model'    => 'microsoft/wizardlm-2-8x22b',
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'max_tokens'  => $maxTokens,
            'temperature' => 0.4,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: ' . base_url(),
            ],
            CURLOPT_TIMEOUT => 45,
        ]);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            log_message('error', 'OpenRouter cURL error: ' . $err);
            return null;
        }

        if ($status < 200 || $status >= 300) {
            log_message('error', 'OpenRouter HTTP error: ' . $status . ' - ' . (string) $response);
            return null;
        }

        $data = json_decode($response, true);
        return $data['choices'][0]['message']['content'] ?? null;
    }
}
