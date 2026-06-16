<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — Quizify</title>
    <meta name="description" content="Masuk ke Quizify — platform kuis online untuk perguruan tinggi." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/style.css" />
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* ── Background Effects ── */
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(30, 58, 138, 0.07) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 85% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse 40% 35% at 10% 70%, rgba(139, 92, 246, 0.04) 0%, transparent 50%),
                var(--bg);
        }

        .login-bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(30, 58, 138, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 58, 138, 0.025) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 20%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 20%, transparent 70%);
        }

        .login-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.4;
            z-index: 0;
            animation: orbDrift 10s ease-in-out infinite;
        }

        .login-orb-1 {
            width: 280px;
            height: 280px;
            background: rgba(59, 130, 246, 0.15);
            top: 5%;
            right: 15%;
        }

        .login-orb-2 {
            width: 200px;
            height: 200px;
            background: rgba(139, 92, 246, 0.1);
            bottom: 15%;
            left: 10%;
            animation-delay: -4s;
        }

        @keyframes orbDrift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(15px, -10px); }
        }

        /* ── Login Card ── */
        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(229, 231, 235, 0.6);
            border-radius: 20px;
            padding: 2.8rem 2.4rem;
            box-shadow:
                0 8px 40px rgba(30, 58, 138, 0.08),
                0 1px 3px rgba(0, 0, 0, 0.04);
            animation: cardIn 0.5s ease both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Brand Header ── */
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #1E3A8A, #3B82F6);
            border-radius: 14px;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
        }

        .login-brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
            margin-bottom: 0.3rem;
        }

        .login-brand-sub {
            font-size: 0.82rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        /* ── Error Alert ── */
        .login-alert {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1rem;
            background: var(--red-light);
            border: 1px solid #FECACA;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #DC2626;
            margin-bottom: 1.2rem;
            animation: shakeX 0.4s ease;
        }

        .login-alert-icon {
            flex-shrink: 0;
            font-size: 1rem;
        }

        @keyframes shakeX {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        /* ── Form Styles ── */
        .login-form-group {
            margin-bottom: 1.1rem;
        }

        .login-form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--black);
            margin-bottom: 0.35rem;
        }

        .login-input-wrap {
            position: relative;
        }

        .login-input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            pointer-events: none;
            opacity: 0.5;
        }

        .login-input {
            width: 100%;
            padding: 0.7rem 0.9rem 0.7rem 2.6rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-family: var(--font);
            font-size: 0.85rem;
            color: var(--black);
            background: rgba(255, 255, 255, 0.6);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .login-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }

        .login-input::placeholder {
            color: var(--gray-400);
        }

        .login-toggle-pw {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            color: var(--gray-400);
            padding: 0.2rem;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
        }

        .login-toggle-pw:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        /* ── Submit Button ── */
        .login-submit {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.4rem;
            background: linear-gradient(135deg, #1E3A8A, #2563EB);
            color: white;
            font-family: var(--font);
            font-size: 0.88rem;
            font-weight: 700;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.15s;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
        }

        .login-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.35);
        }

        .login-submit:active {
            transform: scale(0.99);
            opacity: 0.92;
        }

        /* ── Divider ── */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 1.5rem 0;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .login-divider-text {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Demo Accounts ── */
        .demo-accounts {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .demo-btn {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            width: 100%;
            padding: 0.6rem 0.85rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            cursor: pointer;
            font-family: var(--font);
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
        }

        .demo-btn:hover {
            background: white;
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .demo-btn:active {
            transform: scale(0.99);
        }

        .demo-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .demo-dot-admin {
            background: linear-gradient(135deg, #1E3A8A, #3B82F6);
        }

        .demo-dot-dosen {
            background: linear-gradient(135deg, #F97316, #F59E0B);
        }

        .demo-dot-mhs {
            background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        }

        .demo-info {
            flex: 1;
            text-align: left;
        }

        .demo-role-name {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--black);
            line-height: 1;
            margin-bottom: 0.15rem;
        }

        .demo-email {
            font-size: 0.7rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        .demo-arrow {
            font-size: 0.75rem;
            color: var(--gray-400);
            transition: color 0.15s, transform 0.15s;
        }

        .demo-btn:hover .demo-arrow {
            color: var(--accent);
            transform: translateX(2px);
        }

        /* ── Back to landing ── */
        .login-back {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-back-link {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--gray-400);
            transition: color 0.2s;
        }

        .login-back-link:hover {
            color: var(--primary);
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }

            .login-brand-name {
                font-size: 1.3rem;
            }

            .login-submit {
                font-size: 0.82rem;
            }
        }

        /* Inline SVG styling */
        .login-logo svg {
            width: 24px;
            height: 24px;
            stroke: white;
            display: block;
        }

        .login-alert-icon svg {
            width: 16px;
            height: 16px;
            stroke: #DC2626;
            display: block;
        }

        .login-input-icon svg {
            width: 16px;
            height: 16px;
            stroke: var(--gray-600);
            display: block;
        }

        .login-toggle-pw svg {
            width: 18px;
            height: 18px;
            stroke: var(--gray-400);
            display: block;
            transition: stroke 0.15s;
        }

        .login-toggle-pw:hover svg {
            stroke: var(--primary);
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="login-bg"></div>
<div class="login-bg-grid"></div>
<div class="login-orb login-orb-1"></div>
<div class="login-orb login-orb-2"></div>

<!-- Login Card -->
<div class="login-card">

    <!-- Brand -->
    <div class="login-brand">
        <div class="login-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h1 class="login-brand-name">Quizify</h1>
        <p class="login-brand-sub">Masuk ke akun kamu untuk melanjutkan</p>
    </div>

    <!-- Error -->
    <?php if (!empty($error)): ?>
    <div class="login-alert">
        <span class="login-alert-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </span>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" action="index.php?page=login&action=process" id="loginForm">
        <div class="login-form-group">
            <label for="email" class="login-form-label">Email</label>
            <div class="login-input-wrap">
                <span class="login-input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </span>
                <input type="email" id="email" name="email" class="login-input"
                    placeholder="email@quizify.com" required autocomplete="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
            </div>
        </div>

        <div class="login-form-group">
            <label for="password" class="login-form-label">Password</label>
            <div class="login-input-wrap">
                <span class="login-input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input type="password" id="password" name="password" class="login-input"
                    placeholder="••••••••" required autocomplete="current-password" />
                <button type="button" class="login-toggle-pw" id="togglePw" aria-label="Tampilkan password">
                    <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="login-submit">
            Masuk →
        </button>
    </form>

    <!-- Demo Accounts -->
    <div class="login-divider">
        <span class="login-divider-text">Akun Demo</span>
    </div>

    <div class="demo-accounts">
        <button type="button" class="demo-btn" onclick="fillDemo('admin@quizify.com','password')">
            <span class="demo-dot demo-dot-admin"></span>
            <div class="demo-info">
                <div class="demo-role-name">Admin</div>
                <div class="demo-email">admin@quizify.com</div>
            </div>
            <span class="demo-arrow">→</span>
        </button>
        <button type="button" class="demo-btn" onclick="fillDemo('budi@quizify.com','password')">
            <span class="demo-dot demo-dot-dosen"></span>
            <div class="demo-info">
                <div class="demo-role-name">Dosen</div>
                <div class="demo-email">budi@quizify.com</div>
            </div>
            <span class="demo-arrow">→</span>
        </button>
        <button type="button" class="demo-btn" onclick="fillDemo('andi@quizify.com','password')">
            <span class="demo-dot demo-dot-mhs"></span>
            <div class="demo-info">
                <div class="demo-role-name">Mahasiswa</div>
                <div class="demo-email">andi@quizify.com</div>
            </div>
            <span class="demo-arrow">→</span>
        </button>
    </div>

    <!-- Back link -->
    <div class="login-back">
        <a href="index.php" class="login-back-link">← Kembali ke Beranda</a>
    </div>
</div>

<script>
// Toggle password visibility
const toggleBtn = document.getElementById('togglePw');
const pwInput   = document.getElementById('password');
toggleBtn.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    toggleBtn.querySelector('.eye-open').style.display = show ? 'none' : 'block';
    toggleBtn.querySelector('.eye-closed').style.display = show ? 'block' : 'none';
});

// Autofill demo account
function fillDemo(email, password) {
    document.getElementById('email').value    = email;
    document.getElementById('password').value = password;

    // Visual feedback
    const btn = event.currentTarget;
    btn.style.borderColor = '#22C55E';
    btn.style.background = '#F0FDF4';
    setTimeout(() => {
        btn.style.borderColor = '';
        btn.style.background = '';
    }, 600);
}

// Auto-focus email input
window.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    if (!emailInput.value) emailInput.focus();
});
</script>

</body>
</html>
