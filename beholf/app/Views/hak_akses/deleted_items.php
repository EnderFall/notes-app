<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Deleted Items - Hak Akses</h3>
                <p class="text-subtitle text-muted">Kelola item yang telah dihapus</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="deletedItemsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="deleted-forms-tab" data-bs-toggle="tab" data-bs-target="#deleted-forms" type="button" role="tab" aria-controls="deleted-forms" aria-selected="true">
                            <i class="bi bi-file-earmark-x"></i> Deleted Forms
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted-levels-tab" data-bs-toggle="tab" data-bs-target="#deleted-levels" type="button" role="tab" aria-controls="deleted-levels" aria-selected="false">
                            <i class="bi bi-shield-x"></i> Deleted Levels
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted-user-levels-tab" data-bs-toggle="tab" data-bs-target="#deleted-user-levels" type="button" role="tab" aria-controls="deleted-user-levels" aria-selected="false">
                            <i class="bi bi-person-x"></i> Deleted User Levels
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="deletedItemsTabContent">
                    <!-- Tab 1: Deleted Forms -->
                    <div class="tab-pane fade show active" id="deleted-forms" role="tabpanel" aria-labelledby="deleted-forms-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Deleted Forms</h5>
                            <a href="<?= base_url('hak_akses') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Hak Akses
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-deleted-forms">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Route</th>
                                        <th>Jenis Form</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($deleted_forms)): ?>
                                        <?php $no = 1; foreach ($deleted_forms as $form): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($form['deskripsi']) ?></td>
                                            <td><?= esc($form['route']) ?></td>
                                            <td><?= esc($form['jenis_form']) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="confirmRestoreForm(<?= $form['id_form'] ?>, '<?= esc($form['deskripsi']) ?>')">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmPermanentDeleteForm(<?= $form['id_form'] ?>, '<?= esc($form['deskripsi']) ?>')">
                                                    <i class="bi bi-trash"></i> Delete Permanently
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No deleted forms found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Deleted Levels -->
                    <div class="tab-pane fade" id="deleted-levels" role="tabpanel" aria-labelledby="deleted-levels-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Deleted Levels</h5>
                            <a href="<?= base_url('hak_akses') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Hak Akses
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-deleted-levels">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Level</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($deleted_levels)): ?>
                                        <?php $no = 1; foreach ($deleted_levels as $level): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($level['nama_level']) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="confirmRestoreLevel(<?= $level['id_level'] ?>, '<?= esc($level['nama_level']) ?>')">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmPermanentDeleteLevel(<?= $level['id_level'] ?>, '<?= esc($level['nama_level']) ?>')">
                                                    <i class="bi bi-trash"></i> Delete Permanently
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No deleted levels found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Deleted User Levels -->
                    <div class="tab-pane fade" id="deleted-user-levels" role="tabpanel" aria-labelledby="deleted-user-levels-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Deleted User Level Assignments</h5>
                            <a href="<?= base_url('hak_akses') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Hak Akses
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-deleted-user-levels">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Level</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($deleted_user_levels)): ?>
                                        <?php $no = 1; foreach ($deleted_user_levels as $user_level): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php
                                                $user = array_filter($users, function($u) use ($user_level) {
                                                    return $u['id_user'] == $user_level['id_user'];
                                                });
                                                $user_name = 'Unknown User';
                                                if (!empty($user)) {
                                                    $user_data = reset($user);
                                                    $user_name = esc($user_data['username']) . ' (' . esc($user_data['email']) . ')';
                                                }
                                                echo $user_name;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $level = array_filter($levels, function($l) use ($user_level) {
                                                    return $l['id_level'] == $user_level['id_level'];
                                                });
                                                $level_name = 'Unknown Level';
                                                if (!empty($level)) {
                                                    $level_data = reset($level);
                                                    $level_name = esc($level_data['nama_level']);
                                                }
                                                echo $level_name;
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="confirmRestoreUserLevel(<?= $user_level['id_hak_akses'] ?>, '<?= $user_name ?> - <?= $level_name ?>')">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmPermanentDeleteUserLevel(<?= $user_level['id_hak_akses'] ?>, '<?= $user_name ?> - <?= $level_name ?>')">
                                                    <i class="bi bi-trash"></i> Delete Permanently
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No deleted user level assignments found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#table-deleted-forms').DataTable();
    $('#table-deleted-levels').DataTable();
    $('#table-deleted-user-levels').DataTable();
});

function confirmRestoreForm(formId, formName) {
    if (confirm('Apakah Anda yakin ingin merestore form "' + formName + '"?')) {
        window.location.href = '<?= base_url('hak_akses/aksi_restore_form') ?>/' + formId;
    }
}

function confirmRestoreLevel(levelId, levelName) {
    if (confirm('Apakah Anda yakin ingin merestore level "' + levelName + '"?')) {
        window.location.href = '<?= base_url('hak_akses/aksi_restore_level') ?>/' + levelId;
    }
}

function confirmRestoreUserLevel(idHakAkses, assignmentName) {
    if (confirm('Apakah Anda yakin ingin merestore assignment "' + assignmentName + '"?')) {
        window.location.href = '<?= base_url('hak_akses/aksi_restore_user_level') ?>/' + idHakAkses;
    }
}

function confirmPermanentDeleteForm(formId, formName) {
    if (confirm('Apakah Anda yakin ingin menghapus permanen form "' + formName + '"? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = '<?= base_url('hak_akses/aksi_permanent_delete_form') ?>/' + formId;
    }
}

function confirmPermanentDeleteLevel(levelId, levelName) {
    if (confirm('Apakah Anda yakin ingin menghapus permanen level "' + levelName + '"? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = '<?= base_url('hak_akses/aksi_permanent_delete_level') ?>/' + levelId;
    }
}

function confirmPermanentDeleteUserLevel(idHakAkses, assignmentName) {
    if (confirm('Apakah Anda yakin ingin menghapus permanen assignment "' + assignmentName + '"? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = '<?= base_url('hak_akses/aksi_permanent_delete_user_level') ?>/' + idHakAkses;
    }
}
</script>
