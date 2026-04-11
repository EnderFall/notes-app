<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?? 'ELLIE' ?></title>
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
    /* --- Chatbot styles (minimal, responsive) --- */
    :root {
      --accent: #4f46e5;
      --bg: #0f172a;
      --card: #0b1220;
    }

    .chatbot-btn {
      position: fixed;
      right: 24px;
      bottom: 24px;
      z-index: 9999;
      border-radius: 999px;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent);
      color: #fff;
      box-shadow: 0 8px 24px rgba(79, 70, 229, 0.18);
      cursor: pointer;
    }

    .chatbot-wrap {
      position: fixed;
      right: 24px;
      bottom: 100px;
      z-index: 9999;
      width: 360px;
      max-width: calc(100% - 48px);
      font-family: Inter, Roboto, Poppins, sans-serif;
    }

    .chatbot-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(2, 6, 23, 0.2);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 520px;
    }

    .chatbot-header {
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid #eee;
    }

    .chatbot-header .title {
      font-weight: 700;
    }

    .chatbot-messages {
      padding: 12px;
      overflow: auto;
      flex: 1;
      background: linear-gradient(180deg, #f7f8fc, #fff);
    }

    .msg {
      margin: 8px 0;
      max-width: 86%;
      display: inline-block;
      padding: 10px 12px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.4;
    }

    .msg.user {
      background: #e6f4ff;
      margin-left: auto;
      border-bottom-right-radius: 2px;
    }

    .msg.bot {
      background: #f2f2f7;
      border-bottom-left-radius: 2px;
    }

    .chatbot-input {
      padding: 8px;
      border-top: 1px solid #eee;
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .chatbot-input input[type="text"] {
      flex: 1;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .chatbot-input button {
      padding: 8px 12px;
      border-radius: 8px;
      background: var(--accent);
      color: #fff;
      border: none;
    }

    .chatbot-minimized {
      display: none;
    }

    @media (max-width:420px) {
      .chatbot-wrap {
        right: 12px;
        left: 12px;
        bottom: 80px;
        width: auto;
      }
    }
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
        <img src="<?= base_url('assets/dash/img/Ellie-MeetingAssistentBackground.png') ?>" alt="Hero"
          class="hero-image">
        <div class="image-overlay"></div>

        <div class="hero-content">
          <h2><span>Ellie</span> Notes Assistant</h2>
          <p>Your assistant for smarter note-taking</p>
        </div>
      </div>
    </section>

  </main>

  <!-- ===== Chatbot UI ===== -->
  <div class="chatbot-wrap" id="chatbotWrap" aria-hidden="true" style="display:none;">
    <div class="chatbot-card" role="dialog" aria-label="Ellie Chatbot">
      <div class="chatbot-header">
        <div>
          <div class="title">Ellie — Notes Assistant</div>
          <div style="font-size:12px;color:#6b7280">Tanyakan tentang catatan, agenda, atau bantuan lain.</div>
        </div>
        <div style="margin-left:auto">
          <button id="chatCloseBtn" title="Tutup"
            style="border:none;background:transparent;cursor:pointer;font-size:18px">✕</button>
        </div>
      </div>
      <div class="chatbot-messages" id="chatMessages"></div>
      <form id="chatForm" class="chatbot-input" onsubmit="return false;">
        <input id="chatInput" type="text" autocomplete="off" placeholder="Ketik pesan..." />
        <button id="chatSendBtn" type="submit">Kirim</button>
      </form>
    </div>
  </div>

  <button class="chatbot-btn" id="chatToggleBtn" aria-expanded="false" title="Buka chat">
    <i class="bi bi-chat-dots" style="font-size:20px"></i>
  </button>

  <!-- Footer (kept) -->
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

              <p class="mt-3"><strong>Phone:</strong> <span>Elysia : +81 570 314 3572</span><br><span>Ryuku : +81 570
                  226 7958</span></p>
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

  <script>
    // --- Simple frontend chatbot logic ---
    const chatToggleBtn = document.getElementById('chatToggleBtn');
    const chatbotWrap = document.getElementById('chatbotWrap');
    const chatCloseBtn = document.getElementById('chatCloseBtn');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');

    function appendMessage(text, who = 'bot') {
      const div = document.createElement('div');
      div.className = 'msg ' + (who === 'user' ? 'user' : 'bot');
      div.textContent = text;
      chatMessages.appendChild(div);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      return div;
    }

    function typeMessage(targetEl, text, delay = 20) {
      let i = 0;
      targetEl.textContent = "";
      const timer = setInterval(() => {
        targetEl.textContent += text.charAt(i);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        i++;
        if (i >= text.length) clearInterval(timer);
      }, delay);
    }

    chatToggleBtn.addEventListener('click', () => {
      const isOpen = chatbotWrap.style.display !== 'none';
      chatbotWrap.style.display = isOpen ? 'none' : 'block';
      chatToggleBtn.setAttribute('aria-expanded', String(!isOpen));
      if (!isOpen) { chatbotWrap.querySelector('#chatInput').focus(); }
    });

    chatCloseBtn.addEventListener('click', () => {
      chatbotWrap.style.display = 'none';
      chatToggleBtn.setAttribute('aria-expanded', 'false');
    });

    // Welcome message
    appendMessage('Halo! Saya Ellie — asisten rapat virtualmu 🤖. Tanyakan apa saja, misalnya "ringkasan rapat hari ini" atau "jadwal minggu depan".');

    // Handle form submit
    chatForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const text = chatInput.value.trim();
      if (!text) return;
      appendMessage(text, 'user');
      chatInput.value = '';

      // Typing indicator
      const typing = appendMessage('...', 'bot');

      try {
        // 🔥 Use CodeIgniter base_url
        const res = await fetch('<?= base_url('api') ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: text })
        });

        typing.remove();

        if (!res.ok) throw new Error('Server Error: ' + res.status);
        const data = await res.json();

        if (data && data.reply) {
          const replyBox = appendMessage('', 'bot');
          typeMessage(replyBox, data.reply, 15); // typing animation
        } else {
          appendMessage('Maaf, saya belum bisa menjawab pertanyaan itu.', 'bot');
        }

      } catch (err) {
        typing.remove();
        appendMessage('⚠️ Tidak dapat terhubung ke server. Pastikan koneksi aktif dan endpoint `/api` tersedia.', 'bot');
        console.error('Chatbot error:', err);
      }
    });

    // Optional: press Enter to send
    chatInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
      }
    });
  </script>


</body>

</html>