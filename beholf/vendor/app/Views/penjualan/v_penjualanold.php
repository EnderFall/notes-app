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
							<li class="breadcrumb-item"><a href="<?=base_url('Admin')?>">Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <a href="<?php echo base_url('penjualan/tambah_penjualan/')?>"><button class="btn btn-primary mt-2"><i class="fa-solid fa-plus"></i>
                            Tambah</button></a>

                        <a href="<?= base_url('penjualan/dihapus_penjualan') ?>">
                           <button class="btn btn-secondary mt-2">Data penjualan yang Dihapus</button>
                        </a>
                    </div>
                </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-penjualan">
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
                            <?php $no = 1; foreach ($penjualan as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalMobil<?= $p->id_penjualan ?>">
                                            Lihat Mobil
                                        </button>

                                        <div class="modal fade" id="modalMobil<?= $p->id_penjualan ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Mobil</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama:</strong> <?= $p->nama_mobil ?></p>
                                                        <p><strong>Kode:</strong> <?= $p->kode_mobil ?></p>
                                                        <p><strong>Plat:</strong> <?= $p->plat_mobil ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td><?= $p->nama_pelanggan ?></td>
                                    <td><?= date('d/m/Y', strtotime($p->tanggal_jual)) ?></td>
                                    <td>Rp <?= number_format($p->harga_jual, 0, ',', '.') ?></td>

                                    <td>
                                        <?php if ($p->catatan): ?>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalCatatan<?= $p->id_penjualan ?>">
                                                Lihat
                                            </button>

                                            <div class="modal fade" id="modalCatatan<?= $p->id_penjualan ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Catatan Penjualan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?= nl2br($p->catatan) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item text-info" href="<?= base_url('penjualan/detail_penjualan/' . $p->id_penjualan) ?>">
                                                        <i class="fa-solid fa-info me-1"></i> Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-warning" href="<?= base_url('penjualan/edit_penjualan/'. $p->id_penjualan) ?>">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="<?= base_url('penjualan/delete_penjualan/'. $p->id_penjualan) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<script>
	$(document).ready(function() {
		$('#table-penjualan').DataTable({
		});
	});
</script>