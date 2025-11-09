-- ============================================
-- WawaHouse E-Ticaret Sistemi
-- Örnek Veriler (Seed Data)
--
-- @package WawaHouse
-- @author Onysoft Veri Merkezi A.Ş.
-- @website www.onysoft.com.tr
-- ============================================

USE `wawahousesql`;

-- ============================================
-- Admin Kullanıcı
-- ============================================
-- Email: admin@wawahouse.com
-- Şifre: admin123
INSERT INTO `kullanicilar` (`ad_soyad`, `email`, `sifre`, `rol`, `aktif`, `email_dogrulandi`) VALUES
('Admin User', 'admin@wawahouse.com', '$2y$12$W6V0l5a4hNRC8iD65lRgkuQ/Hp.AgvdRuGHHEm99zHYzStT2Qfqtq', 'admin', 1, 1);

-- ============================================
-- Kategoriler (Bebek Kategorileri)
-- ============================================
INSERT INTO `kategoriler` (`id`, `parent_id`, `ad`, `slug`, `sira`, `aktif`) VALUES
(1, NULL, 'Bebek Kıyafetleri', 'bebek-kiyafetleri', 1, 1),
(2, NULL, 'Yaş Grupları', 'yas-gruplari', 2, 1),
(3, NULL, 'Ürün Tipleri', 'urun-tipleri', 3, 1),
(4, NULL, 'Mevsimlik', 'mevsimlik', 4, 1),
(5, 2, '0-3 Ay', '0-3-ay', 1, 1),
(6, 2, '3-6 Ay', '3-6-ay', 2, 1),
(7, 2, '6-12 Ay', '6-12-ay', 3, 1),
(8, 2, '1-2 Yaş', '1-2-yas', 4, 1),
(9, 3, 'Tulumlar', 'tulumlar', 1, 1),
(10, 3, 'Bodyler', 'bodyler', 2, 1),
(11, 3, 'Takımlar', 'takimlar', 3, 1),
(12, 3, 'Patikler', 'patikler', 4, 1),
(13, 4, 'Yaz Koleksiyonu', 'yaz-koleksiyonu', 1, 1),
(14, 4, 'Kış Koleksiyonu', 'kis-koleksiyonu', 2, 1);

-- ============================================
-- Örnek Ürünler
-- ============================================
INSERT INTO `urunler` (`kategori_id`, `ad`, `slug`, `aciklama`, `fiyat`, `indirimli_fiyat`, `sku`, `cinsiyet`, `aktif`, `one_cikan`) VALUES
(5, 'Organik Pamuklu Bebek Tulumu', 'organik-pamuklu-bebek-tulumu', '%100 organik pamuktan üretilmiş, bebeğinizin hassas cildi için ideal tulum.', 149.90, 119.90, 'WH-TLM-001', 'unisex', 1, 1),
(5, 'Yenidoğan Body Seti (3 Adet)', 'yenidogan-body-seti', 'Yumuşak pamuklu kumaştan, yenidoğan bebekler için özel tasarlanmış body seti.', 89.90, NULL, 'WH-BDY-001', 'unisex', 1, 1),
(6, 'Pamuklu Bebek Takımı', 'pamuklu-bebek-takimi', 'Üst ve alt takım, sevimli baskılı tasarım.', 129.90, 99.90, 'WH-TKM-001', 'kiz', 1, 0),
(7, 'Kışlık Bebek Tulumu', 'kislik-bebek-tulumu', 'Soğuk havalarda bebeğinizi sıcak tutacak, kaliteli kumaş.', 199.90, NULL, 'WH-TLM-002', 'erkek', 1, 1),
(8, 'Renkli Patik Seti', 'renkli-patik-seti', 'El örgüsü görünümlü, sıcak tutan bebek patikleri (2 adet).', 39.90, 29.90, 'WH-PTK-001', 'unisex', 1, 0),
(10, 'Çıtçıtlı Body (5li Paket)', 'citcitli-body-5li', 'Pratik çıtçıtlı body, kolay giyim sağlar.', 149.90, 129.90, 'WH-BDY-002', 'unisex', 1, 1),
(11, 'Hastane Çıkışı Seti', 'hastane-cikisi-seti', 'Yenidoğan bebek için özel tasarlanmış, şık hastane çıkış seti.', 299.90, 249.90, 'WH-HST-001', 'kiz', 1, 1),
(13, 'Yazlık İnce Tulum', 'yazlik-ince-tulum', 'Serin ve rahat, yaz ayları için ideal ince tulum.', 79.90, NULL, 'WH-YAZ-001', 'unisex', 1, 0);

-- ============================================
-- Ürün Varyantları
-- ============================================
INSERT INTO `urun_varyantlari` (`urun_id`, `beden`, `renk`, `renk_kodu`, `stok`, `ek_fiyat`) VALUES
-- Organik Pamuklu Bebek Tulumu (1)
(1, '0-3 Ay', 'Beyaz', '#FFFFFF', 15, 0.00),
(1, '0-3 Ay', 'Pembe', '#FFB6C1', 12, 0.00),
(1, '0-3 Ay', 'Mavi', '#ADD8E6', 10, 0.00),
(1, '3-6 Ay', 'Beyaz', '#FFFFFF', 8, 0.00),
(1, '3-6 Ay', 'Pembe', '#FFB6C1', 5, 0.00),
-- Yenidoğan Body Seti (2)
(2, '0-3 Ay', 'Karışık', '#FFFFFF', 20, 0.00),
(2, '3-6 Ay', 'Karışık', '#FFFFFF', 15, 0.00),
-- Pamuklu Bebek Takımı (3)
(3, '3-6 Ay', 'Pembe', '#FFB6C1', 10, 0.00),
(3, '6-12 Ay', 'Pembe', '#FFB6C1', 8, 0.00),
-- Kışlık Bebek Tulumu (4)
(4, '6-12 Ay', 'Lacivert', '#000080', 12, 0.00),
(4, '6-12 Ay', 'Gri', '#808080', 10, 0.00),
-- Renkli Patik Seti (5)
(5, 'Tek Beden', 'Karışık', '#FFFFFF', 30, 0.00),
-- Çıtçıtlı Body (6)
(6, '0-3 Ay', 'Beyaz', '#FFFFFF', 25, 0.00),
(6, '3-6 Ay', 'Beyaz', '#FFFFFF', 20, 0.00),
-- Hastane Çıkışı Seti (7)
(7, '0-3 Ay', 'Pembe', '#FFB6C1', 6, 0.00),
(7, '0-3 Ay', 'Mavi', '#ADD8E6', 4, 0.00),
-- Yazlık İnce Tulum (8)
(8, '3-6 Ay', 'Sarı', '#FFFF00', 15, 0.00),
(8, '6-12 Ay', 'Sarı', '#FFFF00', 12, 0.00);

-- ============================================
-- Site Ayarları
-- ============================================
INSERT INTO `ayarlar` (`anahtar`, `deger`, `grup`) VALUES
('site_adi', 'WawaHouse - Bebek Giyim ve Aksesuar', 'genel'),
('site_aciklama', 'Bebeğiniz için en kaliteli ve uygun fiyatlı bebek kıyafetleri', 'genel'),
('site_keywords', 'bebek giyim, bebek kıyafetleri, yenidoğan, bebek tulumu, bebek body', 'genel'),
('iletisim_telefon', '+90 232 XXX XX XX', 'iletisim'),
('iletisim_email', 'info@wawahouse.com', 'iletisim'),
('iletisim_adres', 'İzmir, Türkiye', 'iletisim'),
('sosyal_facebook', 'https://facebook.com/wawahouse', 'sosyal'),
('sosyal_instagram', 'https://instagram.com/wawahouse', 'sosyal'),
('sosyal_twitter', 'https://twitter.com/wawahouse', 'sosyal'),
('bedava_kargo_limiti', '500', 'kargo'),
('varsayilan_kargo_ucreti', '29.90', 'kargo');

-- ============================================
-- Sliderlar
-- ============================================
INSERT INTO `sliderlar` (`resim`, `baslik`, `aciklama`, `buton_text`, `buton_link`, `sira`, `aktif`) VALUES
('slider1.jpg', 'Yeni Sezon Koleksiyonu', 'Bebeğiniz için en şirin kıyafetler WawaHouse\'da!', 'Alışverişe Başla', '/urunler', 1, 1),
('slider2.jpg', 'Ücretsiz Kargo', '500 TL ve üzeri tüm siparişlerde', 'Ürünleri İncele', '/urunler', 2, 1),
('slider3.jpg', 'Organik Pamuklu Ürünler', 'Bebeğinizin hassas cildi için özel', 'Keşfet', '/kategori/organik', 3, 1);

-- ============================================
-- Sayfalar
-- ============================================
INSERT INTO `sayfalar` (`baslik`, `slug`, `icerik`, `aktif`, `menude_goster`) VALUES
('Hakkımızda', 'hakkimizda', '<h2>WawaHouse Hakkında</h2><p>WawaHouse, bebeğiniz için en kaliteli ve uygun fiyatlı bebek kıyafetlerini sunmaktadır. %100 organik ve doğal kumaşlardan üretilen ürünlerimiz, bebeğinizin hassas cildi için özel olarak tasarlanmıştır.</p>', 1, 1),
('İletişim', 'iletisim', '<h2>İletişim Bilgileri</h2><p><strong>Adres:</strong> İzmir, Türkiye</p><p><strong>Telefon:</strong> +90 232 XXX XX XX</p><p><strong>Email:</strong> info@wawahouse.com</p>', 1, 1),
('Mesafeli Satış Sözleşmesi', 'mesafeli-satis-sozlesmesi', '<h2>Mesafeli Satış Sözleşmesi</h2><p>Mesafeli satış sözleşmesi içeriği burada yer alacaktır...</p>', 1, 0),
('KVKK', 'kvkk', '<h2>Kişisel Verilerin Korunması</h2><p>KVKK metni burada yer alacaktır...</p>', 1, 0),
('İade ve Değişim', 'iade-ve-degisim', '<h2>İade ve Değişim Şartları</h2><p>14 gün içinde iade hakkınız bulunmaktadır...</p>', 1, 1),
('Kargo ve Teslimat', 'kargo-ve-teslimat', '<h2>Kargo ve Teslimat</h2><p>500 TL üzeri siparişlerde kargo ücretsizdir. Teslimat süresi 2-5 iş günüdür.</p>', 1, 1);

-- ============================================
-- SSS
-- ============================================
INSERT INTO `sss` (`soru`, `cevap`, `kategori`, `sira`, `aktif`) VALUES
('Kargo ücreti ne kadar?', '500 TL altı siparişlerde kargo ücreti 29,90 TL, 500 TL ve üzeri siparişlerde ücretsizdir.', 'kargo', 1, 1),
('Teslimat süresi ne kadar?', 'Siparişiniz 2-5 iş günü içinde adresinize teslim edilir.', 'kargo', 2, 1),
('İade şartları nelerdir?', 'Ürünü teslim aldığınız tarihten itibaren 14 gün içinde iade edebilirsiniz.', 'iade', 3, 1),
('Ürünler organik mi?', 'Evet, tüm ürünlerimiz %100 organik pamuktan üretilmiştir.', 'urun', 4, 1);

-- ============================================
-- Kargo Firmaları
-- ============================================
INSERT INTO `kargo_firmalari` (`ad`, `web_url`, `aktif`) VALUES
('Aras Kargo', 'https://www.araskargo.com.tr', 1),
('MNG Kargo', 'https://www.mngkargo.com.tr', 1),
('Yurtiçi Kargo', 'https://www.yurticikargo.com', 1),
('PTT Kargo', 'https://www.ptt.gov.tr', 1);

-- ============================================
-- Demo Kupon
-- ============================================
INSERT INTO `kuponlar` (`kod`, `indirim_tipi`, `indirim_degeri`, `min_tutar`, `kullanim_limiti`, `gecerlilik_baslangic`, `gecerlilik_bitis`, `aktif`) VALUES
('ILKALIS10', 'yuzde', 10.00, 100.00, 100, '2024-01-01', '2024-12-31', 1),
('BEDAVAKARGO', 'bedava_kargo', 0.00, 200.00, NULL, '2024-01-01', '2024-12-31', 1);

COMMIT;
