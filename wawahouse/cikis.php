<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Çıkış Yap
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

require_once __DIR__ . '/config.php';

// Session temizle
unset($_SESSION['user_id']);
unset($_SESSION['user_email']);
unset($_SESSION['user_name']);
unset($_SESSION['user_role']);

session_destroy();

redirect(url('giris.php'));
