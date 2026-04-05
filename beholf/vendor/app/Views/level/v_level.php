<style>
/* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
@media (max-width: 768px) {
  #table-level td,
  #table-level th {
    white-space: nowrap;
    font-size: 13px;
    padding: 6px 8px;
  }

  .card-header .d-flex {
    flex-direction: column;
    gap: 10px;
  }

  .btn {
    font-size: 14px;
    padding: 6px 12px;
  }

  .breadcrumb {
    font-size: 13px;
  }
}
	.legend-dot {
		display: inline-block;
		height: 12px;
		width: 12px;
		border-radius: 50%;
		margin-right: 6px;
	}
	.legend-item {
		margin-right: 20px;
		display: inline-flex;
		align-items: center;
		font-size: 14px;
	}

	.tr-ketua td {
        background-color: #6ECB63 !important;
        color: #000 !important;
    }

    .tr-sekretaris td {
        background-color: #FFD966 !important;
        color: #000 !important;
    }

	.table-warning * {
		color: #000 !important;
	}
	.table-success * {
		color: #000 !important;
	}

</style>

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
                        <a href="<?php echo base_url('level/tambah_level/')?>"><button class="btn btn-primary mt-2"><i class="fa-solid fa-plus"></i>
                            Tambah</button></a>
                        <?php if (session()->get('level') == 4): ?>
                        <a href="<?= base_url('level/dihapus_level') ?>">
                           <button class="btn btn-secondary mt-2">Data Level yang Dihapus</button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped" id="table-level">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                            $no=1; foreach ($a as $b) {
                        ?>
                            <tr class="">
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $b->nama_level?> </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item text-warning" href="<?= base_url('level/edit_level/' . $b->id_level) ?>">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="<?= base_url('level/delete_level/' . $b->id_level) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fa-solid fa-trash me-1"></i> Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

<script>
	$(document).ready(function() {
		$('#table-level').DataTable({
		});
	});
</script>