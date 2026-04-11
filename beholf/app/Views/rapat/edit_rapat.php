<?php
$tanggal = isset($data->tanggal) ? date('Y-m-d\TH:i', strtotime($data->tanggal)) : '';
?>
<div id="main-content">
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3>Input <?= $title ?></h3>
          <p class="text-subtitle text-muted">
            Silakan Masukkan <?= $title ?>
          </p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Admin') ?>">Dashboard</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Input <?= $title ?>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section id="basic-horizontal-layouts">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body">
                <form class="form form-horizontal"
                  action="<?= base_url('rapat/update/' . $rapat->id_note) ?>" method="post" enctype="multipart/form-data">
                    <div class="form-body">

                      <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" id="judul" class="form-control" name="judul" value="<?= $rapat->judul ?>"
                              required>
                          </div>

                          <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="datetime-local" id="tanggal" class="form-control" name="tanggal"
                              value="<?= $rapat->tanggal ?>" required>
                          </div>

                          <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" id="lokasi" class="form-control" name="lokasi"
                              value="<?= $rapat->lokasi ?>" required>
                          </div>

                        </div>

                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control"
                              rows="3"><?= $rapat->keterangan ?></textarea>
                          </div>
                          <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select" required>
                              <option value="" disabled>Select Category</option>
                              <?php foreach ($divisi as $l): ?>
                                <option value="<?= is_object($l) ? $l->id_category : $l['id_category'] ?>" <?= (is_object($l) ? $l->id_category : $l['id_category']) == $rapat->category ? 'selected' : '' ?>>
                                  <?= is_object($l) ? $l->name : $l['name'] ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="document_upload" class="form-label">Import from Document</label>
                            <input type="file" id="document_upload" class="form-control" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Upload PDF/Word to append text to content</small>
                            <div id="uploadStatus" class="mt-2"></div>
                          </div>

                        </div>
                      </div>

                      <!-- Full Width Note Content (Quill Editor) -->
                      <div class="row mt-3">
                        <div class="col-md-12">
                          <div class="mb-3">
                            <label class="form-label">Note Content</label>
                            <link rel="stylesheet" href="<?= base_url('assets/extensions/quill/quill.snow.css') ?>">
                            <div id="quill-editor" style="min-height:400px; font-size:14px;"></div>
                            <input type="hidden" id="content" name="content" value="<?= htmlspecialchars($rapat->content ?? '', ENT_QUOTES) ?>">
                            <small class="text-muted">Use the toolbar to format your note. Content is saved as rich HTML.</small>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-1 mb-1">
                          Submit
                        </button>
                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                          Reset
                        </button>
                      </div>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/quill/quill.min.js') ?>"></script>
<script>
$(document).ready(function () {
    // ── Quill Rich Text Editor ──────────────────────────────────────────
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write your note content here...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Load existing content into Quill
    const existingContent = $('#content').val();
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Sync Quill HTML → hidden input before form submit
    $('form').on('submit', function () {
        $('#content').val(quill.root.innerHTML);
    });

    // ── Document Import ──────────────────────────────────────────────────
    $('#document_upload').on('change', function () {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('document', file);

        const statusDiv = $('#uploadStatus');
        statusDiv.html('<div class="spinner-border spinner-border-sm me-2" role="status"></div><span class="text-info">Extracting text from document...</span>');

        $.ajax({
            url: '<?= base_url('rapat/extract_document_text') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    const current = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
                    quill.root.innerHTML = current + (current ? '<br>' : '') + '<p>' + response.text.replace(/\n/g, '<br>') + '</p>';
                    statusDiv.html('<div class="alert alert-success alert-dismissible fade show">✓ Text extracted! (' + response.length + ' characters)<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    $('#document_upload').val('');
                } else {
                    statusDiv.html('<div class="alert alert-danger alert-dismissible fade show">✗ Error: ' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }
            },
            error: function () {
                statusDiv.html('<div class="alert alert-danger alert-dismissible fade show">✗ Failed to extract text.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        });
    });
});
</script>