<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil Pengguna</h3>
                <p class="text-subtitle text-muted">Ubah informasi akun Anda</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body text-center">
                <?php
                $foto = $user['foto'] ?? 'default-avatar.jpg';
                ?>

                <form action="<?= base_url('profile/update/' . $user['id_user']) ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- <div class="mb-3">
                        <img id="previewImage"
                             src="<?= base_url('assets/img/' . $foto) ?>"
                             class="rounded-circle shadow-sm" width="120" height="120"
                             style="object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Ganti Foto</label>
                        <input type="file" class="form-control" name="fotoFile" id="foto" accept="image/*" onchange="previewFile(event)">
                    </div> -->
                    <div id="upload-demo"
                        style="width: 200px; height: 200px; margin: 0 auto; border: 2px dashed #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #666; font-size: 14px; cursor: pointer; overflow: hidden;">
                        <?php if (!empty($foto)): ?>
                            <img src="<?= base_url('assets/img/' . $foto) ?>" alt="Current Photo"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                            Klik untuk memilih gambar
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="foto" id="cropped-image" value="<?= !empty($foto) ? $foto : '' ?>">

                    <div class="row justify-content-center text-start">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label><strong>Nama:</strong></label>
                                <input type="text" name="nama" class="form-control"
                                    value="<?= esc($user['username']) ?>">
                            </div>

                            <div class="mb-3">
                                <label><strong>Email:</strong></label>
                                <input type="email" name="email" class="form-control"
                                    value="<?= esc($user['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label><strong>Nomor HP:</strong></label>
                                <input type="text" name="nohp" class="form-control"
                                    value="<?= esc($user['nomor_hp'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label><strong>Divisi:</strong></label>
                                <input type="text" class="form-control" value="<?= esc($user['nama_divisi'] ?? '') ?>"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label><strong>Password (opsional):</strong></label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Isi untuk mengganti password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </form>
                <form action="<?= base_url('profile/reset_password/' . $user['id_user']) ?>" method="post"
                    style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-warning mt-3"
                        onclick="return confirm('Yakin ingin mereset password ke default?')">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>

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
                        <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload"
                            type="button" role="tab" aria-controls="upload" aria-selected="false">Upload Baru</button>
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
                            <div id="uploadPreview" style="width: 300px; height: 300px; margin: 0 auto; display: none;">
                            </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Load Croppie JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

<script>
    function initializeImageChooser() {
        console.log('Checking dependencies...');

        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded');
            setTimeout(initializeImageChooser, 100);
            return;
        }

        const $ = jQuery;

        // Check if Bootstrap is loaded
        if (typeof bootstrap === 'undefined') {
            console.log('Bootstrap not found, loading dynamically...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            script.onload = function () {
                console.log('Bootstrap loaded dynamically');
                checkCroppie();
            };
            document.head.appendChild(script);
        } else {
            checkCroppie();
        }

        function checkCroppie() {
            if (typeof $.fn.croppie === 'undefined') {
                console.log('Croppie not found, loading dynamically...');
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js';
                script.onload = function () {
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
            let currentImageType = null; // 'library' or 'upload'

            const ajaxImageLibraryUrl = '<?= base_url("profile/get_image_library") ?>';
            const uploadToGalleryUrl = '<?= base_url("profile/upload_to_gallery") ?>';

            $(document).ready(function () {
                console.log('Document ready, initializing components...');

                try {
                    cropDemo = $('#crop-demo').croppie({
                        viewport: {
                            width: 200,
                            height: 200,
                            type: 'circle'
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
                        // Show loading state
                        $('#uploadPreview').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p>Mengupload gambar...</p></div>').show();
                        
                        const formData = new FormData();
                        formData.append('image', file);
                        
                        // Upload to server first
                        $.ajax({
                            url: uploadToGalleryUrl,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    // Reload library to show new image
                                    loadImageLibrary();
                                    
                                    // Bind the uploaded image to croppie
                                    currentImageSrc = response.url;
                                    currentImageType = 'upload';
                                    $('#uploadPreview').html(`<img src="${response.url}" class="img-fluid">`).show();
                                    cropDemo.croppie('bind', { url: response.url });
                                    $('#cropArea').show();
                                    $('#selectImageBtn').prop('disabled', false);
                                } else {
                                    alert('Upload gagal: ' + response.message);
                                    $('#uploadPreview').hide().empty();
                                }
                            },
                            error: function() {
                                alert('Terjadi kesalahan saat upload gambar');
                                $('#uploadPreview').hide().empty();
                            }
                        });
                    }
                });

                // Select image button
                $('#selectImageBtn').on('click', function () {
                    if (currentImageType === 'upload') {
                        // For newly uploaded images, crop and use base64
                        cropDemo.croppie('result', {
                            type: 'base64',
                            size: 'viewport',
                            format: 'jpeg',
                            quality: 0.8
                        }).then(function (resp) {
                            selectedImageData = resp;

                            // Store the base64 data in the hidden field
                            $('#cropped-image').val(resp);

                            // Update preview
                            $('#upload-demo').html(`<img src="${resp}" style="width:200px; height:200px; border-radius:50%; object-fit:cover;">`);

                            $('#imageChooserModal').modal('hide');
                            resetModal();
                        });
                    } else if (currentImageType === 'library') {
                        // For library images, just use the filename
                        const selectedItem = $('.image-item.selected');
                        if (selectedItem.length) {
                            const imageFilename = selectedItem.data('filename');

                            // Store the filename in the hidden field
                            $('#cropped-image').val(imageFilename);

                            // Update preview - use the image from main folder if exists, otherwise from gallery
                            const previewUrl = `<?= base_url('assets/img/') ?>${imageFilename}`;
                            $('#upload-demo').html(`<img src="${previewUrl}" onerror="this.src='<?= base_url('assets/img/gallery/') ?>${imageFilename}'" style="width:200px; height:200px; border-radius:50%; object-fit:cover;">`);

                            $('#imageChooserModal').modal('hide');
                            resetModal();
                        }
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
                    currentImageType = 'library';
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
                currentImageType = null;

                // Reset to library tab
                $('#library-tab').tab('show');
            }

            // Global function to open modal
            window.openImageChooser = function () {
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