<style>
    /* Notes App Modern Design */
    .note-header {
        background: #9E6247;
        color: white;
        padding: 2rem;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .note-title {
        font-size: 2rem;
        color: #fff;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .note-metadata {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .metadata-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .metadata-item i {
        color: #9E6247;
        font-size: 1.1rem;
    }

    .metadata-label {
        font-weight: 600;
        color: #495057;
    }

    .metadata-value {
        color: #6c757d;
    }

    .note-content {
        padding: 2rem;
        background: white;
        min-height: 200px;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #2d3748;
    }

    .note-content h1, .note-content h2, .note-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: #1a202c;
    }

    .note-content ul, .note-content ol {
        padding-left: 2rem;
        margin: 1rem 0;
    }

    .note-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin: 1.5rem 0;
        color: #4a5568;
        font-style: italic;
    }

    .note-section-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-left: 4px solid #667eea;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .note-section-header:hover {
        background: #e9ecef;
    }

    .note-section-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tag-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .category-badge {
        background: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Dropdown Navigation Styles */
    .section-dropdown {
        background: #9E6247;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    .section-dropdown h5 {
        color: white;
        margin-bottom: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-dropdown select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px;
        background: white;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        cursor: pointer;
        transition: all 0.3s;
    }

    .section-dropdown select:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-dropdown select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    }

    .section-content {
        display: none;
        animation: fadeIn 0.3s ease-in;
    }

    .section-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-indicator {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-left: auto;
    }

    /* Hide original collapsible headers */
    .collapsible-section .note-section-header {
        display: none;
    }

    /* Show sections as full cards when active */
    .collapsible-section {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: visible;
    }

    .collapsible-section .section-content {
        display: none;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
    }

    .collapsible-section .section-content.active {
        display: block;
    }

    /* Keyword highlighting */
    .keyword-highlight {
        background-color: #fef3c7;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
        color: #92400e;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Right-click highlight context menu */
    #highlightContextMenu {
        position: fixed;
        z-index: 9999;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
        padding: 6px 0;
        min-width: 180px;
        display: none;
    }
    #highlightContextMenu .ctx-title {
        padding: 4px 14px 6px;
        font-size: .75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 4px;
    }
    #highlightContextMenu .ctx-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        cursor: pointer;
        font-size: .9rem;
        transition: background .15s;
    }
    #highlightContextMenu .ctx-item:hover { background: #f8f9fa; }
    #highlightContextMenu .ctx-swatch {
        width: 16px; height: 16px;
        border-radius: 3px;
        border: 1px solid rgba(0,0,0,.15);
        flex-shrink: 0;
    }
    #highlightContextMenu .ctx-divider {
        border-top: 1px solid #f0f0f0;
        margin: 4px 0;
    }

    /* Floating chatbot widget */
    #askNotesFab {
        position: fixed;
        right: 22px;
        bottom: 22px;
        width: 58px;
        height: 58px;
        border: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        box-shadow: 0 12px 28px rgba(37, 99, 235, .35);
        z-index: 9997;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    #askNotesFab:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 16px 32px rgba(37, 99, 235, .42);
    }

    #askNotesFab .fab-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        background: #f59e0b;
        color: #111827;
        font-size: .7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    #askNotesWidget {
        position: fixed;
        right: 22px;
        bottom: 92px;
        width: min(380px, calc(100vw - 24px));
        max-height: min(72vh, 620px);
        background: #fff;
        border: 1px solid #dbe4f0;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, .22);
        overflow: hidden;
        z-index: 9997;
        display: none;
    }

    #askNotesWidget.open {
        display: flex;
        flex-direction: column;
    }

    .ask-notes-header {
        padding: 14px 16px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-bottom: 1px solid #dbeafe;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .ask-notes-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ask-notes-title .icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ask-notes-prompts {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 14px 0;
    }

    .ask-notes-prompts .btn {
        border-radius: 999px;
        font-size: .78rem;
    }

    #chatMessages {
        min-height: 180px;
        max-height: 340px;
        overflow-y: auto;
        background: #f8fafc;
        margin: 10px 14px 0;
        border-radius: 14px;
        padding: 12px 10px;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        scroll-behavior: smooth;
    }

    /* Chat bubble row */
    .chat-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
    }
    .chat-row.user-row { justify-content: flex-end; }
    .chat-row.ai-row   { justify-content: flex-start; }

    /* Avatar */
    .chat-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700;
    }
    .chat-avatar.ai-avatar  { background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; }
    .chat-avatar.usr-avatar { background: #6b7280; color: #fff; order: 2; }

    /* Bubble */
    .chat-bubble {
        max-width: 78%;
        padding: .55rem .9rem;
        border-radius: 16px;
        font-size: .875rem;
        line-height: 1.55;
        word-break: break-word;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .user-bubble {
        background: #2563eb;
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    .ai-bubble {
        background: #fff;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 4px;
        position: relative;
    }

    /* Markdown output inside AI bubble */
    .ai-bubble p       { margin: 0 0 .4em; }
    .ai-bubble p:last-child { margin-bottom: 0; }
    .ai-bubble ul, .ai-bubble ol { margin: .3em 0 .3em 1.1em; padding: 0; }
    .ai-bubble li      { margin-bottom: .2em; }
    .ai-bubble strong  { color: #111827; }
    .ai-bubble code    { background: #f3f4f6; border-radius: 4px; padding: 1px 5px; font-size: .82em; }
    .ai-bubble pre     { background: #1e1e2e; color: #cdd6f4; border-radius: 8px; padding: .7rem 1rem; overflow-x:auto; margin: .4em 0; }
    .ai-bubble pre code { background: none; padding: 0; color: inherit; font-size: .82em; }
    .ai-bubble h1, .ai-bubble h2, .ai-bubble h3 { font-size: .95rem; font-weight: 600; margin: .5em 0 .25em; }
    .ai-bubble blockquote { border-left: 3px solid #d1d5db; margin: .4em 0 .4em .5em; padding-left: .75em; color: #6b7280; }

    /* Copy button on AI bubble */
    .bubble-copy-btn {
        display: none;
        position: absolute; top: 5px; right: 6px;
        border: none; background: #f3f4f6; border-radius: 6px;
        padding: 2px 6px; cursor: pointer; font-size: .7rem; color: #6b7280;
        transition: background .15s;
    }
    .ai-bubble:hover .bubble-copy-btn { display: inline-block; }
    .bubble-copy-btn:hover { background: #e5e7eb; color: #111; }

    /* Typing dots animation */
    .chat-typing-dots {
        display: flex; gap: 4px; align-items: center; padding: 6px 2px;
    }
    .chat-typing-dots span {
        width: 7px; height: 7px; border-radius: 50%;
        background: #9ca3af;
        animation: dotBounce 1.2s infinite ease-in-out;
    }
    .chat-typing-dots span:nth-child(2) { animation-delay: .2s; }
    .chat-typing-dots span:nth-child(3) { animation-delay: .4s; }
    @keyframes dotBounce {
        0%, 80%, 100% { transform: scale(0.8); opacity:.5; }
        40%            { transform: scale(1.2); opacity:1; }
    }
    .ask-notes-footer {
        padding: 12px 14px 14px;
        border-top: 1px solid #eef2f7;
        background: #fff;
    }

    @media (max-width: 768px) {
        .note-title {
            font-size: 1.5rem;
        }
        
        .note-metadata {
            flex-direction: column;
            gap: 1rem;
        }
        
        .note-content {
            padding: 1rem;
            font-size: 1rem;
        }

        #askNotesFab {
            right: 14px;
            bottom: 14px;
        }

        #askNotesWidget {
            right: 12px;
            left: 12px;
            width: auto;
            bottom: 82px;
            max-height: 76vh;
        }
    }
</style>
<?php
$route = service('uri')->getSegment(1);
$formModel = new \App\Models\M_form();
$form = $formModel->where('route', $route)->where('status_delete', 0)->first();
$can_create = 0;
$can_approve = 0;
if ($form) {
    $groupFormModel = new \App\Models\M_group_form();
    $perm = $groupFormModel->where('id_form', $form['id_form'])->where('id_level', session()->get('level'))->where('status_delete', 0)->first();
    if ($perm && $perm['can_create'] == 1) {
        $can_create = 1;
    }
    if ($perm && $perm['can_approve'] == 1) {
        $can_approve = 1;
    }
}
?>

<div class="container-fluid" style="max-width: 1200px;">
    <?php if ($can_approve): ?>
        
        <!-- Note Header -->
        <div class="card shadow-lg mt-4 mb-4" style="border: none;">
            <div class="note-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h1 class="note-title"><?= esc($rapat->judul) ?></h1>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <?php if (!empty($rapat->category)): 
                                $categoryModel = new \App\Models\M_category();
                                $category = $categoryModel->find($rapat->category);
                                if ($category):
                            ?>
                                <span class="category-badge">
                                    <i class="bi bi-folder"></i> <?= esc($category['name']) ?>
                                </span>
                            <?php endif; endif; ?>
                            
                            <?php if (!empty($rapat->tags)): 
                                $tags = explode(',', $rapat->tags);
                                foreach ($tags as $tag): 
                            ?>
                                <span class="tag-badge">
                                    <i class="bi bi-tag"></i> <?= esc(trim($tag)) ?>
                                </span>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                    <a href="<?= base_url('rapat') ?>" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Note Metadata -->
            <div class="note-metadata">
                <div class="metadata-item">
                    <i class="bi bi-calendar-event"></i>
                    <span class="metadata-label">Tanggal:</span>
                    <span class="metadata-value"><?= esc(date('d F Y, H:i', strtotime($rapat->tanggal))) ?></span>
                </div>
                
                <?php if (!empty($rapat->lokasi)): ?>
                <div class="metadata-item">
                    <i class="bi bi-geo-alt"></i>
                    <span class="metadata-label">Lokasi:</span>
                    <span class="metadata-value"><?= esc($rapat->lokasi) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="metadata-item">
                    <i class="bi bi-clock-history"></i>
                    <span class="metadata-label">Dibuat:</span>
                    <span class="metadata-value"><?= esc(date('d M Y, H:i', strtotime($rapat->created_at))) ?></span>
                </div>
                
                <?php if (!empty($rapat->updated_at)): ?>
                <div class="metadata-item">
                    <i class="bi bi-pencil-square"></i>
                    <span class="metadata-label">Diupdate:</span>
                    <span class="metadata-value"><?= esc(date('d M Y, H:i', strtotime($rapat->updated_at))) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Note Description -->
            <?php if (!empty($rapat->keterangan)): ?>
            <div style="padding: 1.5rem; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                <div class="metadata-item">
                    <i class="bi bi-info-circle"></i>
                    <span class="metadata-label">Deskripsi:</span>
                </div>
                <p class="mb-0 mt-2" style="color: #6c757d;"><?= nl2br(esc($rapat->keterangan)) ?></p>
            </div>
            <?php endif; ?>

            <!-- Right-click highlight context menu -->
            <div id="highlightContextMenu">
                <div class="ctx-title"><i class="bi bi-highlighter"></i> Highlight</div>
                <div class="ctx-item" onclick="contextHighlight('#fef08a')"><span class="ctx-swatch" style="background:#fef08a"></span> Yellow</div>
                <div class="ctx-item" onclick="contextHighlight('#bbf7d0')"><span class="ctx-swatch" style="background:#bbf7d0"></span> Green</div>
                <div class="ctx-item" onclick="contextHighlight('#bfdbfe')"><span class="ctx-swatch" style="background:#bfdbfe"></span> Blue</div>
                <div class="ctx-item" onclick="contextHighlight('#fecaca')"><span class="ctx-swatch" style="background:#fecaca"></span> Red</div>
                <div class="ctx-item" onclick="contextHighlight('#e9d5ff')"><span class="ctx-swatch" style="background:#e9d5ff"></span> Purple</div>
                <div class="ctx-divider"></div>
                <div class="ctx-item text-muted" onclick="hideContextMenu()"><i class="bi bi-x-circle me-1"></i> Cancel</div>
            </div>

            <!-- Main Note Content -->
            <div class="note-content">
                <h5 class="mb-3" style="color: #9E6247; font-weight: 600;">
                    <i class="bi bi-file-text"></i> Isi Catatan
                </h5>
                <?php if (!empty($rapat->content)): ?>
                    <div class="note-content-body">
                        <?= $rapat->content ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted"><em>Belum ada konten catatan.</em></p>
                <?php endif; ?>
            </div>
        </div>

    <?php
    $pesertaByDivisi = [];
    foreach ($peserta as $p) {
        $divisi = $p['nama_divisi'] ?? 'Tidak Ada Divisi';
        $pesertaByDivisi[$divisi][] = $p;
    }
    ?>

    <!-- Section Navigation Dropdown -->
    <div class="section-dropdown">
        <h5>
            <i class="bi bi-list-ul"></i> 
            Navigasi Fitur
            <span class="section-indicator" id="sectionIndicator">Pilih Fitur</span>
        </h5>
        <select id="sectionSelector" onchange="showSection(this.value)">
            <option value="">-- Pilih Fitur untuk Dibuka --</option>
            <?php if (!empty($pesertaByDivisi)): ?>
            <option value="participantsSection">👥 Peserta yang Terdaftar (<?= count($peserta) ?>)</option>
            <?php endif; ?>
            <?php if ($can_approve): ?>
            <option value="aiSection">💡 AI Ringkasan & Analisis</option>
            <option value="transcriptionSection">🎤 Speech-to-Text & Transkrip</option>
            <?php endif; ?>
            <option value="summarySection">📝 Smart Summaries</option>
            <option value="flashSection">🎴 Flash Cards</option>
            <option value="highlightSection">📑 Highlights</option>
            <option value="structureSection">🔧 Smart Structure</option>
            <option value="termsSection">📚 Scientific Term Lookup</option>
            <option value="relatedSection">🔗 Related Notes</option>
        </select>
    </div>

    <!-- Participants Section -->
    <?php if (!empty($pesertaByDivisi)): ?>
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('participantsSection')">
            <h5>
                <span><i class="bi bi-people"></i> Peserta yang Terdaftar (<?= count($peserta) ?>)</span>
                <i class="bi bi-chevron-down" id="participantsIcon"></i>
            </h5>
        </div>
        <div id="participantsSection" class="section-content">
            <?php foreach ($pesertaByDivisi as $divisi => $listPeserta): ?>
                <h6 class="mt-3" style="color: #667eea;">
                    <i class="bi bi-building"></i> <?= esc($divisi) ?>
                </h6>
                <ul class="list-group mb-3">
                    <?php foreach ($listPeserta as $p): ?>
                        <li class="list-group-item">
                            <i class="bi bi-person"></i> <?= esc($p['username']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- AI Summarization Section -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('aiSection')">
            <h5>
                <span><i class="bi bi-lightbulb"></i> AI Ringkasan & Analisis</span>
                <i class="bi bi-chevron-down" id="aiIcon"></i>
            </h5>
        </div>
        <div id="aiSection" class="section-content">
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg" id="summarizeBtn">
                    <i class="bi bi-magic"></i> Ringkas Catatan dengan AI
                </button>
            </div>

            <!-- Summary Results -->
            <div id="summaryResults" style="display: none;" class="mt-4">
                <!-- Summary Text -->
                <div class="alert alert-info">
                    <h6><i class="bi bi-file-text"></i> Ringkasan:</h6>
                    <p id="summaryText" class="mb-0"></p>
                </div>

                <!-- Keywords -->
                <div class="alert alert-success">
                    <h6><i class="bi bi-tags"></i> Kata Kunci:</h6>
                    <div id="keywordsList"></div>
                </div>

                <!-- Scientific Terms -->
                <div id="scientificTermsSection" style="display: none;" class="alert alert-warning">
                    <h6><i class="bi bi-book"></i> Istilah Ilmiah:</h6>
                    <div id="scientificTermsList"></div>
                </div>

                <!-- Highlighted Content -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-highlighter"></i> Teks dengan Kata Kunci Disorot</h6>
                    </div>
                    <div class="card-body" id="highlightedContent" style="max-height: 400px; overflow-y: auto;">
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="summaryLoading" style="display: none;" class="text-center mt-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Menganalisis catatan...</p>
            </div>
        </div>
    </div>

    <!-- Speech-to-Text & Transcription Section -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('transcriptionSection')">
            <h5>
                <span><i class="bi bi-mic"></i> Speech-to-Text & Transkrip</span>
                <i class="bi bi-chevron-down" id="transcriptionIcon"></i>
            </h5>
        </div>
        <div id="transcriptionSection" class="section-content">
            <!-- Speech-to-Text Upload -->
            <form action="<?= base_url('transkrip/upload') ?>" method="post" enctype="multipart/form-data" class="mb-4">
                <input type="hidden" name="id_rapat" value="<?= esc($rapat->id_note) ?>">

                <!-- Mode Switch -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Mode</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="audio_mode" id="mode_upload" value="upload" checked>
                        <label class="btn btn-outline-primary" for="mode_upload">
                            <i class="bi bi-upload"></i> Upload File
                        </label>

                        <input type="radio" class="btn-check" name="audio_mode" id="mode_record" value="record">
                        <label class="btn btn-outline-primary" for="mode_record">
                            <i class="bi bi-mic"></i> Rekam Audio
                        </label>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="mb-3" id="uploadSection">
                    <label for="audio" class="form-label fw-bold">Upload Audio</label>
                    <input type="file" class="form-control" id="audio" name="audio" accept="audio/*" required>
                    <div class="form-text">Format: WAV, MP3, M4A, OGG, WEBM</div>
                </div>

                <!-- Record Section -->
                <div class="mb-3" id="recordSection" style="display: none;">
                    <label class="form-label fw-bold">Rekam Audio</label>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <button type="button" class="btn btn-warning" id="recordBtn">
                            <i class="bi bi-mic"></i> Mulai Rekam
                        </button>
                        <span id="recordingStatus" class="text-muted">Tekan tombol untuk mulai merekam</span>
                    </div>
                    <audio id="recordPreview" controls class="w-100" style="display: none;"></audio>
                    <input type="hidden" id="recordedAudioData" name="recorded_audio_data">
                </div>

                <div class="mb-3">
                    <label for="language" class="form-label fw-bold">Bahasa Audio</label>
                    <select class="form-select" id="language" name="language" required>
                        <option value="id-ID" selected>🇮🇩 Bahasa Indonesia</option>
                        <option value="en-US">🇺🇸 English (US)</option>
                        <option value="en-GB">🇬🇧 English (UK)</option>
                        <option value="ja-JP">🇯🇵 Japanese</option>
                        <option value="zh-CN">🇨🇳 Mandarin (Simplified)</option>
                        <option value="es-ES">🇪🇸 Spanish</option>
                        <option value="fr-FR">🇫🇷 French</option>
                        <option value="ar-SA">🇸🇦 Arabic</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label fw-bold">Judul Transkrip</label>
                    <input type="text" class="form-control" id="judul" name="judul"
                        placeholder="Masukkan judul transkrip" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Upload & Transkrip
                </button>
            </form>

            <hr class="my-4">

            <!-- Text-to-Speech -->
            <h6 class="mb-3"><i class="bi bi-volume-up"></i> Text-to-Speech Preview</h6>
            <div class="mb-3">
                <label for="ttsText" class="form-label fw-bold">Masukkan Teks</label>
                <textarea id="ttsText" class="form-control" rows="3">Halo, ini contoh text to speech!</textarea>
            </div>
            <button type="button" class="btn btn-success" onclick="speak()">
                <i class="bi bi-play-circle"></i> Preview Suara
            </button>

            <hr class="my-4">

            <!-- Transcription History -->
            <h6 class="mb-3"><i class="bi bi-clock-history"></i> Histori Transkrip</h6>
            <?php if (empty($transkrip)): ?>
                <p class="text-muted">Belum ada transkrip yang diupload.</p>
            <?php else: ?>
                <form id="deleteSelectedForm" method="post" action="<?= base_url('transkrip/delete_selected') ?>">
                    <?= csrf_field() ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                            <label for="selectAll" class="form-check-label">Pilih Semua</label>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus transkrip terpilih?')">
                            <i class="bi bi-trash"></i> Hapus Terpilih
                        </button>
                    </div>

                    <ul class="list-group">
                        <?php foreach ($transkrip as $t): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" name="selected_ids[]" value="<?= esc($t['id_transkrip_rapat']) ?>"
                                        class="form-check-input me-2">
                                    <div>
                                        <strong><?= esc($t['file_url']) ?></strong><br>
                                        <small class="text-muted">
                                            <?= esc(date('d F Y H:i', strtotime($t['waktu_upload']))) ?>
                                            by <?= esc($t['username']) ?>
                                        </small>
                                    </div>
                                </div>

                                <div>
                                    <a href="<?= base_url('transkrip/view/' . $t['id_transkrip_rapat']) ?>"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-file-earmark-text"></i> Lihat
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: SUMMARIZATION (short / bullets / detailed + saved list)
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('summarySection')">
            <h5>
                <span><i class="bi bi-journal-text"></i> Smart Summaries</span>
                <i class="bi bi-chevron-down" id="summarySectionIcon"></i>
            </h5>
        </div>
        <div id="summarySection" class="section-content">
            <div class="mb-3 d-flex flex-wrap gap-2">
                <button class="btn btn-primary" onclick="runSummary('short')"><i class="bi bi-lightning"></i> Short</button>
                <button class="btn btn-info text-white" onclick="runSummary('bullets')"><i class="bi bi-list-ul"></i> Bullet Points</button>
                <button class="btn btn-secondary" onclick="runSummary('detailed')"><i class="bi bi-file-earmark-text"></i> Detailed</button>
            </div>
            <div id="summaryLoaderNew" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Generating summary…</p>
            </div>
            <div id="summaryResultNew" class="alert alert-info" style="display:none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong id="summaryTypeLabel">Summary</strong>
                    <button class="btn btn-sm btn-light" onclick="copyText('summaryBodyNew')"><i class="bi bi-clipboard"></i> Copy</button>
                </div>
                <div id="summaryBodyNew" style="white-space:pre-wrap;"></div>
            </div>
            <hr>
            <h6 class="text-muted mb-2"><i class="bi bi-clock-history"></i> Saved Summaries</h6>
            <div id="savedSummaryList">
                <p class="text-muted small">Loading saved summaries…</p>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: FLASH CARDS
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('flashSection')">
            <h5>
                <span><i class="bi bi-card-list"></i> Flash Cards</span>
                <i class="bi bi-chevron-down" id="flashSectionIcon"></i>
            </h5>
        </div>
        <div id="flashSection" class="section-content">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-primary" onclick="generateFlashCards()"><i class="bi bi-magic"></i> Auto-Generate Cards</button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCardModal"><i class="bi bi-plus-circle"></i> Add Card</button>
                <button class="btn btn-outline-secondary ms-auto" id="studyModeBtn" onclick="openStudyMode()" style="display:none;"><i class="bi bi-play-circle"></i> Study Mode</button>
            </div>
            <div id="flashCardsLoader" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Generating flash cards…</p>
            </div>
            <div id="flashCardsList"></div>
        </div>
    </div>

    <!-- Add Card Modal -->
    <div class="modal fade" id="addCardModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add Flash Card</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Question</label><textarea id="newCardQ" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label">Answer</label><textarea id="newCardA" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label">Difficulty</label>
                        <select id="newCardDiff" class="form-select">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="saveNewCard()">Save Card</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Study Mode Modal -->
    <div class="modal fade" id="studyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-card-list"></i> Study Mode</h5>
                    <span id="studyProgress" class="badge bg-secondary ms-2"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="studyCard" class="card shadow" style="min-height:220px; cursor:pointer; border:2px solid #667eea;" onclick="flipCard()">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                            <span class="badge mb-3" id="studyCardSide" style="background:#667eea;">QUESTION</span>
                            <p class="fs-5 mb-0" id="studyCardText"></p>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Click card to flip</small>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-outline-secondary" onclick="prevCard()"><i class="bi bi-arrow-left"></i> Prev</button>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="markDifficulty('easy')">Easy</button>
                        <button class="btn btn-warning btn-sm" onclick="markDifficulty('medium')">Medium</button>
                        <button class="btn btn-danger btn-sm" onclick="markDifficulty('hard')">Hard</button>
                    </div>
                    <button class="btn btn-outline-primary" onclick="nextCard()">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: TEXT HIGHLIGHTS
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('highlightSection')">
            <h5>
                <span><i class="bi bi-highlighter"></i> Highlights</span>
                <i class="bi bi-chevron-down" id="highlightSectionIcon"></i>
            </h5>
        </div>
        <div id="highlightSection" class="section-content">
            <p class="text-muted small mb-3">Select any text in the note content above, then click a color below to save it as a highlight.</p>
            <div class="d-flex gap-2 flex-wrap mb-3" id="highlightColorBtns">
                <button class="btn btn-sm" style="background:#fef08a; border:1px solid #ccc;" onclick="saveHighlight('#fef08a')">Yellow</button>
                <button class="btn btn-sm" style="background:#bbf7d0; border:1px solid #ccc;" onclick="saveHighlight('#bbf7d0')">Green</button>
                <button class="btn btn-sm" style="background:#bfdbfe; border:1px solid #ccc;" onclick="saveHighlight('#bfdbfe')">Blue</button>
                <button class="btn btn-sm" style="background:#fecaca; border:1px solid #ccc;" onclick="saveHighlight('#fecaca')">Red</button>
                <button class="btn btn-sm" style="background:#e9d5ff; border:1px solid #ccc;" onclick="saveHighlight('#e9d5ff')">Purple</button>
            </div>
            <h6 class="text-muted mb-2"><i class="bi bi-bookmarks"></i> Saved Highlights</h6>
            <div id="savedHighlightsList"><p class="text-muted small">Loading…</p></div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: SMART STRUCTURE EXTRACTION
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('structureSection')">
            <h5>
                <span><i class="bi bi-diagram-3"></i> Smart Structure</span>
                <i class="bi bi-chevron-down" id="structureSectionIcon"></i>
            </h5>
        </div>
        <div id="structureSection" class="section-content">
            <button class="btn btn-primary mb-3" onclick="extractStructure()"><i class="bi bi-magic"></i> Extract Structure</button>
            <div id="structureLoader" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Analyzing structure…</p>
            </div>
            <div id="structureResult" style="display:none;">
                <div class="card mb-3 border-primary">
                    <div class="card-header bg-primary text-white py-2"><i class="bi bi-bullseye"></i> Main Idea</div>
                    <div class="card-body"><p id="structMainIdea" class="mb-0"></p></div>
                </div>
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white py-2"><i class="bi bi-list-check"></i> Key Points</div>
                    <div class="card-body"><p id="structKeyPoints" class="mb-0" style="white-space:pre-wrap;"></p></div>
                </div>
                <div class="card mb-3 border-info">
                    <div class="card-header bg-info text-white py-2"><i class="bi bi-info-circle"></i> Supporting Details</div>
                    <div class="card-body"><p id="structSupporting" class="mb-0"></p></div>
                </div>
                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning py-2"><i class="bi bi-flag"></i> Conclusion</div>
                    <div class="card-body"><p id="structConclusion" class="mb-0"></p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: SCIENTIFIC TERM LOOKUP
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('termsSection')">
            <h5>
                <span><i class="bi bi-book-half"></i> Scientific Term Lookup</span>
                <i class="bi bi-chevron-down" id="termsSectionIcon"></i>
            </h5>
        </div>
        <div id="termsSection" class="section-content">
            <p class="text-muted small mb-3">Select a word in the note above and click "Explain Selected", or type any term below.</p>
            <div class="input-group mb-3">
                <input type="text" id="termInput" class="form-control" placeholder="Type a scientific term…">
                <button class="btn btn-primary" onclick="lookupTerm()"><i class="bi bi-search"></i> Look Up</button>
                <button class="btn btn-outline-secondary" onclick="lookupSelected()"><i class="bi bi-cursor-text"></i> Explain Selected</button>
            </div>
            <div id="termLoader" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <div id="termResult" style="display:none;" class="card border-info">
                <div class="card-header bg-info text-white py-2">
                    <strong id="termResultName"></strong>
                    <span class="badge bg-light text-dark ms-2" id="termCacheBadge"></span>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Simple:</strong> <span id="termSimple"></span></p>
                    <p class="mb-0"><strong>Technical:</strong> <span id="termTechnical"></span></p>
                </div>
            </div>
            <hr>
            <h6 class="text-muted mb-2"><i class="bi bi-clock-history"></i> Looked Up Terms</h6>
            <div id="savedTermsList"></div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FEATURE: RELATED NOTES (Auto Linking)
    ══════════════════════════════════════════════════════════════════ -->
    <div class="collapsible-section shadow-sm mb-4">
        <div class="note-section-header" onclick="toggleSection('relatedSection'); loadRelatedNotes();">
            <h5>
                <span><i class="bi bi-diagram-2"></i> Related Notes</span>
                <i class="bi bi-chevron-down" id="relatedSectionIcon"></i>
            </h5>
        </div>
        <div id="relatedSection" class="section-content">
            <p class="text-muted small mb-3">Notes automatically matched by category, tags, and keywords.</p>
            <div id="relatedLoader" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted small">Finding related notes…</p>
            </div>
            <div id="relatedNotesList">
                <p class="text-muted small">Open this section to load related notes.</p>
            </div>
        </div>
    </div>

</div><!-- end .container-fluid -->

<button id="askNotesFab" type="button" aria-label="Open Ask Your Notes chatbot" onclick="toggleChatWidget()">
    <i class="bi bi-chat-dots-fill fs-4"></i>
    <span class="fab-badge">AI</span>
</button>

<div id="askNotesWidget" aria-live="polite" aria-label="Ask Your Notes chatbot panel">
    <div class="ask-notes-header">
        <div class="ask-notes-title">
            <span class="icon-wrap"><i class="bi bi-chat-quote-fill"></i></span>
            <div>
                <div class="fw-semibold text-dark">Ask Your Notes</div>
                <div class="text-muted small">Chat with this note using AI context</div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-light border" onclick="closeChatWidget()" aria-label="Close chatbot">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="ask-notes-prompts">
        <button class="btn btn-sm btn-outline-primary" onclick="sendChat('Explain this note simply in a few sentences.')">
            <i class="bi bi-lightning-charge"></i> Explain Simply
        </button>
        <button class="btn btn-sm btn-outline-primary" onclick="sendChat('What is the main idea of this note?')">
            <i class="bi bi-bullseye"></i> Main Idea
        </button>
        <button class="btn btn-sm btn-outline-primary" onclick="sendChat('Give me a real-world example related to this note.')">
            <i class="bi bi-lightbulb"></i> Give Example
        </button>
        <button class="btn btn-sm btn-outline-warning" onclick="sendChat('What are the most important things I should remember from this note?')">
            <i class="bi bi-star"></i> Key Takeaways
        </button>
    </div>

    <div id="chatMessages">
        <p class="text-muted text-center small mb-0" id="chatEmptyMsg" style="padding:1rem 0;">Ask anything about this note. Use a quick prompt or type your own question below.</p>
    </div>

    <div id="chatTypingRow" style="display:none; padding: 4px 14px 0; margin-bottom: -4px;">
        <div class="chat-row ai-row">
            <div class="chat-avatar ai-avatar"><i class="bi bi-stars" style="font-size:.75rem;"></i></div>
            <div class="chat-bubble ai-bubble">
                <div class="chat-typing-dots"><span></span><span></span><span></span></div>
            </div>
        </div>
    </div>

    <div class="ask-notes-footer">
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-sm btn-outline-danger" onclick="doClearChat()" title="Clear conversation">
                <i class="bi bi-trash"></i> Clear
            </button>
        </div>
        <div class="input-group">
            <input type="text" id="chatInput" class="form-control" placeholder="Ask about this note..." onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button class="btn btn-primary" onclick="sendChatMessage()">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

<!-- ══ SELECTION ACTION TOOLBAR (floating, shown on text select) ══ -->
<div id="selectionToolbar"
     style="display:none; position:fixed; z-index:9998; background:#1e1e2e; border-radius:10px;
            box-shadow:0 4px 20px rgba(0,0,0,.35); padding:6px 8px; gap:4px; align-items:center;">
    <button class="btn btn-sm text-white border-0" style="background:transparent;" onclick="selAction('summarize')" title="Summarize">
        <i class="bi bi-file-earmark-text-fill text-info"></i> <span style="font-size:.8rem;">Summarize</span>
    </button>
    <div style="width:1px;background:rgba(255,255,255,.2);height:24px;"></div>
    <button class="btn btn-sm text-white border-0" style="background:transparent;" onclick="selAction('explain')" title="Explain">
        <i class="bi bi-question-circle-fill text-warning"></i> <span style="font-size:.8rem;">Explain</span>
    </button>
    <div style="width:1px;background:rgba(255,255,255,.2);height:24px;"></div>
    <button class="btn btn-sm text-white border-0" style="background:transparent;" onclick="selAction('flashcard')" title="Create Flashcard">
        <i class="bi bi-card-heading text-success"></i> <span style="font-size:.8rem;">Flashcard</span>
    </button>
    <div style="width:1px;background:rgba(255,255,255,.2);height:24px;"></div>
    <div style="display:flex;gap:3px;">
        <button class="btn btn-sm border-0 p-1" style="background:#fef08a;border-radius:4px;width:22px;height:22px;" onclick="selAction('highlight','#fef08a')" title="Yellow"></button>
        <button class="btn btn-sm border-0 p-1" style="background:#bbf7d0;border-radius:4px;width:22px;height:22px;" onclick="selAction('highlight','#bbf7d0')" title="Green"></button>
        <button class="btn btn-sm border-0 p-1" style="background:#bfdbfe;border-radius:4px;width:22px;height:22px;" onclick="selAction('highlight','#bfdbfe')" title="Blue"></button>
        <button class="btn btn-sm border-0 p-1" style="background:#fecaca;border-radius:4px;width:22px;height:22px;" onclick="selAction('highlight','#fecaca')" title="Red"></button>
        <button class="btn btn-sm border-0 p-1" style="background:#e9d5ff;border-radius:4px;width:22px;height:22px;" onclick="selAction('highlight','#e9d5ff')" title="Purple"></button>
    </div>
</div>

<!-- ══ SELECTION ACTION RESULT MODAL ══ -->
<div class="modal fade" id="selActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0" id="selActionModalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="selActionLoader" class="text-center py-3" style="display:none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Processing…</p>
                </div>
                <div id="selActionContent"></div>
            </div>
            <div class="modal-footer py-2" id="selActionFooter" style="display:none;">
                <!-- dynamic buttons injected here -->
            </div>
        </div>
    </div>
</div>

<script>
// Toggle collapsible sections
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
    
    if (section.style.display === 'none' || !section.classList.contains('active')) {
        // Hide all sections first
        document.querySelectorAll('.section-content').forEach(s => {
            s.classList.remove('active');
        });
        
        // Show selected section
        section.classList.add('active');
        
        // Update all icons to down
        document.querySelectorAll('[id$="Icon"]').forEach(icon => {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        });
        
        // Update selected icon to up
        if (icon) {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        }
        
        // Update dropdown
        const selector = document.getElementById('sectionSelector');
        if (selector) {
            selector.value = sectionId;
        }
        
        // Update indicator
        updateSectionIndicator(sectionId);
    } else {
        // Hide section
        section.classList.remove('active');
        
        // Update icon
        if (icon) {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        }
        
        // Reset dropdown
        const selector = document.getElementById('sectionSelector');
        if (selector) {
            selector.value = '';
        }
        
        // Reset indicator
        updateSectionIndicator('');
    }
}

// Show section based on dropdown selection
function showSection(sectionId) {
    if (!sectionId) {
        // Hide all sections
        document.querySelectorAll('.section-content').forEach(s => {
            s.classList.remove('active');
        });
        
        // Reset all icons
        document.querySelectorAll('[id$="Icon"]').forEach(icon => {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        });
        
        updateSectionIndicator('');
        return;
    }
    
    const section = document.getElementById(sectionId);
    if (!section) return;
    
    // Hide all sections first
    document.querySelectorAll('.section-content').forEach(s => {
        s.classList.remove('active');
    });
    
    // Show selected section
    section.classList.add('active');
    
    // Update all icons to down
    document.querySelectorAll('[id$="Icon"]').forEach(icon => {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    });
    
    // Update selected icon to up
    const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
    if (icon) {
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    }
    
    // Update indicator
    updateSectionIndicator(sectionId);
    
    // Trigger section-specific loading if needed
    if (sectionId === 'relatedSection') {
        setTimeout(() => loadRelatedNotes(), 100);
    } else if (sectionId === 'summarySection') {
        setTimeout(() => loadSavedSummaries(), 100);
    } else if (sectionId === 'flashSection') {
        setTimeout(() => loadFlashCards(), 100);
    } else if (sectionId === 'highlightSection') {
        setTimeout(() => loadSavedHighlights(), 100);
    } else if (sectionId === 'structureSection') {
        setTimeout(async () => {
            const res  = await fetch(BASE + 'note-features/structure/' + NOTE_ID);
            const data = await res.json();
            if (data.structure) {
                const s = data.structure;
                document.getElementById('structMainIdea').textContent  = s.main_idea || '';
                document.getElementById('structKeyPoints').textContent  = s.key_points || '';
                document.getElementById('structSupporting').textContent = s.supporting_details || '';
                document.getElementById('structConclusion').textContent = s.conclusion || '';
                document.getElementById('structureResult').style.display = 'block';
            }
        }, 100);
    } else if (sectionId === 'termsSection') {
        setTimeout(() => loadSavedTerms(), 100);
    }
}

// Update section indicator text
function updateSectionIndicator(sectionId) {
    const indicator = document.getElementById('sectionIndicator');
    if (!indicator) return;
    
    const sectionNames = {
        'participantsSection': '👥 Peserta',
        'aiSection': '💡 AI Ringkasan',
        'transcriptionSection': '🎤 Speech-to-Text',
        'summarySection': '📝 Smart Summaries',
        'flashSection': '🎴 Flash Cards',
        'highlightSection': '📑 Highlights',
        'structureSection': '🔧 Smart Structure',
        'termsSection': '📚 Scientific Terms',
        'relatedSection': '🔗 Related Notes'
    };
    
    indicator.textContent = sectionNames[sectionId] || 'Pilih Fitur';
}

// TTS function
function speak() {
    const text = document.getElementById("ttsText").value;
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = "id-ID";
    speechSynthesis.speak(utterance);
}

    // Recording system - only activates in record mode
    document.addEventListener('DOMContentLoaded', function () {
        let mediaRecorder = null;
        let recordedChunks = [];
        let isRecording = false;
        let recordedWavBlob = null;
        let recordingInitialized = false;

        // Mode switching
        function switchMode(mode) {
            const uploadSection = document.getElementById('uploadSection');
            const recordSection = document.getElementById('recordSection');
            const fileInput = document.getElementById('audio');

            if (mode === 'upload') {
                // Show upload, hide record
                uploadSection.style.display = 'block';
                recordSection.style.display = 'none';

                // Clear file input requirement for record mode
                fileInput.required = true;

                // Stop recording if active
                if (isRecording) {
                    stopRecording();
                }

                console.log('📤 Mode: Upload File');

            } else if (mode === 'record') {
                // Show record, hide upload
                uploadSection.style.display = 'none';
                recordSection.style.display = 'block';

                // Remove file input requirement
                fileInput.required = false;

                // Initialize recording system only once
                if (!recordingInitialized) {
                    initializeRecording();
                    recordingInitialized = true;
                }

                console.log('🎙️ Mode: Rekam Audio');
            }
        }

        // Initialize recording system
        function initializeRecording() {
            const recordBtn = document.getElementById('recordBtn');

            recordBtn.addEventListener('click', function () {
                if (isRecording) {
                    stopRecording();
                } else {
                    startRecording();
                }
            });
        }

        // Recording functions
        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                recordedChunks = [];

                const options = {};
                if (MediaRecorder.isTypeSupported('audio/webm')) {
                    options.mimeType = 'audio/webm';
                } else if (MediaRecorder.isTypeSupported('audio/ogg')) {
                    options.mimeType = 'audio/ogg';
                }

                mediaRecorder = new MediaRecorder(stream, options);

                mediaRecorder.ondataavailable = (event) => {
                    if (event.data && event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = async () => {
                    const recordedBlob = new Blob(recordedChunks, {
                        type: recordedChunks[0]?.type || 'audio/webm'
                    });

                    try {
                        recordedWavBlob = await convertBlobToWav(recordedBlob);
                        const audioURL = URL.createObjectURL(recordedWavBlob);
                        const recordPreview = document.getElementById('recordPreview');
                        recordPreview.src = audioURL;
                        recordPreview.style.display = 'block';

                        document.getElementById('recordingStatus').textContent = 'Rekaman siap diupload';

                    } catch (err) {
                        console.error('Gagal konversi ke WAV:', err);
                        alert('Gagal mengonversi rekaman. Silakan coba lagi.');
                    } finally {
                        try {
                            stream.getTracks().forEach(track => track.stop());
                        } catch (e) { }
                    }
                };

                mediaRecorder.start();
                isRecording = true;

                const recordBtn = document.getElementById('recordBtn');
                recordBtn.innerHTML = '<i class="bi bi-stop-circle"></i> Stop Rekaman';
                recordBtn.classList.remove('btn-warning');
                recordBtn.classList.add('btn-danger');

                document.getElementById('recordingStatus').textContent = 'Sedang merekam...';

            } catch (error) {
                alert('Tidak dapat mengakses mikrofon. Mohon izinkan akses mikrofon di browser.');
                console.error('Error accessing microphone:', error);
            }
        }

        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                isRecording = false;

                const recordBtn = document.getElementById('recordBtn');
                recordBtn.innerHTML = '<i class="bi bi-mic"></i> Mulai Rekam';
                recordBtn.classList.remove('btn-danger');
                recordBtn.classList.add('btn-warning');
            }
        }

        // Audio conversion
        async function convertBlobToWav(inputBlob) {
            const arrayBuffer = await inputBlob.arrayBuffer();
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            let audioBuffer;

            try {
                audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
            } catch (err) {
                audioBuffer = await new Promise((resolve, reject) => {
                    audioCtx.decodeAudioData(arrayBuffer, resolve, reject);
                });
            }

            const wavBuffer = encodeWAV(audioBuffer);
            return new Blob([wavBuffer], { type: 'audio/wav' });
        }

        function encodeWAV(audioBuffer) {
            const numChannels = audioBuffer.numberOfChannels;
            const sampleRate = audioBuffer.sampleRate;
            let interleaved = audioBuffer.getChannelData(0);

            const buffer = new ArrayBuffer(44 + interleaved.length * 2);
            const view = new DataView(buffer);

            // WAV header
            writeString(view, 0, 'RIFF');
            view.setUint32(4, 36 + interleaved.length * 2, true);
            writeString(view, 8, 'WAVE');
            writeString(view, 12, 'fmt ');
            view.setUint32(16, 16, true);
            view.setUint16(20, 1, true);
            view.setUint16(22, numChannels, true);
            view.setUint32(24, sampleRate, true);
            view.setUint32(28, sampleRate * numChannels * 2, true);
            view.setUint16(32, numChannels * 2, true);
            view.setUint16(34, 16, true);
            writeString(view, 36, 'data');
            view.setUint32(40, interleaved.length * 2, true);

            // PCM data
            floatTo16BitPCM(view, 44, interleaved);
            return view.buffer;
        }

        function writeString(view, offset, string) {
            for (let i = 0; i < string.length; i++) {
                view.setUint8(offset + i, string.charCodeAt(i));
            }
        }

        function floatTo16BitPCM(output, offset, input) {
            for (let i = 0; i < input.length; i++, offset += 2) {
                let s = Math.max(-1, Math.min(1, input[i]));
                s = s < 0 ? s * 0x8000 : s * 0x7FFF;
                output.setInt16(offset, s, true);
            }
        }

        // Form submission handler
        document.querySelector('form').addEventListener('submit', function (event) {
            const currentMode = document.querySelector('input[name="audio_mode"]:checked').value;

            if (currentMode === 'record') {
                // Handle record mode submission
                if (!recordedWavBlob) {
                    event.preventDefault();
                    alert('Silakan rekam audio terlebih dahulu sebelum mengupload.');
                    return;
                }

                event.preventDefault();

                // Create file from recorded blob
                const recordedFile = new File([recordedWavBlob], 'recorded_audio.wav', {
                    type: 'audio/wav'
                });

                // Add to file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(recordedFile);
                document.getElementById('audio').files = dataTransfer.files;

                // Submit form
                this.submit();
            }

            // Upload mode: let form submit normally (no JavaScript interference)
        });

        // Mode switch event listeners
        document.getElementById('mode_upload').addEventListener('change', function () {
            if (this.checked) switchMode('upload');
        });

        document.getElementById('mode_record').addEventListener('change', function () {
            if (this.checked) switchMode('record');
        });

        // Initialize in upload mode
        switchMode('upload');
    });
</script>
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// AI Summarization
document.getElementById('summarizeBtn')?.addEventListener('click', async function() {
    const btn = this;
    const originalText = btn.innerHTML;
    const summaryResults = document.getElementById('summaryResults');
    const summaryLoading = document.getElementById('summaryLoading');
    
    // Show loading
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
    summaryLoading.style.display = 'block';
    summaryResults.style.display = 'none';
    
    try {
        const response = await fetch('<?= base_url('rapat/summarize/' . $rapat->id_note) ?>', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            // Display summary
            document.getElementById('summaryText').textContent = data.data.summary;
            
            // Display keywords as tags
            const keywordsList = document.getElementById('keywordsList');
            keywordsList.innerHTML = '';
            data.data.keywords.forEach(keyword => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-success me-2 mb-2';
                badge.style.fontSize = '1rem';
                badge.textContent = keyword;
                keywordsList.appendChild(badge);
            });
            
            // Display scientific terms if any
            if (data.data.scientific_terms && data.data.scientific_terms.length > 0) {
                const scientificTermsSection = document.getElementById('scientificTermsSection');
                const scientificTermsList = document.getElementById('scientificTermsList');
                scientificTermsList.innerHTML = '';
                
                data.data.scientific_terms.forEach(term => {
                    const termDiv = document.createElement('div');
                    termDiv.className = 'mb-2';
                    termDiv.innerHTML = `<strong>${term.term}:</strong> ${term.definition}`;
                    scientificTermsList.appendChild(termDiv);
                });
                
                scientificTermsSection.style.display = 'block';
            }
            
            // Get highlighted content
            const highlightResponse = await fetch('<?= base_url('rapat/highlightKeywords/' . $rapat->id_note) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ keywords: data.data.keywords })
            });
            
            const highlightData = await highlightResponse.json();
            if (highlightData.status === 'success') {
                document.getElementById('highlightedContent').innerHTML = highlightData.highlighted_text;
            }
            
            // Show results
            summaryResults.style.display = 'block';
        } else {
            alert('Gagal meringkas catatan: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat meringkas catatan');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
        summaryLoading.style.display = 'none';
    }
});

</script>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/marked@9/marked.min.js"></script>
<script>
const NOTE_ID = <?= (int)$rapat->id_note ?>;
const BASE    = '<?= base_url() ?>';

// ── Utility ───────────────────────────────────────────────────────────────
function copyText(elId) {
    const text = document.getElementById(elId)?.innerText || '';
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard!');
    });
}

// ── FEATURE: SUMMARIZATION ───────────────────────────────────────────────
async function runSummary(type) {
    const loader = document.getElementById('summaryLoaderNew');
    const result = document.getElementById('summaryResultNew');
    const body   = document.getElementById('summaryBodyNew');
    const label  = document.getElementById('summaryTypeLabel');

    loader.style.display = 'block';
    result.style.display = 'none';

    try {
        const res  = await fetch(BASE + 'note-features/summarize/' + NOTE_ID, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type })
        });
        const data = await res.json();
        if (data.summary) {
            const labels = { short: 'Short Summary', bullets: 'Bullet Summary', detailed: 'Detailed Summary' };
            label.textContent = labels[type] || 'Summary';
            body.textContent  = data.summary;
            result.style.display = 'block';
            loadSavedSummaries();
        } else {
            alert('Error generating summary.');
        }
    } catch(e) { alert('Network error.'); }
    finally    { loader.style.display = 'none'; }
}

async function loadSavedSummaries() {
    const list = document.getElementById('savedSummaryList');
    try {
        const res  = await fetch(BASE + 'note-features/summaries/' + NOTE_ID);
        const data = await res.json();
        if (!data.summaries || !data.summaries.length) {
            list.innerHTML = '<p class="text-muted small">No saved summaries yet.</p>';
            return;
        }
        list.innerHTML = data.summaries.map(s => `
            <div class="card mb-2 border-0 bg-light">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-primary me-2">${s.summary_type}</span>
                        <small class="text-muted">${s.created_at}</small>
                        <button class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="deleteSummary(${s.id})"><i class="bi bi-trash"></i></button>
                    </div>
                    <p class="mb-0 mt-1 small" style="white-space:pre-wrap;">${escHtml(s.summary_text)}</p>
                </div>
            </div>`).join('');
    } catch(e) { list.innerHTML = '<p class="text-danger small">Failed to load.</p>'; }
}

async function deleteSummary(id) {
    if (!confirm('Delete this summary?')) return;
    await fetch(BASE + 'note-features/summary/delete/' + id);
    loadSavedSummaries();
}

// Load saved summaries when section opens
// Note: This is now handled by showSection function

// ── FEATURE: FLASH CARDS ─────────────────────────────────────────────────
let allCards   = [];
let studyIndex = 0;
let showAnswer = false;

async function loadFlashCards() {
    const list = document.getElementById('flashCardsList');
    const studyBtn = document.getElementById('studyModeBtn');
    try {
        const res  = await fetch(BASE + 'note-features/flashcards/' + NOTE_ID);
        const data = await res.json();
        allCards = data.cards || [];
        renderCardList();
        studyBtn.style.display = allCards.length ? 'block' : 'none';
    } catch(e) { list.innerHTML = '<p class="text-danger small">Failed to load.</p>'; }
}

function renderCardList() {
    const list = document.getElementById('flashCardsList');
    if (!allCards.length) {
        list.innerHTML = '<p class="text-muted small">No flash cards yet. Click Auto-Generate or Add Card.</p>';
        document.getElementById('studyModeBtn').style.display = 'none';
        return;
    }
    document.getElementById('studyModeBtn').style.display = 'block';
    list.innerHTML = `<div class="row g-3">${allCards.map((c, i) => `
        <div class="col-md-6">
            <div class="card h-100 border-2" style="border-color:${diffColor(c.difficulty)}!important;">
                <div class="card-body">
                    <span class="badge mb-2" style="background:${diffColor(c.difficulty)}">${c.difficulty}</span>
                    <p class="fw-semibold mb-1">Q: ${escHtml(c.question)}</p>
                    <p class="text-muted mb-0 small">A: ${escHtml(c.answer)}</p>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex gap-2">
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCard(${c.id})"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>`).join('')}</div>`;
}

function diffColor(d) { return d === 'easy' ? '#16a34a' : d === 'hard' ? '#dc2626' : '#d97706'; }

async function generateFlashCards() {
    const loader = document.getElementById('flashCardsLoader');
    loader.style.display = 'block';
    try {
        const res  = await fetch(BASE + 'note-features/flashcards/generate/' + NOTE_ID, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ count: 8 })
        });
        const data = await res.json();
        allCards = data.cards || [];
        renderCardList();
        document.getElementById('studyModeBtn').style.display = allCards.length ? 'block' : 'none';
    } catch(e) { alert('Network error.'); }
    finally    { loader.style.display = 'none'; }
}

async function saveNewCard() {
    const q    = document.getElementById('newCardQ').value.trim();
    const a    = document.getElementById('newCardA').value.trim();
    const diff = document.getElementById('newCardDiff').value;
    if (!q || !a) { alert('Question and answer are required.'); return; }

    const res  = await fetch(BASE + 'note-features/flashcards/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ note_id: NOTE_ID, question: q, answer: a, difficulty: diff })
    });
    const data = await res.json();
    if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('addCardModal'))?.hide();
        document.getElementById('newCardQ').value = '';
        document.getElementById('newCardA').value = '';
        loadFlashCards();
    }
}

async function deleteCard(id) {
    if (!confirm('Delete this card?')) return;
    await fetch(BASE + 'note-features/flashcards/delete/' + id);
    allCards = allCards.filter(c => c.id != id);
    renderCardList();
}

// Study Mode
function openStudyMode() {
    if (!allCards.length) return;
    studyIndex = 0;
    showAnswer = false;
    renderStudyCard();
    new bootstrap.Modal(document.getElementById('studyModal')).show();
}

function renderStudyCard() {
    const card = allCards[studyIndex];
    if (!card) return;
    document.getElementById('studyCardText').textContent = showAnswer ? card.answer : card.question;
    document.getElementById('studyCardSide').textContent = showAnswer ? 'ANSWER' : 'QUESTION';
    document.getElementById('studyCardSide').style.background = showAnswer ? '#10b981' : '#667eea';
    document.getElementById('studyProgress').textContent = `${studyIndex + 1} / ${allCards.length}`;
}

function flipCard()  { showAnswer = !showAnswer; renderStudyCard(); }
function nextCard()  { studyIndex = (studyIndex + 1) % allCards.length; showAnswer = false; renderStudyCard(); }
function prevCard()  { studyIndex = (studyIndex - 1 + allCards.length) % allCards.length; showAnswer = false; renderStudyCard(); }
async function markDifficulty(diff) {
    const card = allCards[studyIndex];
    if (!card) return;
    await fetch(BASE + 'note-features/flashcards/update/' + card.id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ question: card.question, answer: card.answer, difficulty: diff })
    });
    card.difficulty = diff;
    nextCard();
}

// Load flash cards when section opens
// Note: This is now handled by showSection function

// ── FEATURE: HIGHLIGHTS ──────────────────────────────────────────────────
let pendingHighlightText    = '';
let pendingHighlightBefore  = '';
let pendingHighlightAfter   = '';

const ctxMenu = document.getElementById('highlightContextMenu');

function hideContextMenu() {
    if (ctxMenu) ctxMenu.style.display = 'none';
}

function contextHighlight(color) {
    hideContextMenu();
    saveHighlight(color);
}

function selectWordAtPoint(x, y) {
    const sel = window.getSelection();
    if (!sel) return '';

    const range = document.caretRangeFromPoint
        ? document.caretRangeFromPoint(x, y)
        : (document.caretPositionFromPoint ? (() => {
            const pos = document.caretPositionFromPoint(x, y);
            if (!pos) return null;
            const r = document.createRange();
            r.setStart(pos.offsetNode, pos.offset);
            r.setEnd(pos.offsetNode, pos.offset);
            return r;
        })() : null);

    if (!range || !range.startContainer || range.startContainer.nodeType !== Node.TEXT_NODE) return '';

    const textNode = range.startContainer;
    const content  = textNode.textContent || '';
    let start = range.startOffset;
    let end   = range.startOffset;

    while (start > 0 && /[\w-]/.test(content[start - 1])) start--;
    while (end < content.length && /[\w-]/.test(content[end])) end++;

    const word = content.substring(start, end).trim();
    if (!word) return '';

    const wordRange = document.createRange();
    wordRange.setStart(textNode, start);
    wordRange.setEnd(textNode, end);
    sel.removeAllRanges();
    sel.addRange(wordRange);
    return word;
}

// Show context menu on right-click inside note content
document.querySelector('.note-content-body')?.addEventListener('contextmenu', function(e) {
    const sel = window.getSelection();
    if (!sel) return;

    let text = (!sel.isCollapsed ? sel.toString().trim() : '');
    if (!text) {
        text = selectWordAtPoint(e.clientX, e.clientY);
    }
    if (!text) return;

    e.preventDefault();
    pendingHighlightText   = text;
    // grab surrounding context from the range
    const range = sel.getRangeAt(0);
    const container = range.commonAncestorContainer;
    const fullText   = container.textContent || '';
    const start      = fullText.indexOf(text);
    pendingHighlightBefore = start > 0 ? fullText.substring(Math.max(0, start - 100), start) : '';
    pendingHighlightAfter  = fullText.substring(start + text.length, start + text.length + 100);

    // Position menu near cursor, keep inside viewport
    const menuW = 190, menuH = 200;
    let x = e.clientX, y = e.clientY;
    if (x + menuW > window.innerWidth)  x = window.innerWidth  - menuW - 8;
    if (y + menuH > window.innerHeight) y = window.innerHeight - menuH - 8;
    ctxMenu.style.left    = x + 'px';
    ctxMenu.style.top     = y + 'px';
    ctxMenu.style.display = 'block';
});

document.querySelector('.note-content-body')?.addEventListener('mouseup', function() {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) return;
    const text   = sel.toString().trim();
    if (!text)   return;
    pendingHighlightText   = text;
    pendingHighlightBefore = text.substring(0, 100);
    pendingHighlightAfter  = text.substring(Math.max(0, text.length - 100));
});

// Hide context menu when clicking elsewhere
document.addEventListener('click', function(e) {
    if (ctxMenu && !ctxMenu.contains(e.target)) hideContextMenu();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hideContextMenu();
});

async function saveHighlight(color) {
    if (!pendingHighlightText) {
        const sel = window.getSelection();
        if (!sel || sel.isCollapsed) { alert('Please select some text in the note first.'); return; }
        pendingHighlightText = sel.toString().trim();
    }
    if (!pendingHighlightText) { alert('No text selected.'); return; }

    const res  = await fetch(BASE + 'note-features/highlights/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            note_id:        NOTE_ID,
            selected_text:  pendingHighlightText,
            color,
            context_before: pendingHighlightBefore,
            context_after:  pendingHighlightAfter
        })
    });
    const data = await res.json();
    if (data.success) {
        pendingHighlightText = '';
        applyHighlightInView(pendingHighlightText || data.text, color);
        loadSavedHighlights();
    }
}

function applyHighlightInView(text, color) {
    // Visually wrap the matched text in the note body
    const body = document.querySelector('.note-content-body');
    if (!body || !pendingHighlightText) return;
    body.innerHTML = body.innerHTML.replace(
        new RegExp('(' + escRegex(pendingHighlightText) + ')', 'g'),
        `<mark style="background:${color};padding:0 2px;border-radius:3px;">$1</mark>`
    );
}

async function loadSavedHighlights() {
    const list = document.getElementById('savedHighlightsList');
    try {
        const res  = await fetch(BASE + 'note-features/highlights/' + NOTE_ID);
        const data = await res.json();
        const hl   = data.highlights || [];
        if (!hl.length) { list.innerHTML = '<p class="text-muted small">No highlights saved yet.</p>'; return; }

        // Apply all highlights to note content
        const body = document.querySelector('.note-content-body');
        hl.forEach(h => {
            if (body) {
                body.innerHTML = body.innerHTML.replace(
                    new RegExp('(' + escRegex(h.selected_text.substring(0, 60)) + ')', 'g'),
                    `<mark style="background:${h.color};padding:0 2px;border-radius:3px;">$1</mark>`
                );
            }
        });

        list.innerHTML = hl.map(h => `
            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:${h.color}20;">
                <span class="flex-grow-1 small">${escHtml(h.selected_text.substring(0, 80))}${h.selected_text.length > 80 ? '…' : ''}</span>
                <span class="badge" style="background:${h.color};border:1px solid #ccc;">&nbsp;</span>
                <button class="btn btn-sm btn-link text-danger p-0" onclick="deleteHighlight(${h.id})"><i class="bi bi-x"></i></button>
            </div>`).join('');
    } catch(e) { list.innerHTML = '<p class="text-danger small">Failed to load.</p>'; }
}

async function deleteHighlight(id) {
    await fetch(BASE + 'note-features/highlights/delete/' + id);
    loadSavedHighlights();
}

// Load highlights when section opens
// Note: This is now handled by showSection function

// ── FEATURE: STRUCTURE EXTRACTION ────────────────────────────────────────
async function extractStructure() {
    const loader = document.getElementById('structureLoader');
    const result = document.getElementById('structureResult');
    loader.style.display = 'block';
    result.style.display = 'none';

    try {
        const res  = await fetch(BASE + 'note-features/structure/extract/' + NOTE_ID, { method: 'POST' });
        const data = await res.json();
        const s    = data.structure || {};
        document.getElementById('structMainIdea').textContent  = s.main_idea || '';
        document.getElementById('structKeyPoints').textContent  = s.key_points || '';
        document.getElementById('structSupporting').textContent = s.supporting_details || '';
        document.getElementById('structConclusion').textContent = s.conclusion || '';
        result.style.display = 'block';
    } catch(e) { alert('Network error.'); }
    finally    { loader.style.display = 'none'; }
}

// Load saved structure when section opens
// Note: This is now handled by showSection function

// ── FEATURE: SCIENTIFIC TERM LOOKUP ──────────────────────────────────────
async function lookupTerm() {
    const term = document.getElementById('termInput').value.trim();
    if (!term) { alert('Please enter a term.'); return; }
    await doTermLookup(term);
}

function lookupSelected() {
    const sel = window.getSelection()?.toString().trim();
    if (!sel) { alert('Please select a word in the note first.'); return; }
    document.getElementById('termInput').value = sel;
    doTermLookup(sel);
}

async function doTermLookup(term) {
    const loader = document.getElementById('termLoader');
    const result = document.getElementById('termResult');
    loader.style.display = 'block';
    result.style.display = 'none';

    try {
        const res  = await fetch(BASE + 'note-features/term/lookup', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ term, note_id: NOTE_ID })
        });
        const data = await res.json();
        document.getElementById('termResultName').textContent  = data.term || term;
        document.getElementById('termSimple').textContent     = data.simple || '';
        document.getElementById('termTechnical').textContent  = data.technical || '';
        document.getElementById('termCacheBadge').textContent = data.cached ? 'cached' : 'new';
        result.style.display = 'block';
        loadSavedTerms();
    } catch(e) { alert('Network error.'); }
    finally    { loader.style.display = 'none'; }
}

async function loadSavedTerms() {
    const list = document.getElementById('savedTermsList');
    try {
        const res  = await fetch(BASE + 'note-features/terms/' + NOTE_ID);
        const data = await res.json();
        const terms = data.terms || [];
        if (!terms.length) { list.innerHTML = '<p class="text-muted small">No terms looked up for this note yet.</p>'; return; }
        list.innerHTML = terms.map(t => `
            <div class="card border-0 bg-light mb-2">
                <div class="card-body py-2 px-3">
                    <strong class="text-info">${escHtml(t.term)}</strong>
                    <p class="mb-0 small">${escHtml(t.simple_definition || '')}</p>
                </div>
            </div>`).join('');
    } catch(e) {}
}

// Load terms when section opens
// Note: This is now handled by showSection function

// ── Helpers ───────────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escRegex(s) {
    return String(s).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// ══════════════════════════════════════════════════════════════════════════
// CHAT WITH NOTES
// ══════════════════════════════════════════════════════════════════════════
let chatHistory = [];  // in-memory history for context window
let chatLoaded  = false;

function toggleChatWidget(forceOpen = null) {
    const widget = document.getElementById('askNotesWidget');
    if (!widget) return;

    const shouldOpen = forceOpen === null ? !widget.classList.contains('open') : forceOpen;
    widget.classList.toggle('open', shouldOpen);
    if (shouldOpen) {
        loadChatHistory();
        setTimeout(() => document.getElementById('chatInput')?.focus(), 120);
    }
}

function openChatWidget() {
    toggleChatWidget(true);
}

function closeChatWidget() {
    toggleChatWidget(false);
}

async function loadChatHistory() {
    if (chatLoaded) return;
    chatLoaded = true;
    try {
        const res  = await fetch(BASE + 'note-features/chat/history/' + NOTE_ID);
        const data = await res.json();
        const rows = data.history || [];
        if (rows.length) {
            document.getElementById('chatEmptyMsg')?.remove();
            rows.forEach(r => appendChatBubble(r.role, r.message));
            chatHistory = rows.map(r => ({ role: r.role, content: r.message }));
        }
    } catch(e) {}
}

function appendChatBubble(role, text) {
    const box = document.getElementById('chatMessages');
    document.getElementById('chatEmptyMsg')?.remove();

    const isUser = role === 'user';
    const row = document.createElement('div');
    row.className = 'chat-row ' + (isUser ? 'user-row' : 'ai-row');

    if (isUser) {
        row.innerHTML = `
            <div class="chat-avatar usr-avatar"><i class="bi bi-person-fill" style="font-size:.75rem;"></i></div>
            <div class="chat-bubble user-bubble">${escHtml(text)}</div>`;
    } else {
        // Render markdown for AI messages
        const rendered = (typeof marked !== 'undefined')
            ? marked.parse(text, { breaks: true, gfm: true })
            : escHtml(text).replace(/\n/g, '<br>');

        const copyId = 'cp-' + Date.now();
        row.innerHTML = `
            <div class="chat-avatar ai-avatar"><i class="bi bi-stars" style="font-size:.75rem;"></i></div>
            <div class="chat-bubble ai-bubble">
                <button class="bubble-copy-btn" onclick="copyBubble('${copyId}')" title="Copy"><i class="bi bi-clipboard"></i></button>
                <div id="${copyId}">${rendered}</div>
            </div>`;
    }

    box.appendChild(row);
    box.scrollTop = box.scrollHeight;
}

function copyBubble(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const text = el.innerText || el.textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = el.previousElementSibling;
        if (btn) { btn.innerHTML = '<i class="bi bi-check2"></i>'; setTimeout(() => { btn.innerHTML = '<i class="bi bi-clipboard"></i>'; }, 1500); }
    }).catch(() => {});
}

async function sendChat(question) {
    openChatWidget();
    document.getElementById('chatInput').value = question;
    await sendChatMessage();
}

async function sendChatMessage() {
    const input    = document.getElementById('chatInput');
    const question = input.value.trim();
    if (!question) return;
    input.value = '';
    input.disabled = true;

    appendChatBubble('user', question);

    const typingRow = document.getElementById('chatTypingRow');
    const box = document.getElementById('chatMessages');
    typingRow.style.display = 'block';
    box.scrollTop = box.scrollHeight;

    try {
        const res  = await fetch(BASE + 'note-features/chat/' + NOTE_ID, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question, history: chatHistory })
        });
        const data = await res.json();
        const answer = data.answer || 'No answer returned.';
        typingRow.style.display = 'none';
        appendChatBubble('assistant', answer);
        chatHistory.push({ role: 'user',      content: question });
        chatHistory.push({ role: 'assistant', content: answer   });
        if (chatHistory.length > 24) chatHistory = chatHistory.slice(-24);
    } catch(e) {
        typingRow.style.display = 'none';
        appendChatBubble('assistant', 'Network error. Please try again.');
    } finally {
        input.disabled = false;
        input.focus();
    }
}

function openChatWithPrompt(promptText) {
    const chatInput = document.getElementById('chatInput');
    if (chatInput) chatInput.value = promptText;
    bootstrap.Modal.getInstance(document.getElementById('selActionModal'))?.hide();
    openChatWidget();
    setTimeout(() => chatInput?.focus(), 400);
}

async function doClearChat() {
    if (!confirm('Clear conversation history?')) return;
    await fetch(BASE + 'note-features/chat/clear/' + NOTE_ID);
    document.getElementById('chatMessages').innerHTML =
        '<p class="text-muted text-center small mb-0" id="chatEmptyMsg">Ask anything about this note — use the buttons above or type below.</p>';
    chatHistory = [];
    chatLoaded  = false;
}

// ══════════════════════════════════════════════════════════════════════════
// RELATED NOTES
// ══════════════════════════════════════════════════════════════════════════
let relatedLoaded = false;

async function loadRelatedNotes() {
    if (relatedLoaded) return;
    relatedLoaded = true;
    const loader = document.getElementById('relatedLoader');
    const list   = document.getElementById('relatedNotesList');
    loader.style.display = 'block';
    list.innerHTML = '';

    try {
        const res  = await fetch(BASE + 'note-features/related/' + NOTE_ID);
        const data = await res.json();
        const notes = data.related || [];

        if (!notes.length) {
            list.innerHTML = '<p class="text-muted small">No closely related notes found. Add more notes with similar tags or categories.</p>';
            return;
        }

        list.innerHTML = `<div class="row g-3">${notes.map(n => `
            <div class="col-md-6 col-lg-4">
                <a href="${BASE}rapat/detail_rapat/${n.id}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm" style="transition:.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                        <div class="card-body py-3">
                            <h6 class="card-title mb-1 text-dark">${escHtml(n.judul)}</h6>
                            ${n.tags ? `<div class="mt-1">${n.tags.split(',').map(t => `<span class="badge bg-light text-secondary border me-1" style="font-size:.72rem;">${escHtml(t.trim())}</span>`).join('')}</div>` : ''}
                            <p class="text-muted mb-0 mt-2" style="font-size:.75rem;">${n.created_at ? n.created_at.substring(0,10) : ''}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-1">
                            <small class="text-primary"><i class="bi bi-link-45deg"></i> ${n.score} match point${n.score > 1 ? 's' : ''}</small>
                        </div>
                    </div>
                </a>
            </div>`).join('')}
        </div>`;
    } catch(e) {
        list.innerHTML = '<p class="text-danger small">Failed to load related notes.</p>';
    } finally {
        loader.style.display = 'none';
    }
}

// ══════════════════════════════════════════════════════════════════════════
// SELECTION FLOATING TOOLBAR
// ══════════════════════════════════════════════════════════════════════════
const selToolbar   = document.getElementById('selectionToolbar');
const selModal     = new bootstrap.Modal(document.getElementById('selActionModal'));
let   selText      = '';
let   selActionBsy = false;

function hideSelToolbar() { if (selToolbar) selToolbar.style.display = 'none'; }

document.querySelector('.note-content-body')?.addEventListener('mouseup', function() {
    setTimeout(showSelToolbarIfNeeded, 10);
});

document.addEventListener('selectionchange', function() {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed || !sel.toString().trim()) hideSelToolbar();
});

function showSelToolbarIfNeeded() {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) return;
    const text = sel.toString().trim();
    if (!text || text.length < 3) return;

    // Ensure selection is inside the note body
    const range   = sel.getRangeAt(0);
    const noteBody = document.querySelector('.note-content-body');
    if (!noteBody) return;
    if (!noteBody.contains(range.commonAncestorContainer)) return;

    selText = text;
    // Also set pendingHighlightText for the existing saveHighlight()
    pendingHighlightText   = text;
    pendingHighlightBefore = '';
    pendingHighlightAfter  = '';

    const rect = range.getBoundingClientRect();
    const toolW = selToolbar.offsetWidth || 310;
    let x = rect.left + (rect.width / 2) - toolW / 2 + window.scrollX;
    let y = rect.top  - 54 + window.scrollY;
    x = Math.max(8, Math.min(x, window.innerWidth - toolW - 8));
    if (y < window.scrollY + 8) y = rect.bottom + window.scrollY + 8;

    selToolbar.style.left    = x + 'px';
    selToolbar.style.top     = y + 'px';
    selToolbar.style.display = 'flex';
}

document.addEventListener('mousedown', function(e) {
    if (selToolbar && !selToolbar.contains(e.target)) hideSelToolbar();
});

async function selAction(type, color) {
    hideSelToolbar();
    if (!selText) return;

    if (type === 'highlight') {
        saveHighlight(color);
        return;
    }

    if (type === 'flashcard') {
        // Pre-fill the add card modal if it exists
        const qField = document.getElementById('newCardQuestion');
        const aField = document.getElementById('newCardAnswer');
        if (qField) qField.value = selText.substring(0, 200);
        if (aField) aField.focus();
        const modal = document.getElementById('addCardModal');
        if (modal) { new bootstrap.Modal(modal).show(); }
        else        { alert('Open the Flash Cards section to add a card.'); }
        return;
    }

    // summarize or explain — show modal
    if (selActionBsy) return;
    selActionBsy = true;

    const titleEl   = document.getElementById('selActionModalTitle');
    const loaderEl  = document.getElementById('selActionLoader');
    const contentEl = document.getElementById('selActionContent');
    const footerEl  = document.getElementById('selActionFooter');

    titleEl.textContent  = type === 'summarize' ? 'Summary of Selection' : 'Explanation';
    loaderEl.style.display  = 'block';
    contentEl.innerHTML     = '';
    footerEl.style.display  = 'none';
    selModal.show();

    try {
        let result = '';
        if (type === 'summarize') {
            const res  = await fetch(BASE + 'note-features/summarize/' + NOTE_ID, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'short', source_text: selText })
            });
            const data = await res.json();
            result = data.summary || 'No summary generated.';
        } else {
            const res  = await fetch(BASE + 'note-features/explain', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ text: selText, note_id: NOTE_ID })
            });
            const data = await res.json();
            result = data.explanation || 'No explanation generated.';
        }

        contentEl.innerHTML = `
            <blockquote class="blockquote border-start border-4 border-primary ps-3 mb-3">
                <p class="fst-italic text-muted mb-0" style="font-size:.85rem;">"${escHtml(selText.substring(0, 120))}${selText.length > 120 ? '…' : ''}"</p>
            </blockquote>
            <p style="white-space:pre-wrap; line-height:1.7;">${escHtml(result)}</p>`;

        // Add "Ask follow-up" button
        footerEl.innerHTML = '<button id="selAskFollowUpBtn" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat-dots"></i> Ask follow-up</button>';
        const followUpPrompt = 'Can you elaborate more on: ' + selText.substring(0, 80);
        document.getElementById('selAskFollowUpBtn')?.addEventListener('click', function() {
            openChatWithPrompt(followUpPrompt);
        });
        footerEl.style.display = 'flex';
    } catch(e) {
        contentEl.innerHTML = '<p class="text-danger">Error generating response. Please try again.</p>';
    } finally {
        loaderEl.style.display = 'none';
        selActionBsy = false;
    }
}
</script>
