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

<!-- Croppie Scripts -->
<link href="<?= base_url('assets/extensions/croppie/croppie.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/extensions/croppie/croppie.min.js') ?>"></script>
<script>let $cropDemo;
  let selectedImageData = null;
  let currentImageSrc = null;

  // Base URL for AJAX
  const ajaxImageLibraryUrl = '<?= base_url("user/get_image_library") ?>';

  $(document).ready(function () {
    // Initialize Croppie
    $cropDemo = $('#crop-demo').croppie({
      viewport: { width: 200, height: 200, type: 'circle' },
      boundary: { width: 400, height: 400 },
      enableExif: true
    });

    // Load image library
    loadImageLibrary();

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
          $('#uploadPreview').html(`<img src="${e.target.result}" style="max-width:100%; max-height:100%;">`).show();
          $cropDemo.croppie('bind', { url: e.target.result });
          $('#cropArea').show();
          $('#selectImageBtn').prop('disabled', false);
        };
        reader.readAsDataURL(file);
      }
    });

    // Select image button
    $('#selectImageBtn').on('click', function () {
      if ($cropDemo) {
        $cropDemo.croppie('result', { type: 'base64', size: 'viewport' }).then(function (resp) {
          selectedImageData = resp;
          $('#cropped-image').val(resp);
          // Optional: show preview
          if ($('#upload-demo').length) {
            $('#upload-demo').html(`<img src="${resp}" style="width:200px; height:200px; border-radius:50%; object-fit:cover;">`);
          }
          $('#imageChooserModal').modal('hide');
          resetModal();
        });
      }
    });

    // Reset modal on close
    $('#imageChooserModal').on('hidden.bs.modal', resetModal);

    // Click on gallery image
    $('#imageLibrary').on('click', '.image-item', function () {
      $('.image-item').removeClass('selected');
      $(this).addClass('selected');
      const imageSrc = $(this).find('img').attr('src');
      currentImageSrc = imageSrc;
      $cropDemo.croppie('bind', { url: imageSrc });
      $('#cropArea').show();
      $('#selectImageBtn').prop('disabled', false);
    });
  });

  // Load images via AJAX
  function loadImageLibrary() {
    $.ajax({
      url: ajaxImageLibraryUrl,
      method: 'GET',
      success: function (response) {
        $('#imageLibrary').html(response);
      },
      error: function () {
        $('#imageLibrary').html('<p class="text-muted">Gagal memuat galeri gambar.</p>');
      }
    });
  }

  // Reset modal function
  function resetModal() {
    $('#imageSearch').val('');
    $('#newImageUpload').val('');
    $('#uploadPreview').hide();
    $('#cropArea').hide();
    $('#selectImageBtn').prop('disabled', true);
    $('.image-item').removeClass('selected');
    selectedImageData = null;
    currentImageSrc = null;
  }

  // Open modal externally
  function openImageChooser() {
    $('#imageChooserModal').modal('show');
  }

</script>

<style>
.image-item {
  cursor: pointer;
  padding: 5px;
  border: 2px solid transparent;
  transition: border-color 0.3s;
}
.image-item:hover {
  border-color: #007bff;
}
.image-item.selected {
  border-color: #28a745;
}
.image-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
}

</style>