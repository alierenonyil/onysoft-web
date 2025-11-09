<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Dashboard
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// İstatistikleri al
$stats = getDashboardStats();
$chartData = getMonthlyChartData();
?>

<div class="row g-4 mb-4">
    <!-- Bugünkü Satışlar -->
    <div class="col-xl-3 col-lg-6">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-lira-sign"></i>
            </div>
            <h6 class="text-muted mb-1">Bugünkü Satışlar</h6>
            <h3 class="mb-0"><?= formatPrice($stats['today_sales']) ?></h3>
            <small class="text-muted"><?= $stats['today_orders'] ?> sipariş</small>
        </div>
    </div>

    <!-- Bu Ayki Satışlar -->
    <div class="col-xl-3 col-lg-6">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h6 class="text-muted mb-1">Bu Ayki Satışlar</h6>
            <h3 class="mb-0"><?= formatPrice($stats['month_sales']) ?></h3>
            <small class="text-muted"><?= $stats['month_orders'] ?> sipariş</small>
        </div>
    </div>

    <!-- Toplam Müşteri -->
    <div class="col-xl-3 col-lg-6">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h6 class="text-muted mb-1">Toplam Müşteri</h6>
            <h3 class="mb-0"><?= number_format($stats['total_customers']) ?></h3>
            <small class="text-muted">Kayıtlı üye</small>
        </div>
    </div>

    <!-- Toplam Ürün -->
    <div class="col-xl-3 col-lg-6">
        <div class="stat-card danger">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <h6 class="text-muted mb-1">Toplam Ürün</h6>
            <h3 class="mb-0"><?= number_format($stats['total_products']) ?></h3>
            <small class="text-muted">Aktif ürün</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Satış Grafiği -->
    <div class="col-xl-8">
        <div class="table-card">
            <h5 class="mb-4">
                <i class="fas fa-chart-area text-primary"></i>
                Aylık Satış Grafiği (<?= date('Y') ?>)
            </h5>
            <canvas id="salesChart" height="80"></canvas>
        </div>
    </div>

    <!-- Düşük Stok Uyarıları -->
    <div class="col-xl-4">
        <div class="table-card">
            <h5 class="mb-4">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                Düşük Stok Uyarıları
            </h5>

            <?php if (empty($stats['low_stock'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Tüm ürünler yeterli stokta!
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($stats['low_stock'] as $item): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= clean($item['ad']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= clean($item['beden']) ?> - <?= clean($item['renk']) ?>
                                    </small>
                                </div>
                                <span class="badge bg-warning">
                                    <?= $item['stok'] ?> adet
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Son Siparişler -->
<div class="table-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart text-primary"></i>
            Son Siparişler
        </h5>
        <a href="siparisler.php" class="btn btn-sm btn-outline-primary">
            Tümünü Gör <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <?php if (empty($stats['recent_orders'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Henüz sipariş bulunmuyor.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['recent_orders'] as $order): ?>
                        <tr>
                            <td>
                                <strong><?= clean($order['siparis_no']) ?></strong>
                            </td>
                            <td>
                                <?= clean($order['ad_soyad']) ?><br>
                                <small class="text-muted"><?= clean($order['email']) ?></small>
                            </td>
                            <td>
                                <strong><?= formatPrice($order['toplam_tutar']) ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-<?= getOrderStatusColor($order['durum']) ?> badge-status">
                                    <?= getOrderStatusText($order['durum']) ?>
                                </span>
                            </td>
                            <td><?= formatDate($order['created_at']) ?></td>
                            <td>
                                <a href="siparis-detay.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$extraScripts = <<<'JS'
<script>
// Satış Grafiği
const ctx = document.getElementById('salesChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['months']) ?>,
            datasets: [{
                label: 'Satışlar (₺)',
                data: <?= json_encode($chartData['sales']) ?>,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('tr-TR') + ' ₺';
                        }
                    }
                }
            }
        }
    });
}
</script>
JS;

require_once __DIR__ . '/includes/footer.php';
?>
