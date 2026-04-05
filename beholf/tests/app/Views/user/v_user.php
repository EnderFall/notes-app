<style>
/* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
@media (max-width: 768px) {
  #table-user td,
  #table-user th {
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
@media (max-width: 768px) {
  .container-fluid {
    --bs-gutter-x: 0.25rem; /* smaller padding than default */
    padding-left: 3px;
    padding-right: 0.25rem;
  }

  .card {
    margin-left: 0;
    margin-right: 0;
  }

  .card-body {
    padding: 0.5rem; /* smaller inner spacing */
  }
}

</style>


<div id="main-content">
    <div class="container-fluid">
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
                        <a href="<?php echo base_url('user/tambah_user/')?>"><button class="btn btn-primary mt-2"><i class="fa-solid fa-plus"></i>
                            Tambah</button></a>
                        <?php if (session()->get('level') == 4): ?>
                        <a href="<?= base_url('user/dihapus_user') ?>">
                           <button class="btn btn-secondary mt-2">Data user yang Dihapus</button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="table-user">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No Hp</th>
                                <th>Level</th>
                                <th>Divisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php
                            $no=1; foreach ($user as $b) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $b->username?> </td>
                                <td><?php echo $b->email ?> </td>
                                <td><?php echo $b-> nomor_hp ?></td>
                                <td><?php echo $b-> level ?></td>
                                <td><?php echo $b-> divisi ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if (session()->get('level') == 4): ?>
                                            <li>
                                                <a class="dropdown-item text-info" href="<?= base_url('user/detail_user/' . $b->id_user) ?>">
                                                    <i class="fa-solid fa-info me-1"></i> Detail
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <a class="dropdown-item text-warning" href="<?= base_url('user/edit_user/'. $b->id_user) ?>">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="<?= base_url('user/delete_user/'. $b->id_user) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
        </div>

<script>
	$(document).ready(function() {
		$('#table-user').DataTable({
		});
	});
</script>