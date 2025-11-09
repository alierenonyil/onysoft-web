        </div><!-- .admin-content -->

        <!-- Admin Footer -->
        <footer class="admin-footer" style="background: white; padding: 20px 30px; text-align: center; border-top: 1px solid #dee2e6; margin-left: 0;">
            <p class="mb-1">&copy; <?= date('Y') ?> WawaHouse - Bebek Giyim ve Aksesuar. Tüm hakları saklıdır.</p>
            <p class="mb-0">
                <i class="fas fa-code text-danger"></i>
                Powered by
                <a href="<?= DEVELOPER_URL ?>" target="_blank" class="text-decoration-none fw-bold text-primary">
                    <?= DEVELOPER_NAME ?>
                </a>
                |
                <a href="<?= DEVELOPER_URL ?>" target="_blank" class="text-decoration-none">
                    <?= DEVELOPER_WEB ?>
                </a>
            </p>
        </footer>

    </div><!-- .admin-main -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Admin JS -->
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete
        document.querySelectorAll('[data-confirm]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                if (!confirm(this.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });

        // Mobile menu toggle
        const sidebar = document.querySelector('.admin-sidebar');
        const menuToggle = document.querySelector('.menu-toggle');

        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    </script>

    <?php if (isset($extraScripts)): ?>
        <?= $extraScripts ?>
    <?php endif; ?>

</body>
</html>
