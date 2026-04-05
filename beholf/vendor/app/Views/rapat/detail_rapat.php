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
<div class="container">
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
            <!-- Speech-to-Text -->
            <h5 class="mb-3">Speech-to-Text</h5>
            <form action="<?= base_url('transkrip/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_rapat" value="<?= esc($rapat->id_rapat) ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Sumber Audio</label>
                    <div class="btn-group d-flex flex-wrap gap-2" role="group">
                        <input type="radio" class="btn-check" name="audioSource" id="uploadOption" value="upload"
                            checked>
                        <label class="btn btn-outline-primary" for="uploadOption">
                            <i class="bi bi-upload"></i> Upload File
                        </label>

                        <input type="radio" class="btn-check" name="audioSource" id="recordOption" value="record">
                        <label class="btn btn-outline-success" for="recordOption">
                            <i class="bi bi-mic-fill"></i> Rekam dari Perangkat
                        </label>
                    </div>
                </div>

                <!-- Upload Audio -->
                <div id="uploadSection" class="mb-3">
                    <label for="audio" class="form-label fw-bold">Upload Audio</label>
                    <input type="file" class="form-control" id="audio" name="audio" accept="audio/*">
                </div>

                <!-- Record Audio -->
                <div id="recordSection" class="mb-3" style="display:none;">
                    <p class="text-muted">Klik tombol di bawah untuk mulai atau berhenti merekam:</p>
                    <button type="button" id="startRecord" class="btn btn-danger">
                        <i class="bi bi-mic"></i> Mulai Rekam
                    </button>
                    <button type="button" id="stopRecord" class="btn btn-secondary" disabled>
                        <i class="bi bi-stop-circle"></i> Stop
                    </button>
                    <audio id="recordedAudio" controls class="mt-2 w-100" style="display:none;"></audio>
                    <input type="hidden" name="recorded_audio" id="recordedAudioData">
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
        <div class="card-header text-center">
            <h5>Histori Transkrip</h5>
        </div>
        <div class="card-body">
            <?php if (empty($transkrip)): ?>
                <p class="text-muted">Belum ada transkrip yang diupload.</p>
            <?php else: ?>
                <ul class="list-group mt-4">
                    <?php foreach ($transkrip as $t): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= esc($t['file_url']) ?></strong><br>
                                <small class="text-muted">
                                    <?= esc(date('d F Y H:i', strtotime($t['waktu_upload']))) ?>
                                    by <?= esc($t['username']) ?>
                                </small>

                            </div>
                            <a href="<?= base_url('transkrip/view/' . $t['id_transkrip_rapat']) ?>"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-text"></i> Lihat
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </div>
    </div>

</div>
<div class="card shadow-sm">
    <div class="card-header text-center">
        <h5 class="mb-0">Transkrip Hasil Rapat</h5>
    </div>

    <div class="card-body">
        <h5 class="mb-3">Speech-to-Text</h5>
        <form action="<?= base_url('transkrip/upload') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_rapat" value="<?= esc($rapat->id_rapat) ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Sumber Audio</label>
                <div class="btn-group d-flex flex-wrap gap-2" role="group">
                    <input type="radio" class="btn-check" name="audioSource" id="uploadOption" value="upload" checked>
                    <label class="btn btn-outline-primary" for="uploadOption">
                        <i class="bi bi-upload"></i> Upload File
                    </label>

                    <input type="radio" class="btn-check" name="audioSource" id="recordOption" value="record">
                    <label class="btn btn-outline-success" for="recordOption">
                        <i class="bi bi-mic-fill"></i> Rekam dari Perangkat
                    </label>
                </div>
            </div>

            <!-- Upload Audio -->
            <div id="uploadSection" class="mb-3">
                <label for="audio" class="form-label fw-bold">Upload Audio</label>
                <input type="file" class="form-control" id="audio" name="audio" accept="audio/*">
            </div>

            <!-- Record Audio -->
            <div id="recordSection" class="mb-3" style="display:none;">
                <p class="text-muted">Klik tombol di bawah untuk mulai atau berhenti merekam:</p>
                <button type="button" id="startRecord" class="btn btn-danger">
                    <i class="bi bi-mic"></i> Mulai Rekam
                </button>
                <button type="button" id="stopRecord" class="btn btn-secondary" disabled>
                    <i class="bi bi-stop-circle"></i> Stop
                </button>
                <audio id="recordedAudio" controls class="mt-2 w-100" style="display:none;"></audio>
                <input type="hidden" name="recorded_audio" id="recordedAudioData">
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
                <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul transkrip"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Upload & Transkrip
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/recorder-js@1.0.4/dist/recorder.min.js"></script>
<script>
    // ======= Toggle Upload / Record =======
    const uploadOption = document.getElementById('uploadOption');
    const recordOption = document.getElementById('recordOption');
    const uploadSection = document.getElementById('uploadSection');
    const recordSection = document.getElementById('recordSection');

    uploadOption.addEventListener('change', () => {
        uploadSection.style.display = 'block';
        recordSection.style.display = 'none';
    });

    recordOption.addEventListener('change', () => {
        uploadSection.style.display = 'none';
        recordSection.style.display = 'block';
    });

    // ======= Rekaman Audio =======
    const startBtn = document.getElementById('startRecord');
    const stopBtn = document.getElementById('stopRecord');
    const audioPreview = document.getElementById('recordedAudio');
    const audioDataInput = document.getElementById('recordedAudioData');

    let recorder;

    startBtn.addEventListener('click', async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const source = audioContext.createMediaStreamSource(stream);
            recorder = new Recorder(source, { numChannels: 1 });
            recorder.record();

            startBtn.disabled = true;
            stopBtn.disabled = false;

            stopBtn.onclick = () => {
                recorder.stop();

                recorder.exportWAV(blob => {
                    // tampilkan audio di <audio>
                    const audioUrl = URL.createObjectURL(blob);
                    audioPreview.src = audioUrl;
                    audioPreview.style.display = 'block';

                    // simpan WAV ke input hidden dalam Base64
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        audioDataInput.value = reader.result; // base64 WAV
                    };
                    reader.readAsDataURL(blob);
                });

                recorder.clear();
                startBtn.disabled = false;
                stopBtn.disabled = true;

                // hentikan mikrofon
                stream.getTracks().forEach(track => track.stop());
            };

        } catch (err) {
            alert('Tidak dapat mengakses mikrofon: ' + err.message);
        }
    });


    // ======= Text-to-Speech =======
    function speak() {
        const text = document.getElementById("ttsText").value;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = "id-ID"; // bahasa Indonesia, bisa diganti
        speechSynthesis.speak(utterance);
    }
</script>