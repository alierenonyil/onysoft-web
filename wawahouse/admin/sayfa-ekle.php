<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Sayfa Ekle
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Yeni Sayfa Ekle';

// POST işlemlerini header'dan ÖNCE yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/includes/auth.php';

    $baslik = clean($_POST['baslik']);
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($baslik);
    $icerik = $_POST['icerik']; // HTML içerik olduğu için clean() kullanmıyoruz
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

    $newId = db()->insert('sayfalar', $data);
    logAdminAction('insert', 'sayfalar', $newId, null, 'Yeni sayfa eklendi: ' . $baslik);
    setFlash('success', 'Sayfa başarıyla eklendi!');
    redirect(url('admin/sayfalar.php'));
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="sayfalar.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Yeni Sayfa Ekle</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Sayfa Başlığı *</label>
                        <input type="text" name="baslik" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control"
                               placeholder="Boş bırakılırsa otomatik oluşturulur">
                        <small class="text-muted">Örnek: hakkimizda, kvkk, iade-sartlari</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">İçerik *</label>
                        <textarea name="icerik" id="icerik" class="form-control" rows="15"></textarea>
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
                                <input type="number" name="sira" class="form-control" value="0">
                            </div>

                            <div class="form-check mb-2">
                                <input type="checkbox" name="aktif" id="aktif"
                                       class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="menude_goster" id="menude_goster"
                                       class="form-check-input" value="1">
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
                                <input type="text" name="seo_title" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Açıklama</label>
                                <textarea name="seo_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Anahtar Kelimeler</label>
                                <input type="text" name="seo_keywords" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Sayfayı Kaydet
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
