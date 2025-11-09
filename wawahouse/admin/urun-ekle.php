<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Ürün Ekle
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Yeni Ürün Ekle';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $urunId = db()->insert('urunler', $data);

    if ($urunId) {
        logAdminAction('insert', 'urunler', $urunId, null, 'Yeni ürün eklendi');
        success('Ürün başarıyla eklendi! Şimdi resim ve varyant ekleyebilirsiniz.');
        redirect(url('admin/urun-duzenle.php?id=' . $urunId));
    } else {
        error('Ürün eklenirken bir hata oluştu!');
    }
}

$categories = getCategoryTree();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Yeni Ürün Ekle</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Ürün Adı *</label>
                                <input type="text" name="ad" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">Kategori Seçin</option>
                                    <?= renderCategoryOptions($categories) ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Açıklama</label>
                                <textarea name="aciklama" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Özellikler (Kumaş, Bakım vb.)</label>
                                <textarea name="ozellikler" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fiyat *</label>
                                <input type="number" step="0.01" name="fiyat" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">İndirimli Fiyat</label>
                                <input type="number" step="0.01" name="indirimli_fiyat" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SKU Kodu</label>
                                <input type="text" name="sku" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cinsiyet</label>
                                <select name="cinsiyet" class="form-select">
                                    <option value="unisex">Unisex</option>
                                    <option value="kiz">Kız</option>
                                    <option value="erkek">Erkek</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="aktif" class="form-check-input" id="aktif" checked>
                                    <label class="form-check-label" for="aktif">Aktif</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="one_cikan" class="form-check-input" id="one_cikan">
                                    <label class="form-check-label" for="one_cikan">Öne Çıkan</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6>SEO Ayarları</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">SEO Başlık</label>
                                <input type="text" name="seo_title" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">SEO Açıklama</label>
                                <textarea name="seo_description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">SEO Anahtar Kelimeler</label>
                                <input type="text" name="seo_keywords" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet ve Devam Et
                        </button>
                        <a href="urunler.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
