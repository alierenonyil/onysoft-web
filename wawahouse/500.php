<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * 500 - Sunucu Hatası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

http_response_code(500);
$pageTitle = '500 - Sunucu Hatası';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
            <div class="error-page">
                <h1 class="display-1 text-warning" style="font-size: 150px; font-weight: bold;">500</h1>
                <h2 class="mb-4">Sunucu Hatası</h2>
                <p class="lead mb-4">
                    Üzgünüz, bir şeyler ters gitti. Lütfen daha sonra tekrar deneyiniz.
                </p>

                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 80px;"></i>
                </div>

                <p class="text-muted mb-4">
                    Sorun devam ederse lütfen <a href="<?= url('iletisim.php') ?>">bizimle iletişime</a> geçin.
                </p>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="<?= url() ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Ana Sayfaya Dön
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
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
