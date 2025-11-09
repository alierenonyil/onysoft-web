<?php
$pageTitle = 'Yorum Yönetimi';

// GET işlemlerini header'dan ÖNCE yap (headers already sent hatasını önlemek için)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['approve'])) {
    db()->update('yorumlar', ['onay' => 1], 'id = :id', ['id' => (int)$_GET['approve']]);
    setFlash('success', 'Yorum onaylandı!');
    redirect(url('admin/yorumlar.php'));
}

if (isset($_GET['delete'])) {
    db()->delete('yorumlar', 'id = :id', ['id' => (int)$_GET['delete']]);
    setFlash('success', 'Yorum silindi!');
    redirect(url('admin/yorumlar.php'));
}

require_once __DIR__ . '/includes/header.php';

$comments = db()->fetchAll("
    SELECT y.*, u.ad_soyad, ur.ad as urun_ad
    FROM yorumlar y
    LEFT JOIN kullanicilar u ON u.id = y.kullanici_id
    LEFT JOIN urunler ur ON ur.id = y.urun_id
    ORDER BY y.created_at DESC
    LIMIT 50
");
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-comments"></i> Yorumlar</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>Müşteri</th>
                    <th>Yorum</th>
                    <th>Puan</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?= clean($comment['urun_ad']) ?></td>
                        <td><?= clean($comment['ad_soyad']) ?></td>
                        <td><?= substr(clean($comment['yorum']), 0, 50) ?>...</td>
                        <td><?= str_repeat('⭐', $comment['puan']) ?></td>
                        <td><?= formatDate($comment['created_at']) ?></td>
                        <td>
                            <?= $comment['onay'] ? '<span class="badge bg-success">Onaylı</span>' : '<span class="badge bg-warning">Bekliyor</span>' ?>
                        </td>
                        <td>
                            <?php if (!$comment['onay']): ?>
                                <a href="?approve=<?= $comment['id'] ?>" class="btn btn-sm btn-success">Onayla</a>
                            <?php endif; ?>
                            <a href="?delete=<?= $comment['id'] ?>" class="btn btn-sm btn-danger" data-confirm="Silmek istediğinize emin misiniz?">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
