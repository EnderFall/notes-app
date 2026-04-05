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
                    <pre></pre>
                    <form action="<?= base_url('penjualan') ?>" method="get" class="ms-auto d-flex align-items-center gap-2">
                        <select name="bulan" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            <?php
                                $bulanDipilih = @$_GET['bulan'];
                                for ($i = 1; $i <= 12; $i++) {
                                    $selected = ($i == $bulanDipilih) ? 'selected' : '';
                                    echo '<option value="' . $i . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $i, 10)) . '</option>';
                                }
                                ?>

                        </select>
                        <button class="btn btn-outline-primary">Filter</button>
                        <a href="<?= base_url('penjualan') ?>" class="btn btn-outline-secondary">Reset</a>

                    </form>

                </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-mobil">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mobil</th>
                                <th>Kode</th>
                                <th>Plat</th>
                                <th>Harga</th>
                                <th>Tanggal Keluar</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($penjualan as $m): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $m->nama_mobil ?></td>
                                    <td><?= $m->kode_mobil ?></td>
                                    <td><?= $m->plat_mobil ?></td>
                                    <td>Rp <?= number_format($m->harga_jual, 0, ',', '.') ?></td>
                                    <td><?= $m->tanggal_jual ? date('d/m/Y', strtotime($m->tanggal_jual)) : '-' ?></td>
                                    <td><?= $m->catatan ?: '-' ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item text-info" href="<?= base_url('penjualan/detail_penjualan/' . $m->id_penjualan) ?>">
                                                        <i class="fa-solid fa-info me-1"></i> Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-warning" href="<?= base_url('penjualan/edit_penjualan/'. $m->id_penjualan) ?>">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="<?= base_url('penjualan/delete_penjualan/'. $m->id_penjualan) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

<script>
	$(document).ready(function() {
		$('#table-mobil').DataTable({
		});
	});
</script>