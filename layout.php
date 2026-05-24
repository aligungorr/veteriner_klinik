<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: giris.php');
    exit;
}
require_once 'dal/db.php';
require_once 'bl/services.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetKlinik Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; }
        .sidebar {
            width: 260px; min-height: 100vh;
            background: linear-gradient(180deg, #1565c0 0%, #1976d2 60%, #1e88e5 100%);
            position: fixed; top: 0; left: 0; z-index: 1000;
            box-shadow: 4px 0 15px rgba(0,0,0,0.15);
        }
        .sidebar-logo {
            padding: 30px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            text-align: center;
        }
        .sidebar-logo .logo-icon {
            width: 65px; height: 65px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .sidebar-logo .logo-icon i { font-size: 28px; color: white; }
        .sidebar-logo h5 { color: white; font-weight: 700; font-size: 1rem; margin: 0; }
        .sidebar-logo small { color: rgba(255,255,255,0.6); font-size: 0.75rem; }
        .sidebar-menu { padding: 15px 0; }
        .menu-label {
            color: rgba(255,255,255,0.5); font-size: 0.7rem;
            font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; padding: 15px 20px 8px;
        }
        .nav-item { margin: 2px 10px; }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            border-radius: 10px; padding: 11px 15px;
            display: flex; align-items: center;
            transition: all 0.3s; font-size: 0.9rem;
            text-decoration: none;
        }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; transform: translateX(4px); }
        .nav-link.active { background: rgba(255,255,255,0.25); color: white !important; font-weight: 600; }
        .nav-link i { width: 20px; margin-right: 10px; font-size: 1rem; }
        .sidebar-footer {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 15px; border-top: 1px solid rgba(255,255,255,0.15);
        }
        .user-info {
            display: flex; align-items: center;
            background: rgba(255,255,255,0.1);
            border-radius: 10px; padding: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-right: 10px;
        }
        .user-avatar i { color: white; font-size: 0.9rem; }
        .user-name { color: white; font-size: 0.85rem; font-weight: 600; }
        .user-role { color: rgba(255,255,255,0.5); font-size: 0.7rem; }
        .logout-btn { color: rgba(255,255,255,0.6) !important; margin-left: auto; padding: 4px 8px; border-radius: 6px; transition: all 0.3s; text-decoration: none; }
        .logout-btn:hover { background: rgba(255,0,0,0.2); color: white !important; }
        .main-wrapper { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: white; padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .topbar-title { font-weight: 700; color: #1565c0; font-size: 1.1rem; }
        .topbar-badge { background: #e3f2fd; color: #1565c0; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .content-area { padding: 30px; }
        .card { border: none; border-radius: 16px; box-shadow: 0 2px 20px rgba(0,0,0,0.06); }
        .card-header { background: white; border-bottom: 1px solid #f0f4f8; border-radius: 16px 16px 0 0 !important; padding: 18px 24px; font-weight: 700; color: #1565c0; }
        .table th { background: #f8fbff; color: #1565c0; font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e3f2fd; padding: 14px 16px; }
        .table td { padding: 13px 16px; vertical-align: middle; color: #444; font-size: 0.9rem; }
        .table tbody tr:hover { background: #f8fbff; }
        .btn-primary-custom { background: linear-gradient(135deg, #1976d2, #1565c0); border: none; color: white; border-radius: 10px; padding: 10px 20px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; box-shadow: 0 4px 12px rgba(21,101,192,0.3); cursor: pointer; }
        .btn-primary-custom:hover { transform: translateY(-2px); color: white; }
        .btn-edit { background: #fff3cd; border: none; color: #856404; border-radius: 8px; padding: 6px 10px; transition: all 0.2s; cursor: pointer; }
        .btn-edit:hover { background: #ffc107; color: white; }
        .btn-delete { background: #fde8e8; border: none; color: #dc3545; border-radius: 8px; padding: 6px 10px; transition: all 0.2s; cursor: pointer; }
        .btn-delete:hover { background: #dc3545; color: white; }
        .modal-content { border: none; border-radius: 20px; overflow: hidden; }
        .modal-header { background: linear-gradient(135deg, #1976d2, #1565c0); color: white; padding: 20px 25px; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-title { font-weight: 700; font-size: 1rem; }
        .modal-body { padding: 25px; }
        .modal-footer { border-top: 1px solid #f0f0f0; padding: 15px 25px; }
        .form-label { font-weight: 600; color: #333; font-size: 0.85rem; }
        .form-control, .form-select { border: 2px solid #e9ecef; border-radius: 10px; padding: 10px 14px; font-size: 0.9rem; transition: all 0.3s; }
        .form-control:focus, .form-select:focus { border-color: #1976d2; box-shadow: 0 0 0 0.2rem rgba(25,118,210,0.15); }
        .btn-save { background: linear-gradient(135deg, #1976d2, #1565c0); border: none; color: white; border-radius: 10px; padding: 10px 24px; font-weight: 600; cursor: pointer; }
        .btn-save:hover { color: white; opacity: 0.9; }
        .btn-update { background: linear-gradient(135deg, #ffc107, #e0a800); border: none; color: #333; border-radius: 10px; padding: 10px 24px; font-weight: 600; cursor: pointer; }
        .badge-custom { background: #e3f2fd; color: #1565c0; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-paw"></i></div>
        <h5>VetKlinik Pro</h5>
        <small>Yönetim Sistemi</small>
    </div>
    <div class="sidebar-menu">
        <div class="menu-label">Ana Menü</div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </div>
        <div class="menu-label">Yönetim</div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sahipler.php' ? 'active' : '' ?>" href="sahipler.php">
                <i class="fas fa-users"></i> Sahipler
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'doktorlar.php' ? 'active' : '' ?>" href="doktorlar.php">
                <i class="fas fa-user-md"></i> Doktorlar
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'hayvanlar.php' ? 'active' : '' ?>" href="hayvanlar.php">
                <i class="fas fa-dog"></i> Hayvanlar
            </a>
        </div>
        <div class="menu-label">Klinik</div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'muayeneler.php' ? 'active' : '' ?>" href="muayeneler.php">
                <i class="fas fa-stethoscope"></i> Muayeneler
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'odemeler.php' ? 'active' : '' ?>" href="odemeler.php">
                <i class="fas fa-credit-card"></i> Ödemeler
            </a>
        </div>
    </div>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user"></i></div>
            <div>
                <div class="user-name"><?= $_SESSION['username'] ?></div>
                <div class="user-role">Yönetici</div>
            </div>
            <a href="cikis.php" class="logout-btn" title="Çıkış">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">
        <span class="topbar-title"><i class="fas fa-paw me-2"></i>VetKlinik Pro</span>
        <span class="topbar-badge"><i class="fas fa-circle text-success me-1" style="font-size:8px"></i>Sistem Aktif</span>
    </div>
    <div class="content-area">