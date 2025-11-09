<?php
$pageTitle = 'Site Ayarları';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token') continue;

        $exists = db()->fetchOne("SELECT id FROM ayarlar WHERE anahtar = ?", [$key]);

        if ($exists) {
            db()->update('ayarlar', ['deger' => $value], 'anahtar = :key', ['key' => $key]);
        } else {
            db()->insert('ayarlar', ['anahtar' => $key, 'deger' => $value]);
        }
    }

    success('Ayarlar kaydedildi!');
    redirect(url('admin/ayarlar.php'));
}

$settings = [];
$results = db()->fetchAll("SELECT * FROM ayarlar");
foreach ($results as $row) {
    $settings[$row['anahtar']] = $row['deger'];
}
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cog"></i> Site Ayarları</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <h6 class="border-bottom pb-2 mb-3">Genel Ayarlar</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Site Adı</label>
                    <input type="text" name="site_adi" class="form-control"
                           value="<?= clean($settings['site_adi'] ?? 'WawaHouse') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Site Email</label>
                    <input type="email" name="site_email" class="form-control"
                           value="<?= clean($settings['site_email'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label>Site Açıklaması</label>
                <textarea name="site_aciklama" class="form-control" rows="2"><?= clean($settings['site_aciklama'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label>SEO Anahtar Kelimeler</label>
                <input type="text" name="site_keywords" class="form-control"
                       value="<?= clean($settings['site_keywords'] ?? '') ?>">
            </div>

            <hr class="my-4">
            <h6 class="border-bottom pb-2 mb-3">İletişim Bilgileri</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Telefon</label>
                    <input type="text" name="iletisim_telefon" class="form-control"
                           value="<?= clean($settings['iletisim_telefon'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Email</label>
                    <input type="email" name="iletisim_email" class="form-control"
                           value="<?= clean($settings['iletisim_email'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Adres</label>
                    <input type="text" name="iletisim_adres" class="form-control"
                           value="<?= clean($settings['iletisim_adres'] ?? '') ?>">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="border-bottom pb-2 mb-3">Sosyal Medya</h6>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Facebook</label>
                    <input type="url" name="sosyal_facebook" class="form-control"
                           value="<?= clean($settings['sosyal_facebook'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Instagram</label>
                    <input type="url" name="sosyal_instagram" class="form-control"
                           value="<?= clean($settings['sosyal_instagram'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Twitter</label>
                    <input type="url" name="sosyal_twitter" class="form-control"
                           value="<?= clean($settings['sosyal_twitter'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Pinterest</label>
                    <input type="url" name="sosyal_pinterest" class="form-control"
                           value="<?= clean($settings['sosyal_pinterest'] ?? '') ?>">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="border-bottom pb-2 mb-3">Kargo Ayarları</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Bedava Kargo Limiti (₺)</label>
                    <input type="number" step="0.01" name="bedava_kargo_limiti" class="form-control"
                           value="<?= clean($settings['bedava_kargo_limiti'] ?? '500') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Varsayılan Kargo Ücreti (₺)</label>
                    <input type="number" step="0.01" name="varsayilan_kargo_ucreti" class="form-control"
                           value="<?= clean($settings['varsayilan_kargo_ucreti'] ?? '29.90') ?>">
                </div>
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
