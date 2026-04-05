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
                <form class="form-horizontal form-label-left" novalidate
                  action="<?= base_url('user/update/' . $user->id_user) ?>" method="post" enctype="multipart/form-data">
                  <form class="form form-horizontal">
                    <div class="form-body">

                      <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="nama" class="form-label">Username</label>
                            <input type="text" id="nama" class="form-control" name="nama" value="<?= $user->username ?>"
                              required>
                          </div>

                          <div class="mb-3">
                            <label for="nohp" class="form-label">Nomor HP</label>
                            <input type="number" id="nohp" class="form-control" name="nohp"
                              value="<?= $user->nomor_hp ?>" required>
                          </div>

                          <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                          </div>

                        </div>

                        <div class="col-md-6">
                          <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <textarea name="email" id="email" class="form-control"
                              rows="3"><?= $user->email ?></textarea>
                          </div>
                          <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select name="level" id="level" class="form-select" required>
                              <option value="" disabled>Pilih Level</option>
                              <?php foreach ($level as $l): ?>
                                <option value="<?= $l->id_level ?>" <?= ($l->id_level == $user->level) ? 'selected' : '' ?>>
                                  <?= $l->nama_level ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="divisi" class="form-label">Divisi</label>
                            <select name="divisi" id="divisi" class="form-select" required>
                              <option value="" disabled>Pilih Divisi</option>
                              <?php foreach ($divisi as $l): ?>
                                <option value="<?= $l->id_divisi ?>" <?= ($l->id_divisi == $user->divisi) ? 'selected' : '' ?>>
                                  <?= $l->nama_divisi ?>
                                </option>
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