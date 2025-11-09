<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Kategori Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);

// URL'den kategori slug al
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: ' . url('urunler.php'));
    exit;
}

// Kategoriyi bul
$kategori = db()->fetchOne("SELECT * FROM kategoriler WHERE slug = ? AND aktif = 1", [$slug]);

if (!$kategori) {
    header('Location: ' . url('urunler.php'));
    exit;
}

$pageTitle = clean($kategori['ad']) . ' - WawaHouse';
$pageDescription = $kategori['seo_description'] ?? clean($kategori['ad']) . ' bebek ürünleri';

require_once __DIR__ . '/includes/header.php';

// Sayfalama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $perPage;

// Alt kategorileri de dahil et
$categoryIds = [$kategori['id']];
$subCategories = db()->fetchAll("SELECT id FROM kategoriler WHERE parent_id = ?", [$kategori['id']]);
foreach ($subCategories as $sub) {
    $categoryIds[] = $sub['id'];
}

$categoryIdsStr = implode(',', $categoryIds);

// Toplam ürün sayısı
$total = db()->fetchOne("
    SELECT COUNT(*) as count
    FROM urunler
    WHERE kategori_id IN ({$categoryIdsStr}) AND aktif = 1
")['count'];

$pagination = paginate($total, $perPage, $page);

// Ürünleri getir
$products = db()->fetchAll("
    SELECT u.*,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM urunler u
    WHERE u.kategori_id IN ({$categoryIdsStr}) AND u.aktif = 1
    ORDER BY u.created_at DESC
    LIMIT {$perPage} OFFSET {$offset}
");
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url() ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="<?= url('urunler.php') ?>">Ürünler</a></li>
            <li class="breadcrumb-item active"><?= clean($kategori['ad']) ?></li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-12">
            <h1><?= clean($kategori['ad']) ?></h1>
            <p class="text-muted"><?= $total ?> ürün bulundu</p>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Bu kategoride henüz ürün bulunmuyor.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['indirimli_fiyat']): ?>
                                <?php $discount = round((($product['fiyat'] - $product['indirimli_fiyat']) / $product['fiyat']) * 100); ?>
                                <span class="badge-discount">-%<?= $discount ?></span>
                            <?php endif; ?>
                            <a href="<?= url('urun/' . $product['slug']) ?>">
                                <img src="<?= upload($product['resim'] ?? 'products/default.jpg') ?>" alt="<?= clean($product['ad']) ?>">
                            </a>
                        </div>
                        <div class="product-info">
                            <a href="<?= url('urun/' . $product['slug']) ?>" class="product-title">
                                <?= clean($product['ad']) ?>
                            </a>
                            <div class="product-price">
                                <?php if ($product['indirimli_fiyat']): ?>
                                    <?= formatPrice($product['indirimli_fiyat']) ?>
                                    <span class="old-price"><?= formatPrice($product['fiyat']) ?></span>
                                <?php else: ?>
                                    <?= formatPrice($product['fiyat']) ?>
                                <?php endif; ?>
                            </div>
                            <button class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                                <i class="fas fa-shopping-cart"></i>
                                Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pagination['has_previous']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Önceki</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagination['has_next']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Sonraki</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
