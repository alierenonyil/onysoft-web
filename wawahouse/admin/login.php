<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Giriş Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

require_once __DIR__ . '/../config.php';

// Zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['admin_id']) && $_SESSION['admin_role'] === 'admin') {
    redirect(url('admin/index.php'));
}

$error = '';

// Form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email ve şifre alanları zorunludur.';
    } else {
        // Giriş denemesi
        $user = db()->fetchOne("SELECT * FROM kullanicilar WHERE email = ? AND rol = 'admin' AND aktif = 1", [$email]);

        if (!$user || !verifyPassword($password, $user['sifre'])) {
            $error = 'Email veya şifre hatalı!';
        } else {
            // Session oluştur
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_name'] = $user['ad_soyad'];
            $_SESSION['admin_role'] = $user['rol'];

            // Son giriş güncelle
            db()->update('kullanicilar', [
                'son_giris' => date('Y-m-d H:i:s'),
                'ip_adresi' => $_SERVER['REMOTE_ADDR']
            ], 'id = :id', ['id' => $user['id']]);

            // Log kaydet
            db()->insert('admin_loglari', [
                'admin_id' => $user['id'],
                'aksiyon' => 'login',
                'tablo' => 'kullanicilar',
                'kayit_id' => $user['id'],
                'aciklama' => 'Admin girişi yaptı',
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);

            redirect(url('admin/index.php'));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - WawaHouse</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #ff9ec5;
            --secondary-color: #a8d8ff;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo i {
            font-size: 60px;
            color: var(--primary-color);
        }

        .login-logo h3 {
            margin-top: 15px;
            color: #333;
            font-weight: bold;
        }

        .login-logo small {
            color: #999;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 158, 197, 0.25);
        }

        .btn-login {
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            transition: transform 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .developer-info {
            text-align: center;
            margin-top: 20px;
            color: white;
            font-size: 14px;
        }

        .developer-info a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-baby-carriage"></i>
                <h3>WawaHouse</h3>
                <small>Admin Panel Girişi</small>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= clean($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Adresi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="admin@wawahouse.com" required value="<?= clean($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Şifre</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </button>
            </form>
        </div>

        <div class="developer-info">
            <p class="mb-1">
                <i class="fas fa-code"></i> Powered by
                <a href="<?= DEVELOPER_URL ?>" target="_blank"><?= DEVELOPER_NAME ?></a>
            </p>
            <p class="mb-0">
                <a href="<?= DEVELOPER_URL ?>" target="_blank"><?= DEVELOPER_WEB ?></a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
