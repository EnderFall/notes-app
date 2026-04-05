<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Hak Akses</h3>
                <p class="text-subtitle text-muted">Kelola hak akses pengguna</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="hakAksesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="forms-tab" data-bs-toggle="tab" data-bs-target="#forms" type="button" role="tab" aria-controls="forms" aria-selected="true">
                            <i class="bi bi-file-earmark-plus"></i> Tambah Menu/Form
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab" aria-controls="permissions" aria-selected="false">
                            <i class="bi bi-shield-check"></i> Buat Level & Permission
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assign-tab" data-bs-toggle="tab" data-bs-target="#assign" type="button" role="tab" aria-controls="assign" aria-selected="false">
                            <i class="bi bi-person-check"></i> Assign Level ke User
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted" type="button" role="tab" aria-controls="deleted" aria-selected="false">
                            <i class="bi bi-trash"></i> Deleted Items
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="hakAksesTabContent">
                    <!-- Tab 1: Forms -->
                    <div class="tab-pane fade show active" id="forms" role="tabpanel" aria-labelledby="forms-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Daftar Menu/Form</h5>
                            <a href="<?= base_url('hak_akses/tambah_form') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Form
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-forms">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Icon</th>
                                        <th>Deskripsi</th>
                                        <th>Route</th>
                                        <th>Jenis Form</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($forms as $form): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><i class="<?= esc($form['icon']) ?>"></i></td>
                                        <td><?= esc($form['deskripsi']) ?></td>
                                        <td><?= esc($form['route']) ?></td>
                                        <td><?= esc($form['jenis_form']) ?></td>
                                        <td>
                                            <a href="<?= base_url('hak_akses/edit_form/' . $form['id_form']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDeleteForm(<?= $form['id_form'] ?>, '<?= esc($form['deskripsi']) ?>')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Permissions -->
                    <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Level & Permission</h5>
                            <a href="<?= base_url('hak_akses/tambah_level_permission') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Buat Level Baru
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-levels">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Level</th>
                                        <th>Jumlah Permission</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($levels as $level): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($level['nama_level']) ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <!-- Count permissions for this level -->
                                                <?php
                                                $M_group_form = new \App\Models\M_group_form();
                                                echo count($M_group_form->getPermissionsByLevel($level['id_level']));
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewPermissions(<?= $level['id_level'] ?>)">
                                                <i class="bi bi-eye"></i> Lihat Permission
                                            </button>
                                            <a href="<?= base_url('hak_akses/edit_level_permission/' . $level['id_level']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDeleteLevel(<?= $level['id_level'] ?>, '<?= esc($level['nama_level']) ?>')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Assign Users -->
                    <div class="tab-pane fade" id="assign" role="tabpanel" aria-labelledby="assign-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Assign Level ke User</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                                <i class="bi bi-plus-circle"></i> Assign Level
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-users">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Email</th>
                                        <th>Level Saat Ini</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($user['username']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <?php
                                            $current_level = array_filter($user_levels, function($ul) use ($user) {
                                                return $ul['id_user'] == $user['id_user'];
                                            });
                                            $level_name = 'Belum ada level';
                                            if (!empty($current_level)) {
                                                $level_id = reset($current_level)['id_level'];
                                                $level_data = array_filter($levels, function($l) use ($level_id) {
                                                    return $l['id_level'] == $level_id;
                                                });
                                                if (!empty($level_data)) {
                                                    $level_name = reset($level_data)['nama_level'];
                                                }
                                            }
                                            echo esc($level_name);
                                            ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editUserLevel(<?= $user['id_user'] ?>)">
                                                <i class="bi bi-pencil"></i> Edit Level
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    <!-- Tab 4: Deleted Items -->
                    <div class="tab-pane fade" id="deleted" role="tabpanel" aria-labelledby="deleted-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Deleted Items</h5>
                            
                        </div>

                        <!-- Sub-tabs for Deleted Items -->
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
                            <!-- Sub-tab 1: Deleted Forms -->
                            <div class="tab-pane fade show active" id="deleted-forms" role="tabpanel" aria-labelledby="deleted-forms-tab">
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

                            <!-- Sub-tab 2: Deleted Levels -->
                            <div class="tab-pane fade" id="deleted-levels" role="tabpanel" aria-labelledby="deleted-levels-tab">
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

                            <!-- Sub-tab 3: Deleted User Levels -->
                            <div class="tab-pane fade" id="deleted-user-levels" role="tabpanel" aria-labelledby="deleted-user-levels-tab">
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
            </div>
        </div>
    </section>
</div>

<!-- Modal for Assign Level -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Level ke User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hak_akses/aksi_assign_user_level') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_user" class="form-label">Pilih User</label>
                        <select name="id_user" id="id_user" class="form-select" required>
                            <option value="">Pilih User</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id_user'] ?>"><?= esc($user['username']) ?> (<?= esc($user['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_level" class="form-label">Pilih Level</label>
                        <select name="id_level" id="id_level" class="form-select" required>
                            <option value="">Pilih Level</option>
                            <?php foreach ($levels as $level): ?>
                            <option value="<?= $level['id_level'] ?>"><?= esc($level['nama_level']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Assign Level</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-forms').DataTable();
    $('#table-levels').DataTable();
    $('#table-users').DataTable();
    $('#table-deleted-forms').DataTable();
    $('#table-deleted-levels').DataTable();
    $('#table-deleted-user-levels').DataTable();
});

function viewPermissions(levelId) {
    // AJAX call to get permissions
    $.get('<?= base_url('hak_akses/get_permissions_by_level') ?>/' + levelId, function(data) {
        let modalContent = `<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permissionsModalLabel">Permissions for Level: ${data.level_name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${data.permissions.length > 0 ? `
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Form/Menu</th>
                                            <th>Can Read</th>
                                            <th>Can Create</th>
                                            <th>Can Approve</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.permissions.map(perm => `
                                            <tr>
                                                <td>${perm.deskripsi}</td>
                                                <td><span class="badge ${perm.can_read == 1 ? 'bg-success' : 'bg-danger'}">${perm.can_read == 1 ? 'Yes' : 'No'}</span></td>
                                                <td><span class="badge ${perm.can_create == 1 ? 'bg-success' : 'bg-danger'}">${perm.can_create == 1 ? 'Yes' : 'No'}</span></td>
                                                <td><span class="badge ${perm.can_approve == 1 ? 'bg-success' : 'bg-danger'}">${perm.can_approve == 1 ? 'Yes' : 'No'}</span></td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        ` : '<p class="text-muted">No permissions assigned to this level.</p>'}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>`;

        // Remove existing modal if present
        $('#permissionsModal').remove();
        // Append new modal to body
        $('body').append(modalContent);
        // Show the modal
        $('#permissionsModal').modal('show');
    });
}

function editUserLevel(userId) {
    // Get current level and populate modal
    $.get('<?= base_url('hak_akses/get_user_level') ?>/' + userId, function(data) {
        $('#id_user').val(userId);
        $('#id_level').val(data ? data.id_level : '');
        $('#assignModal').modal('show');
    });
}

function confirmDeleteForm(formId, formName) {
    if (confirm('Apakah Anda yakin ingin menghapus form "' + formName + '"?')) {
        window.location.href = '<?= base_url('hak_akses/aksi_delete_form') ?>/' + formId;
    }
}

function confirmDeleteLevel(levelId, levelName) {
    if (confirm('Apakah Anda yakin ingin menghapus level "' + levelName + '"?')) {
        window.location.href = '<?= base_url('hak_akses/aksi_delete_level') ?>/' + levelId;
    }
}

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
