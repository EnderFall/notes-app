<style>
/* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
@media (max-width: 768px) {
  #table-log td,
  #table-log th {
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
                        <h5>Log Activity</h5>
                    </div>
                </div>

            <div class="card-body">
                <form id="delete-form" action="<?= base_url('log_activity/delete') ?>" method="post">
                    <?php if ($can_approve): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="submit" class="btn btn-danger" id="delete-btn" disabled>
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="table-log">
                            <thead>
                                <tr>
                                    <?php if ($can_approve): ?>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <?php endif; ?>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Time</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no=1; foreach ($logs as $log) {
                                ?>
                                    <tr>
                                        <?php if ($can_approve): ?>
                                        <td>
                                            <input type="checkbox" class="log-checkbox" name="selected_logs[]" value="<?= $log['id_log'] ?>">
                                        </td>
                                        <?php endif; ?>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $log['username'] ?? 'Unknown' ?> </td>
                                        <td><?php echo $log['what_happens'] ?> </td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($log['happens_at'])) ?></td>
                                        <td><?php echo $log['ip_address'] ?> </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        </div>

<script>
	$(document).ready(function() {
		$('#table-log').DataTable({
		});

		// Handle select all checkbox
		$('#select-all').on('change', function() {
			$('.log-checkbox').prop('checked', $(this).prop('checked'));
			toggleDeleteButton();
		});

		// Handle individual checkboxes
		$(document).on('change', '.log-checkbox', function() {
			var totalCheckboxes = $('.log-checkbox').length;
			var checkedCheckboxes = $('.log-checkbox:checked').length;

			$('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
			toggleDeleteButton();
		});

		function toggleDeleteButton() {
			var checkedCount = $('.log-checkbox:checked').length;
			$('#delete-btn').prop('disabled', checkedCount === 0);
		}

		// Confirm delete action
		$('#delete-form').on('submit', function(e) {
			var checkedCount = $('.log-checkbox:checked').length;
			if (checkedCount === 0) {
				e.preventDefault();
				alert('Please select at least one log to delete.');
				return false;
			}
			if (!confirm('Are you sure you want to permanently delete the selected ' + checkedCount + ' log(s)?')) {
				e.preventDefault();
				return false;
			}
		});
	});
</script>
