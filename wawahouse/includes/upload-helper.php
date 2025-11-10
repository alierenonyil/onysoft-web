<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Dosya Yükleme Helper Fonksiyonları
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

/**
 * Dosya yükle
 *
 * @param array $file $_FILES array element
 * @param string $uploadPath Upload dizini
 * @param array $allowedTypes İzin verilen MIME tipleri
 * @param int $maxSize Maksimum dosya boyutu (byte)
 * @return array ['success' => bool, 'filename' => string, 'path' => string, 'error' => string]
 */
function uploadFile(array $file, string $uploadPath = 'products', array $allowedTypes = [], int $maxSize = 5242880): array {
    // Varsayılan izin verilen tipler (resimler)
    if (empty($allowedTypes)) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    }

    // Dosya var mı kontrol et
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Geçersiz dosya'];
    }

    // Upload hataları kontrol et
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'error' => 'Dosya boyutu çok büyük'];
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'error' => 'Dosya seçilmedi'];
        default:
            return ['success' => false, 'error' => 'Bilinmeyen hata'];
    }

    // Dosya boyutu kontrol
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'Dosya boyutu ' . ($maxSize / 1048576) . 'MB\'dan büyük olamaz'];
    }

    // MIME tipi kontrol
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'İzin verilmeyen dosya tipi'];
    }

    // Uzantı kontrol
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowedExtensions)) {
        return ['success' => false, 'error' => 'İzin verilmeyen dosya uzantısı'];
    }

    // Upload dizini oluştur
    $fullUploadPath = __DIR__ . '/../assets/uploads/' . $uploadPath;
    if (!file_exists($fullUploadPath)) {
        mkdir($fullUploadPath, 0755, true);
    }

    // Benzersiz dosya adı oluştur
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $fullUploadPath . '/' . $filename;

    // Dosyayı taşı
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'error' => 'Dosya yüklenemedi'];
    }

    // Resimse boyutlandır (opsiyonel - basit versiyon)
    if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
        resizeImage($filepath, 1200, 1200); // Maksimum 1200x1200
    }

    return [
        'success' => true,
        'filename' => $filename,
        'path' => '/assets/uploads/' . $uploadPath . '/' . $filename,
        'full_path' => $filepath
    ];
}

/**
 * Resmi yeniden boyutlandır (aspect ratio korunarak)
 */
function resizeImage(string $filepath, int $maxWidth, int $maxHeight): bool {
    $imageInfo = getimagesize($filepath);
    if (!$imageInfo) return false;

    list($width, $height, $type) = $imageInfo;

    // Yeniden boyutlandırma gerekli mi?
    if ($width <= $maxWidth && $height <= $maxHeight) {
        return true;
    }

    // Aspect ratio koru
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int)($width * $ratio);
    $newHeight = (int)($height * $ratio);

    // Kaynak resmi yükle
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($filepath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($filepath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($filepath);
            break;
        default:
            return false;
    }

    // Yeni resim oluştur
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // PNG transparency koru
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    // Resize
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Kaydet
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($newImage, $filepath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($newImage, $filepath, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($newImage, $filepath);
            break;
    }

    imagedestroy($source);
    imagedestroy($newImage);

    return true;
}

/**
 * Dosyayı sil
 */
function deleteFile(string $filepath): bool {
    $fullPath = __DIR__ . '/../' . ltrim($filepath, '/');
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}
