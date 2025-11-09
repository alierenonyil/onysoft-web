<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Panel Header
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$adminUser = getAdminUser();
$pageTitle = $pageTitle ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= clean($pageTitle) ?> - WawaHouse Admin</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Admin CSS -->
    <link href="<?= url('assets/css/admin.css') ?>" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #0d6efd;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }

        .admin-sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .logo h4 {
            color: white;
            margin: 0;
            font-weight: bold;
        }

        .admin-sidebar .nav-menu {
            padding: 20px 0;
        }

        .admin-sidebar .nav-item {
            margin: 5px 15px;
        }

        .admin-sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            text-decoration: none;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            background: white;
            height: var(--header-height);
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-header .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Content */
        .admin-content {
            padding: 30px;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card.primary .icon {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .stat-card.success .icon {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .stat-card.warning .icon {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stat-card.danger .icon {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Tables */
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="logo">
            <h4><i class="fas fa-baby-carriage"></i> WawaHouse</h4>
            <small style="color: #95a5a6;">Admin Panel</small>
        </div>

        <nav class="nav-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kategoriler.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'kategoriler.php' ? 'active' : '' ?>">
                        <i class="fas fa-folder"></i>
                        <span>Kategoriler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="urunler.php" class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'urun') ? 'active' : '' ?>">
                        <i class="fas fa-box"></i>
                        <span>Ürünler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="siparisler.php" class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'siparis') ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Siparişler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="musteriler.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'musteriler.php' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Müşteriler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kuponlar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'kuponlar.php' ? 'active' : '' ?>">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Kuponlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="yorumlar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'yorumlar.php' ? 'active' : '' ?>">
                        <i class="fas fa-comments"></i>
                        <span>Yorumlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="sliderlar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'sliderlar.php' ? 'active' : '' ?>">
                        <i class="fas fa-images"></i>
                        <span>Sliderlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="sayfalar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'sayfalar.php' ? 'active' : '' ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Sayfalar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="iadeler.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'iadeler.php' ? 'active' : '' ?>">
                        <i class="fas fa-undo"></i>
                        <span>İadeler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="raporlar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'raporlar.php' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Raporlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="ayarlar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'ayarlar.php' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i>
                        <span>Ayarlar</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div>
                <h5 class="mb-0"><?= clean($pageTitle) ?></h5>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($adminUser['ad_soyad'], 0, 1)) ?>
                </div>
                <div>
                    <strong><?= clean($adminUser['ad_soyad']) ?></strong>
                    <br>
                    <small class="text-muted"><?= clean($adminUser['email']) ?></small>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger ms-3">
                    <i class="fas fa-sign-out-alt"></i> Çıkış
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <div class="admin-content">
            <?= renderAlerts() ?>
