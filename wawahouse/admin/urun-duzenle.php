<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Ürün Düzenle
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$urunId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$urun = db()->fetchOne("SELECT * FROM urunler WHERE id = ?", [$urunId]);

if (!$urun) {
    error('Ürün bulunamadı!');
    redirect(url('admin/urunler.php'));
}

$pageTitle = 'Ürün Düzenle: ' . $urun['ad'];
require_once __DIR__ . '/includes/header.php';

// Ürün güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $data = [
        'kategori_id' => (int)$_POST['kategori_id'],
        'ad' => clean($_POST['ad']),
        'slug' => createSlug($_POST['ad']),
        'aciklama' => $_POST['aciklama'],
        'ozellikler' => $_POST['ozellikler'] ?? '',
        'fiyat' => (float)$_POST['fiyat'],
        'indirimli_fiyat' => !empty($_POST['indirimli_fiyat']) ? (float)$_POST['indirimli_fiyat'] : null,
        'sku' => clean($_POST['sku']),
        'cinsiyet' => clean($_POST['cinsiyet']),
        'aktif' => isset($_POST['aktif']) ? 1 : 0,
        'one_cikan' => isset($_POST['one_cikan']) ? 1 : 0,
        'seo_title' => clean($_POST['seo_title'] ?? ''),
        'seo_description' => clean($_POST['seo_description'] ?? ''),
        'seo_keywords' => clean($_POST['seo_keywords'] ?? '')
    ];

    db()->update('urunler', $data, 'id = :id', ['id' => $urunId]);
    logAdminAction('update', 'urunler', $urunId, null, 'Ürün güncellendi');
    success('Ürün güncellendi!');
    redirect(url('admin/urun-duzenle.php?id=' . $urunId));
}

$categories = getCategoryTree();
$images = db()->fetchAll("SELECT * FROM urun_resimleri WHERE urun_id = ? ORDER BY ana_resim DESC, sira ASC", [$urunId]);
$variants = db()->fetchAll("SELECT * FROM urun_varyantlari WHERE urun_id = ? ORDER BY beden, renk", [$urunId]);
?>

<div class="row">
    <div class="col-md-12 mb-3">
        <a href="urunler.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Ürün Bilgileri</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="update_product" value="1">

                    <div class="mb-3">
                        <label class="form-label">Ürün Adı *</label>
                        <input type="text" name="ad" class="form-control" value="<?= clean($urun['ad']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <select name="kategori_id" class="form-select" required>
                            <?= renderCategoryOptions($categories, $urun['kategori_id']) ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fiyat *</label>
                                <input type="number" step="0.01" name="fiyat" class="form-control" value="<?= $urun['fiyat'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">İndirimli Fiyat</label>
                                <input type="number" step="0.01" name="indirimli_fiyat" class="form-control" value="<?= $urun['indirimli_fiyat'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" value="<?= clean($urun['sku']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cinsiyet</label>
                                <select name="cinsiyet" class="form-select">
                                    <option value="unisex" <?= $urun['cinsiyet'] === 'unisex' ? 'selected' : '' ?>>Unisex</option>
                                    <option value="kiz" <?= $urun['cinsiyet'] === 'kiz' ? 'selected' : '' ?>>Kız</option>
                                    <option value="erkek" <?= $urun['cinsiyet'] === 'erkek' ? 'selected' : '' ?>>Erkek</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" class="form-control" rows="5"><?= clean($urun['aciklama']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Özellikler</label>
                        <textarea name="ozellikler" class="form-control" rows="3"><?= clean($urun['ozellikler']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="aktif" class="form-check-input" id="aktif" <?= $urun['aktif'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="one_cikan" class="form-check-input" id="one_cikan" <?= $urun['one_cikan'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="one_cikan">Öne Çıkan</label>
                        </div>
                    </div>

                    <hr>
                    <h6>SEO</h6>
                    <div class="mb-3">
                        <label class="form-label">SEO Başlık</label>
                        <input type="text" name="seo_title" class="form-control" value="<?= clean($urun['seo_title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Açıklama</label>
                        <textarea name="seo_description" class="form-control" rows="2"><?= clean($urun['seo_description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Anahtar Kelimeler</label>
                        <input type="text" name="seo_keywords" class="form-control" value="<?= clean($urun['seo_keywords']) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </form>
            </div>
        </div>

        <!-- Varyantlar -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cube"></i> Ürün Varyantları (Beden/Renk/Stok)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Bebek bedenleri: 0-3 Ay, 3-6 Ay, 6-9 Ay, 9-12 Ay, 12-18 Ay, 18-24 Ay, 2-3 Yaş</p>

                <?php if (empty($variants)): ?>
                    <div class="alert alert-info">Henüz varyant eklenmemiş.</div>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Beden</th>
                                <th>Renk</th>
                                <th>Stok</th>
                                <th>Ek Fiyat</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($variants as $variant): ?>
                                <tr>
                                    <td><?= clean($variant['beden']) ?></td>
                                    <td><?= clean($variant['renk']) ?></td>
                                    <td><?= $variant['stok'] ?></td>
                                    <td><?= formatPrice($variant['ek_fiyat']) ?></td>
                                    <td><?= clean($variant['sku']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <p class="text-muted small mt-3">Not: Varyant ekleme özelliği geliştirilecek.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Resimler -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-images"></i> Ürün Resimleri</h5>
            </div>
            <div class="card-body">
                <?php if (empty($images)): ?>
                    <div class="alert alert-info">Henüz resim eklenmemiş.</div>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($images as $image): ?>
                            <div class="col-6">
                                <div class="position-relative">
                                    <img src="<?= upload($image['resim']) ?>" class="img-fluid rounded" alt="">
                                    <?php if ($image['ana_resim']): ?>
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-1">Ana</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <p class="text-muted small mt-3">Not: Resim yükleme özelliği geliştirilecek.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
