<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Ürün Detay Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);

// URL'den slug al
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: ' . url('urunler.php'));
    exit;
}

// Ürünü getir
$product = db()->fetchOne("SELECT * FROM urunler WHERE slug = ? AND aktif = 1", [$slug]);

if (!$product) {
    header('Location: ' . url('urunler.php'));
    exit;
}

$pageTitle = clean($product['ad']) . ' - WawaHouse';
$pageDescription = truncateDescription($product['aciklama'], 160);

require_once __DIR__ . '/includes/header.php';

// Ürün resimlerini getir
$images = db()->fetchAll("SELECT * FROM urun_resimleri WHERE urun_id = ? ORDER BY ana_resim DESC, sira ASC", [$product['id']]);

// Ürün varyantlarını getir
$variants = db()->fetchAll("SELECT * FROM urun_varyantlari WHERE urun_id = ? AND aktif = 1", [$product['id']]);

// Kategoriye göre ilgili ürünler
$relatedProducts = db()->fetchAll("
    SELECT u.*,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM urunler u
    WHERE u.kategori_id = ? AND u.id != ? AND u.aktif = 1
    ORDER BY RAND()
    LIMIT 4
", [$product['kategori_id'], $product['id']]);
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url() ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="<?= url('urunler.php') ?>">Ürünler</a></li>
            <li class="breadcrumb-item active"><?= clean($product['ad']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Ürün Resimleri -->
        <div class="col-lg-6 mb-4">
            <div class="product-images">
                <?php if (!empty($images)): ?>
                    <img src="<?= upload($images[0]['resim']) ?>" alt="<?= clean($product['ad']) ?>" class="img-fluid product-main-image mb-3" style="border-radius: 15px; width: 100%;">

                    <?php if (count($images) > 1): ?>
                        <div class="product-thumbnails d-flex gap-2">
                            <?php foreach ($images as $image): ?>
                                <img src="<?= upload($image['resim']) ?>" alt="" class="img-thumbnail product-thumbnail" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" data-image="<?= upload($image['resim']) ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <img src="<?= asset('images/no-image.jpg') ?>" alt="Resim yok" class="img-fluid" style="border-radius: 15px;">
                <?php endif; ?>
            </div>
        </div>

        <!-- Ürün Bilgileri -->
        <div class="col-lg-6">
            <h1 class="h2 mb-3"><?= clean($product['ad']) ?></h1>

            <div class="product-price mb-4">
                <?php if ($product['indirimli_fiyat']): ?>
                    <h3 class="text-primary"><?= formatPrice($product['indirimli_fiyat']) ?></h3>
                    <p class="text-muted"><del><?= formatPrice($product['fiyat']) ?></del></p>
                <?php else: ?>
                    <h3 class="text-primary"><?= formatPrice($product['fiyat']) ?></h3>
                <?php endif; ?>
            </div>

            <?php if (!empty($variants)): ?>
                <div class="variants mb-4">
                    <!-- Beden Seçimi -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Beden:</strong></label>
                        <div class="d-flex gap-2">
                            <?php
                            $sizes = array_unique(array_column($variants, 'beden'));
                            foreach ($sizes as $size):
                            ?>
                                <button class="btn btn-outline-primary variant-size" data-size="<?= clean($size) ?>">
                                    <?= clean($size) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Renk Seçimi -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Renk:</strong></label>
                        <div class="d-flex gap-2">
                            <?php
                            $colors = array_unique(array_column($variants, 'renk'));
                            foreach ($colors as $color):
                            ?>
                                <button class="btn btn-outline-primary variant-color" data-color="<?= clean($color) ?>">
                                    <?= clean($color) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-grid gap-2 mb-4">
                <button class="btn btn-primary btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                    <i class="fas fa-shopping-cart"></i>
                    Sepete Ekle
                </button>
                <button class="btn btn-outline-secondary" onclick="addToFavorites(<?= $product['id'] ?>)">
                    <i class="far fa-heart"></i>
                    Favorilere Ekle
                </button>
            </div>

            <div class="product-description">
                <h5>Ürün Açıklaması</h5>
                <p><?= nl2br(clean($product['aciklama'])) ?></p>
            </div>

            <?php if ($product['ozellikler']): ?>
                <div class="product-features mt-4">
                    <h5>Ürün Özellikleri</h5>
                    <p><?= nl2br(clean($product['ozellikler'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- İlgili Ürünler -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">İlgili Ürünler</h3>
            </div>
            <?php foreach ($relatedProducts as $relProduct): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="<?= url('urun/' . $relProduct['slug']) ?>">
                                <img src="<?= upload($relProduct['resim'] ?? 'products/default.jpg') ?>" alt="<?= clean($relProduct['ad']) ?>">
                            </a>
                        </div>
                        <div class="product-info">
                            <a href="<?= url('urun/' . $relProduct['slug']) ?>" class="product-title">
                                <?= clean($relProduct['ad']) ?>
                            </a>
                            <div class="product-price">
                                <?= formatPrice($relProduct['indirimli_fiyat'] ?? $relProduct['fiyat']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
