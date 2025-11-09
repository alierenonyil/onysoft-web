<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Sipariş Tamamlandı Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Sipariş Tamamlandı';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/header.php';

// Giriş kontrolü
if (!isLoggedIn()) {
    setFlash('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız!');
    redirect(url('giris.php'));
}

$userId = currentUser()['id'];
$siparisNo = $_GET['siparis'] ?? null;

// Sipariş bilgisi varsa getir
$siparis = null;
if ($siparisNo) {
    $siparis = db()->fetchOne(
        "SELECT * FROM siparisler WHERE siparis_no = ? AND kullanici_id = ?",
        [$siparisNo, $userId]
    );
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if ($siparis): ?>
            <!-- Sipariş Başarılı -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="text-success">Siparişiniz Alındı!</h2>
                <p class="lead">Siparişiniz başarıyla oluşturuldu.</p>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Sipariş Özeti</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Sipariş No:</strong><br>
                            <span class="text-primary fs-5"><?= clean($siparis['siparis_no']) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Sipariş Tarihi:</strong><br>
                            <?= formatDate($siparis['created_at']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Toplam Tutar:</strong><br>
                            <span class="text-success fs-4"><?= formatPrice($siparis['toplam_tutar']) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ödeme Yöntemi:</strong><br>
                            <?php
                            $odemeYontemleri = [
                                'kredi_karti' => 'Kredi Kartı',
                                'kapida_odeme' => 'Kapıda Ödeme',
                                'havale' => 'Havale/EFT'
                            ];
                            echo $odemeYontemleri[$siparis['odeme_yontemi']] ?? 'Belirtilmemiş';
                            ?>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        Sipariş durumunuzu <strong>Hesabım > Siparişlerim</strong> sayfasından takip edebilirsiniz.
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-truck"></i> Teslimat Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Teslimat Adresi</h6>
                            <p>
                                <strong><?= clean($siparis['teslimat_ad_soyad']) ?></strong><br>
                                <?= clean($siparis['teslimat_adres']) ?><br>
                                <?= clean($siparis['teslimat_ilce']) ?> / <?= clean($siparis['teslimat_il']) ?><br>
                                Telefon: <?= clean($siparis['teslimat_telefon']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Fatura Adresi</h6>
                            <p>
                                <strong><?= clean($siparis['fatura_ad_soyad']) ?></strong><br>
                                <?= clean($siparis['fatura_adres']) ?><br>
                                <?= clean($siparis['fatura_ilce']) ?> / <?= clean($siparis['fatura_il']) ?><br>
                                Telefon: <?= clean($siparis['fatura_telefon']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="<?= url('hesabim.php?tab=orders') ?>" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> Siparişlerimi Görüntüle
                </a>
                <a href="<?= url() ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-home"></i> Ana Sayfaya Dön
                </a>
            </div>

            <?php else: ?>
            <!-- Sipariş Bulunamadı -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 80px;"></i>
                </div>
                <h2 class="text-warning">Sipariş Bulunamadı</h2>
                <p class="lead">Belirtilen sipariş numarasına ait bir sipariş bulunamadı.</p>
            </div>

            <div class="text-center">
                <a href="<?= url('hesabim.php?tab=orders') ?>" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> Siparişlerim
                </a>
                <a href="<?= url() ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-home"></i> Ana Sayfaya Dön
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Sepeti temizle (başarılı sipariş sonrası) -->
<?php if ($siparis): ?>
<script>
// Sepeti temizle
localStorage.removeItem('cart');
// Sepet badge'ini güncelle
if (document.querySelector('.cart-badge')) {
    document.querySelector('.cart-badge').textContent = '0';
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
