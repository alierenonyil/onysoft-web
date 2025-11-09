<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Dinamik Sayfa
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);

// URL'den slug al
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: ' . url());
    exit;
}

// Sayfayı getir
$page = db()->fetchOne("SELECT * FROM sayfalar WHERE slug = ? AND aktif = 1", [$slug]);

if (!$page) {
    header('Location: ' . url());
    exit;
}

$pageTitle = clean($page['seo_title'] ?? $page['baslik']);
$pageDescription = clean($page['seo_description'] ?? truncateDescription($page['icerik'], 160));

require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url() ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item active"><?= clean($page['baslik']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="mb-4"><?= clean($page['baslik']) ?></h1>
                    <div class="page-content">
                        <?= $page['icerik'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
