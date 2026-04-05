<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Website Settings</h3>
                <p class="text-subtitle text-muted">Manage website title and logo</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Website Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('setting/update') ?>" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Website Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="<?= $web_detail['title'] ?? 'Ellie Secretary' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logo">Website Logo</label>
                                        <div id="upload-demo"
                                             style="width: 200px; height: 200px; margin: 0 auto; border: 2px dashed #ccc; border-radius: 0; display: flex; align-items: center; justify-content: center; color: #666; font-size: 14px; cursor: pointer; overflow: hidden;">
                                            <?php if (!empty($web_detail['logo'])): ?>
                                                <img src="<?= base_url('assets/dash/img/' . $web_detail['logo']) ?>" alt="Current Logo"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                Klik untuk memilih gambar
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="logo" id="cropped-image"
                                               value="<?= !empty($web_detail['logo']) ? $web_detail['logo'] : '' ?>">
                                        <?php if (!empty($web_detail['logo'])): ?>
                                            <p class="text-muted mt-1">Logo saat ini: <?= $web_detail['logo'] ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">Upload a new logo (PNG, JPG, JPEG)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Settings</button>
                                    <a href="<?= base_url('Admin') ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Image Chooser Modal -->
<div class="modal fade" id="imageChooserModal" tabindex="-1" aria-labelledby="imageChooserModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageChooserModalLabel">Pilih Gambar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="imageChooserTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="library-tab" data-bs-toggle="tab" data-bs-target="#library"
              type="button" role="tab" aria-controls="library" aria-selected="true">Galeri</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button"
              role="tab" aria-controls="upload" aria-selected="false">Upload Baru</button>
          </li>
        </ul>
        <div class="tab-content" id="imageChooserTabsContent">
          <!-- Library Tab -->
          <div class="tab-pane fade show active" id="library" role="tabpanel" aria-labelledby="library-tab">
            <div class="mt-3">
              <input type="text" class="form-control mb-3" id="imageSearch" placeholder="Cari gambar...">
              <div id="imageLibrary" class="row" style="max-height: 400px; overflow-y: auto;">
                <!-- Images will be loaded here via AJAX -->
                <div class="col-12 text-center">
                  <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Upload Tab -->
          <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <div class="mt-3">
              <input type="file" id="newImageUpload" accept="image/*" class="form-control mb-3">
              <div id="uploadPreview" style="width: 300px; height: 300px; margin: 0 auto; display: none;"></div>
            </div>
          </div>
        </div>
        <!-- Crop Area (shown when image is selected) -->
        <div id="cropArea" class="mt-3" style="display: none;">
          <h6>Crop Gambar</h6>
          <div id="crop-demo" style="width: 400px; height: 400px; margin: 0 auto;"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="selectImageBtn" disabled>Pilih Gambar</button>
      </div>
    </div>
  </div>
</div>

<!-- Load Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Load Croppie JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

<script>
// Wait for all dependencies to be loaded
function initializeImageChooser() {
    console.log('Checking dependencies...');

    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
        setTimeout(initializeImageChooser, 100);
        return;
    }

    const $ = jQuery;

    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.log('Bootstrap not found, loading dynamically...');
        // Load Bootstrap dynamically
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
        script.onload = function() {
            console.log('Bootstrap loaded dynamically');
            checkCroppie();
        };
        document.head.appendChild(script);
    } else {
        checkCroppie();
    }

    function checkCroppie() {
        // Check if Croppie is loaded
        if (typeof $.fn.croppie === 'undefined') {
            console.log('Croppie not found, loading dynamically...');
            // Load Croppie dynamically
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js';
            script.onload = function() {
                console.log('Croppie loaded dynamically');
                startImageChooser();
            };
            document.head.appendChild(script);
        } else {
            startImageChooser();
        }
    }

    function startImageChooser() {
        console.log('All dependencies loaded, starting image chooser...');

        let cropDemo;
        let selectedImageData = null;
        let currentImageSrc = null;

        // Base URL for AJAX
        const ajaxImageLibraryUrl = '<?= base_url("user/get_image_library") ?>';

        $(document).ready(function () {
            console.log('Document ready, initializing components...');

            try {
                // Initialize Croppie for square logo
                cropDemo = $('#crop-demo').croppie({
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 400,
                        height: 400
                    },
                    enableExif: true
                });
                console.log('Croppie initialized successfully');
            } catch (error) {
                console.error('Error initializing Croppie:', error);
                return;
            }

            // Load image library when modal is shown
            $('#imageChooserModal').on('shown.bs.modal', function () {
                console.log('Modal shown, loading image library...');
                loadImageLibrary();
            });

            // Search functionality
            $('#imageSearch').on('input', function () {
                const searchTerm = $(this).val().toLowerCase();
                $('#imageLibrary .image-item').each(function () {
                    const filename = $(this).data('filename').toLowerCase();
                    $(this).toggle(filename.includes(searchTerm));
                });
            });

            // Upload new image
            $('#newImageUpload').on('change', function () {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        currentImageSrc = e.target.result;
                        $('#uploadPreview').html(`<img src="${e.target.result}" class="img-fluid">`).show();
                        cropDemo.croppie('bind', { url: e.target.result });
                        $('#cropArea').show();
                        $('#selectImageBtn').prop('disabled', false);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Select image button
            $('#selectImageBtn').on('click', function () {
                if (cropDemo) {
                    cropDemo.croppie('result', {
                        type: 'base64',
                        size: 'viewport',
                        format: 'jpeg',
                        quality: 0.8
                    }).then(function (resp) {
                        selectedImageData = resp;
                        $('#cropped-image').val(resp);

                        // Update preview
                        $('#upload-demo').html(`<img src="${resp}" style="width:200px; height:200px; object-fit:cover;">`);

                        $('#imageChooserModal').modal('hide');
                        resetModal();
                    });
                }
            });

            // Reset modal on close
            $('#imageChooserModal').on('hidden.bs.modal', resetModal);

            // Click on gallery image
            $(document).on('click', '.image-item', function () {
                $('.image-item').removeClass('selected');
                $(this).addClass('selected');
                const imageSrc = $(this).find('img').attr('src');
                currentImageSrc = imageSrc;
                cropDemo.croppie('bind', { url: imageSrc });
                $('#cropArea').show();
                $('#selectImageBtn').prop('disabled', false);
            });

            // Click on upload demo to open modal
            $('#upload-demo').on('click', function () {
                openImageChooser();
            });
        });

        // Load images via AJAX
        function loadImageLibrary() {
            $.ajax({
                url: ajaxImageLibraryUrl,
                method: 'GET',
                beforeSend: function () {
                    $('#imageLibrary').html('<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                success: function (response) {
                    $('#imageLibrary').html(response);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#imageLibrary').html('<div class="col-12"><p class="text-muted text-center">Gagal memuat galeri gambar.</p></div>');
                }
            });
        }

        // Reset modal function
        function resetModal() {
            $('#imageSearch').val('');
            $('#newImageUpload').val('');
            $('#uploadPreview').hide().empty();
            $('#cropArea').hide();
            $('#selectImageBtn').prop('disabled', true);
            $('.image-item').removeClass('selected');
            selectedImageData = null;
            currentImageSrc = null;

            // Reset to library tab
            $('#library-tab').tab('show');
        }

        // Global function to open modal
        window.openImageChooser = function() {
            console.log('Opening image chooser modal...');
            const modal = new bootstrap.Modal(document.getElementById('imageChooserModal'));
            modal.show();
        };
    }
}

// Start the initialization when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeImageChooser);
} else {
    initializeImageChooser();
}
</script>

<style>
.image-item {
    cursor: pointer;
    padding: 5px;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.3s;
}
.image-item:hover {
    border-color: #007bff;
    transform: scale(1.05);
}
.image-item.selected {
    border-color: #28a745;
    background-color: #f8f9fa;
}
.image-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}
</style>


