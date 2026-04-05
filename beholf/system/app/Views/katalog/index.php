<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Catalog | AMB</title>
  <link href="assets/dash/img/amb-logo.png" rel="icon">
  <link href="assets/dash/img/amb-logo.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <link href="assets/dash/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/dash/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/dash/css/main.css" rel="stylesheet">
  <style>
    .catalog-section {
      padding: 100px 100px;
      background-color: #f7f7f7;
    }
    .product-card {
      background: #fff;
      width: 90%;
      margin: 0 auto;
      border-radius: 8px;
      transition: 0.3s;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    .product-info {
      padding: 20px;
    }
    .product-info h5 {
      font-weight: 600;
    }
    .product-info p {
      font-size: 0.9rem;
      color: #666;
    }
    .product-info .price {
      color:rgb(0, 145, 96);
      font-weight: bold;
      margin-top: 10px;
    }
    .product-info button {
      margin-top: 15px;
      background-color:rgb(7, 151, 108);
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }
    .product-info button:hover {
      background-color:rgb(10, 114, 92);
    }
    .modal-body img {
      max-height: 300px;
      object-fit: cover;
    }

  </style>
</head>

<body>

  <!-- Header -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?= base_url('/')?>" class="logo d-flex align-items-center me-auto me-lg-0">
        <h1 class="sitename">AMB</h1>
        <span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/#hero">Beranda<br></a></li>
          <li><a href="/#about">Tentang</a></li>
          <li><a href="/#services">Layanan</a></li>
          <li><a href="/#contact">Kontak</a></li>
          <li><a href="/Katalog"  class="active">Katalog</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="<?= base_url('/login') ?>">Login</a>

    </div>
  </header>

  <main class="main">

    <section class="catalog-section">
      <div class="container">
        <h2 class="text-center mb-5" style="font-weight: bold;">Katalog Produk</h2>
        <div class="row">
  <!-- Sidebar kiri -->
  <div class="col-lg-3 mb-5">
    <div class="card p-4 shadow-sm">
      <h5 class="mb-3">Cari Produk</h5>
      <form action="" method="GET">
      <input type="text" name="search" class="form-control mb-3" placeholder="Cari mobil..." value="<?= esc($_GET['search'] ?? '') ?>">
        
        <h6 class="mt-4">Filter Harga</h6>
        <select name="price" class="form-select mb-3">
          <option value="">Semua Harga</option>
          <option value="1" <?= @$_GET['price'] == '1' ? 'selected' : '' ?>>Rp1.000 - Rp99.999.999</option>
          <option value="2" <?= @$_GET['price'] == '2' ? 'selected' : '' ?>>Rp100.000.000 - Rp500.000.000</option>
          <option value="3" <?= @$_GET['price'] == '3' ? 'selected' : '' ?>>> Rp500.000.000</option>
        </select>

        <!-- <h6 class="mt-4">Kategori</h6>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="kategori[]" value="SUV" id="suv">
          <label class="form-check-label" for="suv">SUV</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="kategori[]" value="Sedan" id="sedan">
          <label class="form-check-label" for="sedan">Sedan</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="kategori[]" value="Hatchback" id="hatchback">
          <label class="form-check-label" for="hatchback">Hatchback</label>
        </div> -->

        <button type="submit" class="btn btn-success w-100 mt-4">Terapkan</button>
      </form> 
    </div>
  </div>

<!-- Katalog produk -->
<div class="col-lg-9">
  <div class="row g-3">
<?php if (empty($mobil)): ?>
  <div class="col-12 text-center">
    <p class="text-muted">Tidak ada mobil yang ditemukan.</p>
  </div>
<?php endif; ?>

    <?php foreach($mobil as $m): ?>
    <div class="col-md-6 col-xl-4 pb-3 px-3">
      <div class="product-card">
        <img src="<?= base_url('uploads/mobil/' .$m->foto_mobil ?? 'assets/dash/img/default-car.png') ?>" alt="<?= esc($m->nama_mobil) ?>" width="1px" height="1px">
        <div class="product-info">
          <h5><?= esc($m->nama_mobil) ?></h5>
          <!-- <p><?= esc($m->keterangan) ?></p> -->
          <div class="price">Rp <?= number_format($m->harga_mobil, 0, ',', '.') ?></div>

          <a href="#" class="btn btn-sm btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#detailModal<?= $m->id_mobil ?>">Lihat Selengkapnya</a>

          <button>Beli Sekarang</button>
        </div>
      </div>
    </div>
    <!-- Modal Detail -->
<div class="modal fade" id="detailModal<?= $m->id_mobil ?>" tabindex="-1" aria-labelledby="modalLabel<?= $m->id_mobil ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel<?= $m->id_mobil ?>"><?= esc($m->nama_mobil) ?> - <?= esc($m->kode_mobil) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <img src="<?= base_url('uploads/mobil/' . $m->foto_mobil) ?>" class="img-fluid rounded">
          </div>
          <div class="col-md-6">
            <p><strong>Harga:</strong> Rp <?= number_format($m->harga_mobil, 0, ',', '.') ?></p>
            
            <p class="mt-3"><strong>Keterangan:</strong><br><?= esc($m->keterangan) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <?php endforeach; ?>
  </div>
</div>



    </section>

  </main>

  <!-- Footer -->
  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">AMB</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Komp. Golden Gate blok B no 1, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29444</p>
              
              <p class="mt-3"><strong>Phone:</strong> <span>Jefri : +62 823 8414 4951</span><br><span>Dany Lim : +62 852 7227 2229</span></p>
            </div>
            <div class="social-links d-flex mt-4">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Tautan Lompatan</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="/#hero"> Beranda</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="/#about"> Tentang</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="/#services"> Layanan</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="/#contact"> Kontak</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="/Katalog"> Katalog</a></li>
            </ul>
          </div>


          <div class="col-lg-4 col-md-12 footer-newsletter">
            <h4>Berita Terbaru</h4>
            <p>Langganan untuk informasi terbaru dari kami!</p>
            <form action="Home/newsletter" method="post" class="php-email-form">
              <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe">
              </div>
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your subscription request has been sent. Thank you!</div>
            </form>
          </div>

        </div>
      </div>
    </div>


  </footer>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="assets/dash/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/dash/js/main.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.lihat-selengkapnya').forEach(function(button) {
      button.addEventListener('click', function () {
        const paragraph = this.previousElementSibling;
        if (paragraph.style.webkitLineClamp === "3") {
          paragraph.style.webkitLineClamp = "unset";
          this.textContent = "Tutup";
        } else {
          paragraph.style.webkitLineClamp = "3";
          this.textContent = "Lihat Selengkapnya";
        }
      });
    });
  });
</script>

</body>
</html>
