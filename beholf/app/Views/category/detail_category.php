<style>
    .category-hero {
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .stats-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?></h3>
                        <p class="text-subtitle text-muted">Category details and statistics</p>
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

            <!-- Category Hero Section -->
            <div class="category-hero" style="background: linear-gradient(135deg, <?= $category->color ?>20 0%, <?= $category->color ?>05 100%); border: 2px solid <?= $category->color ?>;">
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size: 48px; color: <?= $category->color ?>;">
                        <i class="bi <?= $category->icon ?>"></i>
                    </div>
                    <div>
                        <h2 class="mb-2" style="color: <?= $category->color ?>;"><?= esc($category->name) ?></h2>
                        <p class="text-muted mb-0"><?= esc($category->description) ?></p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stats-card" style="border-left-color: <?= $category->color ?>;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Notes</h6>
                                    <h2 class="mb-0"><?= isset($notes_count) ? $notes_count : 0 ?></h2>
                                </div>
                                <div class="avatar bg-light-primary">
                                    <i class="bi bi-journal-text" style="font-size: 2rem; color: <?= $category->color ?>;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stats-card" style="border-left-color: <?= $category->color ?>;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Color Code</h6>
                                    <h4 class="mb-0"><?= strtoupper($category->color) ?></h4>
                                </div>
                                <div class="avatar" style="background-color: <?= $category->color ?>; width: 60px; height: 60px; border-radius: 10px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stats-card" style="border-left-color: <?= $category->color ?>;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Icon</h6>
                                    <h4 class="mb-0"><?= $category->icon ?></h4>
                                </div>
                                <div class="avatar bg-light-secondary">
                                    <i class="bi <?= $category->icon ?>" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Information -->
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4>Category Information</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="200">Category ID</th>
                                    <td><?= $category->id_category ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?= esc($category->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?= esc($category->description) ?></td>
                                </tr>
                                <tr>
                                    <th>Color</th>
                                    <td>
                                        <span class="badge" style="background-color: <?= $category->color ?>; padding: 10px 20px;">
                                            <?= strtoupper($category->color) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Icon</th>
                                    <td>
                                        <i class="bi <?= $category->icon ?>" style="font-size: 24px; color: <?= $category->color ?>;"></i>
                                        <?= $category->icon ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Preview</th>
                                    <td>
                                        <span class="badge" style="background-color: <?= $category->color ?>20; color: <?= $category->color ?>; border: 2px solid <?= $category->color ?>; padding: 12px 20px; font-size: 16px;">
                                            <i class="bi <?= $category->icon ?>"></i>
                                            <?= esc($category->name) ?>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <a href="<?= base_url('category') ?>" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <a href="<?= base_url('category/edit_category/' . $category->id_category) ?>" class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i> Edit Category
                            </a>
                            <a href="<?= base_url('rapat') ?>?category=<?= $category->id_category ?>" class="btn btn-primary">
                                <i class="fa-solid fa-eye"></i> View Notes
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
