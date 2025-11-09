<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Sipariş Detay
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

// POST/GET işlemlerini header'dan ÖNCE yap (headers already sent hatasını önlemek için)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$siparisId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Durum güncelleme (redirect içeriyor, header'dan önce olmalı)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $yeniDurum = clean($_POST['durum']);
    $kargoTakipNo = clean($_POST['kargo_takip_no'] ?? '');

    $data = ['durum' => $yeniDurum];
    if (!empty($kargoTakipNo)) {
        $data['kargo_takip_no'] = $kargoTakipNo;
    }

    db()->update('siparisler', $data, 'id = :id', ['id' => $siparisId]);
    logAdminAction('update', 'siparisler', $siparisId, null, 'Sipariş durumu güncellendi');
    setFlash('success', 'Sipariş güncellendi!');
    redirect(url('admin/siparis-detay.php?id=' . $siparisId));
}

$siparis = db()->fetchOne("
    SELECT s.*, k.ad_soyad, k.email, k.telefon
    FROM siparisler s
    LEFT JOIN kullanicilar k ON k.id = s.kullanici_id
    WHERE s.id = ?
", [$siparisId]);

if (!$siparis) {
    setFlash('error', 'Sipariş bulunamadı!');
    redirect(url('admin/siparisler.php'));
}

$pageTitle = 'Sipariş Detay: ' . $siparis['siparis_no'];
require_once __DIR__ . '/includes/header.php';

// Sipariş kalemleri
$items = db()->fetchAll("SELECT * FROM siparis_detaylari WHERE siparis_id = ?", [$siparisId]);
?>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="siparisler.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Sipariş Kalemleri -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box"></i> Sipariş Kalemleri</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ürün</th>
                            <th>Beden/Renk</th>
                            <th>Adet</th>
                            <th>Fiyat</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= clean($item['urun_ad']) ?></td>
                                <td><?= clean($item['beden']) ?> - <?= clean($item['renk']) ?></td>
                                <td><?= $item['adet'] ?></td>
                                <td><?= formatPrice($item['fiyat']) ?></td>
                                <td><?= formatPrice($item['toplam']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Ara Toplam:</strong></td>
                            <td><strong><?= formatPrice($siparis['ara_toplam']) ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Kargo:</td>
                            <td><?= formatPrice($siparis['kargo_ucreti']) ?></td>
                        </tr>
                        <?php if ($siparis['kupon_indirim'] > 0): ?>
                            <tr class="text-success">
                                <td colspan="4" class="text-end">Kupon İndirimi (<?= clean($siparis['kupon_kodu']) ?>):</td>
                                <td>-<?= formatPrice($siparis['kupon_indirim']) ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr class="table-primary">
                            <td colspan="4" class="text-end"><strong>TOPLAM:</strong></td>
                            <td><strong class="h5"><?= formatPrice($siparis['toplam_tutar']) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Teslimat Bilgileri -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Teslimat Bilgileri</h5>
            </div>
            <div class="card-body">
                <h6>Teslimat Adresi:</h6>
                <p><?= nl2br(clean($siparis['teslimat_adresi'])) ?></p>

                <?php if ($siparis['fatura_adresi']): ?>
                    <hr>
                    <h6>Fatura Adresi:</h6>
                    <p><?= nl2br(clean($siparis['fatura_adresi'])) ?></p>
                <?php endif; ?>

                <?php if ($siparis['notlar']): ?>
                    <hr>
                    <h6>Sipariş Notları:</h6>
                    <p><?= nl2br(clean($siparis['notlar'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Sipariş Bilgileri -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Sipariş Bilgileri</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Sipariş No:</dt>
                    <dd class="col-sm-7"><strong><?= clean($siparis['siparis_no']) ?></strong></dd>

                    <dt class="col-sm-5">Tarih:</dt>
                    <dd class="col-sm-7"><?= formatDate($siparis['created_at']) ?></dd>

                    <dt class="col-sm-5">Durum:</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-<?= getOrderStatusColor($siparis['durum']) ?>">
                            <?= getOrderStatusText($siparis['durum']) ?>
                        </span>
                    </dd>

                    <dt class="col-sm-5">Ödeme:</dt>
                    <dd class="col-sm-7">
                        <?= $siparis['odeme_yontemi'] === 'kredi_karti' ? 'Kredi Kartı' : 'Kapıda Ödeme' ?>
                    </dd>

                    <dt class="col-sm-5">IP Adresi:</dt>
                    <dd class="col-sm-7"><?= clean($siparis['ip_adresi']) ?></dd>
                </dl>
            </div>
        </div>

        <!-- Müşteri Bilgileri -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Müşteri</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong><?= clean($siparis['ad_soyad']) ?></strong></p>
                <p class="mb-1"><?= clean($siparis['email']) ?></p>
                <p class="mb-0"><?= clean($siparis['telefon']) ?></p>
            </div>
        </div>

        <!-- Durum Güncelleme -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Durum Güncelle</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="update_status" value="1">

                    <div class="mb-3">
                        <label class="form-label">Sipariş Durumu</label>
                        <select name="durum" class="form-select">
                            <option value="beklemede" <?= $siparis['durum'] === 'beklemede' ? 'selected' : '' ?>>Beklemede</option>
                            <option value="onaylandi" <?= $siparis['durum'] === 'onaylandi' ? 'selected' : '' ?>>Onaylandı</option>
                            <option value="hazirlaniyor" <?= $siparis['durum'] === 'hazirlaniyor' ? 'selected' : '' ?>>Hazırlanıyor</option>
                            <option value="kargoda" <?= $siparis['durum'] === 'kargoda' ? 'selected' : '' ?>>Kargoda</option>
                            <option value="teslim_edildi" <?= $siparis['durum'] === 'teslim_edildi' ? 'selected' : '' ?>>Teslim Edildi</option>
                            <option value="iptal" <?= $siparis['durum'] === 'iptal' ? 'selected' : '' ?>>İptal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kargo Takip No</label>
                        <input type="text" name="kargo_takip_no" class="form-control"
                               value="<?= clean($siparis['kargo_takip_no']) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
