<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Adres Düzenleme Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

require_once __DIR__ . '/config.php';

// Giriş kontrolü
if (!isLoggedIn()) {
    setFlash('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız!');
    redirect(url('giris.php'));
}

$userId = currentUser()['id'];
$adresId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Adresi getir ve kullanıcıya ait olduğunu kontrol et
$adres = db()->fetchOne("SELECT * FROM adresler WHERE id = ? AND kullanici_id = ?", [$adresId, $userId]);

if (!$adres) {
    setFlash('error', 'Adres bulunamadı!');
    redirect(url('hesabim.php?tab=addresses'));
}

// POST işlemlerini header'dan ÖNCE yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ad_soyad' => clean($_POST['ad_soyad']),
        'telefon' => clean($_POST['telefon']),
        'il' => clean($_POST['il']),
        'ilce' => clean($_POST['ilce']),
        'adres' => clean($_POST['adres']),
        'posta_kodu' => clean($_POST['posta_kodu'] ?? ''),
        'adres_tipi' => clean($_POST['adres_tipi'] ?? 'ev'),
        'varsayilan' => isset($_POST['varsayilan']) ? 1 : 0
    ];

    // Eğer varsayılan olarak işaretlendiyse, diğerlerini kaldır
    if ($data['varsayilan'] == 1) {
        db()->update('adresler', ['varsayilan' => 0], 'kullanici_id = :userId AND id != :adresId', [
            'userId' => $userId,
            'adresId' => $adresId
        ]);
    }

    db()->update('adresler', $data, 'id = :id', ['id' => $adresId]);
    setFlash('success', 'Adres başarıyla güncellendi!');
    redirect(url('hesabim.php?tab=addresses'));
}

$pageTitle = 'Adres Düzenle';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Adres Düzenle</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ad Soyad *</label>
                                <input type="text" name="ad_soyad" class="form-control" required
                                       value="<?= clean($adres['ad_soyad']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon *</label>
                                <input type="tel" name="telefon" class="form-control" required
                                       placeholder="05XX XXX XX XX"
                                       value="<?= clean($adres['telefon']) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İl *</label>
                                <input type="text" name="il" class="form-control" required
                                       value="<?= clean($adres['il']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İlçe *</label>
                                <input type="text" name="ilce" class="form-control" required
                                       value="<?= clean($adres['ilce']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adres *</label>
                            <textarea name="adres" class="form-control" rows="3" required><?= clean($adres['adres']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Posta Kodu</label>
                                <input type="text" name="posta_kodu" class="form-control"
                                       value="<?= clean($adres['posta_kodu'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adres Tipi</label>
                                <select name="adres_tipi" class="form-select">
                                    <option value="ev" <?= $adres['adres_tipi'] === 'ev' ? 'selected' : '' ?>>Ev</option>
                                    <option value="is" <?= $adres['adres_tipi'] === 'is' ? 'selected' : '' ?>>İş</option>
                                    <option value="diger" <?= $adres['adres_tipi'] === 'diger' ? 'selected' : '' ?>>Diğer</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="varsayilan" id="varsayilan"
                                       class="form-check-input" value="1"
                                       <?= $adres['varsayilan'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="varsayilan">
                                    Varsayılan adres olarak kaydet
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Değişiklikleri Kaydet
                            </button>
                            <a href="<?= url('hesabim.php?tab=addresses') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
