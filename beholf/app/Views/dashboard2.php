<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ELLIE — Notes Assistant</title>
  <link rel="icon" type="image/png" href="assets/dash/img/ellie-logo.jpg">
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --cream: #FFF8F0;
      --pink-light: #FFE8EC;
      --pink: #FFB3C1;
      --pink-deep: #FF7096;
      --peach: #FFDAB3;
      --peach-deep: #FFB347;
      --brown: #6B3A2A;
      --brown-light: #C17B5C;
      --white: #FFFFFF;
      --text-dark: #3D1F1F;
      --text-mid: #7A4A4A;
      --text-light: #BFA09A;
      --shadow-soft: 0 8px 32px rgba(255,112,150,0.12);
      --shadow-card: 0 4px 24px rgba(107,58,42,0.08);
      --radius-xl: 32px;
      --radius-lg: 20px;
      --radius-md: 14px;
      --radius-sm: 8px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Nunito', sans-serif;
      background: var(--cream);
      color: var(--text-dark);
      overflow-x: hidden;
      cursor: default;
    }

    /* ─── DECORATIVE BLOBS ─── */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.35;
      pointer-events: none;
      animation: drift 8s ease-in-out infinite alternate;
    }
    .blob-1 { width: 340px; height: 340px; background: var(--pink); top: -80px; right: -60px; }
    .blob-2 { width: 220px; height: 220px; background: var(--peach); top: 200px; left: -80px; animation-delay: 2s; }
    .blob-3 { width: 180px; height: 180px; background: var(--pink-light); bottom: 60px; right: 10%; animation-delay: 4s; }

    @keyframes drift {
      from { transform: translate(0, 0) scale(1); }
      to   { transform: translate(20px, 30px) scale(1.08); }
    }

    /* ─── DOTS DECORATION ─── */
    .dot {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      animation: pop 3s ease-in-out infinite alternate;
    }
    @keyframes pop {
      from { transform: scale(1); opacity: 0.7; }
      to   { transform: scale(1.3); opacity: 1; }
    }

    /* ─── HEADER ─── */
    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      background: rgba(255,248,240,0.82);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1.5px solid rgba(255,179,193,0.3);
      padding: 0 5%;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: box-shadow 0.3s;
    }
    header.scrolled { box-shadow: 0 4px 24px rgba(255,112,150,0.15); }

    .logo {
      font-family: 'Baloo 2', cursive;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--brown);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 2px;
    }
    .logo span { color: var(--pink-deep); }

    nav ul {
      list-style: none;
      display: flex;
      gap: 2rem;
    }
    nav ul li a {
      font-family: 'Nunito', sans-serif;
      font-weight: 600;
      font-size: 0.95rem;
      color: var(--text-mid);
      text-decoration: none;
      padding: 6px 0;
      position: relative;
      transition: color 0.2s;
    }
    nav ul li a::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0;
      width: 0; height: 2px;
      background: var(--pink-deep);
      border-radius: 2px;
      transition: width 0.25s;
    }
    nav ul li a:hover { color: var(--pink-deep); }
    nav ul li a:hover::after { width: 100%; }

    .btn-login {
      background: var(--pink-deep);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 10px 26px;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 14px rgba(255,112,150,0.35);
    }
    .btn-login:hover {
      background: var(--brown);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(107,58,42,0.25);
    }

    /* ─── HERO ─── */
    #hero {
      min-height: 100vh;
      padding: 120px 5% 80px;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    .hero-inner {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }

    .hero-text h2 {
      font-family: 'Baloo 2', cursive;
      font-size: clamp(2.6rem, 5vw, 4.2rem);
      font-weight: 800;
      line-height: 1.1;
      color: var(--brown);
      margin-bottom: 20px;
    }
    .hero-text h2 span { color: var(--pink-deep); }

    .hero-text p {
      font-size: 1.1rem;
      color: var(--text-mid);
      line-height: 1.7;
      margin-bottom: 32px;
      max-width: 440px;
    }

    .hero-cta {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 48px;
    }

    .btn-primary-hero {
      background: var(--pink-deep);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 14px 32px;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 6px 20px rgba(255,112,150,0.4);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-primary-hero:hover {
      background: var(--brown);
      transform: translateY(-3px);
      box-shadow: 0 10px 28px rgba(107,58,42,0.28);
      color: #fff;
    }

    .btn-secondary-hero {
      background: transparent;
      color: var(--brown);
      border: 2px solid var(--pink);
      border-radius: 50px;
      padding: 12px 28px;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-secondary-hero:hover {
      background: var(--pink-light);
      border-color: var(--pink-deep);
      transform: translateY(-3px);
    }

    .hero-social {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .social-pill {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: var(--white);
      border: 1.5px solid var(--pink-light);
      border-radius: 50px;
      padding: 8px 18px;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-mid);
      text-decoration: none;
      width: fit-content;
      transition: all 0.2s;
      box-shadow: 0 2px 8px rgba(255,112,150,0.08);
      cursor: pointer;
    }
    .social-pill:hover {
      background: var(--pink-light);
      border-color: var(--pink-deep);
      transform: translateX(4px);
    }
    .social-pill i { color: var(--pink-deep); font-size: 1rem; }

    /* Hero Illustration */
    .hero-visual {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .note-card-big {
      background: var(--white);
      border-radius: var(--radius-xl);
      padding: 32px;
      box-shadow: 0 20px 60px rgba(255,112,150,0.18), 0 4px 16px rgba(107,58,42,0.06);
      width: 100%;
      max-width: 380px;
      position: relative;
      transform: rotate(-2deg);
      transition: transform 0.3s;
      animation: floatCard 5s ease-in-out infinite alternate;
    }
    .note-card-big:hover { transform: rotate(0deg) scale(1.02); }

    @keyframes floatCard {
      from { transform: rotate(-2deg) translateY(0); }
      to   { transform: rotate(-1deg) translateY(-12px); }
    }

    .card-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--pink-light);
      color: var(--pink-deep);
      border-radius: 50px;
      padding: 4px 14px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 16px;
    }

    .card-title {
      font-family: 'Baloo 2', cursive;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--brown);
      margin-bottom: 12px;
    }

    .card-lines {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 20px;
    }
    .card-line {
      height: 10px;
      border-radius: 10px;
      background: var(--cream);
    }
    .card-line.short { width: 65%; }
    .card-line.medium { width: 85%; }
    .card-line.long { width: 100%; }

    .card-footer-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 16px;
    }
    .avatar-stack { display: flex; }
    .avatar {
      width: 30px; height: 30px;
      border-radius: 50%;
      border: 2.5px solid #fff;
      margin-left: -8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: 700;
      color: #fff;
    }
    .avatar:first-child { margin-left: 0; }
    .av1 { background: var(--pink-deep); }
    .av2 { background: var(--peach-deep); }
    .av3 { background: var(--brown-light); }

    .card-date {
      font-size: 0.78rem;
      color: var(--text-light);
      font-weight: 600;
    }

    /* Floating mini cards */
    .mini-card {
      position: absolute;
      background: var(--white);
      border-radius: var(--radius-lg);
      padding: 14px 18px;
      box-shadow: var(--shadow-card);
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--brown);
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
      animation: floatMini 4s ease-in-out infinite alternate;
    }
    .mini-card i { color: var(--pink-deep); font-size: 1rem; }
    .mini-card-1 { top: -20px; right: -30px; animation-delay: 1s; }
    .mini-card-2 { bottom: 30px; left: -40px; animation-delay: 2.5s; }

    @keyframes floatMini {
      from { transform: translateY(0) rotate(-1deg); }
      to   { transform: translateY(-10px) rotate(2deg); }
    }

    /* ─── WAVE DIVIDER ─── */
    .wave-divider {
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }
    .wave-divider svg { display: block; width: 100%; }

    /* ─── FEATURES STRIP ─── */
    #features-strip {
      background: var(--white);
      padding: 48px 5%;
    }
    .strip-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
    }
    .strip-item {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .strip-icon {
      width: 56px; height: 56px;
      border-radius: var(--radius-md);
      background: var(--pink-light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
      transition: transform 0.2s, background 0.2s;
    }
    .strip-item:hover .strip-icon {
      background: var(--pink-deep);
      color: #fff;
      transform: rotate(-6deg) scale(1.1);
    }
    .strip-icon i { color: var(--pink-deep); }
    .strip-item:hover .strip-icon i { color: #fff; }
    .strip-text h4 {
      font-family: 'Baloo 2', cursive;
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--brown);
      margin-bottom: 4px;
    }
    .strip-text p { font-size: 0.85rem; color: var(--text-mid); line-height: 1.5; }

    /* ─── ABOUT / FEATURE SECTIONS ─── */
    .section-wrap {
      padding: 100px 5%;
      position: relative;
      overflow: hidden;
    }
    .section-wrap.alt { background: var(--pink-light); }

    .section-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      align-items: center;
    }
    .section-inner.reverse { direction: rtl; }
    .section-inner.reverse > * { direction: ltr; }

    .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--pink-light);
      color: var(--pink-deep);
      border-radius: 50px;
      padding: 5px 16px;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 16px;
    }
    .section-inner.reverse .section-badge { background: var(--peach); color: var(--brown); }

    .section-title {
      font-family: 'Baloo 2', cursive;
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 800;
      color: var(--brown);
      line-height: 1.2;
      margin-bottom: 16px;
    }
    .section-title span { color: var(--pink-deep); }

    .section-body {
      font-size: 1rem;
      color: var(--text-mid);
      line-height: 1.8;
      margin-bottom: 24px;
    }

    .feature-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 28px;
    }
    .feature-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text-mid);
    }
    .feature-list li i { color: var(--pink-deep); font-size: 1rem; }

    .btn-section {
      background: var(--brown);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 12px 28px;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 16px rgba(107,58,42,0.2);
    }
    .btn-section:hover {
      background: var(--pink-deep);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(255,112,150,0.35);
      color: #fff;
    }

    /* Visual panels for sections */
    .visual-panel {
      position: relative;
      display: flex;
      justify-content: center;
    }

    .note-mockup {
      background: var(--white);
      border-radius: var(--radius-xl);
      padding: 28px;
      box-shadow: var(--shadow-soft), 0 2px 8px rgba(107,58,42,0.05);
      width: 100%;
      max-width: 340px;
      position: relative;
    }

    .note-mockup-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .mockup-title {
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 1rem;
      color: var(--brown);
    }
    .mockup-dots { display: flex; gap: 5px; }
    .mockup-dot {
      width: 9px; height: 9px;
      border-radius: 50%;
    }
    .md1 { background: #FFB3C1; }
    .md2 { background: #FFD580; }
    .md3 { background: #B8F0C8; }

    .note-item {
      background: var(--cream);
      border-radius: var(--radius-md);
      padding: 14px 16px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: transform 0.15s, background 0.15s;
      cursor: pointer;
    }
    .note-item:hover { background: var(--pink-light); transform: translateX(4px); }
    .note-icon {
      width: 36px; height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .ni-pink { background: var(--pink-light); color: var(--pink-deep); }
    .ni-peach { background: var(--peach); color: var(--peach-deep); }
    .ni-cream { background: #E8F5E9; color: #4CAF50; }

    .note-item-text h5 {
      font-family: 'Baloo 2', cursive;
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--brown);
      margin-bottom: 2px;
    }
    .note-item-text p { font-size: 0.75rem; color: var(--text-light); font-weight: 500; }

    /* AI suggestion bubble */
    .ai-bubble {
      position: absolute;
      bottom: -20px;
      right: -24px;
      background: var(--pink-deep);
      color: #fff;
      border-radius: var(--radius-lg) var(--radius-lg) var(--radius-lg) 4px;
      padding: 12px 16px;
      max-width: 180px;
      font-size: 0.78rem;
      font-weight: 600;
      line-height: 1.5;
      box-shadow: 0 8px 24px rgba(255,112,150,0.35);
    }
    .ai-bubble::before {
      content: '✨ AI';
      display: block;
      font-size: 0.7rem;
      font-weight: 800;
      margin-bottom: 4px;
      opacity: 0.8;
    }

    /* Stats panel */
    .stats-panel {
      position: absolute;
      top: -24px;
      left: -32px;
      background: var(--white);
      border-radius: var(--radius-lg);
      padding: 16px 20px;
      box-shadow: var(--shadow-card);
      text-align: center;
      animation: floatMini 5s ease-in-out infinite alternate;
    }
    .stats-num {
      font-family: 'Baloo 2', cursive;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--pink-deep);
      line-height: 1;
    }
    .stats-label { font-size: 0.72rem; color: var(--text-light); font-weight: 600; }

    /* Calendar mockup */
    .cal-mockup {
      background: var(--white);
      border-radius: var(--radius-xl);
      padding: 24px;
      box-shadow: var(--shadow-soft);
      width: 100%;
      max-width: 340px;
    }
    .cal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }
    .cal-month {
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      color: var(--brown);
      font-size: 1rem;
    }
    .cal-nav { display: flex; gap: 8px; }
    .cal-nav button {
      background: var(--pink-light);
      border: none;
      border-radius: 8px;
      width: 28px; height: 28px;
      cursor: pointer;
      color: var(--pink-deep);
      font-size: 0.85rem;
      transition: background 0.15s;
    }
    .cal-nav button:hover { background: var(--pink-deep); color: #fff; }

    .cal-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
      text-align: center;
    }
    .cal-day-name { font-size: 0.7rem; font-weight: 700; color: var(--text-light); padding: 4px 0; }
    .cal-day {
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--text-mid);
      padding: 6px 4px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.15s;
    }
    .cal-day:hover { background: var(--pink-light); color: var(--pink-deep); }
    .cal-day.today { background: var(--pink-deep); color: #fff; border-radius: 50%; }
    .cal-day.has-note { position: relative; }
    .cal-day.has-note::after {
      content: '';
      position: absolute;
      bottom: 2px; left: 50%;
      transform: translateX(-50%);
      width: 4px; height: 4px;
      border-radius: 50%;
      background: var(--peach-deep);
    }
    .cal-day.empty { opacity: 0; pointer-events: none; }

    .upcoming-notes { margin-top: 16px; }
    .upcoming-title { font-size: 0.75rem; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 10px; }
    .upcoming-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      border-bottom: 1px dashed var(--pink-light);
      cursor: pointer;
      transition: background 0.15s;
    }
    .upcoming-item:last-child { border-bottom: none; }
    .upcoming-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .upcoming-item h5 { font-size: 0.82rem; font-weight: 700; color: var(--brown); margin-bottom: 1px; }
    .upcoming-item p { font-size: 0.72rem; color: var(--text-light); }

    /* ─── WAVE ─── */
    .wave-top svg, .wave-bottom svg { display: block; width: 100%; }

    /* ─── FOOTER ─── */
    footer {
      background: var(--brown);
      color: #fff;
      padding: 60px 5% 32px;
      position: relative;
      overflow: hidden;
    }
    .footer-blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.1;
      pointer-events: none;
    }
    .fb1 { width: 300px; height: 300px; background: var(--pink); top: -80px; right: 10%; }
    .fb2 { width: 200px; height: 200px; background: var(--peach); bottom: 0; left: 5%; }

    .footer-grid {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 2fr 1fr 2fr;
      gap: 60px;
      margin-bottom: 48px;
    }

    .footer-logo {
      font-family: 'Baloo 2', cursive;
      font-size: 2rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 12px;
    }
    .footer-logo span { color: var(--pink); }

    .footer-tagline { font-size: 0.9rem; color: rgba(255,255,255,0.6); line-height: 1.7; margin-bottom: 20px; }

    .footer-social { display: flex; gap: 10px; }
    .social-btn {
      width: 38px; height: 38px;
      border-radius: 50%;
      background: rgba(255,255,255,0.1);
      border: 1.5px solid rgba(255,179,193,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,0.7);
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
    }
    .social-btn:hover { background: var(--pink-deep); border-color: var(--pink-deep); color: #fff; transform: translateY(-2px); }

    .footer-col h4 {
      font-family: 'Baloo 2', cursive;
      font-size: 1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 16px;
    }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .footer-col ul li a {
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      font-size: 0.88rem;
      transition: color 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .footer-col ul li a:hover { color: var(--pink); }
    .footer-col ul li a i { font-size: 0.8rem; }

    .newsletter-form-footer {
      display: flex;
      gap: 0;
      background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,179,193,0.2);
      border-radius: 50px;
      padding: 4px 4px 4px 16px;
      margin-top: 12px;
    }
    .newsletter-form-footer input {
      flex: 1;
      background: transparent;
      border: none;
      outline: none;
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-size: 0.88rem;
    }
    .newsletter-form-footer input::placeholder { color: rgba(255,255,255,0.4); }
    .newsletter-form-footer button {
      background: var(--pink-deep);
      border: none;
      border-radius: 50px;
      padding: 10px 20px;
      color: #fff;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 0.85rem;
      cursor: pointer;
      transition: background 0.2s;
      white-space: nowrap;
    }
    .newsletter-form-footer button:hover { background: var(--peach-deep); }

    .footer-bottom {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 24px;
      border-top: 1px solid rgba(255,179,193,0.1);
      font-size: 0.82rem;
      color: rgba(255,255,255,0.4);
    }

    /* ─── SCROLL TOP ─── */
    #scroll-top {
      position: fixed;
      bottom: 28px;
      right: 28px;
      width: 44px; height: 44px;
      border-radius: 50%;
      background: var(--pink-deep);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      text-decoration: none;
      box-shadow: 0 4px 16px rgba(255,112,150,0.4);
      opacity: 0;
      transform: translateY(10px);
      transition: all 0.3s;
      z-index: 999;
    }
    #scroll-top.visible { opacity: 1; transform: translateY(0); }
    #scroll-top:hover { background: var(--brown); transform: translateY(-3px); }

    /* ─── CLOUD WAVE ─── */
    .cloud-wave {
      width: 100%;
      overflow: hidden;
      line-height: 0;
      margin-top: -2px;
    }

    /* ─── ANIMATIONS ─── */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 900px) {
      .hero-inner, .section-inner, .strip-inner, .footer-grid {
        grid-template-columns: 1fr;
        gap: 40px;
      }
      .section-inner.reverse { direction: ltr; }
      .hero-visual { display: none; }
      nav { display: none; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
      .footer-grid { grid-template-columns: 1fr; }
      .strip-inner { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header id="header">
    <a href="#" class="logo">ELLIE<span>.</span></a>
    <nav>
      <ul>
        <li><a href="#hero">Home</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#smart">Smart Notes</a></li>
        <li><a href="#organize">Organize</a></li>
      </ul>
    </nav>
    <a href="login" class="btn-login">Login</a>
  </header>

  <!-- HERO -->
  <section id="hero">
    <!-- Background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <!-- Decorative dots -->
    <div class="dot" style="width:12px;height:12px;background:var(--pink-deep);top:18%;right:48%;animation-delay:0.5s;"></div>
    <div class="dot" style="width:8px;height:8px;background:var(--peach-deep);top:35%;right:42%;animation-delay:1.5s;"></div>
    <div class="dot" style="width:14px;height:14px;background:var(--pink);bottom:22%;left:42%;animation-delay:0.8s;"></div>

    <div class="hero-inner">
      <div class="hero-text">
        <p style="font-size:0.85rem;font-weight:700;color:var(--pink-deep);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">✨ Your Smart Notes Companion</p>
        <h2>
          Notes that <span>think</span><br>
          with you<span>,</span><br>
          beautifully!
        </h2>
        <p>Ellie is your AI-powered meeting and note-taking assistant. Capture ideas effortlessly, organize instantly, and never miss a detail again.</p>
        <div class="hero-cta">
          <a href="login" class="btn-primary-hero"><i class="bi bi-stars"></i> Get Started Free</a>
          <a href="#features" class="btn-secondary-hero"><i class="bi bi-play-circle"></i> See How It Works</a>
        </div>
        <div class="hero-social">
          <a href="#" class="social-pill"><i class="bi bi-pinterest"></i> Follow on Pinterest</a>
          <a href="#" class="social-pill"><i class="bi bi-facebook"></i> Join our Community</a>
          <a href="#" class="social-pill"><i class="bi bi-instagram"></i> @ellienotes</a>
          <a href="#" class="social-pill"><i class="bi bi-twitter-x"></i> Latest Updates</a>
        </div>
      </div>
      <div class="hero-visual">
        <div class="note-card-big">
          <div class="card-tag"><i class="bi bi-pencil-square"></i> Meeting Notes</div>
          <div class="card-title">Q2 Product Review</div>
          <div class="card-lines">
            <div class="card-line long" style="background:var(--pink-light);"></div>
            <div class="card-line medium" style="background:var(--cream);"></div>
            <div class="card-line long" style="background:var(--pink-light);"></div>
            <div class="card-line short" style="background:var(--cream);"></div>
            <div class="card-line medium" style="background:var(--peach);"></div>
          </div>
          <div style="background:var(--cream);border-radius:var(--radius-sm);padding:10px 12px;font-size:0.8rem;color:var(--text-mid);margin-bottom:16px;">
            <span style="color:var(--pink-deep);font-weight:700;">✨ AI Summary:</span> Action items identified — launch deadline moved to May 15, design review scheduled.
          </div>
          <div class="card-footer-row">
            <div class="avatar-stack">
              <div class="avatar av1">E</div>
              <div class="avatar av2">R</div>
              <div class="avatar av3">M</div>
            </div>
            <span class="card-date">Apr 13, 2026</span>
          </div>
        </div>
        <div class="mini-card mini-card-1"><i class="bi bi-lightning-charge-fill"></i> Auto-tagged</div>
        <div class="mini-card mini-card-2"><i class="bi bi-check2-circle"></i> 3 Action Items</div>
      </div>
    </div>
  </section>

  <!-- WAVE DOWN -->
  <div class="wave-divider">
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
    </svg>
  </div>

  <!-- FEATURES STRIP -->
  <section id="features" style="background:#fff;padding:60px 5%;">
    <div class="strip-inner">
      <div class="strip-item fade-in">
        <div class="strip-icon"><i class="bi bi-cpu"></i></div>
        <div class="strip-text">
          <h4>AI-Powered Notes</h4>
          <p>Smart summaries and key takeaways generated automatically.</p>
        </div>
      </div>
      <div class="strip-item fade-in" style="transition-delay:0.15s">
        <div class="strip-icon"><i class="bi bi-collection"></i></div>
        <div class="strip-text">
          <h4>Auto-Organize</h4>
          <p>Notes sorted by topic, date, and priority without any effort.</p>
        </div>
      </div>
      <div class="strip-item fade-in" style="transition-delay:0.3s">
        <div class="strip-icon"><i class="bi bi-people"></i></div>
        <div class="strip-text">
          <h4>Team Collaboration</h4>
          <p>Share notes and collaborate in real-time with your teammates.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- WAVE UP -->
  <div class="wave-divider" style="background:#fff;">
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,0 C480,80 960,0 1440,60 L1440,80 L0,80 Z" fill="var(--cream)"/>
    </svg>
  </div>

  <!-- SECTION 1 — Smart Notes -->
  <section id="smart" class="section-wrap">
    <div class="blob" style="width:260px;height:260px;background:var(--pink);top:-60px;right:-60px;opacity:0.2;"></div>
    <div class="section-inner">
      <div class="visual-panel">
        <div class="note-mockup" style="transform:rotate(1.5deg);animation:floatCard 6s ease-in-out infinite alternate;">
          <div class="note-mockup-header">
            <span class="mockup-title">My Notes</span>
            <div class="mockup-dots">
              <div class="mockup-dot md1"></div>
              <div class="mockup-dot md2"></div>
              <div class="mockup-dot md3"></div>
            </div>
          </div>
          <div class="note-item">
            <div class="note-icon ni-pink"><i class="bi bi-mic-fill"></i></div>
            <div class="note-item-text">
              <h5>Morning Stand-up</h5>
              <p>2 min ago · 3 action items</p>
            </div>
          </div>
          <div class="note-item">
            <div class="note-icon ni-peach"><i class="bi bi-lightbulb-fill"></i></div>
            <div class="note-item-text">
              <h5>Product Brainstorm</h5>
              <p>Yesterday · 7 ideas captured</p>
            </div>
          </div>
          <div class="note-item">
            <div class="note-icon ni-cream"><i class="bi bi-journal-text"></i></div>
            <div class="note-item-text">
              <h5>Weekly Planning</h5>
              <p>Apr 10 · Completed</p>
            </div>
          </div>
          <div class="note-item">
            <div class="note-icon ni-pink"><i class="bi bi-camera-video-fill"></i></div>
            <div class="note-item-text">
              <h5>Client Meeting</h5>
              <p>Apr 9 · Summarized</p>
            </div>
          </div>
        </div>
        <div class="ai-bubble">Summarized your 45-min meeting into 5 key points!</div>
        <div class="stats-panel">
          <div class="stats-num">98%</div>
          <div class="stats-label">Accuracy</div>
        </div>
      </div>
      <div class="fade-in">
        <span class="section-badge"><i class="bi bi-stars"></i> AI-Powered</span>
        <h2 class="section-title">Notes that <span>capture</span> everything for you</h2>
        <p class="section-body">Ellie listens to your meetings, conversations, and voice memos — then intelligently extracts key points, action items, and decisions so you can stay fully present in the moment.</p>
        <ul class="feature-list">
          <li><i class="bi bi-check-circle-fill"></i> Real-time transcription & summarization</li>
          <li><i class="bi bi-check-circle-fill"></i> Auto-detect action items & deadlines</li>
          <li><i class="bi bi-check-circle-fill"></i> Smart keyword tagging & search</li>
          <li><i class="bi bi-check-circle-fill"></i> Works offline, syncs when connected</li>
        </ul>
        <a href="login" class="btn-section"><i class="bi bi-arrow-right-circle"></i> Try Smart Notes</a>
      </div>
    </div>
  </section>

  <!-- WAVE -->
  <div class="wave-divider" style="background:var(--cream);">
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,60 C360,0 1080,80 1440,20 L1440,80 L0,80 Z" fill="var(--pink-light)"/>
    </svg>
  </div>

  <!-- SECTION 2 — Organize -->
  <section id="organize" class="section-wrap alt">
    <div class="blob" style="width:240px;height:240px;background:var(--peach-deep);bottom:-40px;left:-60px;opacity:0.15;"></div>
    <div class="section-inner reverse">
      <div class="fade-in">
        <span class="section-badge" style="background:var(--peach);color:var(--brown);"><i class="bi bi-calendar3"></i> Organize</span>
        <h2 class="section-title">Always know <span>what's</span> next</h2>
        <p class="section-body">Ellie links your notes to your calendar so nothing falls through the cracks. See all your meetings, notes, and action items in one beautiful timeline view.</p>
        <ul class="feature-list">
          <li><i class="bi bi-check-circle-fill"></i> Calendar-linked meeting notes</li>
          <li><i class="bi bi-check-circle-fill"></i> Deadline tracking & reminders</li>
          <li><i class="bi bi-check-circle-fill"></i> Multi-workspace support</li>
          <li><i class="bi bi-check-circle-fill"></i> Export to PDF, Notion, Slack</li>
        </ul>
        <a href="login" class="btn-section" style="background:var(--brown-light);"><i class="bi bi-calendar-check"></i> Explore Calendar View</a>
      </div>
      <div class="visual-panel">
        <div class="cal-mockup fade-in">
          <div class="cal-header">
            <span class="cal-month">April 2026</span>
            <div class="cal-nav">
              <button onclick="this.blur()"><i class="bi bi-chevron-left"></i></button>
              <button onclick="this.blur()"><i class="bi bi-chevron-right"></i></button>
            </div>
          </div>
          <div class="cal-grid">
            <div class="cal-day-name">Su</div>
            <div class="cal-day-name">Mo</div>
            <div class="cal-day-name">Tu</div>
            <div class="cal-day-name">We</div>
            <div class="cal-day-name">Th</div>
            <div class="cal-day-name">Fr</div>
            <div class="cal-day-name">Sa</div>
            <div class="cal-day empty">-</div>
            <div class="cal-day empty">-</div>
            <div class="cal-day">1</div>
            <div class="cal-day has-note">2</div>
            <div class="cal-day">3</div>
            <div class="cal-day has-note">4</div>
            <div class="cal-day">5</div>
            <div class="cal-day">6</div>
            <div class="cal-day has-note">7</div>
            <div class="cal-day">8</div>
            <div class="cal-day has-note">9</div>
            <div class="cal-day">10</div>
            <div class="cal-day has-note">11</div>
            <div class="cal-day">12</div>
            <div class="cal-day today">13</div>
            <div class="cal-day">14</div>
            <div class="cal-day">15</div>
            <div class="cal-day">16</div>
            <div class="cal-day">17</div>
            <div class="cal-day">18</div>
            <div class="cal-day">19</div>
            <div class="cal-day">20</div>
            <div class="cal-day">21</div>
            <div class="cal-day">22</div>
            <div class="cal-day">23</div>
            <div class="cal-day">24</div>
            <div class="cal-day">25</div>
            <div class="cal-day">26</div>
            <div class="cal-day">27</div>
            <div class="cal-day">28</div>
            <div class="cal-day">29</div>
            <div class="cal-day">30</div>
          </div>
          <div class="upcoming-notes">
            <div class="upcoming-title">Today's Notes</div>
            <div class="upcoming-item" onclick="alert('Opening note... 📝')">
              <div class="upcoming-dot" style="background:var(--pink-deep);"></div>
              <div>
                <h5>Product Team Sync</h5>
                <p>9:00 AM · 6 notes captured</p>
              </div>
            </div>
            <div class="upcoming-item" onclick="alert('Opening note... 📝')">
              <div class="upcoming-dot" style="background:var(--peach-deep);"></div>
              <div>
                <h5>Client Call — Aether Co.</h5>
                <p>2:00 PM · Scheduled</p>
              </div>
            </div>
            <div class="upcoming-item" onclick="alert('Opening note... 📝')">
              <div class="upcoming-dot" style="background:var(--brown-light);"></div>
              <div>
                <h5>Weekly Retrospective</h5>
                <p>4:30 PM · Recurring</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WAVE -->
  <div class="wave-divider" style="background:var(--pink-light);">
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,20 C480,80 960,0 1440,60 L1440,80 L0,80 Z" fill="var(--cream)"/>
    </svg>
  </div>

  <!-- FOOTER WAVE (cloud style) -->
  <div style="background:var(--cream);">
    <div class="cloud-wave">
      <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,60 Q60,20 120,50 Q180,80 240,40 Q300,0 380,45 Q460,90 540,35 Q620,-10 700,45 Q780,90 860,40 Q940,-10 1020,45 Q1100,90 1180,40 Q1260,-10 1340,50 Q1380,70 1440,50 L1440,60 L0,60 Z" fill="var(--brown)"/>
      </svg>
    </div>
  </div>

  <!-- FOOTER -->
  <footer>
    <div class="footer-blob fb1"></div>
    <div class="footer-blob fb2"></div>
    <div class="footer-grid">
      <div>
        <div class="footer-logo">ELLIE<span>.</span></div>
        <p class="footer-tagline">Your AI-powered meeting and notes assistant. Capture, organize, and act — beautifully.</p>
        <p style="font-size:0.82rem;color:rgba(255,255,255,0.45);margin-bottom:16px;">
          Elysian Realm · Central Archive<br>Horizon Spire 12, Reverie District<br>Phone: +81 570 314 3572
        </p>
        <div class="footer-social">
          <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#hero"><i class="bi bi-chevron-right"></i> Home</a></li>
          <li><a href="#features"><i class="bi bi-chevron-right"></i> Features</a></li>
          <li><a href="#smart"><i class="bi bi-chevron-right"></i> Smart Notes</a></li>
          <li><a href="#organize"><i class="bi bi-chevron-right"></i> Organize</a></li>
          <li><a href="login"><i class="bi bi-chevron-right"></i> Login</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Stay Updated</h4>
        <p style="font-size:0.85rem;color:rgba(255,255,255,0.55);margin-bottom:16px;">Subscribe for the latest from Ellie!</p>
        <div class="newsletter-form-footer">
          <input type="email" placeholder="your@email.com">
          <button onclick="this.textContent='✓ Subscribed!';setTimeout(()=>this.textContent='Subscribe',2500)">Subscribe</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 ELLIE Notes Assistant. All rights reserved.</span>
      <span>Made with <span style="color:var(--pink);">♥</span> for smart note-takers</span>
    </div>
  </footer>

  <!-- SCROLL TOP -->
  <a href="#" id="scroll-top"><i class="bi bi-arrow-up-short"></i></a>

  <script>
    // Header scroll effect
    const header = document.getElementById('header');
    const scrollTop = document.getElementById('scroll-top');
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 20);
      scrollTop.classList.toggle('visible', window.scrollY > 300);
    });

    // Fade-in on scroll
    const faders = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    faders.forEach(el => observer.observe(el));

    // Trigger strip items
    document.querySelectorAll('.strip-item.fade-in').forEach(el => observer.observe(el));
  </script>
</body>
</html>