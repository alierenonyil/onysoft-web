<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Sayfa Düzenle
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$sayfaId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Sayfayı getir
$sayfa = db()->fetchOne("SELECT * FROM sayfalar WHERE id = ?", [$sayfaId]);

if (!$sayfa) {
    setFlash('error', 'Sayfa bulunamadı!');
    redirect(url('admin/sayfalar.php'));
}

$pageTitle = 'Sayfa Düzenle: ' . $sayfa['baslik'];

// POST işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = clean($_POST['baslik']);
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($baslik);
    $icerik = $_POST['icerik']; // HTML içerik
    $sira = (int)$_POST['sira'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $menude_goster = isset($_POST['menude_goster']) ? 1 : 0;

    // SEO
    $seo_title = clean($_POST['seo_title'] ?? $baslik);
    $seo_description = clean($_POST['seo_description'] ?? '');
    $seo_keywords = clean($_POST['seo_keywords'] ?? '');

    $data = [
        'baslik' => $baslik,
        'slug' => $slug,
        'icerik' => $icerik,
        'sira' => $sira,
        'aktif' => $aktif,
        'menude_goster' => $menude_goster,
        'seo_title' => $seo_title,
        'seo_description' => $seo_description,
        'seo_keywords' => $seo_keywords
    ];

    db()->update('sayfalar', $data, 'id = :id', ['id' => $sayfaId]);
    logAdminAction('update', 'sayfalar', $sayfaId, null, 'Sayfa güncellendi: ' . $baslik);
    setFlash('success', 'Sayfa başarıyla güncellendi!');
    redirect(url('admin/sayfa-duzenle.php?id=' . $sayfaId));
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="sayfalar.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
        <a href="<?= url('sayfa/' . $sayfa['slug']) ?>" target="_blank" class="btn btn-info">
            <i class="fas fa-eye"></i> Sayfayı Görüntüle
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Sayfa Düzenle</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Sayfa Başlığı *</label>
                        <input type="text" name="baslik" class="form-control" required
                               value="<?= clean($sayfa['baslik']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control"
                               value="<?= clean($sayfa['slug']) ?>">
                        <small class="text-muted">Örnek: hakkimizda, kvkk, iade-sartlari</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">İçerik *</label>
                        <textarea name="icerik" id="icerik" class="form-control" rows="15"><?= $sayfa['icerik'] ?></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Yayın Ayarları</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Sıra</label>
                                <input type="number" name="sira" class="form-control"
                                       value="<?= $sayfa['sira'] ?>">
                            </div>

                            <div class="form-check mb-2">
                                <input type="checkbox" name="aktif" id="aktif"
                                       class="form-check-input" value="1"
                                       <?= $sayfa['aktif'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="menude_goster" id="menude_goster"
                                       class="form-check-input" value="1"
                                       <?= $sayfa['menude_goster'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="menude_goster">
                                    Footer Menüsünde Göster
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <strong>SEO Ayarları</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">SEO Başlık</label>
                                <input type="text" name="seo_title" class="form-control"
                                       value="<?= clean($sayfa['seo_title'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Açıklama</label>
                                <textarea name="seo_description" class="form-control" rows="3"><?= clean($sayfa['seo_description'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Anahtar Kelimeler</label>
                                <input type="text" name="seo_keywords" class="form-control"
                                       value="<?= clean($sayfa['seo_keywords'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Değişiklikleri Kaydet
                </button>
                <a href="sayfalar.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#icerik',
    height: 500,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | forecolor backcolor | ' +
             'link image media | removeformat code fullscreen',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
    language: 'tr_TR',
    branding: false,
    promotion: false
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
