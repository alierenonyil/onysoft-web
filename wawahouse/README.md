# 🛍️ WawaHouse - Bebek Giyim E-Ticaret Sistemi

**Müşteri:** WawaHouse (Bebek elbiseleri e-ticaret sitesi)
**Geliştirici:** Onysoft Veri Merkezi A.Ş.
**Web:** [www.onysoft.com.tr](https://www.onysoft.com.tr)
**Demo URL:** https://staravcisi.com/

---

## 📋 Proje Hakkında

WawaHouse, bebekler için özel tasarlanmış, modern ve kullanıcı dostu bir e-ticaret platformudur. %100 organik pamuklu bebek kıyafetleri satışı için geliştirilmiştir.

### ✨ Özellikler

- ✅ Modern ve bebek temalı tasarım (pastel renkler, sevimli ikonlar)
- ✅ PHP 8.0+ ile modern kod yapısı (type hints, match, named arguments)
- ✅ Güvenli PDO database bağlantısı (SQL injection koruması)
- ✅ Admin paneli (Dashboard, ürün/kategori/sipariş yönetimi)
- ✅ Ürün varyantları (beden, renk, stok takibi)
- ✅ Sepet sistemi (session ve kullanıcı bazlı)
- ✅ Kupon sistemi (yüzde, tutar, bedava kargo)
- ✅ Sipariş yönetimi ve tracking
- ✅ Responsive tasarım (mobile-first)
- ✅ Bootstrap 5 + Font Awesome
- ✅ Chart.js ile istatistik grafikleri
- ✅ SEO dostu URL yapısı
- ✅ CSRF, XSS koruması

---

## 🚀 Kurulum

### 1️⃣ Gereksinimler

- PHP 8.0 veya üzeri
- MySQL 5.7+ / MariaDB 10.3+
- Apache/Nginx web server
- Composer (PHP bağımlılık yöneticisi)
- mod_rewrite aktif (Apache için)

### 2️⃣ Adım Adım Kurulum

#### A) Dosyaları Sunucuya Yükleyin

```bash
# FTP/SFTP ile dosyaları public_html veya htdocs klasörüne yükleyin
# VEYA
git clone <repository-url>
cd wawahouse
```

#### B) Composer Bağımlılıklarını Yükleyin

```bash
composer install
```

#### C) Database Oluşturun

1. phpMyAdmin veya MySQL komut satırından database oluşturun:

```sql
CREATE DATABASE wawahousesql CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Database yapısını import edin:

```bash
mysql -u kullanici_adi -p wawahousesql < database.sql
```

3. Örnek verileri import edin:

```bash
mysql -u kullanici_adi -p wawahousesql < seed_data.sql
```

#### D) Config Dosyasını Düzenleyin

`config.php` dosyasını açın ve aşağıdaki bilgileri güncelleyin:

```php
// Database Ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'wawahousesql');
define('DB_USER', 'wawahousekullanici');
define('DB_PASS', '14531453aO.!');

// Site Ayarları
define('SITE_URL', 'https://staravcisi.com/');
define('SITE_EMAIL', 'info@wawahouse.com');

// SMTP Mail Ayarları (opsiyonel)
define('SMTP_HOST', 'mail.staravcisi.com');
define('SMTP_USER', 'noreply@wawahouse.com');
define('SMTP_PASS', 'your_mail_password');
```

#### E) Klasör İzinlerini Ayarlayın

```bash
chmod -R 755 assets/uploads/
chmod -R 644 config.php
```

#### F) .htaccess Kontrolü

Apache kullanıyorsanız `.htaccess` dosyasının aktif olduğundan emin olun. `mod_rewrite` modülü aktif olmalı.

---

## 🔐 Giriş Bilgileri

### Admin Paneli

**URL:** https://staravcisi.com/admin/login.php

**Email:** admin@wawahouse.com
**Şifre:** admin123

⚠️ **ÖNEMLİ:** İlk girişten sonra admin şifresini mutlaka değiştirin!

---

## 📁 Proje Yapısı

```
wawahouse/
├── admin/                  # Admin paneli
│   ├── includes/          # Header, footer, auth
│   ├── index.php          # Dashboard
│   ├── login.php          # Admin girişi
│   └── ...                # Diğer admin sayfaları
├── assets/
│   ├── css/               # Stil dosyaları
│   ├── js/                # JavaScript dosyaları
│   ├── images/            # Görseller
│   └── uploads/           # Yüklenen dosyalar
├── includes/
│   ├── config.php         # Temel ayarlar
│   ├── db.php             # Database sınıfı
│   ├── functions.php      # Helper fonksiyonlar
│   ├── header.php         # Frontend header
│   └── footer.php         # Frontend footer
├── index.php              # Ana sayfa
├── urunler.php            # Ürün listesi
├── urun-detay.php         # Ürün detay
├── sepet.php              # Sepet
├── odeme.php              # Ödeme
├── config.php             # Konfigürasyon (✏️ Düzenleyin!)
├── database.sql           # Database yapısı
├── seed_data.sql          # Örnek veriler
├── .htaccess              # URL rewriting
├── composer.json          # PHP bağımlılıkları
└── README.md              # Bu dosya
```

---

## 🗄️ Database Yapısı

Proje **25 tablo** içermektedir:

1. **kategoriler** - Ürün kategorileri (hiyerarşik)
2. **urunler** - Ürün bilgileri
3. **urun_varyantlari** - Beden, renk, stok
4. **urun_resimleri** - Ürün görselleri
5. **kullanicilar** - Müşteri ve admin kullanıcılar
6. **adresler** - Teslimat adresleri
7. **siparisler** - Sipariş bilgileri
8. **siparis_detaylari** - Sipariş kalemleri
9. **sepet** - Alışveriş sepeti
10. **kuponlar** - İndirim kuponları
11. **kupon_kullanim** - Kupon kullanım geçmişi
12. **yorumlar** - Ürün yorumları
13. **favoriler** - Favori ürünler
14. **sliderlar** - Ana sayfa slider
15. **sayfalar** - Dinamik sayfalar (Hakkımızda, KVKK vb.)
16. **ayarlar** - Site ayarları
17. **bulten** - Newsletter aboneleri
18. **kargo_firmalari** - Kargo firmaları
19. **iadeler** - İade talepleri
20. **stok_bildirimleri** - Stok gelince bildir
21. **banka_loglari** - Ödeme transaction logları
22. **admin_loglari** - Admin aksiyonları
23. **son_gezilen** - Son gezilen ürünler
24. **sss** - Sıkça sorulan sorular
25. **iletisim_mesajlari** - İletişim formu mesajları

---

## 🎨 Tasarım Özellikleri

### Renk Paleti (Bebek Teması)

- **Primary:** `#ff9ec5` (Açık pembe)
- **Secondary:** `#a8d8ff` (Açık mavi)
- **Light BG:** `#fef9fb` (Çok açık pembe)

### Responsive Breakpoints

- Mobile: < 768px
- Tablet: 768px - 992px
- Desktop: > 992px

---

## 🔒 Güvenlik

Projede uygulanan güvenlik önlemleri:

- ✅ PDO Prepared Statements (SQL Injection koruması)
- ✅ `htmlspecialchars()` ile XSS koruması
- ✅ CSRF Token sistemi
- ✅ Password hashing (Argon2id)
- ✅ Session güvenliği
- ✅ File upload validasyonu
- ✅ Input sanitization
- ✅ PHP 8 type declarations

---

## 📦 Composer Paketleri

```json
{
  "require": {
    "phpmailer/phpmailer": "^6.8",
    "phpoffice/phpspreadsheet": "^1.29",
    "tecnickcom/tcpdf": "^6.6"
  }
}
```

- **PHPMailer:** Mail gönderimi (sipariş onayı, şifre sıfırlama)
- **PhpSpreadsheet:** Excel export (raporlar)
- **TCPDF:** PDF oluşturma (fatura)

---

## 🛠️ Geliştirme Notları

### Yeni Ürün Ekleme

1. Admin paneline giriş yapın
2. **Ürünler > Yeni Ürün Ekle**
3. Ürün bilgilerini doldurun
4. Varyant ekleyin (beden, renk, stok)
5. Görselleri yükleyin
6. Kaydedin

### Sipariş Yönetimi

1. **Siparişler** menüsünden tüm siparişleri görüntüleyin
2. Sipariş durumunu güncelleyin:
   - Beklemede → Onaylandı → Hazırlanıyor → Kargoda → Teslim Edildi
3. Kargo takip numarası ekleyin
4. Müşteriye otomatik mail gönderilir

### Kategori Yönetimi

Kategoriler hiyerarşik yapıdadır (sınırsız seviye):

```
Bebek Kıyafetleri
├── 0-3 Ay
├── 3-6 Ay
│   ├── Tulumlar
│   ├── Bodyler
│   └── Takımlar
└── 6-12 Ay
```

---

## 🚀 Canlıya Alma Checklist

Projeyi canlı sunucuya taşırken:

- [ ] SSL sertifikası kurun (HTTPS)
- [ ] `config.php` dosyasındaki `DB_*` bilgilerini güncelleyin
- [ ] `SITE_URL` değerini gerçek domain ile değiştirin
- [ ] SMTP ayarlarını gerçek mail sunucusu ile yapın
- [ ] `error_reporting` kapatın (production için)
- [ ] Admin şifresini değiştirin
- [ ] `.env` dosyası oluşturup hassas bilgileri taşıyın
- [ ] Ziraat POS ayarlarını test modundan canlıya çevirin
- [ ] `robots.txt` ve `sitemap.xml` güncelleyin
- [ ] Google Analytics ekleyin (opsiyonel)
- [ ] Backup sistemi kurun

---

## 📞 Destek ve İletişim

**Geliştirici:**
Onysoft Veri Merkezi A.Ş.
**Web:** [www.onysoft.com.tr](https://www.onysoft.com.tr)
**Email:** info@onysoft.com.tr
**Telefon:** İzmir, Türkiye

**Müşteri:**
WawaHouse - Bebek Giyim ve Aksesuar
**Demo:** https://staravcisi.com/

---

## 📝 Lisans

Bu proje WawaHouse müşterisi için özel olarak geliştirilmiştir.
**© 2024 Onysoft Veri Merkezi A.Ş. - Tüm hakları saklıdır.**

---

## 🎯 Gelecek Geliştirmeler

- [ ] Çoklu dil desteği (TR/EN)
- [ ] Canlı destek (Tawk.to entegrasyonu)
- [ ] Ürün karşılaştırma
- [ ] Wishlist özelliği
- [ ] Sosyal medya login (Google, Facebook)
- [ ] SMS bildirimleri
- [ ] Mobil uygulama (React Native)
- [ ] Kargo API entegrasyonları (Aras, MNG, Yurtiçi)
- [ ] Ödeme alternatifi (PayTR, iyzico)

---

**Teşekkürler! 🍼👶**
*Onysoft Veri Merkezi A.Ş. ile güvenle geliştirin.*
