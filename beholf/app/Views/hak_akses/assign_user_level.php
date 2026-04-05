<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Assign Level ke User</h3>
                <p class="text-subtitle text-muted">Tetapkan level akses untuk setiap pengguna</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('hak_akses') ?>">Hak Akses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Level</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Assign Level ke User</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="<?= base_url('hak_akses/aksi_assign_user_level') ?>" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="id_user">Pilih User</label>
                                <div class="form-group">
                                    <select id="id_user" name="id_user" class="form-select" required>
                                        <option value="">Pilih User</option>
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"><?= esc($user['username']) ?> (<?= esc($user['email']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="id_level">Pilih Level</label>
                                <div class="form-group">
                                    <select id="id_level" name="id_level" class="form-select" required>
                                        <option value="">Pilih Level</option>
                                        <?php foreach ($levels as $level): ?>
                                        <option value="<?= $level['id_level'] ?>"><?= esc($level['nama_level']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Assign Level</button>
                                <a href="<?= base_url('hak_akses') ?>" class="btn btn-light-secondary me-1 mb-1">Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Assignments Table -->
        <div class="card mt-3">
            <div class="card-header">
                <h4 class="card-title">Daftar User & Level Saat Ini</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-user-levels">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Level</th>
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
                                    // Find current level for this user
                                    $current_level = null;
                                    foreach ($user_levels ?? [] as $ul) {
                                        if ($ul['id_user'] == $user['id_user']) {
                                            $current_level = $ul;
                                            break;
                                        }
                                    }

                                    if ($current_level) {
                                        // Find level name
                                        foreach ($levels as $level) {
                                            if ($level['id_level'] == $current_level['id_level']) {
                                                echo esc($level['nama_level']);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo '<span class="badge bg-warning">Belum ada level</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editAssignment(<?= $user['id_user'] ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#table-user-levels').DataTable();
});

function editAssignment(userId) {
    // Get current level for user and populate form
    $.get('<?= base_url('hak_akses/get_user_level') ?>/' + userId, function(data) {
        $('#id_user').val(userId);
        $('#id_level').val(data ? data.id_level : '');
        // Scroll to form
        $('html, body').animate({
            scrollTop: $('.card').first().offset().top
        }, 500);
    });
}
</script>
