<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Panel Fonksiyonları
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

/**
 * Dashboard istatistikleri
 */
function getDashboardStats(): array {
    $today = date('Y-m-d');
    $thisMonth = date('Y-m');

    // Bugünkü siparişler
    $todayOrders = db()->count('siparisler', "DATE(created_at) = ?", [$today]);

    // Bugünkü satış
    $todaySales = db()->fetchOne(
        "SELECT COALESCE(SUM(toplam_tutar), 0) as total FROM siparisler WHERE DATE(created_at) = ? AND durum != 'iptal'",
        [$today]
    )['total'] ?? 0;

    // Bu ayki siparişler
    $monthOrders = db()->count('siparisler', "DATE_FORMAT(created_at, '%Y-%m') = ?", [$thisMonth]);

    // Bu ayki satış
    $monthSales = db()->fetchOne(
        "SELECT COALESCE(SUM(toplam_tutar), 0) as total FROM siparisler WHERE DATE_FORMAT(created_at, '%Y-%m') = ? AND durum != 'iptal'",
        [$thisMonth]
    )['total'] ?? 0;

    // Toplam müşteri
    $totalCustomers = db()->count('kullanicilar', "rol = 'musteri'");

    // Toplam ürün
    $totalProducts = db()->count('urunler', "aktif = 1");

    // Düşük stok ürünler (5'in altı)
    $lowStock = db()->fetchAll(
        "SELECT u.ad, v.beden, v.renk, v.stok
         FROM urun_varyantlari v
         INNER JOIN urunler u ON u.id = v.urun_id
         WHERE v.stok < 5 AND v.stok > 0 AND u.aktif = 1
         ORDER BY v.stok ASC
         LIMIT 10"
    );

    // Son siparişler
    $recentOrders = db()->fetchAll(
        "SELECT s.*, k.ad_soyad, k.email
         FROM siparisler s
         INNER JOIN kullanicilar k ON k.id = s.kullanici_id
         ORDER BY s.created_at DESC
         LIMIT 10"
    );

    return [
        'today_orders' => $todayOrders,
        'today_sales' => $todaySales,
        'month_orders' => $monthOrders,
        'month_sales' => $monthSales,
        'total_customers' => $totalCustomers,
        'total_products' => $totalProducts,
        'low_stock' => $lowStock,
        'recent_orders' => $recentOrders,
    ];
}

/**
 * Aylık satış grafiği verisi
 */
function getMonthlyChartData(): array {
    $year = date('Y');
    $months = [];
    $sales = [];

    for ($i = 1; $i <= 12; $i++) {
        $month = str_pad($i, 2, '0', STR_PAD_LEFT);
        $monthName = date('M', mktime(0, 0, 0, $i, 1));

        $total = db()->fetchOne(
            "SELECT COALESCE(SUM(toplam_tutar), 0) as total
             FROM siparisler
             WHERE DATE_FORMAT(created_at, '%Y-%m') = ? AND durum != 'iptal'",
            ["{$year}-{$month}"]
        )['total'] ?? 0;

        $months[] = $monthName;
        $sales[] = (float)$total;
    }

    return [
        'months' => $months,
        'sales' => $sales,
    ];
}

/**
 * Pagination helper
 */
function renderPagination(array $pagination, string $baseUrl): string {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }

    $html = '<nav><ul class="pagination justify-content-center">';

    // Previous
    if ($pagination['has_previous']) {
        $prevPage = $pagination['current_page'] - 1;
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}?page={$prevPage}'>Önceki</a></li>";
    } else {
        $html .= "<li class='page-item disabled'><span class='page-link'>Önceki</span></li>";
    }

    // Pages
    for ($i = 1; $i <= $pagination['total_pages']; $i++) {
        $active = $i === $pagination['current_page'] ? 'active' : '';
        $html .= "<li class='page-item {$active}'><a class='page-link' href='{$baseUrl}?page={$i}'>{$i}</a></li>";
    }

    // Next
    if ($pagination['has_next']) {
        $nextPage = $pagination['current_page'] + 1;
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}?page={$nextPage}'>Sonraki</a></li>";
    } else {
        $html .= "<li class='page-item disabled'><span class='page-link'>Sonraki</span></li>";
    }

    $html .= '</ul></nav>';

    return $html;
}

/**
 * Alert mesajları
 */
function renderAlerts(): string {
    $html = '';

    $types = ['success', 'error', 'warning', 'info'];
    foreach ($types as $type) {
        if ($message = getFlash($type)) {
            $bootstrapType = match($type) {
                'error' => 'danger',
                default => $type
            };
            $html .= "<div class='alert alert-{$bootstrapType} alert-dismissible fade show' role='alert'>";
            $html .= clean($message);
            $html .= "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            $html .= "</div>";
        }
    }

    return $html;
}

/**
 * Kategori ağacı (recursive)
 */
function getCategoryTree(?int $parentId = null, int $level = 0): array {
    $categories = db()->fetchAll(
        "SELECT * FROM kategoriler WHERE parent_id " . ($parentId ? "= ?" : "IS NULL") . " ORDER BY sira ASC",
        $parentId ? [$parentId] : []
    );

    $tree = [];
    foreach ($categories as $category) {
        $category['level'] = $level;
        $category['children'] = getCategoryTree($category['id'], $level + 1);
        $tree[] = $category;
    }

    return $tree;
}

/**
 * Kategori select options
 */
function renderCategoryOptions(array $tree, int $selectedId = 0): string {
    $html = '';
    foreach ($tree as $category) {
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $category['level']);
        $selected = $category['id'] === $selectedId ? 'selected' : '';
        $html .= "<option value='{$category['id']}' {$selected}>{$indent}{$category['ad']}</option>";

        if (!empty($category['children'])) {
            $html .= renderCategoryOptions($category['children'], $selectedId);
        }
    }
    return $html;
}
