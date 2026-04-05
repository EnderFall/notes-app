<style>
/* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
@media (max-width: 768px) {
  #translate-container td,
  #translate-container th {
    white-space: nowrap;
    font-size: 13px;
    padding: 6px 8px;
  }

  .card-header .d-flex {
    flex-direction: column;
    gap: 10px;
  }

  .btn {
    font-size: 14px;
    padding: 6px 12px;
  }

  .breadcrumb {
    font-size: 13px;
  }
}
@media (max-width: 768px) {
  .container-fluid {
    --bs-gutter-x: 0.25rem; /* smaller padding than default */
    padding-left: 3px;
    padding-right: 0.25rem;
  }

  .card {
    margin-left: 0;
    margin-right: 0;
  }

  .card-body {
    padding: 0.5rem; /* smaller inner spacing */
  }
}

.translate-container {
    max-width: 800px;
    margin: 0 auto;
}

.translate-box {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    background-color: #f9f9f9;
}

.language-select {
    margin-bottom: 15px;
}

.textarea-container {
    display: flex;
    gap: 20px;
}

.textarea-wrapper {
    flex: 1;
}

textarea {
    width: 100%;
    height: 150px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
}

.swap-btn {
    align-self: center;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.translate-btn {
    background-color: #4285f4;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 15px;
}

.translate-btn:hover {
    background-color: #3367d6;
}
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?=$title?></h3>
                        <p class="text-subtitle text-muted">Translate text between languages</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?=base_url('admin')?>">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex gap-2">
                            <h5>Language Translator</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="translate-container">
                            <div class="translate-box">
                                <div class="language-select">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select id="source-lang" class="form-select">
                                                <option value="auto">Detect Language</option>
                                                <option value="en">English</option>
                                                <option value="id">Indonesian</option>
                                                <option value="es">Spanish</option>
                                                <option value="fr">French</option>
                                                <option value="de">German</option>
                                                <option value="it">Italian</option>
                                                <option value="pt">Portuguese</option>
                                                <option value="ru">Russian</option>
                                                <option value="ja">Japanese</option>
                                                <option value="ko">Korean</option>
                                                <option value="zh">Chinese</option>
                                                <option value="ar">Arabic</option>
                                                <option value="hi">Hindi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button id="swap-btn" class="swap-btn" title="Swap languages">
                                                <i class="bi bi-arrow-left-right"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-5">
                                            <select id="target-lang" class="form-select">
                                                <option value="en">English</option>
                                                <option value="id" selected>Indonesian</option>
                                                <option value="es">Spanish</option>
                                                <option value="fr">French</option>
                                                <option value="de">German</option>
                                                <option value="it">Italian</option>
                                                <option value="pt">Portuguese</option>
                                                <option value="ru">Russian</option>
                                                <option value="ja">Japanese</option>
                                                <option value="ko">Korean</option>
                                                <option value="zh">Chinese</option>
                                                <option value="ar">Arabic</option>
                                                <option value="hi">Hindi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="textarea-container">
                                    <div class="textarea-wrapper">
                                        <textarea id="source-text" placeholder="Enter text to translate..."></textarea>
                                    </div>
                                    <div class="textarea-wrapper">
                                        <textarea id="target-text" placeholder="Translation will appear here..." readonly></textarea>
                                    </div>
                                </div>

                                <button id="translate-btn" class="translate-btn">
                                    <i class="bi bi-translate"></i> Translate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#translate-btn').click(function() {
        const sourceText = $('#source-text').val().trim();
        const sourceLang = $('#source-lang').val();
        const targetLang = $('#target-lang').val();

        if (!sourceText) {
            alert('Please enter text to translate.');
            return;
        }

        // Show loading
        $('#target-text').val('Translating...');
        $('#translate-btn').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Translating...');

        // Use Google Translate API (unofficial, may require CORS proxy in production)
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${sourceLang}&tl=${targetLang}&dt=t&q=${encodeURIComponent(sourceText)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let translatedText = '';
                if (data && data[0]) {
                    data[0].forEach(item => {
                        if (item[0]) {
                            translatedText += item[0];
                        }
                    });
                }
                $('#target-text').val(translatedText);
            })
            .catch(error => {
                console.error('Translation error:', error);
                $('#target-text').val('Translation failed. Please try again.');
            })
            .finally(() => {
                $('#translate-btn').prop('disabled', false).html('<i class="bi bi-translate"></i> Translate');
            });
    });

    $('#swap-btn').click(function() {
        const sourceLang = $('#source-lang').val();
        const targetLang = $('#target-lang').val();
        const sourceText = $('#source-text').val();
        const targetText = $('#target-text').val();

        $('#source-lang').val(targetLang);
        $('#target-lang').val(sourceLang === 'auto' ? 'en' : sourceLang);
        $('#source-text').val(targetText);
        $('#target-text').val(sourceText);
    });

    // Auto-translate on language change if there's text
    $('#source-lang, #target-lang').change(function() {
        const sourceText = $('#source-text').val().trim();
        if (sourceText) {
            $('#translate-btn').click();
        }
    });
});
</script>
