<?php
$pageTitle = 'Müşteri Yönetimi';
require_once __DIR__ . '/includes/header.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = ADMIN_PER_PAGE;
$total = db()->count('kullanicilar', "rol = 'musteri'");
$pagination = paginate($total, $perPage, $page);

$customers = db()->fetchAll("
    SELECT *, (SELECT COUNT(*) FROM siparisler WHERE kullanici_id = kullanicilar.id) as siparis_sayisi
    FROM kullanicilar
    WHERE rol = 'musteri'
    ORDER BY created_at DESC
    LIMIT {$perPage} OFFSET {$pagination['offset']}
");
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-users"></i> Müşteriler (<?= $total ?>)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Sipariş</th>
                        <th>Kayıt Tarihi</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= clean($customer['ad_soyad']) ?></td>
                            <td><?= clean($customer['email']) ?></td>
                            <td><?= clean($customer['telefon']) ?></td>
                            <td><?= $customer['siparis_sayisi'] ?> adet</td>
                            <td><?= formatDate($customer['created_at']) ?></td>
                            <td>
                                <?= $customer['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= renderPagination($pagination, 'musteriler.php') ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
