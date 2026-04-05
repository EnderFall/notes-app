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
                <form class="form-horizontal form-label-left" novalidate action="<?= base_url('rapat/simpan_rapat') ?>"
                  method="post" enctype="multipart/form-data">
                  <form class="form form-horizontal">
                    <div class="form-body">

                      <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" id="judul" class="form-control" name="judul" required>
                          </div>

                          <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="datetime-local" id="tanggal" class="form-control" name="tanggal" required>
                          </div>
                          <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" id="lokasi" class="form-control" name="lokasi" required>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                          </div>
                          <div class="mb-3">
                            <label for="divisi" class="form-label">Divisi</label>
                            <select name="divisi" id="divisi" class="form-select" required>
                              <option value="" disabled selected>Pilih Divisi</option>
                              <?php foreach ($divisi as $l): ?>
                                <option value="<?= $l->id_divisi ?>"><?= $l->nama_divisi ?></option>
                              <?php endforeach; ?>
                            </select>
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