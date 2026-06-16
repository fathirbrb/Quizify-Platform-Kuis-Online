<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quizify — Platform Kuis Online untuk Kampus</title>
    <meta name="description" content="Platform kuis online modern untuk perguruan tinggi. Buat kuis, kelola kelas, dan pantau nilai mahasiswa secara real-time." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/landing.css" />
</head>
<body class="landing-body">

<!-- ═══════════════ NAVBAR ═══════════════ -->
<nav class="landing-nav" id="landingNav">
    <div class="nav-brand">
        <div class="nav-brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <span class="nav-brand-text">Quizify</span>
    </div>

    <button class="nav-mobile-toggle" id="mobileToggle" aria-label="Toggle menu">☰</button>

    <div class="nav-links" id="navLinks">
        <a href="#fitur" class="nav-link">Fitur</a>
        <a href="#peran" class="nav-link">Peran</a>
        <a href="#cara-kerja" class="nav-link">Cara Kerja</a>
        <a href="index.php?page=login" class="nav-cta">Masuk</a>
    </div>
</nav>

<!-- ═══════════════ HERO ═══════════════ -->
<section class="hero" id="hero">
    <div class="hero-bg"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>
    <div class="hero-grid"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Platform Kuis Online untuk Kampus
        </div>

        <h1 class="hero-title">
            Buat Kuis Lebih <span class="hero-title-accent">Cerdas & Cepat</span>
        </h1>

        <p class="hero-subtitle">
            Platform kuis online modern untuk dosen dan mahasiswa.
            Buat soal, kelola kelas, dan pantau hasil nilai — semua dalam satu sistem terintegrasi.
        </p>

        <div class="hero-actions">
            <a href="index.php?page=login" class="hero-btn-primary">
                Mulai Sekarang
            </a>
            <a href="#fitur" class="hero-btn-secondary">
                Pelajari Fitur
            </a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="500">0</div>
                <div class="hero-stat-label">Mahasiswa</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="120">0</div>
                <div class="hero-stat-label">Kuis Dibuat</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="50">0</div>
                <div class="hero-stat-label">Dosen Aktif</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="98">0</div>
                <div class="hero-stat-label">% Kepuasan</div>
            </div>
        </div>
    </div>
</section>



    <div class="features-grid">
        <div class="feature-card reveal">
            <div class="feature-icon feature-icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
            <h3 class="feature-title">Kuis Real-Time</h3>
            <p class="feature-desc">
                Timer otomatis, auto-save jawaban, dan submit otomatis saat waktu habis.
            </p>
        </div>
        <div class="feature-card reveal">
            <div class="feature-icon feature-icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <h3 class="feature-title">Analisis Nilai</h3>
            <p class="feature-desc">
                Statistik nilai otomatis — rata-rata, tertinggi, terendah, dan tingkat kelulusan.
            </p>
        </div>
        <div class="feature-card reveal">
            <div class="feature-icon feature-icon-orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5M6 12v5m12-5v5m-6-2v7"></path>
                </svg>
            </div>
            <h3 class="feature-title">Manajemen Kelas</h3>
            <p class="feature-desc">
                Kelola kelas, mata kuliah, dan assign dosen-kelas dengan kode undangan.
            </p>
        </div>
        <div class="feature-card reveal">
            <div class="feature-icon feature-icon-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h3 class="feature-title">Aman & Terpercaya</h3>
            <p class="feature-desc">
                Role-based access control untuk admin, dosen, dan mahasiswa.
            </p>
        </div>
    </div>
</section>

<!-- ═══════════════ ROLES ═══════════════ -->
<section class="section" id="peran">
    <div class="section-header reveal">
        <span class="section-tag">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Tiga Peran
        </span>
        <h2 class="section-title">Dirancang untuk Semua Pengguna</h2>
        <p class="section-desc">
            Setiap peran memiliki dashboard dan fitur yang disesuaikan dengan kebutuhannya.
        </p>
    </div>

    <div class="roles-grid">
        <!-- Admin -->
        <div class="role-card role-card-admin reveal">
            <div class="role-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h3 class="role-card-title">Admin</h3>
            <p class="role-card-desc">
                Kendali penuh atas sistem. Kelola akun, kelas, mata kuliah, dan pantau seluruh aktivitas.
            </p>
            <div class="role-card-features">
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Kelola akun pengguna
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Manajemen kelas & jurusan
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Activity log real-time
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Role management
                </div>
            </div>
        </div>

        <!-- Dosen -->
        <div class="role-card role-card-dosen reveal">
            <div class="role-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5v-15z"></path>
                </svg>
            </div>
            <h3 class="role-card-title">Dosen</h3>
            <p class="role-card-desc">
                Buat dan kelola kuis, tambahkan soal, serta pantau hasil dan progres mahasiswa.
            </p>
            <div class="role-card-features">
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Buat kuis & soal pilihan ganda
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Konfigurasi jadwal & durasi
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Monitoring progres mahasiswa
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Laporan nilai & statistik
                </div>
            </div>
        </div>

        <!-- Mahasiswa -->
        <div class="role-card role-card-mhs reveal">
            <div class="role-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                </svg>
            </div>
            <h3 class="role-card-title">Mahasiswa</h3>
            <p class="role-card-desc">
                Gabung kelas, kerjakan kuis dengan timer, dan pantau riwayat nilai secara lengkap.
            </p>
            <div class="role-card-features">
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Join kelas dengan kode undangan
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Kerjakan kuis real-time
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Auto-save jawaban otomatis
                </div>
                <div class="role-feature">
                    <span class="role-feature-check">✓</span>
                    Riwayat nilai & statistik
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════ HOW IT WORKS ═══════════════ -->
<section class="section features-section" id="cara-kerja">
    <div class="section-header reveal">
        <span class="section-tag">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;">
                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path>
            </svg>
            Cara Kerja
        </span>
        <h2 class="section-title">Mulai dalam 3 Langkah</h2>
        <p class="section-desc">
            Proses yang simpel dari registrasi sampai mengerjakan kuis pertamamu.
        </p>
    </div>

    <div class="steps-grid">
        <div class="step-card reveal">
            <div class="step-number">
                <span class="step-digit">1</span>
            </div>
            <h3 class="step-title">Buat Akun</h3>
            <p class="step-desc">
                Admin mendaftarkan akun dosen dan mahasiswa ke dalam sistem Quizify.
            </p>
        </div>
        <div class="step-card reveal">
            <div class="step-number">
                <span class="step-digit">2</span>
            </div>
            <h3 class="step-title">Kelola Kelas</h3>
            <p class="step-desc">
                Dosen membuat kuis dan soal, mahasiswa join kelas menggunakan kode undangan.
            </p>
        </div>
        <div class="step-card reveal">
            <div class="step-number">
                <span class="step-digit">3</span>
            </div>
            <h3 class="step-title">Kerjakan & Pantau</h3>
            <p class="step-desc">
                Mahasiswa mengerjakan kuis, dosen melihat hasil — semua real-time dan otomatis.
            </p>
        </div>
    </div>
</section>

<!-- ═══════════════ CTA ═══════════════ -->
<section class="cta-section">
    <div class="cta-box reveal">
        <h2 class="cta-title">Siap Memulai dengan Quizify?</h2>
        <p class="cta-desc">
            Masuk sekarang dan rasakan pengalaman kuis online yang modern, cepat, dan terintegrasi.
        </p>
        <a href="index.php?page=login" class="cta-btn">
            Masuk ke Quizify →
        </a>
    </div>
</section>

<!-- ═══════════════ FOOTER ═══════════════ -->
<footer class="landing-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <span class="footer-brand-text">Quizify</span>
        </div>
        <div class="footer-links">
            <a href="#fitur" class="footer-link">Fitur</a>
            <a href="#peran" class="footer-link">Peran</a>
            <a href="#cara-kerja" class="footer-link">Cara Kerja</a>
            <a href="index.php?page=login" class="footer-link">Masuk</a>
        </div>
        <span class="footer-copy">© <?= date('Y') ?> Quizify. All rights reserved.</span>
    </div>
</footer>

<!-- ═══════════════ SCRIPTS ═══════════════ -->
<script>
// ── Navbar scroll effect ──
const nav = document.getElementById('landingNav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
});

// ── Mobile menu toggle ──
const mobileToggle = document.getElementById('mobileToggle');
const navLinks = document.getElementById('navLinks');
mobileToggle.addEventListener('click', () => {
    navLinks.classList.toggle('open');
    mobileToggle.textContent = navLinks.classList.contains('open') ? '✕' : '☰';
});

// Close mobile menu on link click
navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('open');
        mobileToggle.textContent = '☰';
    });
});

// ── Counter animation ──
function animateCounters() {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        const duration = 1800;
        const start = performance.now();

        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(ease * target);
            el.textContent = current + (el.closest('.hero-stat-label')?.textContent.includes('%') ? '' : '+');
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target + '+';
        }

        requestAnimationFrame(step);
    });
}

// ── Scroll reveal ──
const revealElements = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add('visible');
            }, index * 80);
            revealObserver.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.15,
    rootMargin: '0px 0px -40px 0px'
});

revealElements.forEach(el => revealObserver.observe(el));

// ── Counter trigger on hero visible ──
const heroObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            heroObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });

heroObserver.observe(document.getElementById('hero'));

// ── Smooth scroll for anchor links ──
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

</body>
</html>
