-- ================================================
-- WawaHouse E-Ticaret Sistemi
-- Hazır İçerik Sayfaları
--
-- Bu dosya Hakkımızda, KVKK, İade Şartları vb.
-- içerik sayfalarını içerir
-- ================================================

-- Mevcut sayfa verilerini temizle (opsiyonel)
TRUNCATE TABLE `sayfalar`;

-- Hakkımızda Sayfası
INSERT INTO `sayfalar` (`baslik`, `slug`, `icerik`, `sira`, `aktif`, `menude_goster`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`) VALUES
('Hakkımızda', 'hakkimizda', '<h2>WawaHouse - Bebek Giyim ve Aksesuar</h2>
<p>WawaHouse olarak 2024 yılından beri bebekleriniz için en kaliteli ve şık ürünleri sunmaktayız. Bebeklerinizin konforunu ve stilini ön planda tutarak, organik ve hypoallerjenik kumaşlardan üretilen ürünlerimizle ailelerin güvenini kazanmış bulunuyoruz.</p>

<h3>Vizyonumuz</h3>
<p>Türkiye\'nin en güvenilir ve tercih edilen bebek giyim markası olmak, bebeklerinizin ilk anlarına şık ve konforlu ürünlerle dokunmak.</p>

<h3>Misyonumuz</h3>
<p>Yüksek kalite standartlarını koruyarak, uygun fiyatlarla bebek giyim sektöründe fark yaratmak ve müşteri memnuniyetini en üst düzeyde tutmak.</p>

<h3>Değerlerimiz</h3>
<ul>
<li><strong>Kalite:</strong> Tüm ürünlerimiz uluslararası kalite standartlarına uygun olarak üretilmektedir.</li>
<li><strong>Güven:</strong> Müşterilerimizin güvenini kazanmak ve korumak önceliğimizdir.</li>
<li><strong>Konfor:</strong> Bebeklerin hassas ciltleri için en yumuşak ve nefes alabilen kumaşları kullanıyoruz.</li>
<li><strong>Çevre Dostu:</strong> Doğaya saygılı, sürdürülebilir üretim anlayışıyla çalışıyoruz.</li>
</ul>

<h3>Neden WawaHouse?</h3>
<ul>
<li>%100 Organik pamuk ürünler</li>
<li>Hypoallerjenik ve dermatol ojik olarak test edilmiş</li>
<li>Avrupa standartlarında üretim</li>
<li>Hızlı ve güvenli kargo</li>
<li>Kolay iade ve değişim</li>
<li>7/24 müşteri hizmetleri</li>
</ul>

<p>Bebekleriniz için en iyisini seçin, WawaHouse\'u seçin!</p>', 1, 1, 1, 'WawaHouse Hakkında | Bebek Giyim', 'WawaHouse bebek giyim ve aksesuar hakkında bilgi edinin. Vizyonumuz, misyonumuz ve değerlerimiz.', 'wawahouse hakkında, bebek giyim, organik bebek kıyafeti', NOW()),

-- KVKK Sayfası
('KVKK - Kişisel Verilerin Korunması', 'kvkk', '<h2>Kişisel Verilerin Korunması ve İşlenmesi Politikası</h2>
<p><strong>Son Güncelleme:</strong> ' . date('d.m.Y') . '</p>

<h3>1. Giriş</h3>
<p>WawaHouse olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında kişisel verilerinizin güvenliğine önem veriyoruz. Bu metin, kişisel verilerinizin nasıl toplandığını, işlendiğini ve korunduğunu açıklamaktadır.</p>

<h3>2. Veri Sorumlusu</h3>
<p>Veri Sorumlusu: WawaHouse<br>
Adres: [Şirket Adresi]<br>
E-posta: info@wawahouse.com<br>
Telefon: [Telefon Numarası]</p>

<h3>3. Toplanan Kişisel Veriler</h3>
<p>Platformumuz üzerinden aşağıdaki kişisel veriler toplanmaktadır:</p>
<ul>
<li>Kimlik Bilgileri: Ad, soyad</li>
<li>İletişim Bilgileri: E-posta adresi, telefon numarası, adres</li>
<li>Müşteri İşlem Bilgileri: Sipariş geçmişi, ödeme bilgileri, sepet içeriği</li>
<li>İşlem Güvenliği Bilgileri: IP adresi, çerez kayıtları</li>
</ul>

<h3>4. Kişisel Verilerin İşlenme Amaçları</h3>
<p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
<ul>
<li>Üyelik işlemlerinin gerçekleştirilmesi</li>
<li>Sipariş ve teslimat süreçlerinin yürütülmesi</li>
<li>Müşteri hizmetleri ve destek sağlanması</li>
<li>Pazarlama ve kampanya faaliyetlerinin yürütülmesi</li>
<li>Yasal yükümlülüklerin yerine getirilmesi</li>
<li>Güvenlik ve dolandırıcılık önleme</li>
</ul>

<h3>5. Kişisel Verilerin Aktarımı</h3>
<p>Kişisel verileriniz, yasal zorunluluklar ve hizmet gereklilikler i çerçevesinde:</p>
<ul>
<li>Kargo firmalarına (teslimat için)</li>
<li>Ödeme hizmet sağlayıcılarına</li>
<li>Yasal mercilere (talep halinde)</li>
<li>Hizmet sağlayıcı firmalar a aktarılabilir.</li>
</ul>

<h3>6. Kişisel Veri Sahibinin Hakları</h3>
<p>KVKK\'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
<ul>
<li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
<li>İşlenmişse bilgi talep etme</li>
<li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme</li>
<li>Yurt içinde/dışında aktarıldığı üçüncü kişileri bilme</li>
<li>Eksik/yanlış işlenmişse düzeltilmesini isteme</li>
<li>Silinmesini/yok edilmesini isteme</li>
<li>Aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
<li>Otomatik sistemlerle analiz edilmesi sebebiyle aleyhinize sonuç çıkmasına itiraz etme</li>
<li>Kanuna aykırı işlenmesi sebebiyle zarara uğramanız halinde tazminat talep etme</li>
</ul>

<h3>7. İletişim</h3>
<p>KVKK kapsamındaki taleplerinizi info@wawahouse.com adresine yazılı olarak iletebilirsiniz.</p>', 2, 1, 1, 'KVKK - Kişisel Verilerin Korunması', 'WawaHouse KVKK ve kişisel verilerin korunması politikası', 'kvkk, kişisel veriler, gizlilik', NOW()),

-- İade ve Değişim Şartları
('İade ve Değişim Şartları', 'iade-sartlari', '<h2>İade ve Değişim Şartları</h2>
<p><strong>Son Güncelleme:</strong> ' . date('d.m.Y') . '</p>

<h3>1. İade Hakkı</h3>
<p>6502 sayılı Tüketicinin Korunması Hakkında Kanun uyarınca, ürünün size teslim edildiği tarihten itibaren <strong>14 gün</strong> içinde herhangi bir gerekçe göstermeksizin ve cezai şart ödemeksizin ürünü iade edebilirsiniz.</p>

<h3>2. İade Koşulları</h3>
<p>İade edilecek ürünlerde aşağıdaki koşulların sağlanması gerekmektedir:</p>
<ul>
<li>Ürün kullanılmamış, yıkanmamış ve etiketleri sökülmemiş olmalıdır</li>
<li>Orijinal ambalajı ile birlikte iade edilmelidir</li>
<li>Fatura/İrsaliye bilgileri eksiksiz olmalıdır</li>
<li>Hijyen ve sağlık açısından uygun olmalıdır</li>
</ul>

<h3>3. İade Edilemeyen Ürünler</h3>
<p>Aşağıdaki durumlar da ürünler iade edilemez:</p>
<ul>
<li>İç çamaşırı ve mayo kategorisindeki ürünler (hijyen nedeniyle)</li>
<li>Yıkanmış veya kullanılmış ürünler</li>
<li>Etiketi koparılmış ürünler</li>
<li>İndirimli/outlet ürünler (kampanyalı olarak belirtilmişse)</li>
<li>Kişiye özel hazırlanmış ürünler</li>
</ul>

<h3>4. İade Süreci</h3>
<ol>
<li><strong>İade Talebi:</strong> Hesabım > Siparişlerim bölümünden veya info@wawahouse.com adresine e-posta göndererek iade talebinde bulunun</li>
<li><strong>Onay:</strong> İade talebiniz 1 iş günü içinde onaylanacaktır</li>
<li><strong>Kargo:</strong> Ürünü anlaşmalı kargo firmamız ücretsiz olarak adresinizden alacaktır</li>
<li><strong>İnceleme:</strong> Ürün depom uza ulaştığında 2-3 iş günü içinde incelenir</li>
<li><strong>İade Ödemesi:</strong> Onay sonrası 5-7 iş günü içinde ödemeniz iade edilir</li>
</ol>

<h3>5. Değişim</h3>
<p>Beden veya renk değişimi yapabilirsiniz. Değişim işlemi için:</p>
<ul>
<li>İade sürecini başlatın</li>
<li>İstediğiniz yeni ürünü sipariş verin</li>
<li>İade onaylandıktan sonra fark tutarı varsa ödenir/iade edilir</li>
</ul>

<h3>6. Hasarlı/Hatalı Ürün</h3>
<p>Ürün hasarlı veya hatalı geldi ise:</p>
<ul>
<li>7 gün içinde fotoğraflarla birlikte bildirim yapın</li>
<li>Kargo ücreti tarafımızdan karşılanır</li>
<li>Yeni ürün 2-3 iş günü içinde gönderilir</li>
</ul>

<h3>7. İletişim</h3>
<p>İade ve değişim talepleriniz için:<br>
E-posta: info@wawahouse.com<br>
Telefon: [Telefon]<br>
WhatsApp: [WhatsApp]</p>', 3, 1, 1, 'İade ve Değişim Şartları', 'WawaHouse iade ve değişim koşulları, iade süreci', 'iade, değişim, iade şartları', NOW()),

-- Gizlilik Politikası
('Gizlilik Politikası', 'gizlilik-politikasi', '<h2>Gizlilik Politikası</h2>
<p><strong>Son Güncelleme:</strong> ' . date('d.m.Y') . '</p>

<h3>1. Giriş</h3>
<p>WawaHouse olarak gizliliğinize saygı duyuyoruz ve kişisel bilgilerinizin güvenliğini sağlamak için gerekli tüm teknik ve idari tedbirleri alıyoruz.</p>

<h3>2. Toplanan Bilgiler</h3>
<p>Sitemizi kullanırken aşağıdaki bilgileri toplayabiliriz:</p>
<ul>
<li>Hesap oluştururken: Ad, soyad, e-posta, telefon</li>
<li>Alışveriş yaparken: Teslimat adresi, fatura bilgileri</li>
<li>Site kullanımında: IP adresi, tarayıcı bilgileri, çerezler</li>
</ul>

<h3>3. Bilgilerin Kullanımı</h3>
<p>Topladığımız bilgileri şu amaçlarla kullanıyoruz:</p>
<ul>
<li>Siparişlerinizi işlemek ve teslimat yapmak</li>
<li>Size özel teklifler ve kampanyalar sunmak</li>
<li>Müşteri hizmetleri sağlamak</li>
<li>Site deneyiminizi iyileştirmek</li>
<li>Yasal yükümlülüklerimizi yerine getirmek</li>
</ul>

<h3>4. Çerezler (Cookies)</h3>
<p>Sitemizde çerezler kullanılmaktadır. Çerezler, site deneyiminizi iyileştirmek ve tercihlerinizi hatırlamak için kullanılır. Tarayıcınızın ayarlarından çerezleri yönetebilirsiniz.</p>

<h3>5. Bilgi Güvenliği</h3>
<p>Kişisel bilgilerinizin güvenliğini sağlamak için:</p>
<ul>
<li>SSL sertifikası ile şifreli iletişim</li>
<li>Güvenli ödeme altyapısı</li>
<li>Düzenli güvenlik güncellemeleri</li>
<li>Yetkisiz erişime karşı koruma</li>
<li>Personel eğitimleri uyguluyoruz</li>
</ul>

<h3>6. Üçüncü Taraf Paylaşımı</h3>
<p>Kişisel bilgileriniz, açık rızanız olmadan üçüncü taraflarla paylaşılmaz. Ancak aşağıdaki durumlarda paylaşım yapılabilir:</p>
<ul>
<li>Kargo teslimatı için</li>
<li>Ödeme işlemi için</li>
<li>Yasal zorunluluk durumunda</li>
</ul>

<h3>7. Haklarınız</h3>
<p>Kişisel verilerinizle ilgili:</p>
<ul>
<li>Bilgilerinizi görüntüleme</li>
<li>Güncelleme veya düzeltme</li>
<li>Silme/unutulma</li>
<li>İşlemeyi durdurma talebinde bulunma hakkına sahipsiniz</li>
</ul>

<h3>8. İletişim</h3>
<p>Gizlilik politikamız hakkında sorularınız için:<br>
E-posta: info@wawahouse.com</p>', 4, 1, 1, 'Gizlilik Politikası', 'WawaHouse gizlilik politikası ve kişisel veri güvenliği', 'gizlilik, veri güvenliği, çerezler', NOW()),

-- Kullanım Koşulları
('Kullanım Koşulları', 'kullanim-kosullari', '<h2>Kullanım Koşulları</h2>
<p><strong>Son Güncelleme:</strong> ' . date('d.m.Y') . '</p>

<h3>1. Kabul ve Onay</h3>
<p>WawaHouse web sitesini kullanarak aşağıdaki kullanım koşullarını kabul etmiş sayılırsınız. Bu koşulları kabul etmiyorsanız lütfen siteyi kullanmayınız.</p>

<h3>2. Hizmet Tanımı</h3>
<p>WawaHouse, bebek giyim ve aksesuar ürünlerinin online satışını yapan bir e-ticaret platformudur.</p>

<h3>3. Kullanıcı Hesapları</h3>
<ul>
<li>Hesap oluştururken doğru ve güncel bilgiler vermelisiniz</li>
<li>Hesap güvenliğiniz sizin sorumluluğunuzdadır</li>
<li>Şifrenizi kimseyle paylaşmayınız</li>
<li>Hesabınızda gerçekleşen tüm işlemlerden siz sorumlusunuz</li>
</ul>

<h3>4. Ürün ve Fiyat Bilgileri</h3>
<ul>
<li>Tüm ürün bilgileri mümkün olduğunca doğru verilmektedir</li>
<li>Fiyatlar önceden haber verilmeksizin değiştirilebilir</li>
<li>Stok durumu anlık güncellenmektedir</li>
<li>Görsel farklılıklar olabilir</li>
</ul>

<h3>5. Sipariş ve Ödeme</h3>
<ul>
<li>Sipariş vermek üyelik gerektirir</li>
<li>Siparişiniz onaylandıktan sonra bağlayıcıdır</li>
<li>Ödeme bilgileriniz güvenli şekilde işlenir</li>
<li>Ödeme onayı banka/finans kuruluşu tarafından yapılır</li>
</ul>

<h3>6. Teslimat</h3>
<ul>
<li>Teslimat süreleri tahminidir</li>
<li>Kargo firması kaynaklı gecikmeler tarafımızdan sorumlu değildir</li>
<li>Adres bilgileriniz eksiksiz olmalıdır</li>
<li>Teslimatta kimlik kontrolü yapılabilir</li>
</ul>

<h3>7. Fikri Mülkiyet Hakları</h3>
<ul>
<li>Site içeriği (logo, tasarım, metin, görsel) WawaHouse\'un mülkiyetindedir</li>
<li>İzinsiz kullanım yasaktır</li>
<li>Kopyalama, çoğaltma veya dağıtma yapılamaz</li>
</ul>

<h3>8. Yasaklı Kullanımlar</h3>
<p>Siteyi aşağıdaki amaçlarla kullanamazsınız:</p>
<ul>
<li>Yasadışı faaliyetler</li>
<li>Başkalarının haklarını ihlal etmek</li>
<li>Virüs, zararlı yazılım yaymak</li>
<li>Site güvenliğini tehdit etmek</li>
<li>Spam veya otomatik işlemler</li>
</ul>

<h3>9. Sorumluluk Reddi</h3>
<ul>
<li>Site "olduğu gibi" sunulmaktadır</li>
<li>Kesintisiz hizmet garantisi verilmemektedir</li>
<li>Üçüncü taraf site bağlantılarından sorumlu değiliz</li>
<li>Dolaylı zararlardan sorumlu tutulamayız</li>
</ul>

<h3>10. Değişiklikler</h3>
<p>WawaHouse, bu kullanım koşullarını önceden haber vermeksizin değiştirme hakkını saklı tutar. Değişiklikler yayınlandığı anda geçerli olur.</p>

<h3>11. Uyuşmazlık Çözümü</h3>
<p>Bu sözleşmeden doğan uyuşmazlıklarda İzmir Mahkemeleri ve İcra Daireleri yetkilidir.</p>

<h3>12. İletişim</h3>
<p>Kullanım koşulları hakkında sorularınız için:<br>
E-posta: info@wawahouse.com<br>
Adres: [Şirket Adresi]</p>', 5, 1, 1, 'Kullanım Koşulları', 'WawaHouse kullanım koşulları ve site kullanım şartları', 'kullanım koşulları, şartlar, site kuralları', NOW()),

-- Kargo ve Teslimat
('Kargo ve Teslimat', 'kargo-teslimat', '<h2>Kargo ve Teslimat</h2>

<h3>1. Kargo Firmaları</h3>
<p>WawaHouse, aşağıdaki anlaşmalı kargo firmaları ile çalışmaktadır:</p>
<ul>
<li>Yurtiçi Kargo</li>
<li>Aras Kargo</li>
<li>MNG Kargo</li>
<li>PTT Kargo</li>
</ul>

<h3>2. Teslimat Süreleri</h3>
<ul>
<li><strong>Aynı Gün Kargo:</strong> İstanbul içi, saat 14:00\'a kadar verilen siparişler</li>
<li><strong>1-2 İş Günü:</strong> Büyükşehirler</li>
<li><strong>2-4 İş Günü:</strong> Diğer iller</li>
<li><strong>3-5 İş Günü:</strong> Uzak bölgeler</li>
</ul>
<p><em>* Teslimat süreleri, kargonun işleme alındığı tarihten itibaren geçerlidir.</em></p>

<h3>3. Kargo Ücreti</h3>
<ul>
<li><strong>500 TL ve üzeri:</strong> ÜCRETSİZ KARGO</li>
<li><strong>500 TL altı:</strong> 29,90 TL</li>
<li><strong>Hızlı teslimat (isteğe bağlı):</strong> +20 TL</li>
</ul>

<h3>4. Sipariş Takibi</h3>
<p>Siparişiniz kargoya verildiğinde:</p>
<ul>
<li>E-posta ile bildirim alırsınız</li>
<li>SMS ile kargo takip numarası gönderilir</li>
<li>Hesabım > Siparişlerim bölümünden takip edebilirsiniz</li>
<li>Kargo firmasının sitesinden anlık takip yapabilirsiniz</li>
</ul>

<h3>5. Teslimat</h3>
<ul>
<li>Kargo, adres bilgilerinizde belirtilen kişiye teslim edilir</li>
<li>Teslim alacak kişi 18 yaşından büyük olmalıdır</li>
<li>Kimlik kontrolü yapılabilir</li>
<li>Paketin hasarlı olup olmadığını kontrol edin</li>
<li>Hasarlı paket kabul etmeyin ve kargo görevlisine tutanak tutturun</li>
</ul>

<h3>6. Teslimat Adresi</h3>
<ul>
<li>Adres bilgileriniz eksiksiz ve doğru olmalıdır</li>
<li>Bina, kat, daire numarası belirtiniz</li>
<li>Telefon numaranız güncel olmalıdır</li>
<li>Adres değişikliği siparişten önce yapılmalıdır</li>
</ul>

<h3>7. Teslimat Yapılamayan Durumlar</h3>
<p>Kargo teslim edilemez ise:</p>
<ul>
<li>Kargo şubesinden bildirim gelir</li>
<li>3 deneme yapılır</li>
<li>3 denemede teslim edilemezse şubeye iade edilir</li>
<li>15 gün içinde ş ubeden teslim alınabilir</li>
<li>15 günden sonra firmamıza iade edilir</li>
</ul>

<h3>8. Özel Durumlar</h3>
<ul>
<li><strong>Resmi Tatiller:</strong> Kargo gönderimi yapılmaz, süre uzayabilir</li>
<li><strong>Hava Koşulları:</strong> Olumsuz hava koşullarında gecikmeler olabilir</li>
<li><strong>Adalar:</strong> 1 gün ek süre gerekebilir</li>
</ul>

<h3>9. İletişim</h3>
<p>Kargo ve teslimat konusunda destek için:<br>
E-posta: kargo@wawahouse.com<br>
Telefon: [Telefon]<br>
Çalışma Saatleri: Hafta içi 09:00 - 18:00</p>', 6, 1, 1, 'Kargo ve Teslimat', 'WawaHouse kargo ve teslimat bilgileri, teslimat süreleri', 'kargo, teslimat, ücretsiz kargo', NOW());

-- ================================================
-- UYARI: Bu dosyayı database.sql çalıştırıldıktan
-- sonra ayrı olarak çalıştırınız!
-- ================================================
