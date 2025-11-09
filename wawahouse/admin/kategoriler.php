<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin - Kategori Yönetimi
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

$pageTitle = 'Kategori Yönetimi';

// POST/GET işlemlerini header'dan ÖNCE yap (headers already sent hatasını önlemek için)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Silme işlemi
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Alt kategorisi var mı kontrol et
    $hasChildren = db()->count('kategoriler', 'parent_id = ?', [$id]);
    if ($hasChildren > 0) {
        setFlash('error', 'Bu kategorinin alt kategorileri var, önce onları silin!');
    } else {
        db()->delete('kategoriler', 'id = :id', ['id' => $id]);
        logAdminAction('delete', 'kategoriler', $id, null, 'Kategori silindi');
        setFlash('success', 'Kategori silindi!');
    }
    redirect(url('admin/kategoriler.php'));
}

// Ekleme/Düzenleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $ad = clean($_POST['ad']);
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $sira = (int)$_POST['sira'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    $slug = createSlug($ad);

    $data = [
        'parent_id' => $parentId,
        'ad' => $ad,
        'slug' => $slug,
        'sira' => $sira,
        'aktif' => $aktif,
        'seo_title' => clean($_POST['seo_title'] ?? ''),
        'seo_description' => clean($_POST['seo_description'] ?? ''),
        'seo_keywords' => clean($_POST['seo_keywords'] ?? '')
    ];

    if ($id > 0) {
        // Güncelleme
        db()->update('kategoriler', $data, 'id = :id', ['id' => $id]);
        logAdminAction('update', 'kategoriler', $id, null, 'Kategori güncellendi');
        setFlash('success', 'Kategori güncellendi!');
    } else {
        // Ekleme
        $newId = db()->insert('kategoriler', $data);
        logAdminAction('insert', 'kategoriler', $newId, null, 'Yeni kategori eklendi');
        setFlash('success', 'Kategori eklendi!');
    }
    redirect(url('admin/kategoriler.php'));
}

require_once __DIR__ . '/includes/header.php';

// Düzenleme için kategori getir
$editCategory = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editCategory = db()->fetchOne("SELECT * FROM kategoriler WHERE id = ?", [$editId]);
}

// Tüm kategorileri getir
$categories = getCategoryTree();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <?= $editCategory ? '<i class="fas fa-edit"></i> Kategori Düzenle' : '<i class="fas fa-plus"></i> Yeni Kategori' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if ($editCategory): ?>
                        <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Kategori Adı *</label>
                        <input type="text" name="ad" class="form-control" required
                               value="<?= $editCategory ? clean($editCategory['ad']) : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Üst Kategori</label>
                        <select name="parent_id" class="form-select">
                            <option value="">Ana Kategori</option>
                            <?= renderCategoryOptions($categories, $editCategory['parent_id'] ?? 0) ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="sira" class="form-control"
                               value="<?= $editCategory['sira'] ?? 0 ?>">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="aktif" class="form-check-input" id="aktif"
                                   <?= !$editCategory || $editCategory['aktif'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
                    </div>

                    <hr>
                    <h6>SEO Ayarları</h6>

                    <div class="mb-3">
                        <label class="form-label">SEO Başlık</label>
                        <input type="text" name="seo_title" class="form-control"
                               value="<?= $editCategory ? clean($editCategory['seo_title']) : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SEO Açıklama</label>
                        <textarea name="seo_description" class="form-control" rows="3"><?= $editCategory ? clean($editCategory['seo_description']) : '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SEO Anahtar Kelimeler</label>
                        <input type="text" name="seo_keywords" class="form-control"
                               value="<?= $editCategory ? clean($editCategory['seo_keywords']) : '' ?>">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <?= $editCategory ? 'Güncelle' : 'Ekle' ?>
                        </button>
                        <?php if ($editCategory): ?>
                            <a href="kategoriler.php" class="btn btn-secondary">İptal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Kategoriler</h5>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <div class="alert alert-info">Henüz kategori eklenmemiş.</div>
                <?php else: ?>
                    <?php
                    function renderCategoryTable($categories) {
                        foreach ($categories as $cat) {
                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $cat['level']);
                            echo '<tr>';
                            echo '<td>' . $indent . clean($cat['ad']) . '</td>';
                            echo '<td>' . clean($cat['slug']) . '</td>';
                            echo '<td>' . $cat['sira'] . '</td>';
                            echo '<td>';
                            echo $cat['aktif']
                                ? '<span class="badge bg-success">Aktif</span>'
                                : '<span class="badge bg-secondary">Pasif</span>';
                            echo '</td>';
                            echo '<td>';
                            echo '<div class="btn-group btn-group-sm">';
                            echo '<a href="?edit=' . $cat['id'] . '" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>';
                            echo '<a href="?delete=' . $cat['id'] . '" class="btn btn-outline-danger" data-confirm="Silmek istediğinize emin misiniz?"><i class="fas fa-trash"></i></a>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';

                            if (!empty($cat['children'])) {
                                renderCategoryTable($cat['children']);
                            }
                        }
                    }
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kategori Adı</th>
                                <th>Slug</th>
                                <th>Sıra</th>
                                <th>Durum</th>
                                <th width="120">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php renderCategoryTable($categories); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
