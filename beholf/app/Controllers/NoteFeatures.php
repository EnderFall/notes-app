<?php

namespace App\Controllers;

use App\Models\M_rapat;
use App\Models\M_note_summary;
use App\Models\M_flash_card;
use App\Models\M_note_highlight;
use App\Models\M_note_structure;
use App\Models\M_term_definition;
use App\Services\SummarizationService;

/**
 * NoteFeatures – AJAX controller for all AI/enrichment features:
 *   • Summarization (short / bullets / detailed)
 *   • Flash cards
 *   • Highlights
 *   • Smart structure extraction
 *   • Scientific term lookup
 */
class NoteFeatures extends BaseController
{
    private function requireAuth(): bool
    {
        return session()->get('id_user') > 0;
    }

    private function json(array $payload, int $status = 200)
    {
        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setBody(json_encode($payload));
    }

    /** Strip HTML and return plain text for AI processing */
    private function plainText(string $html): string
    {
        return trim(strip_tags(html_entity_decode($html)));
    }

    private function noteContent(int $noteId): ?string
    {
        $model = new M_rapat();
        $note  = $model->asObject()->find($noteId);
        return $note ? ($note->content ?? '') : null;
    }

    // ════════════════════════════════════════════════════════════════════
    // SUMMARIZATION
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/summarize/{noteId}
     * Body JSON: { "type": "short|bullets|detailed", "source_text": "(optional)" }
     */
    public function summarize(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $type       = $this->request->getJSON(true)['type']        ?? 'short';
        $sourceText = $this->request->getJSON(true)['source_text'] ?? null;

        if (!$sourceText) {
            $content = $this->noteContent($noteId);
            if ($content === null) return $this->json(['error' => 'Note not found'], 404);
            $sourceText = $content;
        }

        $plain   = $this->plainText($sourceText);
        $service = new SummarizationService();

        $result = match ($type) {
            'bullets'  => $service->generateBulletSummary($plain),
            'detailed' => $service->generateDetailedSummary($plain),
            default    => $service->generateShortSummary($plain),
        };

        // Auto-save
        $model = new M_note_summary();
        $id = $model->insert([
            'note_id'      => $noteId,
            'summary_type' => $type,
            'source_text'  => mb_substr($plain, 0, 5000),
            'summary_text' => $result,
            'created_by'   => session()->get('id_user'),
            'created_at'   => date('Y-m-d H:i:s'),
        ], true);

        return $this->json(['summary' => $result, 'id' => $id, 'type' => $type]);
    }

    /**
     * GET note-features/summaries/{noteId}
     */
    public function getSummaries(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $summaries = (new M_note_summary())->getByNote($noteId);
        return $this->json(['summaries' => $summaries]);
    }

    /**
     * GET note-features/summary/delete/{id}
     */
    public function deleteSummary(int $id)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        (new M_note_summary())->delete($id);
        return $this->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // FLASH CARDS
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/flashcards/generate/{noteId}
     * Body JSON: { "count": 8 }
     */
    public function generateFlashCards(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $content = $this->noteContent($noteId);
        if ($content === null) return $this->json(['error' => 'Note not found'], 404);

        $count   = (int) ($this->request->getJSON(true)['count'] ?? 8);
        $count   = max(3, min(15, $count));
        $service = new SummarizationService();
        $cards   = $service->generateFlashCards($this->plainText($content), $count);

        $model  = new M_flash_card();
        $saved  = [];
        foreach ($cards as $i => $card) {
            $row = [
                'note_id'     => $noteId,
                'question'    => $card['question'],
                'answer'      => $card['answer'],
                'difficulty'  => $card['difficulty'] ?? 'medium',
                'order_index' => $i,
                'created_at'  => date('Y-m-d H:i:s'),
            ];
            $newId    = $model->insert($row, true);
            $row['id'] = $newId;
            $saved[]  = $row;
        }

        return $this->json(['cards' => $saved]);
    }

    /**
     * GET note-features/flashcards/{noteId}
     */
    public function getFlashCards(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $cards = (new M_flash_card())->getByNote($noteId);
        return $this->json(['cards' => $cards]);
    }

    /**
     * POST note-features/flashcards/save
     * Body JSON: { "note_id", "question", "answer", "difficulty" }
     */
    public function saveFlashCard()
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $body = $this->request->getJSON(true);
        $model = new M_flash_card();

        $row = [
            'note_id'     => (int) ($body['note_id'] ?? 0),
            'question'    => trim($body['question'] ?? ''),
            'answer'      => trim($body['answer'] ?? ''),
            'difficulty'  => $body['difficulty'] ?? 'medium',
            'order_index' => (int) ($body['order_index'] ?? 0),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        if (!$row['note_id'] || !$row['question'] || !$row['answer']) {
            return $this->json(['error' => 'Missing required fields'], 422);
        }

        $id = $model->insert($row, true);
        return $this->json(['success' => true, 'id' => $id]);
    }

    /**
     * POST note-features/flashcards/update/{id}
     */
    public function updateFlashCard(int $id)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $body  = $this->request->getJSON(true);
        $model = new M_flash_card();

        $model->update($id, [
            'question'   => trim($body['question'] ?? ''),
            'answer'     => trim($body['answer'] ?? ''),
            'difficulty' => $body['difficulty'] ?? 'medium',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->json(['success' => true]);
    }

    /**
     * GET note-features/flashcards/delete/{id}
     */
    public function deleteFlashCard(int $id)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        (new M_flash_card())->delete($id);
        return $this->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // HIGHLIGHTS
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/highlights/save
     * Body JSON: { "note_id", "selected_text", "color", "context_before", "context_after" }
     */
    public function saveHighlight()
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $body = $this->request->getJSON(true);

        $row = [
            'note_id'        => (int) ($body['note_id'] ?? 0),
            'selected_text'  => mb_substr(trim($body['selected_text'] ?? ''), 0, 2000),
            'color'          => $body['color'] ?? '#fef08a',
            'context_before' => mb_substr($body['context_before'] ?? '', 0, 100),
            'context_after'  => mb_substr($body['context_after'] ?? '', 0, 100),
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        if (!$row['note_id'] || !$row['selected_text']) {
            return $this->json(['error' => 'Missing required fields'], 422);
        }

        $id = (new M_note_highlight())->insert($row, true);
        return $this->json(['success' => true, 'id' => $id]);
    }

    /**
     * GET note-features/highlights/{noteId}
     */
    public function getHighlights(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $highlights = (new M_note_highlight())->getByNote($noteId);
        return $this->json(['highlights' => $highlights]);
    }

    /**
     * GET note-features/highlights/delete/{id}
     */
    public function deleteHighlight(int $id)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        (new M_note_highlight())->delete($id);
        return $this->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // SMART STRUCTURE EXTRACTION
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/structure/extract/{noteId}
     */
    public function extractStructure(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $content = $this->noteContent($noteId);
        if ($content === null) return $this->json(['error' => 'Note not found'], 404);

        $service   = new SummarizationService();
        $structure = $service->extractStructure($this->plainText($content));

        (new M_note_structure())->upsert($noteId, [
            'main_idea'          => $structure['main_idea'],
            'key_points'         => $structure['key_points'],
            'supporting_details' => $structure['supporting_details'],
            'conclusion'         => $structure['conclusion'],
            'raw_result'         => json_encode($structure),
        ]);

        return $this->json(['structure' => $structure]);
    }

    /**
     * GET note-features/structure/{noteId}
     */
    public function getStructure(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $structure = (new M_note_structure())->getByNote($noteId);
        return $this->json(['structure' => $structure]);
    }

    // ════════════════════════════════════════════════════════════════════
    // SCIENTIFIC TERM LOOKUP
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/term/lookup
     * Body JSON: { "term": "...", "note_id": 0 }
     */
    public function lookupTerm()
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $body   = $this->request->getJSON(true);
        $term   = trim($body['term'] ?? '');
        $noteId = (int) ($body['note_id'] ?? 0);

        if (!$term) return $this->json(['error' => 'Term is required'], 422);

        // Check cache first
        $termModel = new M_term_definition();
        $cached    = $termModel->findCached($term);

        if ($cached
            && !empty($cached['simple_definition'])
            && stripos($cached['simple_definition'], 'not available') === false
        ) {
            return $this->json([
                'term'      => $term,
                'simple'    => $cached['simple_definition'],
                'technical' => $cached['technical_definition'],
                'cached'    => true,
            ]);
        }

        // Call AI
        $service = new SummarizationService();
        $def     = $service->lookupTerm($term);

        // Only cache if we got a real definition (never cache failures)
        $isFailure = empty($def['simple'])
            || stripos($def['simple'], 'not available') !== false
            || stripos($def['simple'], 'try again') !== false;

        if (!$isFailure) {
            $termModel->saveDefinition([
                'note_id'              => $noteId ?: null,
                'term'                 => $term,
                'simple_definition'    => $def['simple'],
                'technical_definition' => $def['technical'],
                'source_type'          => 'ai',
            ]);
        }

        return $this->json([
            'term'      => $term,
            'simple'    => $def['simple'],
            'technical' => $def['technical'],
            'cached'    => false,
        ]);
    }

    /**
     * GET note-features/terms/{noteId}
     */
    public function getTerms(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $terms = (new M_term_definition())->getByNote($noteId);
        return $this->json(['terms' => $terms]);
    }

    // ════════════════════════════════════════════════════════════════════
    // CHAT WITH NOTE
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/chat/{noteId}
     * Body JSON: { "question": "...", "history": [{role, content}, ...] }
     */
    public function chat(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $content = $this->noteContent($noteId);
        if ($content === null) return $this->json(['error' => 'Note not found'], 404);

        $body     = $this->request->getJSON(true);
        $question = trim($body['question'] ?? '');
        $history  = is_array($body['history'] ?? null) ? $body['history'] : [];

        if (!$question) return $this->json(['error' => 'Question required'], 422);
        if (mb_strlen($question) < 2) return $this->json(['error' => 'Question too short'], 422);

        $service = new SummarizationService();
        $answer  = $service->chatWithNote($content, $question, $history);

        $db = \Config\Database::connect();
        $db->table('note_chats')->insertBatch([
            ['note_id' => $noteId, 'role' => 'user',      'message' => mb_substr($question, 0, 5000), 'created_at' => date('Y-m-d H:i:s'), 'created_by' => session()->get('id_user')],
            ['note_id' => $noteId, 'role' => 'assistant', 'message' => mb_substr($answer,   0, 5000), 'created_at' => date('Y-m-d H:i:s'), 'created_by' => session()->get('id_user')],
        ]);

        return $this->json(['answer' => $answer]);
    }

    /**
     * GET note-features/chat/history/{noteId}
     */
    public function getChatHistory(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $rows = \Config\Database::connect()
            ->table('note_chats')
            ->where('note_id', $noteId)
            ->orderBy('id', 'ASC')
            ->limit(60)
            ->get()->getResultArray();

        return $this->json(['history' => $rows]);
    }

    /**
     * GET note-features/chat/clear/{noteId}
     */
    public function clearChat(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        \Config\Database::connect()->table('note_chats')->where('note_id', $noteId)->delete();
        return $this->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // EXPLAIN SELECTED TEXT
    // ════════════════════════════════════════════════════════════════════

    /**
     * POST note-features/explain
     * Body JSON: { "text": "...", "note_id": 5 }
     */
    public function explain()
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $body   = $this->request->getJSON(true);
        $text   = trim($body['text'] ?? '');
        $noteId = (int) ($body['note_id'] ?? 0);

        if (!$text) return $this->json(['error' => 'Text required'], 422);

        $context     = $noteId ? ($this->noteContent($noteId) ?? '') : '';
        $service     = new SummarizationService();
        $explanation = $service->explainText($text, $context);

        return $this->json(['explanation' => $explanation]);
    }

    // ════════════════════════════════════════════════════════════════════
    // RELATED NOTES
    // ════════════════════════════════════════════════════════════════════

    /**
     * GET note-features/related/{noteId}
     * Scores other notes by category match, tag overlap, and title keyword overlap.
     */
    public function relatedNotes(int $noteId)
    {
        if (!$this->requireAuth()) return $this->json(['error' => 'Unauthorized'], 401);

        $userId = session()->get('id_user');
        $model  = new M_rapat();
        $note   = $model->asObject()->find($noteId);

        if (!$note) return $this->json(['error' => 'Note not found'], 404);

        $all = $model->where('created_by', $userId)
                     ->where('id_note !=', $noteId)
                     ->where('status_delete', 0)
                     ->orderBy('created_at', 'DESC')
                     ->findAll(100);

        if (empty($all)) return $this->json(['related' => []]);

        $currentTags     = array_filter(array_map('trim', explode(',', strtolower($note->tags ?? ''))));
        $currentCategory = $note->category ?? null;
        $stopWords       = ['the','a','an','of','in','on','and','or','for','to','is','are','was','were',
                            'dengan','dan','yang','di','ke','dari','untuk','ini','itu','ada','pada'];
        $currentWords    = array_diff(
            preg_split('/\s+/', strtolower($note->judul . ' ' . strip_tags($note->content ?? ''))),
            $stopWords
        );

        $scored = [];
        foreach ($all as $n) {
            $nObj  = is_object($n) ? $n : (object) $n;
            $score = 0;

            if ($currentCategory && ($nObj->category ?? null) == $currentCategory) $score += 3;

            $nTags = array_filter(array_map('trim', explode(',', strtolower($nObj->tags ?? ''))));
            foreach ($nTags as $t) {
                if ($t && in_array($t, $currentTags)) $score += 2;
            }

            $nWords = array_diff(preg_split('/\s+/', strtolower($nObj->judul ?? '')), $stopWords);
            foreach ($nWords as $w) {
                if (strlen($w) > 3 && in_array($w, $currentWords)) $score += 1;
            }

            if ($score > 0) {
                $scored[] = [
                    'id'         => $nObj->id_note,
                    'judul'      => $nObj->judul,
                    'tags'       => $nObj->tags ?? '',
                    'category'   => $nObj->category ?? '',
                    'created_at' => $nObj->created_at ?? '',
                    'score'      => $score,
                ];
            }
        }

        usort($scored, fn($a, $b) => $b['score'] - $a['score']);
        return $this->json(['related' => array_slice($scored, 0, 6)]);
    }
}
