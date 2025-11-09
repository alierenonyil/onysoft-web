# 📥 WawaHouse - Basit Kurulum Rehberi

## ⚠️ ÖNEMLİ NOTLAR

- Terminal/SSH erişimi gerekmez
- cPanel veya hosting panel üzerinden kurulum yapılabilir
- phpMyAdmin ile database yönetimi yapılacak
- FTP/FileManager ile dosya yükleme yapılacak

---

## 🚀 ADIM ADIM KURULUM

### ADIM 1: Dosyaları Yükleyin

1. **WinSCP**, **FileZilla** veya **cPanel File Manager** kullanarak:
2. `wawahouse` klasörünün **İÇİNDEKİ** tüm dosyaları yükleyin
3. Yükleme konumu: `public_html` veya `httpdocs` klasörü

**DOĞRU:**
```
public_html/
├── admin/
├── assets/
├── includes/
├── config.php
├── index.php
└── ...
```

**YANLIŞ:**
```
public_html/
└── wawahouse/
    ├── admin/
    ├── assets/
    └── ...
```

---

### ADIM 2: Database Oluşturun

#### A) cPanel > MySQL Databases

1. **MySQL Databases** menüsüne tıklayın
2. **"New Database"** alanına: `wawahousesql` yazın
3. **"Create Database"** butonuna tıklayın

#### B) Database Kullanıcısı Oluşturun

1. **"MySQL Users"** bölümünde **"Add New User"**
2. Kullanıcı adı: `wawahousekullanici` (veya istediğiniz bir ad)
3. Güçlü bir şifre oluşturun (örn: `xR9#mK2pL5qN`)
4. **"Create User"** tıklayın

#### C) Kullanıcıyı Database'e Ekleyin

1. **"Add User to Database"** bölümünde
2. Kullanıcı: `wawahousekullanici` seçin
3. Database: `wawahousesql` seçin
4. **"Add"** butonuna tıklayın
5. Çıkan ekranda **"ALL PRIVILEGES"** seçin
6. **"Make Changes"** tıklayın

---

### ADIM 3: SQL Dosyalarını Import Edin

#### phpMyAdmin ile Import

1. **cPanel > phpMyAdmin** açın
2. Sol taraftan **`wawahousesql`** database'ini seçin
3. Üst menüden **"Import"** (İçe Aktar) sekmesine tıklayın

#### İlk Import: database.sql

1. **"Choose File"** butonuna tıklayın
2. Bilgisayarınızdan **`database.sql`** dosyasını seçin
3. Sayfayı aşağı kaydırın
4. **"Go"** (Git) butonuna tıklayın
5. ✅ **"Import has been successfully finished"** mesajını bekleyin

#### İkinci Import: seed_data.sql

1. Tekrar **"Import"** sekmesine gidin
2. **"Choose File"** ile **`seed_data.sql`** dosyasını seçin
3. **"Go"** butonuna tıklayın
4. ✅ Başarılı mesajını bekleyin

**KONTROL:** Sol menüde 25 tablo görmelisiniz:
- adresler
- admin_loglari
- ayarlar
- banka_loglari
- bulten
- favoriler
- iadeler
- ... (toplam 25 adet)

---

### ADIM 4: Config.php Dosyasını Düzenleyin

#### FTP ile Düzenleme

1. FTP programınızla **`config.php`** dosyasını bilgisayarınıza indirin
2. **Notepad++**, **Sublime Text** veya **VS Code** ile açın
3. Aşağıdaki satırları bulun ve değiştirin:

```php
// Database Ayarları (ZORUNLU!)
define('DB_HOST', 'localhost');              // Genelde localhost
define('DB_NAME', 'wawahousesql');           // Database adınız
define('DB_USER', 'wawahousekullanici');     // ADIM 2'de oluşturduğunuz kullanıcı
define('DB_PASS', '14531453aO.!');           // ADIM 2'de oluşturduğunuz şifre

// Site Ayarları (ZORUNLU!)
define('SITE_URL', 'https://yourdomain.com/');  // Sitenizin URL'i (sonunda / olmalı)
define('SITE_EMAIL', 'info@yourdomain.com');    // Sitenizin email adresi
```

#### Örnek (cPanel için):

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'username_wawahousesql');      // cPanel kullanıcı adı + database adı
define('DB_USER', 'username_wawahousekullanici'); // cPanel kullanıcı adı + kullanıcı adı
define('DB_PASS', 'xR9#mK2pL5qN');               // Oluşturduğunuz güçlü şifre
define('SITE_URL', 'https://www.siteniz.com/');  // Kendi domain'iniz
```

4. Dosyayı **kaydedin**
5. FTP ile sunucuya **geri yükleyin** (üzerine yazın)

---

### ADIM 5: Dosya İzinlerini Kontrol Edin

#### cPanel File Manager ile:

1. `assets/uploads` klasörüne sağ tıklayın
2. **"Change Permissions"** (İzinleri Değiştir) seçin
3. **755** veya **775** yapın
4. **"Recurse into subdirectories"** işaretleyin (Alt klasörlere uygula)
5. **"Change Permissions"** butonuna tıklayın

---

### ADIM 6: Testi Yapın

#### Frontend Testi

1. Tarayıcınızda sitenizi açın: `https://www.siteniz.com/`
2. Ana sayfayı görmelisiniz
3. Ürünler, kategoriler görünüyor mu kontrol edin

#### Admin Paneli Testi

1. Admin girişine gidin: `https://www.siteniz.com/admin/login.php`
2. Giriş yapın:
   - **Email:** admin@wawahouse.com
   - **Şifre:** admin123
3. ✅ Dashboard'u görmelisiniz

---

## ✅ KURULUM TAMAMLANDI!

Tebrikler! WawaHouse e-ticaret siteniz hazır.

---

## ⚙️ SONRAKİ AYARLAR

### 1. Admin Şifresini Değiştirin

⚠️ **ÇOK ÖNEMLİ!** İlk giriş yaptıktan sonra:

1. Admin panel > Ayarlar
2. Admin kullanıcısını düzenleyin
3. Güçlü bir şifre belirleyin

### 2. Site Bilgilerini Güncelleyin

Admin Panel > Ayarlar:
- Site adı
- Logo
- İletişim bilgileri
- Sosyal medya linkleri

### 3. SMTP Mail Ayarları (Opsiyonel)

`config.php` dosyasında:

```php
define('SMTP_HOST', 'mail.yourdomain.com');
define('SMTP_USER', 'noreply@yourdomain.com');
define('SMTP_PASS', 'mail_sifresi');
define('SMTP_PORT', 587);
```

### 4. Kendi Ürünlerinizi Ekleyin

1. Admin Panel > Ürünler > Yeni Ürün Ekle
2. Ürün bilgilerini doldurun
3. Varyant (beden, renk) ekleyin
4. Resim yükleyin
5. Kaydedin

---

## ❓ SORUN GİDERME

### "Database connection failed" Hatası

✅ **Çözüm:**
- `config.php` dosyasındaki database bilgilerini kontrol edin
- Database adı, kullanıcı adı, şifre doğru mu?
- cPanel'de kullanıcı database'e eklenmiş mi?

### "500 Internal Server Error"

✅ **Çözüm:**
- `.htaccess` dosyasını geçici olarak silin/yeniden adlandırın
- PHP sürümü 8.0 veya üzeri mi kontrol edin (cPanel > Select PHP Version)
- Error log'larını kontrol edin (cPanel > Error Log)

### Admin Paneline Giriş Yapamıyorum

✅ **Çözüm:**
- `seed_data.sql` dosyası import edildi mi kontrol edin
- phpMyAdmin'den `kullanicilar` tablosuna bakın
- Email: admin@wawahouse.com var mı?

### Ürün Resimleri Yüklenmiyor

✅ **Çözüm:**
- `assets/uploads` klasörü izinleri 755 veya 775 olmalı
- cPanel > PHP Settings > `upload_max_filesize` en az 10M olmalı

### Sayfalar Açılmıyor (404 Hatası)

✅ **Çözüm:**
- `.htaccess` dosyası var mı kontrol edin
- Apache `mod_rewrite` modülü aktif mi? (cPanel'den kontrol edin)

---

## 📞 DESTEK

**Geliştirici:**
Onysoft Veri Merkezi A.Ş.
🌐 www.onysoft.com.tr
📧 info@onysoft.com.tr

**Demo Site:**
🌐 https://staravcisi.com/

---

## 🎯 HIZLI KONTROL LİSTESİ

- [ ] Dosyalar public_html'e yüklendi
- [ ] Database oluşturuldu (wawahousesql)
- [ ] Database kullanıcısı oluşturuldu
- [ ] Kullanıcı database'e eklendi (ALL PRIVILEGES)
- [ ] database.sql import edildi (25 tablo)
- [ ] seed_data.sql import edildi (örnek veriler)
- [ ] config.php düzenlendi (DB bilgileri)
- [ ] config.php düzenlendi (SITE_URL)
- [ ] uploads klasörü izinleri 755/775
- [ ] Frontend testi yapıldı (ana sayfa açıldı)
- [ ] Admin paneli testi yapıldı (giriş başarılı)
- [ ] Admin şifresi değiştirildi
- [ ] Site bilgileri güncellendi

---

**Başarılar! 🎉**
