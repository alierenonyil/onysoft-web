<?php
$pageTitle = 'Slider Yönetimi';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) {
    db()->delete('sliderlar', 'id = :id', ['id' => (int)$_GET['delete']]);
    success('Slider silindi!');
    redirect(url('admin/sliderlar.php'));
}

$sliders = db()->fetchAll("SELECT * FROM sliderlar ORDER BY sira ASC");
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0"><i class="fas fa-images"></i> Sliderlar</h5>
        <small class="text-muted">Resim yükleme özelliği sonra eklenecek</small>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>Buton</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders as $slider): ?>
                    <tr>
                        <td><?= clean($slider['baslik']) ?></td>
                        <td><?= clean($slider['aciklama']) ?></td>
                        <td><?= clean($slider['buton_text']) ?></td>
                        <td><?= $slider['sira'] ?></td>
                        <td><?= $slider['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?></td>
                        <td>
                            <a href="?delete=<?= $slider['id'] ?>" class="btn btn-sm btn-danger" data-confirm="Silmek istediğinize emin misiniz?">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
