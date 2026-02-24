<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta name="description" content="Advocate Veerendrababu Nanjegowda — MBA, LLB, PhD(Pursuing). Practicing at Supreme Court of India, High Courts & NGT. Author, Filmmaker, Senior Journalist. Bengaluru Law House."/>
<meta name="keywords" content="Veerendrababu Nanjegowda, Advocate, Supreme Court, Bengaluru Law House, ADR, Karnataka Lawyer, Kannada Author"/>
<title>Adv. Veerendrababu Nanjegowda | Bengaluru Law House</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
:root {
  --gold: #c9a84c;
  --gold-light: #e8c878;
  --gold-dark: #8b6914;
  --navy: #0c1428;
  --navy-mid: #152040;
  --navy-light: #1e2f58;
  --cream: #f8f5ef;
  --cream-dark: #ede8de;
  --text: #2a2a2a;
  --text-muted: #666;
  --white: #ffffff;
  --font-display: "Cormorant Garamond", Georgia, serif;
  --font-body: "Outfit", Arial, sans-serif;
}

*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; scroll-behavior:smooth; }

body {
  font-family: var(--font-body);
  background: var(--cream);
  color: var(--text);
  overflow-x: hidden;
}

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width:5px; }
::-webkit-scrollbar-track { background:var(--navy); }
::-webkit-scrollbar-thumb { background:var(--gold); border-radius:10px; }

/* ══════════════════════════════════
   NAVIGATION
══════════════════════════════════ */
.nav {
  position: fixed; top:0; left:0; right:0; z-index:1000;
  background: rgba(12,20,40,0.97);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(201,168,76,0.25);
  padding: 0 40px;
  display: flex; align-items:center; justify-content:space-between;
  height: 64px;
  transition: all 0.3s;
}
.nav-brand {
  display:flex; align-items:center; gap:12px; text-decoration:none;
}
.nav-brand-seal {
  width:36px; height:36px;
  background:linear-gradient(135deg,var(--gold),var(--gold-dark));
  border-radius:50%; display:flex; align-items:center; justify-content:center;
  font-size:1rem; color:var(--navy); font-weight:700;
}
.nav-brand-text { font-family:var(--font-display); color:var(--gold); font-size:1.05rem; font-weight:600; line-height:1.2; }
.nav-brand-sub { font-size:0.62rem; color:rgba(255,255,255,0.45); letter-spacing:2px; text-transform:uppercase; }
.nav-links { display:flex; gap:6px; align-items:center; }
.nav-links a {
  color:rgba(255,255,255,0.65); font-size:0.75rem; letter-spacing:2px;
  text-transform:uppercase; text-decoration:none; padding:8px 14px; border-radius:6px;
  transition:all 0.25s;
}
.nav-links a:hover { color:var(--gold); background:rgba(201,168,76,0.1); }
.nav-cta {
  background:linear-gradient(135deg,var(--gold),var(--gold-dark));
  color:var(--navy) !important; font-weight:600 !important;
  border-radius:6px; padding:8px 18px !important;
}

/* ══════════════════════════════════
   HERO
══════════════════════════════════ */
.hero {
  min-height:100vh;
  background: var(--navy);
  display:flex; align-items:center;
  position:relative; overflow:hidden;
  padding-top:64px;
}
.hero-bg {
  position:absolute; inset:0;
  background:
    radial-gradient(ellipse 80% 60% at 70% 30%, rgba(201,168,76,0.08) 0%, transparent 60%),
    radial-gradient(ellipse 60% 80% at 20% 80%, rgba(30,47,88,0.8) 0%, transparent 50%),
    linear-gradient(160deg, #0c1428 0%, #152040 40%, #0d1e3a 100%);
}
.hero-grid-overlay {
  position:absolute; inset:0;
  background-image:
    linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
  background-size:60px 60px;
}
.hero-inner {
  position:relative; z-index:2;
  max-width:1200px; margin:0 auto; padding:60px 40px;
  display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center;
}
@keyframes fadeInLeft { from { opacity:0; transform:translateX(-30px); } to { opacity:1; transform:translateX(0); } }
@keyframes fadeInRight { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
@keyframes pulse { 0% { transform:scale(1); opacity:1; } 50% { transform:scale(1.2); opacity:0.7; } 100% { transform:scale(1); opacity:1; } }

.hero-text { animation:fadeInLeft 0.9s ease both; }
.hero-badge {
  display:inline-flex; align-items:center; gap:8px;
  border:1px solid rgba(201,168,76,0.45); color:var(--gold);
  font-size:0.7rem; letter-spacing:4px; text-transform:uppercase;
  padding:7px 18px; border-radius:50px; margin-bottom:28px;
  background:rgba(201,168,76,0.06);
}
.hero-badge::before { content:''; width:6px; height:6px; background:var(--gold); border-radius:50%; animation:pulse 2s infinite; }
.hero-name {
  font-family:var(--font-display);
  font-size:clamp(2.4rem,5vw,3.8rem);
  font-weight:700; color:var(--white);
  line-height:1.1; margin-bottom:8px;
}
.hero-name .accent { color:var(--gold); }
.hero-name-kn {
  font-family:var(--font-display); font-style:italic;
  font-size:1.1rem; color:rgba(201,168,76,0.7);
  display:block; margin-bottom:18px; letter-spacing:1px;
}
.hero-quals {
  font-size:0.78rem; color:rgba(255,255,255,0.5);
  letter-spacing:4px; text-transform:uppercase; margin-bottom:24px;
}
.hero-tagline {
  font-family:var(--font-display); font-style:italic;
  font-size:1.25rem; color:rgba(255,255,255,0.82);
  line-height:1.65; margin-bottom:36px;
  border-left:3px solid var(--gold); padding-left:20px;
}
.hero-actions { display:flex; gap:14px; flex-wrap:wrap; }
.btn-primary {
  display:inline-flex; align-items:center; gap:8px;
  background:linear-gradient(135deg,var(--gold),var(--gold-dark));
  color:var(--navy); font-weight:600; font-size:0.85rem;
  padding:14px 28px; border-radius:8px; text-decoration:none;
  letter-spacing:1px; transition:all 0.3s;
  box-shadow:0 4px 20px rgba(201,168,76,0.3);
}
.btn-outline {
  display:inline-flex; align-items:center; gap:8px;
  border:1px solid rgba(201,168,76,0.5); color:var(--gold);
  font-size:0.85rem; padding:14px 28px; border-radius:8px;
  text-decoration:none; letter-spacing:1px; transition:all 0.3s;
  background:rgba(201,168,76,0.06);
}

.hero-visual { animation:fadeInRight 0.9s ease 0.2s both; position:relative; }
.hero-portrait-wrap {
  position:relative; width:320px; height:400px;
}
.hero-portrait {
  width:100%; height:100%; border-radius:20px; overflow:hidden;
  border:2px solid rgba(201,168,76,0.4); box-shadow: 0 20px 50px rgba(0,0,0,0.5);
}
.hero-portrait img { width:100%; height:100%; object-fit:cover; filter: grayscale(20%); }

/* ══════════════════════════════════
   RESOURCES SECTION (Documentation part)
══════════════════════════════════ */
.resources-section { padding: 80px 40px; background: var(--white); }
.resource-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 1000px; margin: 0 auto; }
.resource-card {
  padding: 40px; border-radius: 20px; background: var(--cream);
  border: 1px solid rgba(201,168,76,0.2); transition: all 0.3s;
  text-decoration: none; color: inherit;
}
.resource-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); border-color: var(--gold); }
.resource-icon { font-size: 3rem; margin-bottom: 20px; display: block; color: var(--gold-dark); }
.resource-title { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; margin-bottom: 10px; }
.resource-desc { color: var(--text-muted); line-height: 1.6; }

/* ══════════════════════════════════
   FOOTER
══════════════════════════════════ */
.footer {
  background: #060c18; padding: 60px 40px; text-align: center;
  border-top: 1px solid rgba(201,168,76,0.2); color: white;
}
.footer-logo-wrap { margin-bottom: 30px; opacity: 0.8; }
.footer-logo-wrap img { height: 60px; filter: brightness(0) invert(1); margin: 0 15px; }
.footer-text { font-size: 0.9rem; color: rgba(255,255,255,0.5); letter-spacing: 1px; }

</style>
</head>
<body>

  <nav class="nav">
    <a href="#" class="nav-brand">
      <div class="nav-brand-seal">V</div>
      <div>
        <div class="nav-brand-text">VEERENDRABABU</div>
        <div class="nav-brand-sub">Bengaluru Law House</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#hero">Home</a>
      <a href="https://laravel.com/docs" target="_blank">Documentation</a>
      <a href="https://laracasts.com" target="_blank">Laracasts</a>
      <a href="#contact" class="nav-cta">Contact</a>
    </div>
  </nav>

  <section class="hero" id="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid-overlay"></div>
    <div class="hero-inner">
      <div class="hero-text">
        <div class="hero-badge">Distinguished Legal Expert</div>
        <h1 class="hero-name">Adv. <span class="accent">Veerendrababu</span><br>Nanjegowda</h1>
        <div class="hero-quals">MBA, LLB, PhD(Pursuing)</div>
        <p class="hero-tagline">
          Practicing at the Supreme Court of India, High Courts & NGT. Distinguished Author, Filmmaker, and Senior Journalist dedicated to justice through legal expertise and academic service.
        </p>
        <div class="hero-actions">
          <a href="https://laravel.com/docs" target="_blank" class="btn-primary">Explore Documentation</a>
          <a href="https://laracasts.com" target="_blank" class="btn-outline">Watch Tutorials</a>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-portrait-wrap">
          <div class="hero-portrait">
            <img src="{{ asset('blr.jpg') }}" alt="Adv. Veerendrababu Nanjegowda">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="resources-section">
    <div class="resource-grid">
      <a href="https://laravel.com/docs" target="_blank" class="resource-card">
        <span class="resource-icon">📚</span>
        <h2 class="resource-title">Official Documentation</h2>
        <p class="resource-desc">Access the comprehensive Laravel documentation to master the framework and build robust legal systems.</p>
      </a>
      <a href="https://laracasts.com" target="_blank" class="resource-card">
        <span class="resource-icon">🎥</span>
        <h2 class="resource-title">Laracasts Tutorials</h2>
        <p class="resource-desc">Watch expert-led video tutorials covering modern web development, architecture, and legal tech implementations.</p>
      </a>
    </div>
  </section>

  <footer class="footer">
    <div class="footer-logo-wrap">
      {{-- Bengaluru Law House Logo --}}
      <img src="{{ asset('blr.jpg') }}" alt="BLR">
      {{-- Laravel Logo as requested --}}
      <svg width="40" height="40" viewBox="0 0 262 262" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-left: 10px;">
        <path d="M255.8 261.9L255.8 0 0 0.2 0 261.9h255.8z" fill="#FF2D20"/>
        <path d="M167.6 77.2l-14.7-8.5-43.5 25.1V60.3l14.7-8.5-14.7-8.5v34.1l-14.7 8.5V43.3l-14.7-8.5L80 43.3v85.2l14.7 8.5V51.8l14.7-8.5 14.7 8.5v85.2l14.7 8.5 43.6-25.1v51.1l-14.7 8.5 14.7 8.5V119.8l14.7-8.5v76.8l14.7-8.5V77.2" fill="#FFF"/>
      </svg>
    </div>
    <div class="footer-text">
       &copy; 2025 Bengaluru Law House | Adv. Veerendrababu Nanjegowda. All Rights Reserved.
    </div>
  </footer>

</body>
</html>
