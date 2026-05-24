<?php
session_start();
require_once 'dal/db.php';
require_once 'bl/services.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $kullanici = kullanici_getir($conn, $username);
    
    if ($kullanici && password_verify($password, $kullanici['kullanici_sifre'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $hata = "Kullanıcı adı veya şifre hatalı!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - VetKlinik Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
        }
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 50%, #42a5f5 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -100px; left: -100px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -80px; right: -80px;
        }
        .left-content { position: relative; z-index: 1; text-align: center; }
        .left-logo {
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 30px;
            border: 3px solid rgba(255,255,255,0.3);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
            70% { box-shadow: 0 0 0 20px rgba(255,255,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }
        .left-logo i { font-size: 55px; color: white; }
        .left-title { color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; }
        .left-subtitle { color: rgba(255,255,255,0.8); font-size: 1rem; line-height: 1.8; max-width: 350px; }
        .features { margin-top: 40px; text-align: left; }
        .feature-item {
            display: flex; align-items: center;
            color: rgba(255,255,255,0.9);
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        .feature-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .feature-icon i { color: white; font-size: 0.9rem; }
        .right-panel {
            width: 45%;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
        }
        .login-form-wrapper { width: 100%; max-width: 380px; }
        .welcome-text { font-size: 1.8rem; font-weight: 800; color: #1a1a2e; margin-bottom: 8px; }
        .welcome-sub { color: #6c757d; font-size: 0.9rem; margin-bottom: 40px; }
        .form-group { margin-bottom: 20px; }
        .form-label { font-weight: 600; color: #444; font-size: 0.85rem; margin-bottom: 8px; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 0.9rem; }
        .form-control {
            border: 2px solid #e8ecf4; border-radius: 12px;
            padding: 13px 14px 13px 40px; font-size: 0.95rem;
            transition: all 0.3s; background: white; width: 100%;
        }
        .form-control:focus { border-color: #1976d2; box-shadow: 0 0 0 4px rgba(25,118,210,0.1); outline: none; }
        .eye-btn {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #aaa; cursor: pointer; font-size: 0.9rem;
        }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #1976d2, #1565c0);
            border: none; border-radius: 12px; padding: 15px;
            font-size: 1rem; font-weight: 700; color: white;
            margin-top: 10px; transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(21,101,192,0.35); cursor: pointer;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(21,101,192,0.45); }
        .alert { border-radius: 12px; border: none; font-size: 0.9rem; margin-bottom: 20px; }
        .copyright { text-align: center; margin-top: 30px; color: #bbb; font-size: 0.78rem; }
    </style>
</head>
<body>
<div class="left-panel">
    <div class="left-content">
        <div class="left-logo"><i class="fas fa-paw"></i></div>
        <div class="left-title">VetKlinik Pro</div>
        <div class="left-subtitle">Veteriner kliniklerine özel geliştirilmiş profesyonel yönetim sistemi.</div>
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                Hasta ve sahip yönetimi
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-stethoscope"></i></div>
                Muayene ve tedavi takibi
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                Ödeme ve finans yönetimi
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                Detaylı raporlama ve istatistikler
            </div>
        </div>
    </div>
</div>

<div class="right-panel">
    <div class="login-form-wrapper">
        <div class="welcome-text">Tekrar Hoş Geldiniz! 👋</div>
        <div class="welcome-sub">Devam etmek için lütfen giriş yapın.</div>

        <?php if (isset($hata)): ?>
            <div class="alert alert-danger"><?= $hata ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Kullanıcı Adı</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı adınızı girin" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Şifre</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Şifrenizi girin" required>
                    <button type="button" class="eye-btn" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
            </button>
        </form>
        <div class="copyright">© 2026 VetKlinik Pro · Tüm hakları saklıdır.</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>