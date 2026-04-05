<style>
    @media (max-width: 768px) {
        .btn {
            font-size: 14px;
            padding: 6px 12px;
        }

        .breadcrumb {
            font-size: 13px;
        }
    }

    #language-select {
        max-width: 180px;
    }

    #translate-btn,
    #reset-btn {
        white-space: nowrap;
    }
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?? 'Transkrip Rapat' ?></h3>
                        <p class="text-subtitle text-muted">Detail hasil transkrip rapat</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('transkrip') ?>">Transkrip</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4>Judul: <?= esc($judul) ?></h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Audio Asli:</strong></p>
                        <audio controls style="width:100%; max-width:600px;">
                            <source src="<?= base_url('uploads/' . $file_name) ?>" type="audio/mpeg">
                            Browser kamu tidak mendukung pemutar audio.
                        </audio>

                        <hr>
                        <p><strong>Hasil Transkrip:</strong></p>

                        <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                            <select id="language-select" class="form-select form-select-sm" style="width: 160px;">
                                <option value="id">Bahasa Indonesia (Asli)</option>
                                <option value="en">English</option>
                                <option value="ms">Malay</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="ja">Japanese</option>
                            </select>

                            <button id="translate-btn" class="btn btn-sm btn-success">Terjemahkan</button>
                            <button id="reset-btn" class="btn btn-sm btn-secondary">Kembalikan Asli</button>
                        </div>

                        <div id="transkrip-container"
                            style="white-space: pre-wrap; border:1px solid #eee; padding:10px; border-radius:6px;">
                            <?= esc($isi) ?>
                        </div>

                        <div id="loading-translate" style="display:none;" class="mt-2 text-muted">
                            <em>Sedang menerjemahkan...</em>
                        </div>

                        <hr>
                        <div class="mt-3">
                            <button id="summary-btn" class="btn btn-warning btn-sm">Kesimpulan</button>
                        </div>

                        <div id="loading-summary" style="display:none;" class="mt-2 text-muted">
                            <em>Sedang menganalisis dan merangkum...</em>
                        </div>

                        <div id="summary-container"
                            style="display:none; margin-top:15px; border:1px solid #ffc107; border-radius:6px; padding:10px; background:#fff8e1;">
                            <h6><strong>Kesimpulan Otomatis:</strong></h6>
                            <div id="summary-text" style="white-space: pre-wrap;"></div>
                        </div>


                        <div class="mt-3 d-flex gap-2">
                            <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
                            <a href="<?= base_url('transkrip/export/' . $id_transkrip_rapat . '/word') ?>"
                                class="btn btn-primary">Export Word</a>
                            <a href="<?= base_url('transkrip/export/' . $id_transkrip_rapat . '/pdf') ?>"
                                class="btn btn-danger">Export PDF</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
    const originalText = document.getElementById('transkrip-container').innerText.trim();

    document.getElementById('translate-btn').addEventListener('click', async function () {
        const targetLang = document.getElementById('language-select').value;
        const transkrip = document.getElementById('transkrip-container');
        const loading = document.getElementById('loading-translate');

        if (targetLang === 'id') {
            transkrip.innerText = originalText;
            return;
        }

        loading.style.display = 'block';
        transkrip.style.opacity = 0.6;

        try {
            const res = await fetch("https://api.mymemory.translated.net/get?q=" + encodeURIComponent(originalText) + "&langpair=id|" + targetLang);
            const data = await res.json();

            const translated = data?.responseData?.translatedText || '(Gagal menerjemahkan)';
            transkrip.innerText = translated;
        } catch (err) {
            console.error(err);
            alert("Terjadi kesalahan saat menerjemahkan.");
        } finally {
            loading.style.display = 'none';
            transkrip.style.opacity = 1;
        }
    });

    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('transkrip-container').innerText = originalText;
        document.getElementById('language-select').value = 'id';
    });
</script>
<script>
    document.getElementById('summary-btn').addEventListener('click', async function () {
        const text = document.getElementById('transkrip-container').innerText.trim();
        const loading = document.getElementById('loading-summary');
        const summaryContainer = document.getElementById('summary-container');
        const summaryText = document.getElementById('summary-text');

        if (!text) return alert("Tidak ada teks untuk dirangkum.");

        loading.style.display = 'block';
        summaryContainer.style.display = 'none';

        try {
            // Menggunakan API summarization dari HuggingFace (model distilbart)
            const response = await fetch("https://api-inference.huggingface.co/models/google/pegasus-xsum", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer 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"
                },
                body: JSON.stringify({ inputs: text.substring(0, 3000) })
            });


            const data = await response.json();
            let summary = data[0]?.summary_text || "(Tidak dapat membuat kesimpulan)";

            summaryText.innerText = summary;
            summaryContainer.style.display = 'block';
        } catch (err) {
            console.error(err);
            alert("Gagal membuat kesimpulan. Coba lagi nanti.");
        } finally {
            loading.style.display = 'none';
        }
    });
</script>