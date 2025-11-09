<?php
$pageTitle = 'Raporlar';
require_once __DIR__ . '/includes/header.php';

// Bugünkü satışlar
$todaySales = db()->fetchOne("
    SELECT COUNT(*) as siparis_sayisi, COALESCE(SUM(toplam_tutar), 0) as toplam_tutar
    FROM siparisler
    WHERE DATE(created_at) = CURDATE() AND durum != 'iptal'
");

// Bu ayki satışlar
$monthSales = db()->fetchOne("
    SELECT COUNT(*) as siparis_sayisi, COALESCE(SUM(toplam_tutar), 0) as toplam_tutar
    FROM siparisler
    WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) AND durum != 'iptal'
");

// En çok satan ürünler
$topProducts = db()->fetchAll("
    SELECT u.ad, SUM(sd.adet) as toplam_satis, SUM(sd.toplam) as toplam_tutar
    FROM siparis_detaylari sd
    INNER JOIN urunler u ON u.id = sd.urun_id
    INNER JOIN siparisler s ON s.id = sd.siparis_id
    WHERE s.durum != 'iptal'
    GROUP BY u.id
    ORDER BY toplam_satis DESC
    LIMIT 10
");
?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Bugünkü Satışlar</h6>
                <h3><?= formatPrice($todaySales['toplam_tutar']) ?></h3>
                <small class="text-muted"><?= $todaySales['siparis_sayisi'] ?> sipariş</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Bu Ayki Satışlar</h6>
                <h3><?= formatPrice($monthSales['toplam_tutar']) ?></h3>
                <small class="text-muted"><?= $monthSales['siparis_sayisi'] ?> sipariş</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> En Çok Satan Ürünler</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>Satış Adedi</th>
                    <th>Toplam Tutar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topProducts as $product): ?>
                    <tr>
                        <td><?= clean($product['ad']) ?></td>
                        <td><?= $product['toplam_satis'] ?> adet</td>
                        <td><?= formatPrice($product['toplam_tutar']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
