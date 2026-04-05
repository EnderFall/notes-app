<style>
/* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
@media (max-width: 768px) {
  #table-hpslevel td,
  #table-hpslevel th {
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
                        <?php if (!empty($deleted_level)): ?>    
                            <table class="table table-striped" id="table-hpslevel">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1; foreach ($deleted_level as $value): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?php echo $value->nama_level?> </td>
                                <td>
                                    <a href="<?= base_url('level/restore_level/' . $value->id_level) ?>" class="btn btn-success my-1"><i class="fa fa-undo"></i></a>

                                    <a href="<?= base_url('level/hapus_level/' . $value->id_level) ?>" class="btn btn-danger my-1" 
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
        <a href="<?= base_url('level') ?>" class="btn btn-secondary shadow">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<script>
	$(document).ready(function() {
		$('#table-hpslevel').DataTable({
		});
	});
</script>