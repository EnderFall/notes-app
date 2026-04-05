<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Form</h3>
                <p class="text-subtitle text-muted">Edit menu/form yang ada</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('hak_akses') ?>">Hak Akses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Form</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Menu/Form</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="<?= base_url('hak_akses/aksi_edit_form') ?>" method="post">
                    <input type="hidden" name="id_form" value="<?= $form['id_form'] ?>">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="deskripsi">Deskripsi Form</label>
                                <div class="form-group">
                                    <input type="text" id="deskripsi" class="form-control" name="deskripsi" placeholder="Contoh: Data User" value="<?= esc($form['deskripsi']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="route">Route</label>
                                <div class="form-group">
                                    <input type="text" id="route" class="form-control" name="route" placeholder="Contoh: user" value="<?= esc($form['route']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="icon">Icon</label>
                                <div class="form-group">
                                    <input type="text" id="icon" class="form-control" name="icon" placeholder="Contoh: bi bi-person" value="<?= esc($form['icon']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_form">Jenis Form</label>
                                <div class="form-group">
                                    <select id="jenis_form" name="jenis_form" class="form-select" required>
                                        <option value="">Pilih Jenis Form</option>
                                        <option value="table" <?= $form['jenis_form'] == 'table' ? 'selected' : '' ?>>Table</option>
                                        <option value="input" <?= $form['jenis_form'] == 'input' ? 'selected' : '' ?>>Input</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                <a href="<?= base_url('hak_akses') ?>" class="btn btn-light-secondary me-1 mb-1">Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
