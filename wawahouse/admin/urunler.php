<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Ürün Listesi
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Ürün Yönetimi';
require_once __DIR__ . '/includes/header.php';

// Silme işlemi
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Resimleri sil
    $images = db()->fetchAll("SELECT resim FROM urun_resimleri WHERE urun_id = ?", [$id]);
    foreach ($images as $img) {
        deleteFile($img['resim']);
    }

    db()->delete('urunler', 'id = :id', ['id' => $id]);
    logAdminAction('delete', 'urunler', $id, null, 'Ürün silindi');
    success('Ürün silindi!');
    redirect(url('admin/urunler.php'));
}

// Filtreleme
$where = '1=1';
$params = [];

if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $where .= ' AND u.kategori_id = ?';
    $params[] = (int)$_GET['kategori'];
}

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $where .= ' AND u.ad LIKE ?';
    $params[] = '%' . clean($_GET['q']) . '%';
}

// Sayfalama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = ADMIN_PER_PAGE;
$total = db()->count('urunler u', $where, $params);
$pagination = paginate($total, $perPage, $page);

// Ürünleri getir
$products = db()->fetchAll("
    SELECT u.*, k.ad as kategori_ad,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim,
           (SELECT COUNT(*) FROM urun_varyantlari WHERE urun_id = u.id) as varyant_sayisi,
           (SELECT SUM(stok) FROM urun_varyantlari WHERE urun_id = u.id) as toplam_stok
    FROM urunler u
    LEFT JOIN kategoriler k ON k.id = u.kategori_id
    WHERE {$where}
    ORDER BY u.created_at DESC
    LIMIT {$perPage} OFFSET {$pagination['offset']}
", $params);

$categories = db()->fetchAll("SELECT * FROM kategoriler WHERE aktif = 1 ORDER BY ad ASC");
?>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="urun-ekle.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Ürün Ekle
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-box"></i> Ürünler (<?= $total ?>)</h5>
    </div>
    <div class="card-body">
        <!-- Filtreler -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Ürün ara..."
                       value="<?= clean($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Tüm Kategoriler</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= isset($_GET['kategori']) && $_GET['kategori'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= clean($cat['ad']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-search"></i> Filtrele
                </button>
            </div>
            <div class="col-md-3">
                <a href="urunler.php" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Temizle
                </a>
            </div>
        </form>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">Ürün bulunamadı.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="80">Resim</th>
                            <th>Ürün Adı</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th>Stok</th>
                            <th>Durum</th>
                            <th width="150">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if ($product['resim']): ?>
                                        <img src="<?= upload($product['resim']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #eee; border-radius: 5px;"></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= clean($product['ad']) ?></strong><br>
                                    <small class="text-muted">SKU: <?= clean($product['sku']) ?></small>
                                </td>
                                <td><?= clean($product['kategori_ad']) ?></td>
                                <td>
                                    <?php if ($product['indirimli_fiyat']): ?>
                                        <s class="text-muted"><?= formatPrice($product['fiyat']) ?></s><br>
                                        <strong class="text-danger"><?= formatPrice($product['indirimli_fiyat']) ?></strong>
                                    <?php else: ?>
                                        <?= formatPrice($product['fiyat']) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $product['toplam_stok'] > 10 ? 'success' : ($product['toplam_stok'] > 0 ? 'warning' : 'danger') ?>">
                                        <?= $product['toplam_stok'] ?? 0 ?> adet
                                    </span>
                                </td>
                                <td>
                                    <?php if ($product['aktif']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pasif</span>
                                    <?php endif; ?>
                                    <?php if ($product['one_cikan']): ?>
                                        <span class="badge bg-warning">Öne Çıkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="urun-duzenle.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?= $product['id'] ?>" class="btn btn-outline-danger"
                                           data-confirm="Bu ürünü silmek istediğinize emin misiniz?" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?= renderPagination($pagination, 'urunler.php') ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
