<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Frontend Header
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/..');
}

require_once BASE_PATH . '/config.php';

$pageTitle = $pageTitle ?? 'WawaHouse - Bebek Giyim ve Aksesuar';
$pageDescription = $pageDescription ?? 'Bebeğiniz için en kaliteli bebek kıyafetleri';

// Sepet sayısı
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $cartCount = db()->count('sepet', 'kullanici_id = ?', [$_SESSION['user_id']]);
} else {
    $sessionId = getCartSessionId();
    $cartCount = db()->count('sepet', 'session_id = ?', [$sessionId]);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= clean($pageDescription) ?>">
    <title><?= clean($pageTitle) ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
</head>
<body>

<!-- Header -->
<header class="site-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <a href="<?= url() ?>" class="logo">
                    <i class="fas fa-baby-carriage"></i>
                    WawaHouse
                </a>
            </div>
            <div class="col-md-4 text-center">
                <form action="<?= url('urunler.php') ?>" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Ürün ara...">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('hesabim.php') ?>" class="text-white text-decoration-none me-3">
                        <i class="fas fa-user"></i>
                        Hesabım
                    </a>
                <?php else: ?>
                    <a href="<?= url('giris.php') ?>" class="text-white text-decoration-none me-3">
                        <i class="fas fa-sign-in-alt"></i>
                        Giriş
                    </a>
                <?php endif; ?>
                <a href="<?= url('sepet.php') ?>" class="text-white text-decoration-none position-relative">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Navigation -->
<nav class="main-nav">
    <div class="container">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="<?= url() ?>">Ana Sayfa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('urunler.php') ?>">Tüm Ürünler</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('kategori/0-3-ay') ?>">0-3 Ay</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('kategori/3-6-ay') ?>">3-6 Ay</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('kategori/6-12-ay') ?>">6-12 Ay</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('sayfa/hakkimizda') ?>">Hakkımızda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('sayfa/iletisim') ?>">İletişim</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Flash Messages -->
<?php if ($message = getFlash('success')): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show">
            <?= clean($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<?php if ($message = getFlash('error')): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show">
            <?= clean($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>
