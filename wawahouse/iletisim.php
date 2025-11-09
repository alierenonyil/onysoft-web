<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * İletişim Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

// POST işlemlerini header'dan ÖNCE yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/config.php';

    $data = [
        'ad_soyad' => clean($_POST['ad_soyad']),
        'email' => clean($_POST['email']),
        'telefon' => clean($_POST['telefon'] ?? ''),
        'konu' => clean($_POST['konu']),
        'mesaj' => clean($_POST['mesaj']),
        'ip_adresi' => $_SERVER['REMOTE_ADDR']
    ];

    db()->insert('iletisim_mesajlari', $data);
    setFlash('success', 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.');
    redirect(url('iletisim.php'));
}

$pageTitle = 'İletişim';
require_once __DIR__ . '/includes/header.php';

// Site ayarlarını al
$settings = [];
$results = db()->fetchAll("SELECT * FROM ayarlar");
foreach ($results as $row) {
    $settings[$row['anahtar']] = $row['deger'];
}
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url() ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item active">İletişim</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Bize Ulaşın</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ad Soyad *</label>
                                <input type="text" name="ad_soyad" class="form-control" required
                                       value="<?= isLoggedIn() ? clean(currentUser()['ad_soyad']) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-posta *</label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= isLoggedIn() ? clean(currentUser()['email']) : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="tel" name="telefon" class="form-control"
                                       placeholder="05XX XXX XX XX"
                                       value="<?= isLoggedIn() ? clean(currentUser()['telefon'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konu *</label>
                                <select name="konu" class="form-select" required>
                                    <option value="">Seçiniz...</option>
                                    <option value="Sipariş">Sipariş</option>
                                    <option value="Ürün">Ürün</option>
                                    <option value="Kargo">Kargo</option>
                                    <option value="İade">İade</option>
                                    <option value="Öneri">Öneri</option>
                                    <option value="Şikayet">Şikayet</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mesajınız *</label>
                            <textarea name="mesaj" class="form-control" rows="6" required
                                      placeholder="Mesajınızı buraya yazınız..."></textarea>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Mesajı Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> İletişim Bilgileri</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-map-marker-alt text-primary"></i> <strong>Adres:</strong><br>
                    <?= clean($settings['iletisim_adres'] ?? 'Adres bilgisi girilmemiş') ?></p>

                    <p><i class="fas fa-phone text-success"></i> <strong>Telefon:</strong><br>
                    <?= clean($settings['iletisim_telefon'] ?? SITE_PHONE) ?></p>

                    <p><i class="fas fa-envelope text-danger"></i> <strong>E-posta:</strong><br>
                    <?= clean($settings['iletisim_email'] ?? SITE_EMAIL) ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Çalışma Saatleri</h5>
                </div>
                <div class="card-body">
                    <p><strong>Hafta İçi:</strong> 09:00 - 18:00</p>
                    <p><strong>Cumartesi:</strong> 10:00 - 16:00</p>
                    <p class="mb-0"><strong>Pazar:</strong> Kapalı</p>
                </div>
            </div>

            <?php if (!empty($settings['sosyal_facebook']) || !empty($settings['sosyal_instagram']) ||
                      !empty($settings['sosyal_twitter']) || !empty($settings['sosyal_pinterest'])): ?>
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-share-alt"></i> Sosyal Medya</h5>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <?php if (!empty($settings['sosyal_facebook'])): ?>
                        <a href="<?= clean($settings['sosyal_facebook']) ?>" target="_blank"
                           class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['sosyal_instagram'])): ?>
                        <a href="<?= clean($settings['sosyal_instagram']) ?>" target="_blank"
                           class="btn btn-danger btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['sosyal_twitter'])): ?>
                        <a href="<?= clean($settings['sosyal_twitter']) ?>" target="_blank"
                           class="btn btn-info btn-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($settings['sosyal_pinterest'])): ?>
                        <a href="<?= clean($settings['sosyal_pinterest']) ?>" target="_blank"
                           class="btn btn-danger btn-sm">
                            <i class="fab fa-pinterest"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
