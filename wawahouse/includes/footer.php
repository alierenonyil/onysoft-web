<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5>
                    <i class="fas fa-baby-carriage"></i>
                    WawaHouse
                </h5>
                <p>Bebeğiniz için en kaliteli ve uygun fiyatlı bebek kıyafetleri. %100 organik pamuklu ürünler.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Hızlı Linkler</h5>
                <ul>
                    <li><a href="<?= url() ?>">Ana Sayfa</a></li>
                    <li><a href="<?= url('urunler.php') ?>">Ürünler</a></li>
                    <li><a href="<?= url('sayfa/hakkimizda') ?>">Hakkımızda</a></li>
                    <li><a href="<?= url('sayfa/iletisim') ?>">İletişim</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Müşteri Hizmetleri</h5>
                <ul>
                    <li><a href="<?= url('sayfa/iade-ve-degisim') ?>">İade ve Değişim</a></li>
                    <li><a href="<?= url('sayfa/kargo-ve-teslimat') ?>">Kargo ve Teslimat</a></li>
                    <li><a href="<?= url('sayfa/kvkk') ?>">KVKK</a></li>
                    <li><a href="<?= url('sayfa/mesafeli-satis-sozlesmesi') ?>">Mesafeli Satış Sözleşmesi</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>İletişim</h5>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> İzmir, Türkiye</li>
                    <li><i class="fas fa-phone"></i> +90 232 XXX XX XX</li>
                    <li><i class="fas fa-envelope"></i> info@wawahouse.com</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; <?= date('Y') ?> WawaHouse - Tüm hakları saklıdır.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p>
                        <i class="fas fa-code text-danger"></i>
                        <a href="<?= DEVELOPER_URL ?>" target="_blank">
                            <?= DEVELOPER_NAME ?>
                        </a>
                        tarafından geliştirilmiştir
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Custom JS -->
<script src="<?= asset('js/main.js') ?>"></script>

<!-- Auto-hide alerts -->
<script>
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>

<?php if (isset($extraScripts)): ?>
    <?= $extraScripts ?>
<?php endif; ?>

</body>
</html>
