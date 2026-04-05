<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?=$title?></h3>
                    <p class="text-subtitle text-muted">Anda dapat melihat <?=$title?> di bawah</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?=base_url('admin')?>">Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

        <section class="section">
            <div class="card p-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if (!empty($deleted_penjualan)): ?>    
                            <table class="table table-striped" id="table-hpspenjualan">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mobil</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Tanggal Jual</th>
                                    <th>Harga Jual</th>
                                    <th>Catatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1; foreach ($deleted_penjualan as $value): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalMobil<?= $value->id_penjualan ?>">
                                            Lihat Mobil
                                        </button>

                                        <div class="modal fade" id="modalMobil<?= $value->id_penjualan ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Mobil</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama:</strong> <?= $value->nama_mobil ?></p>
                                                        <p><strong>Kode:</strong> <?= $value->kode_mobil ?></p>
                                                        <p><strong>Plat:</strong> <?= $value->plat_mobil ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $value->nama_pelanggan ?></td>
                                    <td><?= date('d/m/Y', strtotime($value->tanggal_jual)) ?></td>
                                    <td>Rp <?= number_format($value->harga_jual, 0, ',', '.') ?></td>

                                    <td>
                                        <?php if ($value->catatan): ?>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalCatatan<?= $value->id_penjualan ?>">
                                                Lihat
                                            </button>

                                            <div class="modal fade" id="modalCatatan<?= $value->id_penjualan ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Catatan Penjualan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?= nl2br($value->catatan) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="<?= base_url('penjualan/restore_penjualan/' . $value->id_penjualan) ?>" class="btn btn-success my-1"><i class="fa fa-undo"></i></a>

                                        <a href="<?= base_url('penjualan/hapus_penjualan/' . $value->id_penjualan) ?>" class="btn btn-danger my-1" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?')"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Tidak ada <?=$title?>.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

    <div class="position-fixed bottom-3 end-3">
        <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary shadow">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<script>
	$(document).ready(function() {
		$('#table-hpspenjualan').DataTable({
		});
	});
</script>