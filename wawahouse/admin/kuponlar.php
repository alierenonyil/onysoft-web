<?php
$pageTitle = 'Kupon Yönetimi';

// POST/GET işlemlerini header'dan ÖNCE yap (headers already sent hatasını önlemek için)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['delete'])) {
    db()->delete('kuponlar', 'id = :id', ['id' => (int)$_GET['delete']]);
    setFlash('success', 'Kupon silindi!');
    redirect(url('admin/kuponlar.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'kod' => strtoupper(clean($_POST['kod'])),
        'indirim_tipi' => clean($_POST['indirim_tipi']),
        'indirim_degeri' => (float)$_POST['indirim_degeri'],
        'min_tutar' => (float)$_POST['min_tutar'],
        'kullanim_limiti' => !empty($_POST['kullanim_limiti']) ? (int)$_POST['kullanim_limiti'] : null,
        'gecerlilik_baslangic' => $_POST['gecerlilik_baslangic'],
        'gecerlilik_bitis' => $_POST['gecerlilik_bitis'],
        'aktif' => isset($_POST['aktif']) ? 1 : 0
    ];

    db()->insert('kuponlar', $data);
    setFlash('success', 'Kupon oluşturuldu!');
    redirect(url('admin/kuponlar.php'));
}

require_once __DIR__ . '/includes/header.php';

$coupons = db()->fetchAll("SELECT * FROM kuponlar ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Yeni Kupon</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Kupon Kodu *</label>
                        <input type="text" name="kod" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>İndirim Tipi *</label>
                        <select name="indirim_tipi" class="form-select" required>
                            <option value="yuzde">Yüzde (%)</option>
                            <option value="tutar">Tutar (₺)</option>
                            <option value="bedava_kargo">Bedava Kargo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>İndirim Değeri *</label>
                        <input type="number" step="0.01" name="indirim_degeri" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Min. Sepet Tutarı</label>
                        <input type="number" step="0.01" name="min_tutar" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Kullanım Limiti</label>
                        <input type="number" name="kullanim_limiti" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Başlangıç Tarihi</label>
                        <input type="date" name="gecerlilik_baslangic" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Bitiş Tarihi</label>
                        <input type="date" name="gecerlilik_bitis" class="form-control">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="aktif" class="form-check-input" id="aktif" checked>
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> Kuponlar</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kod</th>
                            <th>İndirim</th>
                            <th>Min. Tutar</th>
                            <th>Kullanım</th>
                            <th>Geçerlilik</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $coupon): ?>
                            <tr>
                                <td><strong><?= clean($coupon['kod']) ?></strong></td>
                                <td>
                                    <?php if ($coupon['indirim_tipi'] === 'yuzde'): ?>
                                        %<?= $coupon['indirim_degeri'] ?>
                                    <?php elseif ($coupon['indirim_tipi'] === 'tutar'): ?>
                                        <?= formatPrice($coupon['indirim_degeri']) ?>
                                    <?php else: ?>
                                        Bedava Kargo
                                    <?php endif; ?>
                                </td>
                                <td><?= formatPrice($coupon['min_tutar']) ?></td>
                                <td><?= $coupon['kullanim_sayisi'] ?> / <?= $coupon['kullanim_limiti'] ?? '∞' ?></td>
                                <td>
                                    <small>
                                        <?= $coupon['gecerlilik_baslangic'] ? formatDate($coupon['gecerlilik_baslangic'], 'd.m.Y') : '-' ?>
                                        <br>
                                        <?= $coupon['gecerlilik_bitis'] ? formatDate($coupon['gecerlilik_bitis'], 'd.m.Y') : '-' ?>
                                    </small>
                                </td>
                                <td>
                                    <?= $coupon['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?>
                                </td>
                                <td>
                                    <a href="?delete=<?= $coupon['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Silmek istediğinize emin misiniz?">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
