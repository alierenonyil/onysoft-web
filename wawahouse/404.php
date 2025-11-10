<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * 404 - Sayfa Bulunamadı
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

http_response_code(404);
$pageTitle = '404 - Sayfa Bulunamadı';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
            <div class="error-page">
                <h1 class="display-1 text-primary" style="font-size: 150px; font-weight: bold;">404</h1>
                <h2 class="mb-4">Sayfa Bulunamadı</h2>
                <p class="lead mb-4">
                    Aradığınız sayfa kaldırılmış, adı değiştirilmiş veya geçici olarak kullanılamıyor olabilir.
                </p>

                <div class="mb-4">
                    <i class="fas fa-sad-tear text-muted" style="font-size: 80px;"></i>
                </div>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="<?= url() ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Ana Sayfaya Dön
                    </a>
                    <a href="<?= url('urunler.php') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i> Ürünleri İncele
                    </a>
                    <a href="<?= url('iletisim.php') ?>" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-envelope"></i> Bize Ulaşın
                    </a>
                </div>

                <?php
                // Popüler kategorileri göster
                $popularCategories = db()->fetchAll("
                    SELECT * FROM kategoriler
                    WHERE parent_id IS NULL AND aktif = 1
                    ORDER BY sira ASC
                    LIMIT 6
                ");

                if (!empty($popularCategories)):
                ?>
                <div class="mt-5">
                    <h4 class="mb-3">Popüler Kategoriler</h4>
                    <div class="row g-3">
                        <?php foreach ($popularCategories as $cat): ?>
                        <div class="col-6 col-md-4">
                            <a href="<?= url('kategori/' . $cat['slug']) ?>"
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-folder"></i> <?= clean($cat['ad']) ?>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
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
