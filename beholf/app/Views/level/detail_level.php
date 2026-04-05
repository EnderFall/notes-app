<div class="container">
  <div class="card">
    <div class="card-header text-center">
      <h4><?= $title?> </h4>
    </div>

        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nama Level</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= esc($level->nama_level) ?></td>
                        <td><?= esc($level->created_at) ?></td>
                        <td><?= esc($level->updated_at ?? '-') ?></td>
                        <td><?= esc($level->deleted_at ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="<?= base_url('level') ?>" class="btn btn-secondary shadow">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>