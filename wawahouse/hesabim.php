<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Hesabım Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);
$pageTitle = 'Hesabım - WawaHouse';
require_once __DIR__ . '/includes/header.php';

// Giriş yapmamışsa yönlendir
requireLogin('giris.php');

$user = currentUser();
$activeTab = $_GET['tab'] ?? 'profile';

// Siparişleri getir
$orders = db()->fetchAll("
    SELECT * FROM siparisler
    WHERE kullanici_id = ?
    ORDER BY created_at DESC
    LIMIT 10
", [$user['id']]);

// Adresleri getir
$addresses = db()->fetchAll("
    SELECT * FROM adresler
    WHERE kullanici_id = ?
    ORDER BY varsayilan DESC, created_at DESC
", [$user['id']]);

// Favorileri getir
$favorites = db()->fetchAll("
    SELECT f.*, u.ad, u.slug, u.fiyat, u.indirimli_fiyat,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM favoriler f
    INNER JOIN urunler u ON u.id = f.urun_id
    WHERE f.kullanici_id = ?
    ORDER BY f.created_at DESC
", [$user['id']]);

// Profil güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $adSoyad = clean($_POST['ad_soyad']);
    $telefon = clean($_POST['telefon']);

    db()->update('kullanicilar', [
        'ad_soyad' => $adSoyad,
        'telefon' => $telefon
    ], 'id = :id', ['id' => $user['id']]);

    $_SESSION['user_name'] = $adSoyad;
    success('Profiliniz güncellendi!');
    redirect(url('hesabim.php'));
}

// Şifre değiştirme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!verifyPassword($oldPassword, $user['sifre'])) {
        error('Mevcut şifreniz yanlış!');
    } elseif ($newPassword !== $confirmPassword) {
        error('Yeni şifreler eşleşmiyor!');
    } elseif (!validatePassword($newPassword)) {
        error('Şifre en az ' . PASSWORD_MIN_LENGTH . ' karakter olmalıdır!');
    } else {
        db()->update('kullanicilar', [
            'sifre' => hashPassword($newPassword)
        ], 'id = :id', ['id' => $user['id']]);

        success('Şifreniz değiştirildi!');
        redirect(url('hesabim.php?tab=security'));
    }
}
?>

<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-user-circle"></i>
        Hesabım
    </h1>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="user-avatar mx-auto mb-2" style="width: 80px; height: 80px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                            <?= strtoupper(substr($user['ad_soyad'], 0, 1)) ?>
                        </div>
                        <h5><?= clean($user['ad_soyad']) ?></h5>
                        <p class="text-muted small mb-0"><?= clean($user['email']) ?></p>
                    </div>
                    <hr>
                    <nav class="nav flex-column">
                        <a class="nav-link <?= $activeTab === 'profile' ? 'active' : '' ?>" href="?tab=profile">
                            <i class="fas fa-user"></i> Profilim
                        </a>
                        <a class="nav-link <?= $activeTab === 'orders' ? 'active' : '' ?>" href="?tab=orders">
                            <i class="fas fa-shopping-bag"></i> Siparişlerim
                        </a>
                        <a class="nav-link <?= $activeTab === 'addresses' ? 'active' : '' ?>" href="?tab=addresses">
                            <i class="fas fa-map-marker-alt"></i> Adreslerim
                        </a>
                        <a class="nav-link <?= $activeTab === 'favorites' ? 'active' : '' ?>" href="?tab=favorites">
                            <i class="fas fa-heart"></i> Favorilerim
                        </a>
                        <a class="nav-link <?= $activeTab === 'security' ? 'active' : '' ?>" href="?tab=security">
                            <i class="fas fa-lock"></i> Güvenlik
                        </a>
                        <hr>
                        <a class="nav-link text-danger" href="<?= url('cikis.php') ?>">
                            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-9">
            <?php if ($activeTab === 'profile'): ?>
                <!-- Profil -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Profil Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ad Soyad</label>
                                    <input type="text" name="ad_soyad" class="form-control" value="<?= clean($user['ad_soyad']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?= clean($user['email']) ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefon</label>
                                    <input type="tel" name="telefon" class="form-control" value="<?= clean($user['telefon']) ?>" placeholder="0532 123 45 67">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Üyelik Tarihi</label>
                                    <input type="text" class="form-control" value="<?= formatDate($user['created_at'], 'd.m.Y') ?>" disabled>
                                </div>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                        </form>
                    </div>
                </div>

            <?php elseif ($activeTab === 'orders'): ?>
                <!-- Siparişler -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Siparişlerim</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Henüz siparişiniz bulunmuyor.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sipariş No</th>
                                            <th>Tarih</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                            <th>İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong><?= clean($order['siparis_no']) ?></strong></td>
                                                <td><?= formatDate($order['created_at']) ?></td>
                                                <td><?= formatPrice($order['toplam_tutar']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= getOrderStatusColor($order['durum']) ?>">
                                                        <?= getOrderStatusText($order['durum']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?= url('siparis-detay.php?id=' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Detay
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'addresses'): ?>
                <!-- Adresler -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Adreslerim</h5>
                        <a href="<?= url('adres-ekle.php') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Yeni Adres
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Henüz kayıtlı adresiniz bulunmuyor.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($addresses as $address): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php if ($address['varsayilan']): ?>
                                                    <span class="badge bg-success mb-2">Varsayılan</span>
                                                <?php endif; ?>
                                                <h6><?= clean($address['ad']) ?> <?= clean($address['soyad']) ?></h6>
                                                <p class="mb-1 small"><?= clean($address['adres']) ?></p>
                                                <p class="mb-1 small"><?= clean($address['ilce']) ?> / <?= clean($address['il']) ?></p>
                                                <p class="mb-2 small"><?= clean($address['telefon']) ?></p>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= url('adres-duzenle.php?id=' . $address['id']) ?>" class="btn btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Düzenle
                                                    </a>
                                                    <a href="<?= url('adres-sil.php?id=' . $address['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                                        <i class="fas fa-trash"></i> Sil
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'favorites'): ?>
                <!-- Favoriler -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-heart"></i> Favori Ürünlerim</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($favorites)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Henüz favori ürününüz bulunmuyor.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($favorites as $fav): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="product-card">
                                            <div class="product-image">
                                                <a href="<?= url('urun/' . $fav['slug']) ?>">
                                                    <img src="<?= upload($fav['resim'] ?? 'products/default.jpg') ?>" alt="<?= clean($fav['ad']) ?>">
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <a href="<?= url('urun/' . $fav['slug']) ?>" class="product-title">
                                                    <?= clean($fav['ad']) ?>
                                                </a>
                                                <div class="product-price">
                                                    <?= formatPrice($fav['indirimli_fiyat'] ?? $fav['fiyat']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'security'): ?>
                <!-- Güvenlik -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-lock"></i> Şifre Değiştir</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Mevcut Şifre</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Yeni Şifre</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Yeni Şifre (Tekrar)</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">
                                <i class="fas fa-key"></i> Şifreyi Değiştir
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
