<div class="container">
  <div class="card">
    <div class="card-header text-center">
      <h4><?= $title?> </h4>
    </div>

        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nama user</th>
                        <th>No HP</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= esc($user->username) ?></td>
                        <td><?= esc($user->nomor_hp) ?></td>
                        <td><?= esc($user->created_at) ?></td>
                        <td><?= esc($user->updated_at ?? '-') ?></td>
                        <td><?= esc($user->deleted_at ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="<?= base_url('user') ?>" class="btn btn-secondary shadow">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>