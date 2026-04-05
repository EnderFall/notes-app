<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link href="<?= base_url('assets/dash/img/ellie-logo.jpg') ?>" rel="icon">

  <link href="<?= base_url('assets/dash/img/ellie-logo.jpg') ?>" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <link href="<?= base_url('assets/dash/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/dash/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/dash/vendor/aos/aos.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/dash/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/dash/vendor/glightbox/css/glightbox.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/dash/css/main.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <style>
  </style>

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center me-auto me-lg-0">
        <h1 class="sitename">ELLIE</h1>
        <span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda<br></a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="<?= base_url('/login') ?>">Login</a>

    </div>
  </header>

  <main class="main">



    <!-- Hero Section -->
    <section id="hero" class="hero">
      <div class="hero-image-wrapper">
        <img src="<?= base_url('assets/dash/img/Ellie-MeetingAssistentBackground.png') ?>" alt="Hero" class="hero-image">
        <div class="image-overlay"></div>

        <div class="hero-content">
          <h2><span>Ellie</span> Meeting Assistant</h2>
          <p>Your assistant for smarter meetings</p>
        </div>
      </div>
    </section>








  </main>



  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">ELLIE</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Elysian Realm – Central Archive
                Horizon Spire 12, Reverie District
                Metatron Sector, Lumina Prime City
                Celestia Dominion 52011</p>

              <p class="mt-3"><strong>Phone:</strong> <span>Elysia : +81 570 314 3572</span><br><span>Ryuku : +81 570 226 7958</span></p>
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
              <li><i class="bi bi-chevron-right"></i> <a href="#hero"> Beranda</a></li>
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


  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
  <script src="<?= base_url('assets/dash/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/php-email-form/validate.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/aos/aos.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/swiper/swiper-bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/glightbox/js/glightbox.min.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/imagesloaded/imagesloaded.pkgd.min.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/isotope-layout/isotope.pkgd.min.js') ?>"></script>
  <script src="<?= base_url('assets/dash/vendor/purecounter/purecounter_vanilla.js') ?>"></script>
  <script src="<?= base_url('assets/dash/js/main.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>

</html>