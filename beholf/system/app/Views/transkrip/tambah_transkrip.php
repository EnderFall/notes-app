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
                <a href="<?= base_url('admin') ?>">Dashboard</a>
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
                <form action="<?= base_url('transkrip/upload') ?>" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="audio" class="form-label">Upload Audio</label>
                    <input type="file" class="form-control" id="audio" name="audio" accept="audio/*" required>
                  </div>

                  <div class="mb-3">
                    <label for="judul" class="form-label">Judul Transkrip</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                  </div>

                  <button type="submit" class="btn btn-primary">Upload & Transkrip</button>
                </form>

                <hr>

                <!-- Text-to-Speech Demo -->
                <h5 class="mt-4">Coba Text-to-Speech</h5>
                <div class="mb-3">
                  <textarea id="ttsText" class="form-control" rows="3">Halo, ini contoh text to speech!</textarea>
                </div>
                <button type="button" class="btn btn-success" onclick="speak()">Preview</button>
              </div>

              <script>
                function speak() {
                  const text = document.getElementById("ttsText").value;
                  const utterance = new SpeechSynthesisUtterance(text);
                  utterance.lang = "id-ID"; // bahasa Indonesia, bisa ganti "en-US"
                  speechSynthesis.speak(utterance);
                }
              </script>

            </div>
          </div>
        </div>