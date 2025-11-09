<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Kullanıcı Giriş Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);
$pageTitle = 'Giriş Yap - WawaHouse';
require_once __DIR__ . '/includes/header.php';

// Zaten giriş yapmışsa yönlendir
if (isLoggedIn()) {
    redirect(url('hesabim.php'));
}

$error = '';
$redirect = $_GET['redirect'] ?? 'hesabim';

// Form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email ve şifre alanları zorunludur.';
    } else {
        // Kullanıcı kontrolü
        $user = db()->fetchOne("SELECT * FROM kullanicilar WHERE email = ? AND rol = 'musteri' AND aktif = 1", [$email]);

        if (!$user || !verifyPassword($password, $user['sifre'])) {
            $error = 'Email veya şifre hatalı!';
        } else {
            // Session oluştur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['ad_soyad'];
            $_SESSION['user_role'] = $user['rol'];

            // Son giriş güncelle
            db()->update('kullanicilar', [
                'son_giris' => date('Y-m-d H:i:s'),
                'ip_adresi' => $_SERVER['REMOTE_ADDR']
            ], 'id = :id', ['id' => $user['id']]);

            // Session sepeti kullanıcıya aktar
            $sessionId = getCartSessionId();
            db()->query("UPDATE sepet SET kullanici_id = {$user['id']} WHERE session_id = '{$sessionId}'");

            success('Hoş geldiniz, ' . $user['ad_soyad'] . '!');
            redirect(url($redirect . '.php'));
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-4x text-primary"></i>
                        <h2 class="mt-3">Giriş Yap</h2>
                        <p class="text-muted">WawaHouse hesabınıza giriş yapın</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Adresi</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email"
                                   placeholder="ornek@email.com" required value="<?= clean($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i>
                                Giriş Yap
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-2">
                                <a href="<?= url('sifremi-unuttum.php') ?>" class="text-decoration-none">
                                    Şifremi Unuttum
                                </a>
                            </p>
                            <p>
                                Hesabınız yok mu?
                                <a href="<?= url('kayit.php') ?>" class="text-decoration-none fw-bold">
                                    Kayıt Olun
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
