<?php
$pageTitle = 'Sayfa Yönetimi';

// POST/GET işlemlerini header'dan ÖNCE yap
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Silme işlemi
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    db()->delete('sayfalar', 'id = :id', ['id' => $id]);
    logAdminAction('delete', 'sayfalar', $id, null, 'Sayfa silindi');
    setFlash('success', 'Sayfa silindi!');
    redirect(url('admin/sayfalar.php'));
}

require_once __DIR__ . '/includes/header.php';

$pages = db()->fetchAll("SELECT * FROM sayfalar ORDER BY sira ASC");
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Sayfalar</h5>
        <a href="sayfa-ekle.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Sayfa Ekle
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($pages)): ?>
            <div class="alert alert-info">Henüz sayfa eklenmemiş.</div>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="5%">Sıra</th>
                    <th>Başlık</th>
                    <th>Slug</th>
                    <th width="10%">Durum</th>
                    <th width="10%">Menüde</th>
                    <th width="12%">Tarih</th>
                    <th width="15%">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= $page['sira'] ?></td>
                        <td><strong><?= clean($page['baslik']) ?></strong></td>
                        <td><code><?= clean($page['slug']) ?></code></td>
                        <td><?= $page['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?></td>
                        <td><?= $page['menude_goster'] ? '<i class="fas fa-check text-success"></i>' : '-' ?></td>
                        <td><?= formatDate($page['created_at'], 'd.m.Y') ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="sayfa-duzenle.php?id=<?= $page['id'] ?>"
                                   class="btn btn-info" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="sayfalar.php?delete=<?= $page['id'] ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Bu sayfayı silmek istediğinizden emin misiniz?')"
                                   title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="<?= url('sayfa/' . $page['slug']) ?>"
                                   class="btn btn-secondary"
                                   target="_blank"
                                   title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
