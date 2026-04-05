<style>
    .table-responsive-vertical table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Responsive vertikal di mobile */
    @media (max-width: 768px) {

        .table-responsive-vertical table,
        .table-responsive-vertical thead,
        .table-responsive-vertical tbody,
        .table-responsive-vertical th,
        .table-responsive-vertical td,
        .table-responsive-vertical tr {
            display: block;
            width: 100%;
        }

        .table-responsive-vertical thead tr {
            display: none;
            /* header hilang di mobile */
        }

        .table-responsive-vertical tbody tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            padding: 0.5rem;
            border-radius: 4px;
        }

        .table-responsive-vertical tbody tr td {
            padding-left: 50%;
            position: relative;
            text-align: left;
            border: none;
            border-bottom: 1px solid #eee;
            white-space: normal;
        }

        .table-responsive-vertical tbody tr td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            top: 0;
            font-weight: bold;
            white-space: nowrap;
        }

        .table-responsive-vertical tbody tr td:last-child {
            border-bottom: 0;
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
<div class="container">
    <?php if ($can_approve): ?>
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h4><?= $title ?> </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive-vertical">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Deleted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Judul"><?= esc($rapat->judul) ?></td>
                                <td data-label="Tanggal"><?= esc(date('d M Y, H:i', strtotime($rapat->tanggal))) ?></td>
                                <td data-label="Created At"><?= esc(date('d M Y, H:i', strtotime($rapat->created_at))) ?>
                                </td>
                                <td data-label="Updated At">
                                    <?= esc($rapat->updated_at ? date('d M Y, H:i', strtotime($rapat->updated_at)) : '-') ?>
                                </td>
                                <td data-label="Deleted At">
                                    <?= esc($rapat->deleted_at ? date('d M Y, H:i', strtotime($rapat->deleted_at)) : '-') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <a href="<?= base_url('rapat') ?>" class="btn btn-secondary shadow">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $pesertaByDivisi = [];
    foreach ($peserta as $p) {
        $divisi = $p['nama_divisi'] ?? 'Tidak Ada Divisi';
        $pesertaByDivisi[$divisi][] = $p;
    }
    ?>

    <div class="card shadow-sm mt-4">
        <div class="card-header text-center">
            <h5>Daftar Peserta Rapat</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pesertaByDivisi)): ?>
                <p class="text-muted">Belum ada peserta yang terdaftar.</p>
            <?php else: ?>
                <?php foreach ($pesertaByDivisi as $divisi => $listPeserta): ?>
                    <h6 class="mt-3"><?= esc($divisi) ?></h6>
                    <ul class="list-group mb-2">
                        <?php foreach ($listPeserta as $peserta): ?>
                            <li class="list-group-item">
                                <?= esc($peserta['username']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header text-center">
            <h5 class="mb-0">Transkrip Hasil Rapat</h5>
        </div>

        <div class="card-body">
            <!-- Speech-to-Text -->
            <h5 class="mb-3">Speech-to-Text</h5>
            <form action="<?= base_url('transkrip/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_rapat" value="<?= esc($rapat->id_rapat) ?>">

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

                <!-- Upload Section (Visible by default) -->
                <div class="mb-3" id="uploadSection">
                    <label for="audio" class="form-label fw-bold">Upload Audio</label>
                    <input type="file" class="form-control" id="audio" name="audio" accept="audio/*" required>
                    <div class="form-text">Format yang didukung: WAV, MP3, M4A, OGG, WEBM</div>
                </div>

                <!-- Record Section (Hidden by default) -->
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
            <h5 class="mb-3">Text-to-Speech</h5>
            <div class="mb-3">
                <label for="ttsText" class="form-label fw-bold">Masukkan Text</label>
                <textarea id="ttsText" class="form-control" rows="3">Halo, ini contoh text to speech!</textarea>
            </div>
            <button type="button" class="btn btn-success" onclick="speak()">
                <i class="bi bi-play-circle"></i> Preview
            </button>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-header text-center d-flex justify-content-between align-items-center">
            <h5 class="mb-0 flex-grow-1 text-center">Histori Transkrip</h5>
            <?php if (!empty($transkrip)): ?>
                <form id="deleteSelectedForm" method="post" action="<?= base_url('transkrip/delete_selected') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin hapus transkrip terpilih?')">
                        <i class="bi bi-trash"></i> Hapus Terpilih
                    </button>
                    <div class="form-check mb-2">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label">Pilih Semua</label>
                    </div>

                <?php endif; ?>
        </div>

        <div class="card-body">
            <?php if (empty($transkrip)): ?>
                <p class="text-muted">Belum ada transkrip yang diupload.</p>
            <?php else: ?>
                <ul class="list-group mt-3">
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


</div>

<script>
    // TTS function (available globally)
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
</script>
