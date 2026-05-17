# Onysoft Web Projeler Repository

Onysoft Veri Merkezi A.Ş. tarafından geliştirilen müşteriye özel web yazılım projelerinin bir araya getirildiği monorepo.

## 📦 Repodaki Projeler

### 🍼 [WawaHouse — Bebek Giyim E-Ticaret Sistemi](./wawahouse/)

Bebekler için %100 organik pamuklu giyim satışı yapan modern bir e-ticaret platformu. **PHP 8** ile geliştirildi.

**Öne çıkan özellikler:**

- 👶 Bebek temalı modern ve responsive tasarım (Bootstrap 5)
- 🛒 Tam fonksiyonlu sepet ve sipariş yönetimi sistemi
- 🎨 Ürün varyantları (beden, renk, stok takibi)
- 🎟️ Kupon sistemi (yüzde, sabit tutar, bedava kargo)
- 📊 Admin paneli + Chart.js ile istatistik grafikleri
- 🔐 CSRF, XSS, SQL injection korumalı (PDO prepared statements)
- 🌐 SEO dostu URL yapısı

**Teknolojiler:** PHP 8.0+, MySQL, Bootstrap 5, Chart.js, Composer

**Detaylı dokümantasyon:** [wawahouse/README.md](./wawahouse/README.md)
**Kurulum rehberi:** [wawahouse/KURULUM-REHBERI.md](./wawahouse/KURULUM-REHBERI.md)

---

## 🛠️ Genel Teknoloji Yığını

Bu repodaki projeler ortak olarak şu teknolojileri kullanır:

| Katman | Teknoloji |
|---|---|
| Backend | PHP 8.0+ (modern syntax: type hints, match, named arguments) |
| Veritabanı | MySQL 5.7+ / MariaDB 10.3+ |
| Frontend | Bootstrap 5, Font Awesome, vanilla JavaScript |
| Bağımlılık yönetimi | Composer |
| Web sunucu | Apache (.htaccess ile mod_rewrite) |

## 🚀 Kurulum (Genel)

Her proje kendi `README.md` ve `KURULUM-REHBERI.md` dosyasını içerir. Genel adımlar:

```bash
# 1. Repoyu klonla
git clone https://github.com/alierenonyil/onysoft-web.git
cd onysoft-web

# 2. İlgili proje klasörüne gir
cd wawahouse

# 3. Composer bağımlılıklarını yükle
composer install

# 4. .env dosyasını oluştur
cp .env.example .env
# Veritabanı ve uygulama ayarlarını .env içine yaz

# 5. Veritabanını oluştur ve database.sql dosyasını import et
mysql -u root -p < database.sql

# 6. Tarayıcıdan localhost/wawahouse adresine git
```

## 📁 Repo Yapısı

```
onysoft-web/
├── wawahouse/                  # WawaHouse Bebek E-Ticaret Sistemi
│   ├── admin/                  # Admin paneli
│   ├── assets/                 # CSS, JS, görseller
│   ├── ajax/                   # AJAX endpoint'leri
│   ├── includes/               # Ortak include dosyaları
│   ├── config.php              # Uygulama yapılandırması
│   ├── database.sql            # Veritabanı şeması
│   ├── composer.json           # PHP bağımlılıkları
│   ├── README.md               # WawaHouse dokümantasyonu
│   └── KURULUM-REHBERI.md      # Detaylı kurulum
└── README.md                    # Bu dosya
```

## 🎯 Gelecek Planlar

- [ ] CI/CD pipeline (GitHub Actions ile otomatik test/deploy)
- [ ] Docker desteği (`docker-compose.yml`)
- [ ] Multi-language (TR/EN) altyapısı

## 👥 Katkıda Bulunanlar (Contributors)

- **Ali Eren Onyıl** — [@alierenonyil](https://github.com/alierenonyil) — Tüm projelerin tasarım, geliştirme ve mimarisi

## 🌐 Geliştirici

**Ali Eren Onyıl**
Onysoft Veri Merkezi A.Ş. — Kurucu & Full-Stack Developer

- 🌍 Portfolyo: [ali.onysoft.com](https://ali.onysoft.com)
- 💼 LinkedIn: [linkedin.com/in/alierenonyil](https://linkedin.com/in/alierenonyil)
- 🐙 GitHub: [github.com/alierenonyil](https://github.com/alierenonyil)
- 📧 E-posta: alieren@onysoft.com

## 📄 Lisans

Bu repodaki kod, müşteri projeleri için geliştirilmiştir. Eğitim ve portfolyo amaçlı paylaşılmıştır. Ticari kullanım için iletişime geçiniz.
