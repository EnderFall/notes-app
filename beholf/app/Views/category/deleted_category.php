<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?></h3>
                        <p class="text-subtitle text-muted">View and restore deleted categories</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('category') ?>">Category</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <a href="<?= base_url('category') ?>">
                            <button class="btn btn-primary">
                                <i class="fa-solid fa-arrow-left"></i> Back to Categories
                            </button>
                        </a>
                    </div>

                    <div class="card-body">
                        <?php if (empty($deleted_category)): ?>
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle"></i> No deleted categories found.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-deleted">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Category Name</th>
                                            <th>Description</th>
                                            <th>Preview</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($deleted_category as $c) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= esc($c->name) ?></td>
                                                <td><?= esc($c->description) ?></td>
                                                <td>
                                                    <span class="badge" style="background-color: <?= esc($c->color) ?>20; color: <?= esc($c->color) ?>; border: 1px solid <?= esc($c->color) ?>; padding: 8px 12px;">
                                                        <i class="bi <?= esc($c->icon) ?>"></i>
                                                        <?= esc($c->name) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('category/restore_category/' . $c->id_category) ?>" 
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('Restore this category?')">
                                                        <i class="fa-solid fa-undo"></i> Restore
                                                    </a>
                                                    <a href="<?= base_url('category/hapus_category/' . $c->id_category) ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Permanently delete this category? This cannot be undone!')">
                                                        <i class="fa-solid fa-trash"></i> Delete Permanently
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table-deleted').DataTable();
    });
</script>
