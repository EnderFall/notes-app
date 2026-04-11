<style>
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 500;
    }
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?></h3>
                        <p class="text-subtitle text-muted">Manage note categories</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
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
                                <a href="<?= base_url('category/tambah_category/') ?>">
                                    <button class="btn btn-primary mt-2">
                                        <i class="fa-solid fa-plus"></i> Add Category
                                    </button>
                                </a>
                            <?php endif; ?>

                            <?php if ($can_approve): ?>
                                <a href="<?= base_url('category/dihapus_category') ?>">
                                    <button class="btn btn-secondary mt-2">
                                        Deleted Categories
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="table-category">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Preview</th>
                                        <th>Notes Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($category as $c) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($c->name) ?></td>
                                            <td><?= esc($c->description) ?></td>
                                            <td>
                                                <span class="category-badge" style="background-color: <?= esc($c->color) ?>20; color: <?= esc($c->color) ?>; border: 1px solid <?= esc($c->color) ?>;">
                                                    <i class="bi <?= esc($c->icon) ?>"></i>
                                                    <?= esc($c->name) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= isset($c->notes_count) ? $c->notes_count : 0 ?> notes</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item text-info"
                                                                href="<?= base_url('category/detail_category/' . $c->id_category) ?>">
                                                                <i class="fa-solid fa-info me-1"></i> Detail
                                                            </a>
                                                        </li>
                                                        <?php if ($can_create): ?>
                                                        <li>
                                                            <a class="dropdown-item text-warning"
                                                                href="<?= base_url('category/edit_category/' . $c->id_category) ?>">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                                href="<?= base_url('category/delete_category/' . $c->id_category) ?>"
                                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table-category').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    });
</script>
