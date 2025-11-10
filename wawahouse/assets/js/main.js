/**
 * WawaHouse E-Ticaret Sistemi
 * Frontend JavaScript
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {

        // Smooth Scroll
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if(target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });

        // Loading Overlay
        function showLoading() {
            $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary"></div></div>');
        }

        function hideLoading() {
            $('.loading-overlay').remove();
        }

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = `
                <div class="toast-notification toast-${type}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    ${message}
                </div>
            `;
            $('body').append(toast);
            setTimeout(() => {
                $('.toast-notification').fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }

        // Add to Cart
        window.addToCart = function(productId, variantId = null, quantity = 1) {
            showLoading();

            $.ajax({
                url: 'ajax/add-to-cart.php',
                method: 'POST',
                data: {
                    urun_id: productId,
                    varyant_id: variantId,
                    miktar: quantity
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast('Ürün sepete eklendi!', 'success');
                        // Update cart count
                        if (response.cart_count) {
                            $('.cart-badge').text(response.cart_count).show();
                        }
                        updateCartCount();
                    } else {
                        showToast(response.message || 'Bir hata oluştu', 'error');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    console.error('AJAX Error:', xhr.responseText);
                    showToast('Bir hata oluştu', 'error');
                }
            });
        };

        // Update Cart Count
        function updateCartCount() {
            $.ajax({
                url: 'ajax/get-cart-count.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.cart_count > 0) {
                        $('.cart-badge').text(response.cart_count).show();
                    } else {
                        $('.cart-badge').hide();
                    }
                }
            });
        }

        // Initialize cart count on page load
        updateCartCount();

        // Remove from Cart
        $(document).on('click', '.remove-from-cart', function(e) {
            e.preventDefault();
            const sepetId = $(this).data('sepet-id');

            if (confirm('Bu ürünü sepetten çıkarmak istediğinize emin misiniz?')) {
                showLoading();
                $.ajax({
                    url: 'ajax/remove-from-cart.php',
                    method: 'POST',
                    data: { sepet_id: sepetId },
                    dataType: 'json',
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            location.reload();
                        } else {
                            showToast(response.message, 'error');
                        }
                    },
                    error: function() {
                        hideLoading();
                        showToast('Bir hata oluştu', 'error');
                    }
                });
            }
        });

        // Update Cart Quantity
        $(document).on('change', '.cart-quantity', function() {
            const sepetId = $(this).data('sepet-id');
            const miktar = $(this).val();

            showLoading();
            $.ajax({
                url: 'ajax/update-cart.php',
                method: 'POST',
                data: {
                    sepet_id: sepetId,
                    miktar: miktar
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        location.reload();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    showToast('Bir hata oluştu', 'error');
                }
            });
        });

        // Add to Favorites
        window.addToFavorites = function(productId) {
            $.ajax({
                url: 'ajax/add-to-favorites.php',
                method: 'POST',
                data: { urun_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('Favorilere eklendi!', 'success');
                        $(`.fav-btn[data-product-id="${productId}"]`).addClass('active');
                    } else {
                        if (response.login_required) {
                            window.location.href = 'giris.php';
                        } else {
                            showToast(response.message, 'error');
                        }
                    }
                },
                error: function() {
                    showToast('Bir hata oluştu', 'error');
                }
            });
        };

        // Remove from Favorites
        window.removeFromFavorites = function(productId) {
            $.ajax({
                url: 'ajax/remove-from-favorites.php',
                method: 'POST',
                data: { urun_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('Favorilerden çıkarıldı!', 'success');
                        $(`.fav-btn[data-product-id="${productId}"]`).removeClass('active');
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Bir hata oluştu', 'error');
                }
            });
        };

        // Newsletter Subscribe
        $('#newsletter-form').on('submit', function(e) {
            e.preventDefault();

            const email = $(this).find('input[name="email"]').val();

            $.ajax({
                url: 'ajax/newsletter-subscribe.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('Bültene kaydınız alındı!', 'success');
                        $('#newsletter-form')[0].reset();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Bir hata oluştu', 'error');
                }
            });
        });

        // Product Image Gallery
        $('.product-thumbnail').on('click', function() {
            const newSrc = $(this).data('image');
            $('.product-main-image').attr('src', newSrc);
            $('.product-thumbnail').removeClass('active');
            $(this).addClass('active');
        });

        // Variant Selection
        $('.variant-option').on('click', function() {
            const type = $(this).data('type');
            $(`.variant-option[data-type="${type}"]`).removeClass('active');
            $(this).addClass('active');

            updateProductPrice();
        });

        // Update Product Price based on variants
        function updateProductPrice() {
            const selectedVariants = {};
            $('.variant-option.active').each(function() {
                const type = $(this).data('type');
                const value = $(this).data('value');
                selectedVariants[type] = value;
            });

            // AJAX ile fiyat güncelleme (backend'de implement edilmeli)
            $.ajax({
                url: '/ajax/get-variant-price.php',
                method: 'POST',
                data: { variants: selectedVariants },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.product-price').text(response.price);
                        $('.product-stock').text(response.stock);
                    }
                }
            });
        }

        // Form Validation
        $('form').on('submit', function(e) {
            const requiredFields = $(this).find('[required]');
            let isValid = true;

            requiredFields.each(function() {
                if ($(this).val() === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showToast('Lütfen tüm gerekli alanları doldurun', 'error');
            }
        });

        // Back to Top Button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        $('.back-to-top').on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 800);
            return false;
        });

    });

})(jQuery);

// Loading Overlay CSS (injected)
const loadingCSS = `
<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    z-index: 9999;
    animation: slideInRight 0.3s;
}

.toast-success {
    border-left: 4px solid #28a745;
    color: #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
    color: #dc3545;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: #ff9ec5;
    color: white;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 999;
    transition: all 0.3s;
}

.back-to-top:hover {
    background: #ff7eb3;
    transform: scale(1.1);
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', loadingCSS);
