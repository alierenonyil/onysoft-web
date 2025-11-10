<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * 403 - Erişim Engellendi
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

http_response_code(403);
$pageTitle = '403 - Erişim Engellendi';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger" style="font-size: 150px; font-weight: bold;">403</h1>
                <h2 class="mb-4">Erişim Engellendi</h2>
                <p class="lead mb-4">
                    Bu sayfayı görüntüleme yetkiniz bulunmamaktadır.
                </p>

                <div class="mb-4">
                    <i class="fas fa-lock text-danger" style="font-size: 80px;"></i>
                </div>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="<?= url() ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Ana Sayfaya Dön
                    </a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="<?= url('giris.php') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Giriş Yap
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 50px 0;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
