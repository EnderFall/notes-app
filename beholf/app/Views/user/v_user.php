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

        <?php
        $route = service('uri')->getSegment(1);
        $formModel = new \App\Models\M_form();
        $form = $formModel->where('route', $route)->where('status_delete', 0)->first();
        $can_create = 0;
        $can_approve = 0;
        if ($form) {
            $groupFormModel = new \App\Models\M_group_form();
            $perm = $groupFormModel->where('id_form', $form['id_form'])->where('id_level', session()->get('level'))->where('status_delete', 0)->first();
            if ($perm && $perm['can_create'] == 1) {
                $can_create = 1;
            }
            if ($perm && $perm['can_approve'] == 1) {
                $can_approve = 1;
            }
        }
        ?>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <?php if ($can_create): ?>
                        <a href="<?php echo base_url('user/tambah_user/')?>"><button class="btn btn-primary mt-2"><i class="fa-solid fa-plus"></i>
                            Tambah</button></a>
                        <?php endif; ?>
                        <?php if ($can_approve): ?>
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
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No Hp</th>
                                <th>Level</th>
                                <th>Divisi</th>
                                <?php if ($can_create): ?>
                                <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <?php
                            $no=1; foreach ($user as $b) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td>
                                    <?php if (!empty($b->foto)): ?>
                                        <img src="<?= base_url(relativePath: 'assets/img/' . $b->foto) ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/img/default-avatar.jpg') ?>" alt="Default" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $b->username?> </td>
                                <td><?php echo $b->email ?> </td>
                                <td><?php echo $b-> nomor_hp ?></td>
                                <td><?php echo $b-> level ?></td>
                                <td><?php echo $b-> divisi ?></td>
                                <?php if ($can_create): ?>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if ($can_approve): ?>
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
                                <?php endif; ?>
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