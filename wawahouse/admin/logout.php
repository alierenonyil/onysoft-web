<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Çıkış
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

require_once __DIR__ . '/../config.php';

// Log kaydet
if (isset($_SESSION['admin_id'])) {
    db()->insert('admin_loglari', [
        'admin_id' => $_SESSION['admin_id'],
        'aksiyon' => 'logout',
        'tablo' => 'kullanicilar',
        'kayit_id' => $_SESSION['admin_id'],
        'aciklama' => 'Admin çıkış yaptı',
        'ip_address' => $_SERVER['REMOTE_ADDR']
    ]);
}

// Session temizle
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_role']);

session_destroy();

redirect(url('admin/login.php'));
