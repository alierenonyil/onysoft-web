<?php
$pageTitle = 'İade Talepleri';
require_once __DIR__ . '/includes/header.php';

if (isset($_POST['update_status'])) {
    $iadeId = (int)$_POST['iade_id'];
    $durum = clean($_POST['durum']);
    $adminNotu = clean($_POST['admin_notu'] ?? '');

    db()->update('iadeler', [
        'durum' => $durum,
        'admin_notu' => $adminNotu
    ], 'id = :id', ['id' => $iadeId]);

    success('İade durumu güncellendi!');
    redirect(url('admin/iadeler.php'));
}

$iadeler = db()->fetchAll("
    SELECT i.*, s.siparis_no, k.ad_soyad
    FROM iadeler i
    LEFT JOIN siparisler s ON s.id = i.siparis_id
    LEFT JOIN kullanicilar k ON k.id = i.kullanici_id
    ORDER BY i.created_at DESC
");
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-undo"></i> İade Talepleri</h5>
    </div>
    <div class="card-body">
        <?php if (empty($iadeler)): ?>
            <div class="alert alert-info">İade talebi bulunmuyor.</div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Neden</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($iadeler as $iade): ?>
                        <tr>
                            <td><?= clean($iade['siparis_no']) ?></td>
                            <td><?= clean($iade['ad_soyad']) ?></td>
                            <td><?= clean($iade['neden']) ?></td>
                            <td><?= formatDate($iade['created_at']) ?></td>
                            <td>
                                <?php
                                $statusColor = match($iade['durum']) {
                                    'onaylandi' => 'success',
                                    'reddedildi' => 'danger',
                                    default => 'warning'
                                };
                                ?>
                                <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($iade['durum']) ?></span>
                            </td>
                            <td>
                                <?php if ($iade['durum'] === 'beklemede'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="iade_id" value="<?= $iade['id'] ?>">
                                        <input type="hidden" name="durum" value="onaylandi">
                                        <button type="submit" name="update_status" class="btn btn-sm btn-success">Onayla</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="iade_id" value="<?= $iade['id'] ?>">
                                        <input type="hidden" name="durum" value="reddedildi">
                                        <button type="submit" name="update_status" class="btn btn-sm btn-danger">Reddet</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
