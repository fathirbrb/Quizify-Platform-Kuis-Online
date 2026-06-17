<?php
$defaultEmail = 'admin@quizify.com';
$defaultPassword = 'password';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $defaultEmail = $_POST['email'] ?? '';
    $defaultPassword = '';
}
?>
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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #EFF6FF;
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── Left Branding Column ── */
        .login-left {
            flex: 1.1;
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #3B82F6 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        /* Soft drifting background orbs on the left side */
        .login-left::before {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            filter: blur(80px);
            top: -50px;
            left: -50px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            filter: blur(60px);
            bottom: -50px;
            right: -50px;
        }

        .login-brand-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 2;
        }

        .login-brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .login-brand-logo svg {
            width: 22px;
            height: 22px;
            stroke: white;
            display: block;
        }

        .login-brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .login-hero-content {
            margin-top: auto;
            margin-bottom: auto;
            max-width: 500px;
            position: relative;
            z-index: 2;
        }

        .login-hero-title {
            font-size: 3.25rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -1.5px;
        }

        .login-hero-subtitle {
            font-size: 1.35rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 1.5rem;
        }

        .login-hero-desc {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
        }

        .login-left-footer {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 2;
        }

        /* ── Right Form Column ── */
        .login-right {
            flex: 0.9;
            background: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .login-form-card {
            background: white;
            border-radius: 30px;
            width: 100%;
            max-width: 440px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 20px 40px rgba(30, 58, 138, 0.06);
            text-align: center;
        }

        .card-title {
            font-size: 1.85rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .card-subtitle {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 2.25rem;
        }

        /* ── Error Alert ── */
        .login-alert {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #DC2626;
            margin-bottom: 1.25rem;
            text-align: left;
        }

        .login-alert-icon svg {
            width: 16px;
            height: 16px;
            stroke: #DC2626;
            display: block;
        }

        /* ── Input Styles ── */
        .input-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
            padding-left: 0.75rem;
        }

        .input-field-wrap {
            position: relative;
        }

        .pill-input {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border: 1.5px solid #E5E7EB;
            border-radius: 50px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #FAFAFA;
            font-family: inherit;
        }

        .pill-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            background: white;
        }

        .pill-input::placeholder {
            color: #9CA3AF;
        }

        .login-toggle-pw {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-toggle-pw svg {
            width: 18px;
            height: 18px;
            stroke: #9CA3AF;
            display: block;
            transition: stroke 0.15s;
        }

        .login-toggle-pw:hover svg {
            stroke: #3B82F6;
        }

        .forgot-pw-wrap {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.75rem;
            font-size: 0.8rem;
            padding-right: 0.5rem;
        }

        .forgot-pw-link {
            color: #6B7280;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .forgot-pw-link:hover {
            color: #3B82F6;
        }

        /* ── Submit Button ── */
        .pill-submit {
            width: 100%;
            padding: 0.85rem;
            background: #1E3A8A;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
            font-family: inherit;
        }

        .pill-submit:hover {
            background: #2563EB;
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
        }

        .pill-submit:active {
            transform: scale(0.98);
        }

        /* ── Footer / Back to home ── */
        .back-to-home {
            font-size: 0.825rem;
            color: #6B7280;
        }

        .back-link {
            color: #1E3A8A;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }

        .back-link:hover {
            color: #2563EB;
            text-decoration: underline;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
                padding: 1.5rem;
            }
            .login-form-card {
                padding: 3rem 1.5rem;
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">

    <!-- Left Branding Panel -->
    <div class="login-left">
        <div class="login-brand-group">
            <div class="login-brand-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <span class="login-brand-text">Quizify</span>
        </div>

        <div class="login-hero-content">
            <h1 class="login-hero-title">Hey, Hello!</h1>
            <h2 class="login-hero-subtitle">Selamat Datang di Quizify</h2>
            <p class="login-hero-desc">
                Quizify adalah platform kuis online perguruan tinggi yang dirancang untuk mempermudah pengerjaan dan penilaian kuis secara interaktif dan efisien.
            </p>
        </div>

        <div class="login-left-footer">
            &copy; 2026 Quizify Team. All rights reserved.
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="login-right">
        <div class="login-form-card">
            <h2 class="card-title">Welcome Back</h2>
            <p class="card-subtitle">Silakan masuk ke akun Anda untuk memulai.</p>

            <!-- Error Notification -->
            <?php if (!empty($error)): ?>
            <div class="login-alert">
                <span class="login-alert-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="index.php?page=login&action=process" id="loginForm">
                <div class="input-group">
                    <label for="email" class="input-label">Email</label>
                    <div class="input-field-wrap">
                        <input type="email" id="email" name="email" class="pill-input"
                            placeholder="email@quizify.com" required autocomplete="email"
                            value="<?= htmlspecialchars($defaultEmail) ?>" />
                    </div>
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <div class="input-field-wrap">
                        <input type="password" id="password" name="password" class="pill-input"
                            placeholder="••••••••" required autocomplete="current-password"
                            value="<?= htmlspecialchars($defaultPassword) ?>" />
                        <button type="button" class="login-toggle-pw" id="togglePw" aria-label="Tampilkan password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="forgot-pw-wrap">
                    <a href="#" class="forgot-pw-link">Forgot Password?</a>
                </div>

                <button type="submit" class="pill-submit">Login</button>
            </form>


            <!-- Back to Home -->
            <div class="back-to-home">
                Don't have an account? <a href="index.php" class="back-link">Sign Up / Back</a>
            </div>
        </div>
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


// Auto-focus email input
window.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    if (!emailInput.value) emailInput.focus();
});
</script>

</body>
</html>
