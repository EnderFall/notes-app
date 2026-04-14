<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?></h3>
                        <p class="text-subtitle text-muted">Edit category information</p>
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

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('category/update/' . $category->id_category) ?>" method="post">
                            <input type="hidden" name="id_category" value="<?= $category->id_category ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= esc($category->name) ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                                        <input type="color" class="form-control form-control-color" id="color" name="color" 
                                               value="<?= $category->color ?>" title="Choose category color">
                                        <small class="text-muted">Pick a color to identify this category</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
                                        <select class="form-control" id="icon" name="icon" required>
                                            <option value="bi-folder" <?= $category->icon == 'bi-folder' ? 'selected' : '' ?>>📁 Folder</option>
                                            <option value="bi-book" <?= $category->icon == 'bi-book' ? 'selected' : '' ?>>📚 Book</option>
                                            <option value="bi-briefcase" <?= $category->icon == 'bi-briefcase' ? 'selected' : '' ?>>💼 Briefcase</option>
                                            <option value="bi-lightbulb" <?= $category->icon == 'bi-lightbulb' ? 'selected' : '' ?>>💡 Lightbulb</option>
                                            <option value="bi-heart" <?= $category->icon == 'bi-heart' ? 'selected' : '' ?>>❤️ Heart</option>
                                            <option value="bi-star" <?= $category->icon == 'bi-star' ? 'selected' : '' ?>>⭐ Star</option>
                                            <option value="bi-flag" <?= $category->icon == 'bi-flag' ? 'selected' : '' ?>>🚩 Flag</option>
                                            <option value="bi-tags" <?= $category->icon == 'bi-tags' ? 'selected' : '' ?>>🏷️ Tags</option>
                                            <option value="bi-calendar" <?= $category->icon == 'bi-calendar' ? 'selected' : '' ?>>📅 Calendar</option>
                                            <option value="bi-journal" <?= $category->icon == 'bi-journal' ? 'selected' : '' ?>>📓 Journal</option>
                                            <option value="bi-sticky" <?= $category->icon == 'bi-sticky' ? 'selected' : '' ?>>📝 Sticky Note</option>
                                            <option value="bi-kanban" <?= $category->icon == 'bi-kanban' ? 'selected' : '' ?>>📊 Kanban</option>
                                            <option value="bi-search" <?= $category->icon == 'bi-search' ? 'selected' : '' ?>>🔍 Search</option>
                                            <option value="bi-person-circle" <?= $category->icon == 'bi-person-circle' ? 'selected' : '' ?>>👤 Person</option>
                                            <option value="bi-calendar-range" <?= $category->icon == 'bi-calendar-range' ? 'selected' : '' ?>>📆 Calendar Range</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Preview</label>
                                        <div class="p-3 border rounded" style="background: #f8f9fa;">
                                            <span id="category-preview" class="badge" style="padding: 10px 15px; font-size: 14px; background-color: <?= $category->color ?>20; color: <?= $category->color ?>; border: 1px solid <?= $category->color ?>;">
                                                <i class="bi <?= $category->icon ?>"></i> <span id="preview-text"><?= esc($category->name) ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= esc($category->description) ?></textarea>
                            </div>

                            <div class="form-group">
                                <a href="<?= base_url('category') ?>" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa-solid fa-save"></i> Update Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    // Live preview
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('preview-text').textContent = this.value || 'Category Name';
    });

    document.getElementById('color').addEventListener('input', function() {
        const preview = document.getElementById('category-preview');
        preview.style.backgroundColor = this.value + '20';
        preview.style.color = this.value;
        preview.style.border = '1px solid ' + this.value;
    });

    document.getElementById('icon').addEventListener('change', function() {
        const iconClass = this.value;
        const preview = document.getElementById('category-preview');
        preview.querySelector('i').className = 'bi ' + iconClass;
    });
</script>
