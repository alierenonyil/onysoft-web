<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Ödeme Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

require_once __DIR__ . '/config.php';

// Giriş kontrolü
if (!isLoggedIn()) {
    setFlash('error', 'Ödeme yapmak için giriş yapmalısınız!');
    redirect(url('giris.php'));
}

$userId = currentUser()['id'];

// Sepet kontrolü
$cartItems = db()->fetchAll("
    SELECT s.*, u.ad, u.slug, ur.resim_url
    FROM sepet s
    LEFT JOIN urunler u ON u.id = s.urun_id
    LEFT JOIN urun_resimleri ur ON ur.urun_id = u.id AND ur.ana_resim = 1
    WHERE s.kullanici_id = ?
", [$userId]);

if (empty($cartItems)) {
    setFlash('error', 'Sepetinizde ürün bulunmuyor!');
    redirect(url('sepet.php'));
}

// POST işlemi - Sipariş oluşturma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teslimatAdresId = (int)$_POST['teslimat_adres_id'];
    $faturaAdresId = (int)$_POST['fatura_adres_id'];
    $odemeYontemi = clean($_POST['odeme_yontemi']);
    $kuponKodu = clean($_POST['kupon_kodu'] ?? '');

    // Adresleri kontrol et
    $teslimatAdres = db()->fetchOne("SELECT * FROM adresler WHERE id = ? AND kullanici_id = ?", [$teslimatAdresId, $userId]);
    $faturaAdres = db()->fetchOne("SELECT * FROM adresler WHERE id = ? AND kullanici_id = ?", [$faturaAdresId, $userId]);

    if (!$teslimatAdres || !$faturaAdres) {
        setFlash('error', 'Geçersiz adres seçimi!');
        redirect(url('ode.php'));
    }

    // Sepet toplamını hesapla
    $araToplam = 0;
    foreach ($cartItems as $item) {
        $araToplam += $item['fiyat'] * $item['miktar'];
    }

    // Kargo ücreti
    $kargoUcreti = $araToplam >= FREE_SHIPPING_THRESHOLD ? 0 : DEFAULT_SHIPPING_COST;

    // Kupon kontrolü
    $kuponIndirim = 0;
    if (!empty($kuponKodu)) {
        $kupon = db()->fetchOne("
            SELECT * FROM kuponlar
            WHERE kod = ? AND aktif = 1
            AND (gecerlilik_baslangic IS NULL OR gecerlilik_baslangic <= NOW())
            AND (gecerlilik_bitis IS NULL OR gecerlilik_bitis >= NOW())
            AND (kullanim_limiti IS NULL OR kullanilan < kullanim_limiti)
            AND min_tutar <= ?
        ", [$kuponKodu, $araToplam]);

        if ($kupon) {
            if ($kupon['indirim_tipi'] === 'yuzde') {
                $kuponIndirim = ($araToplam * $kupon['indirim_degeri']) / 100;
            } elseif ($kupon['indirim_tipi'] === 'tutar') {
                $kuponIndirim = $kupon['indirim_degeri'];
            } elseif ($kupon['indirim_tipi'] === 'bedava_kargo') {
                $kargoUcreti = 0;
            }
        }
    }

    $toplamTutar = $araToplam + $kargoUcreti - $kuponIndirim;

    // Sipariş numarası oluştur
    $siparisNo = generateOrderNumber();

    // Siparişi kaydet
    $siparisData = [
        'kullanici_id' => $userId,
        'siparis_no' => $siparisNo,
        'ara_toplam' => $araToplam,
        'kargo_ucreti' => $kargoUcreti,
        'kupon_indirimi' => $kuponIndirim,
        'toplam_tutar' => $toplamTutar,
        'odeme_yontemi' => $odemeYontemi,
        'durum' => 'beklemede',
        'teslimat_ad_soyad' => $teslimatAdres['ad_soyad'],
        'teslimat_telefon' => $teslimatAdres['telefon'],
        'teslimat_il' => $teslimatAdres['il'],
        'teslimat_ilce' => $teslimatAdres['ilce'],
        'teslimat_adres' => $teslimatAdres['adres'],
        'teslimat_posta_kodu' => $teslimatAdres['posta_kodu'] ?? '',
        'fatura_ad_soyad' => $faturaAdres['ad_soyad'],
        'fatura_telefon' => $faturaAdres['telefon'],
        'fatura_il' => $faturaAdres['il'],
        'fatura_ilce' => $faturaAdres['ilce'],
        'fatura_adres' => $faturaAdres['adres'],
        'fatura_posta_kodu' => $faturaAdres['posta_kodu'] ?? '',
        'ip_adresi' => $_SERVER['REMOTE_ADDR']
    ];

    $siparisId = db()->insert('siparisler', $siparisData);

    // Sipariş detaylarını kaydet
    foreach ($cartItems as $item) {
        db()->insert('siparis_detaylari', [
            'siparis_id' => $siparisId,
            'urun_id' => $item['urun_id'],
            'varyant_id' => $item['varyant_id'],
            'adet' => $item['miktar'],
            'birim_fiyat' => $item['fiyat'],
            'toplam_fiyat' => $item['fiyat'] * $item['miktar']
        ]);

        // Stok güncelle
        if ($item['varyant_id']) {
            db()->update('urun_varyantlari', [
                'stok' => db()->fetchOne("SELECT stok FROM urun_varyantlari WHERE id = ?", [$item['varyant_id']])['stok'] - $item['miktar']
            ], 'id = :id', ['id' => $item['varyant_id']]);
        }
    }

    // Kupon kullanımını kaydet
    if (!empty($kupon)) {
        db()->insert('kupon_kullanim', [
            'kupon_id' => $kupon['id'],
            'kullanici_id' => $userId,
            'siparis_id' => $siparisId
        ]);
        db()->update('kuponlar', [
            'kullanilan' => $kupon['kullanilan'] + 1
        ], 'id = :id', ['id' => $kupon['id']]);
    }

    // Sepeti temizle
    db()->delete('sepet', 'kullanici_id = :userId', ['userId' => $userId]);

    setFlash('success', 'Siparişiniz başarıyla oluşturuldu!');
    redirect(url('siparis-tamamlandi.php?siparis=' . $siparisNo));
}

$pageTitle = 'Ödeme';
require_once __DIR__ . '/includes/header.php';

// Kullanıcının adreslerini getir
$adresler = db()->fetchAll("SELECT * FROM adresler WHERE kullanici_id = ? ORDER BY varsayilan DESC", [$userId]);

// Sepet toplamını hesapla
$araToplam = 0;
foreach ($cartItems as $item) {
    $araToplam += $item['fiyat'] * $item['miktar'];
}

$kargoUcreti = $araToplam >= FREE_SHIPPING_THRESHOLD ? 0 : DEFAULT_SHIPPING_COST;
$toplamTutar = $araToplam + $kargoUcreti;
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-credit-card"></i> Ödeme</h2>

    <?php if (empty($adresler)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        Sipariş verebilmek için önce bir adres eklemelisiniz.
        <a href="<?= url('adres-ekle.php') ?>" class="btn btn-sm btn-primary ms-2">
            <i class="fas fa-plus"></i> Adres Ekle
        </a>
    </div>
    <?php else: ?>

    <form method="POST" id="odemeForm">
        <div class="row">
            <div class="col-md-8">
                <!-- Teslimat Adresi -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-truck"></i> Teslimat Adresi</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($adresler as $adres): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="teslimat_adres_id"
                                   id="teslimat_<?= $adres['id'] ?>" value="<?= $adres['id'] ?>"
                                   <?= $adres['varsayilan'] ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="teslimat_<?= $adres['id'] ?>">
                                <strong><?= clean($adres['ad_soyad']) ?></strong>
                                <?= $adres['varsayilan'] ? '<span class="badge bg-success ms-2">Varsayılan</span>' : '' ?>
                                <br>
                                <small class="text-muted">
                                    <?= clean($adres['adres']) ?>, <?= clean($adres['ilce']) ?>/<?= clean($adres['il']) ?>
                                    | Tel: <?= clean($adres['telefon']) ?>
                                </small>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <a href="<?= url('adres-ekle.php') ?>" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-plus"></i> Yeni Adres Ekle
                        </a>
                    </div>
                </div>

                <!-- Fatura Adresi -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Fatura Adresi</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="ayniAdres"
                                   onchange="toggleFaturaAdres()" checked>
                            <label class="form-check-label" for="ayniAdres">
                                Teslimat adresi ile aynı
                            </label>
                        </div>
                        <div id="faturaAdresSecim" style="display: none;">
                            <?php foreach ($adresler as $adres): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="fatura_adres_id"
                                       id="fatura_<?= $adres['id'] ?>" value="<?= $adres['id'] ?>"
                                       <?= $adres['varsayilan'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="fatura_<?= $adres['id'] ?>">
                                    <strong><?= clean($adres['ad_soyad']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= clean($adres['adres']) ?>, <?= clean($adres['ilce']) ?>/<?= clean($adres['il']) ?>
                                    </small>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Ödeme Yöntemi -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Ödeme Yöntemi</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="odeme_yontemi"
                                   id="krediKarti" value="kredi_karti" checked>
                            <label class="form-check-label" for="krediKarti">
                                <i class="fas fa-credit-card text-primary"></i> Kredi Kartı
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="odeme_yontemi"
                                   id="kapidaOdeme" value="kapida_odeme">
                            <label class="form-check-label" for="kapidaOdeme">
                                <i class="fas fa-hand-holding-usd text-success"></i> Kapıda Ödeme
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="odeme_yontemi"
                                   id="havale" value="havale">
                            <label class="form-check-label" for="havale">
                                <i class="fas fa-university text-info"></i> Havale/EFT
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Sipariş Özeti -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shopping-basket"></i> Sipariş Özeti</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ara Toplam:</span>
                            <strong><?= formatPrice($araToplam) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Kargo:</span>
                            <strong class="text-<?= $kargoUcreti == 0 ? 'success' : 'dark' ?>">
                                <?= $kargoUcreti == 0 ? 'Ücretsiz' : formatPrice($kargoUcreti) ?>
                            </strong>
                        </div>
                        <?php if ($araToplam < FREE_SHIPPING_THRESHOLD): ?>
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle"></i>
                            <?= formatPrice(FREE_SHIPPING_THRESHOLD - $araToplam) ?> daha alışveriş yapın, kargo bedava!
                        </div>
                        <?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Toplam:</strong>
                            <strong class="text-primary fs-5"><?= formatPrice($toplamTutar) ?></strong>
                        </div>

                        <!-- Kupon Kodu -->
                        <div class="mb-3">
                            <label class="form-label">Kupon Kodu</label>
                            <input type="text" name="kupon_kodu" class="form-control"
                                   placeholder="Kupon kodunuz varsa girin">
                        </div>

                        <input type="hidden" name="csrf_token" value="<?= csrf() ?>">

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle"></i> Siparişi Tamamla
                        </button>
                    </div>
                </div>

                <!-- Güvenli Alışveriş -->
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt text-success fs-3 mb-2"></i>
                        <p class="small mb-0">
                            <strong>Güvenli Alışveriş</strong><br>
                            256-bit SSL Sertifikası ile korunmaktadır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php endif; ?>
</div>

<script>
function toggleFaturaAdres() {
    const ayniAdres = document.getElementById('ayniAdres').checked;
    const faturaSecim = document.getElementById('faturaAdresSecim');
    const teslimatRadios = document.querySelectorAll('input[name="teslimat_adres_id"]');
    const faturaRadios = document.querySelectorAll('input[name="fatura_adres_id"]');

    if (ayniAdres) {
        faturaSecim.style.display = 'none';
        // Teslimat adresini fatura adresine kopyala
        teslimatRadios.forEach((radio, index) => {
            if (radio.checked && faturaRadios[index]) {
                faturaRadios[index].checked = true;
            }
        });
    } else {
        faturaSecim.style.display = 'block';
    }
}

// Teslimat adresi değiştiğinde fatura adresini de güncelle (eğer aynı seçiliyse)
document.querySelectorAll('input[name="teslimat_adres_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (document.getElementById('ayniAdres').checked) {
            const value = this.value;
            document.querySelectorAll('input[name="fatura_adres_id"]').forEach(faturaRadio => {
                if (faturaRadio.value === value) {
                    faturaRadio.checked = true;
                }
            });
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
