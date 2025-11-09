<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Kullanıcı Kayıt Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);
$pageTitle = 'Kayıt Ol - WawaHouse';
require_once __DIR__ . '/includes/header.php';

// Zaten giriş yapmışsa yönlendir
if (isLoggedIn()) {
    redirect(url('hesabim.php'));
}

$error = '';
$success = false;

// Form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = clean($_POST['ad_soyad'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $telefon = clean($_POST['telefon'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validasyon
    if (empty($adSoyad) || empty($email) || empty($password)) {
        $error = 'Tüm zorunlu alanları doldurun.';
    } elseif (!validateEmail($email)) {
        $error = 'Geçerli bir email adresi girin.';
    } elseif (!validatePassword($password)) {
        $error = 'Şifre en az ' . PASSWORD_MIN_LENGTH . ' karakter olmalıdır.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Şifreler eşleşmiyor.';
    } else {
        // Email kontrolü
        $exists = db()->fetchOne("SELECT id FROM kullanicilar WHERE email = ?", [$email]);

        if ($exists) {
            $error = 'Bu email adresi zaten kayıtlı.';
        } else {
            // Kullanıcı oluştur
            $userId = db()->insert('kullanicilar', [
                'ad_soyad' => $adSoyad,
                'email' => $email,
                'telefon' => $telefon,
                'sifre' => hashPassword($password),
                'rol' => 'musteri',
                'aktif' => 1,
                'email_dogrulandi' => 1, // Demo için direkt onaylı
                'ip_adresi' => $_SERVER['REMOTE_ADDR']
            ]);

            if ($userId) {
                // Session oluştur (otomatik giriş)
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $adSoyad;
                $_SESSION['user_role'] = 'musteri';

                // Session sepeti kullanıcıya aktar
                $sessionId = getCartSessionId();
                db()->query("UPDATE sepet SET kullanici_id = {$userId} WHERE session_id = '{$sessionId}'");

                success('Kayıt başarılı! Hoş geldiniz!');
                redirect(url('hesabim.php'));
            } else {
                $error = 'Kayıt sırasında bir hata oluştu.';
            }
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-4x text-primary"></i>
                        <h2 class="mt-3">Kayıt Ol</h2>
                        <p class="text-muted">WawaHouse'a hoş geldiniz!</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="ad_soyad" class="form-label">Ad Soyad *</label>
                            <input type="text" class="form-control" id="ad_soyad" name="ad_soyad"
                                   placeholder="Adınız Soyadınız" required value="<?= clean($_POST['ad_soyad'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Adresi *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="ornek@email.com" required value="<?= clean($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telefon" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="telefon" name="telefon"
                                   placeholder="0532 123 45 67" value="<?= clean($_POST['telefon'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre *</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="••••••••" required>
                            <small class="text-muted">En az <?= PASSWORD_MIN_LENGTH ?> karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Şifre Tekrar *</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                <a href="<?= url('sayfa/kvkk') ?>" target="_blank">KVKK</a> ve
                                <a href="<?= url('sayfa/mesafeli-satis-sozlesmesi') ?>" target="_blank">Mesafeli Satış Sözleşmesi</a>'ni okudum, kabul ediyorum.
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i>
                                Kayıt Ol
                            </button>
                        </div>

                        <div class="text-center">
                            <p>
                                Zaten hesabınız var mı?
                                <a href="<?= url('giris.php') ?>" class="text-decoration-none fw-bold">
                                    Giriş Yapın
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
