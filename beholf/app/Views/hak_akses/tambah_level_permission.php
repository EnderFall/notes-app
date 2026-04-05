<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Level & Assign Permission</h3>
                <p class="text-subtitle text-muted">Buat level baru dan tetapkan permission untuk form/menu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('hak_akses') ?>">Hak Akses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Level & Permission</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Buat Level Baru</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="<?= base_url('hak_akses/aksi_tambah_level') ?>" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama_level">Nama Level</label>
                                <div class="form-group">
                                    <input type="text" id="nama_level" class="form-control" name="nama_level" placeholder="Contoh: Admin, Manager, Staff" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="mt-3">Assign Permissions</h5>
                                <p class="text-muted">Pilih form/menu yang dapat diakses oleh level ini</p>

                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Form/Menu</th>
                                                <th>Can Read</th>
                                                <th>Can Create</th>
                                                <th>Can Approve</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($forms as $form): ?>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input master-checkbox" type="checkbox" id="select_<?= $form['id_form'] ?>" data-form-id="<?= $form['id_form'] ?>" data-jenis="<?= $form['jenis_form'] ?>">
                                                        <label class="form-check-label" for="select_<?= $form['id_form'] ?>"></label>
                                                    </div>
                                                    <div id="hidden_<?= $form['id_form'] ?>"></div>
                                                </td>
                                                <td>
                                                    <strong><?= esc($form['deskripsi']) ?></strong>
                                                    <br><small class="text-muted">Route: <?= esc($form['route']) ?> | Type: <?= esc($form['jenis_form']) ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($form['jenis_form'] == 'table'): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input sub-checkbox" type="checkbox" name="permissions[<?= $form['id_form'] ?>][can_read]" id="read_<?= $form['id_form'] ?>" value="1" data-form-id="<?= $form['id_form'] ?>">
                                                        <label class="form-check-label" for="read_<?= $form['id_form'] ?>"></label>
                                                    </div>
                                                    <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($form['jenis_form'] == 'table'): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input sub-checkbox" type="checkbox" name="permissions[<?= $form['id_form'] ?>][can_create]" id="create_<?= $form['id_form'] ?>" value="1" data-form-id="<?= $form['id_form'] ?>">
                                                        <label class="form-check-label" for="create_<?= $form['id_form'] ?>"></label>
                                                    </div>
                                                    <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($form['jenis_form'] == 'table'): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input sub-checkbox" type="checkbox" name="permissions[<?= $form['id_form'] ?>][can_approve]" id="approve_<?= $form['id_form'] ?>" value="1" data-form-id="<?= $form['id_form'] ?>">
                                                        <label class="form-check-label" for="approve_<?= $form['id_form'] ?>"></label>
                                                    </div>
                                                    <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.querySelectorAll('.master-checkbox').forEach(function(master) {
                                            master.addEventListener('change', function() {
                                                const formId = this.getAttribute('data-form-id');
                                                const jenis = this.getAttribute('data-jenis');
                                                const hiddenDiv = document.getElementById('hidden_' + formId);
                                                if (jenis === 'table') {
                                                    const subCheckboxes = document.querySelectorAll(`.sub-checkbox[data-form-id="${formId}"]`);
                                                    subCheckboxes.forEach(function(sub) {
                                                        sub.checked = master.checked;
                                                    });
                                                } else {
                                                    // For input forms, add hidden inputs for permissions
                                                    if (master.checked) {
                                                        hiddenDiv.innerHTML = '<input type="hidden" name="permissions[' + formId + '][can_read]" value="1"><input type="hidden" name="permissions[' + formId + '][can_create]" value="1"><input type="hidden" name="permissions[' + formId + '][can_approve]" value="1">';
                                                    } else {
                                                        hiddenDiv.innerHTML = '';
                                                    }
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Buat Level & Simpan Permission</button>
                                <a href="<?= base_url('hak_akses') ?>" class="btn btn-light-secondary me-1 mb-1">Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
