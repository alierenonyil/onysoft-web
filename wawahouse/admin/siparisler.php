<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Sipariş Yönetimi
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Sipariş Yönetimi';
require_once __DIR__ . '/includes/header.php';

// Durum güncelleme
if (isset($_POST['update_status'])) {
    $siparisId = (int)$_POST['siparis_id'];
    $yeniDurum = clean($_POST['durum']);

    db()->update('siparisler', ['durum' => $yeniDurum], 'id = :id', ['id' => $siparisId]);
    logAdminAction('update', 'siparisler', $siparisId, null, 'Sipariş durumu güncellendi: ' . $yeniDurum);
    success('Sipariş durumu güncellendi!');
    redirect(url('admin/siparisler.php'));
}

// Filtreleme
$where = '1=1';
$params = [];

if (isset($_GET['durum']) && !empty($_GET['durum'])) {
    $where .= ' AND s.durum = ?';
    $params[] = clean($_GET['durum']);
}

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $where .= ' AND (s.siparis_no LIKE ? OR k.ad_soyad LIKE ? OR k.email LIKE ?)';
    $q = '%' . clean($_GET['q']) . '%';
    $params[] = $q;
    $params[] = $q;
    $params[] = $q;
}

// Sayfalama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = ADMIN_PER_PAGE;
$total = db()->count('siparisler s LEFT JOIN kullanicilar k ON k.id = s.kullanici_id', $where, $params);
$pagination = paginate($total, $perPage, $page);

// Siparişleri getir
$orders = db()->fetchAll("
    SELECT s.*, k.ad_soyad, k.email
    FROM siparisler s
    LEFT JOIN kullanicilar k ON k.id = s.kullanici_id
    WHERE {$where}
    ORDER BY s.created_at DESC
    LIMIT {$perPage} OFFSET {$pagination['offset']}
", $params);
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Siparişler (<?= $total ?>)</h5>
    </div>
    <div class="card-body">
        <!-- Filtreler -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Sipariş no, müşteri ara..."
                       value="<?= clean($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="durum" class="form-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="beklemede" <?= isset($_GET['durum']) && $_GET['durum'] === 'beklemede' ? 'selected' : '' ?>>Beklemede</option>
                    <option value="onaylandi" <?= isset($_GET['durum']) && $_GET['durum'] === 'onaylandi' ? 'selected' : '' ?>>Onaylandı</option>
                    <option value="hazirlaniyor" <?= isset($_GET['durum']) && $_GET['durum'] === 'hazirlaniyor' ? 'selected' : '' ?>>Hazırlanıyor</option>
                    <option value="kargoda" <?= isset($_GET['durum']) && $_GET['durum'] === 'kargoda' ? 'selected' : '' ?>>Kargoda</option>
                    <option value="teslim_edildi" <?= isset($_GET['durum']) && $_GET['durum'] === 'teslim_edildi' ? 'selected' : '' ?>>Teslim Edildi</option>
                    <option value="iptal" <?= isset($_GET['durum']) && $_GET['durum'] === 'iptal' ? 'selected' : '' ?>>İptal</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-search"></i> Filtrele
                </button>
            </div>
            <div class="col-md-3">
                <a href="siparisler.php" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Temizle
                </a>
            </div>
        </form>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info">Sipariş bulunamadı.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sipariş No</th>
                            <th>Müşteri</th>
                            <th>Tutar</th>
                            <th>Ödeme</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th width="150">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?= clean($order['siparis_no']) ?></strong></td>
                                <td>
                                    <?= clean($order['ad_soyad']) ?><br>
                                    <small class="text-muted"><?= clean($order['email']) ?></small>
                                </td>
                                <td><strong><?= formatPrice($order['toplam_tutar']) ?></strong></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= $order['odeme_yontemi'] === 'kredi_karti' ? 'Kredi Kartı' : 'Kapıda Ödeme' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getOrderStatusColor($order['durum']) ?> badge-status">
                                        <?= getOrderStatusText($order['durum']) ?>
                                    </span>
                                </td>
                                <td><?= formatDate($order['created_at']) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="siparis-detay.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary" title="Detay">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?= renderPagination($pagination, 'siparisler.php') ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
