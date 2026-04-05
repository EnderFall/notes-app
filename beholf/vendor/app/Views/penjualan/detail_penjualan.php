<div class="container">
  <div class="card">
    <div class="card-header text-center">
      <h4><?= $title?> </h4>
    </div>

        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Jual</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= esc($penjualan->nama_pelanggan) ?></td>
                        <td><?= esc($penjualan->tanggal_jual) ?></td>
                        <td><?= esc($penjualan->created_at) ?></td>
                        <td><?= esc($penjualan->updated_at ?? '-') ?></td>
                        <td><?= esc($penjualan->deleted_at ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary shadow">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>