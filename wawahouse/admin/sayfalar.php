<?php
$pageTitle = 'Sayfa Yönetimi';
require_once __DIR__ . '/includes/header.php';

$pages = db()->fetchAll("SELECT * FROM sayfalar ORDER BY sira ASC");
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Sayfalar</h5>
        <small class="text-muted">Sayfa düzenleme özelliği sonra eklenecek</small>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Slug</th>
                    <th>Durum</th>
                    <th>Menüde</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><strong><?= clean($page['baslik']) ?></strong></td>
                        <td><code><?= clean($page['slug']) ?></code></td>
                        <td><?= $page['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?></td>
                        <td><?= $page['menude_goster'] ? '<i class="fas fa-check text-success"></i>' : '-' ?></td>
                        <td><?= formatDate($page['created_at'], 'd.m.Y') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
