<?php
include_once 'includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

$page = isset($data['page']) ? (int)$data['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$categories = $data['categories'] ?? [];
$brands = $data['brands'] ?? [];
$ram_filters = $data['ram'] ?? [];
$stock_filters = $data['stock'] ?? [];
$max_price = isset($data['price']) ? (float)$data['price'] : 250000;
$sort = $data['sort'] ?? 'latest';

$where = ["offer_price <= :max_price"];
$params = [':max_price' => $max_price];

if (!empty($categories)) {
    $where[] = "category IN ('" . implode("','", array_map('addslashes', $categories)) . "')";
}

if (!empty($brands)) {
    $where[] = "brand IN ('" . implode("','", array_map('addslashes', $brands)) . "')";
}

if (!empty($ram_filters)) {
    $ram_clauses = [];
    foreach ($ram_filters as $i => $ram) {
        $key = ":ram$i";
        $ram_clauses[] = "specifications LIKE $key";
        $params[$key] = "%$ram%";
    }
    $where[] = "(" . implode(" OR ", $ram_clauses) . ")";
}

if (!empty($stock_filters)) {
    if (count($stock_filters) == 1) {
        if ($stock_filters[0] == 'in') $where[] = "stock > 0";
        else $where[] = "stock <= 0";
    }
}

$orderBy = "p.id DESC";
if ($sort == 'price_low') $orderBy = "p.offer_price ASC";
elseif ($sort == 'price_high') $orderBy = "p.offer_price DESC";
elseif ($sort == 'popular') $orderBy = "review_count DESC, p.id DESC";

$where_clause = implode(" AND ", $where);

// Count
$count_query = "SELECT COUNT(*) FROM products WHERE $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();

// Fetch
$query = "SELECT p.*, 
          COALESCE(AVG(r.rating), 0) as avg_rating, 
          COUNT(r.id) as review_count 
          FROM products p
          LEFT JOIN reviews r ON p.id = r.product_id
          WHERE $where_clause
          GROUP BY p.id
          ORDER BY $orderBy
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
foreach ($params as $key => $val) {
    if (is_numeric($val)) $stmt->bindValue($key, $val, PDO::PARAM_INT);
    else $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

$html = '';
if (count($products) > 0) {
    foreach ($products as $p) {
        $is_out_of_stock = ($p['stock'] <= 0);
        $avg = round($p['avg_rating'], 1);
        $discount = 0;
        if ($p['price'] > $p['offer_price']) {
            $discount = round((($p['price'] - $p['offer_price']) / $p['price']) * 100);
        }

        $specs_arr = explode("\n", $p['specifications']);
        $short_desc = count($specs_arr) > 0 ? trim($specs_arr[0]) : '';
        if (count($specs_arr) > 1) $short_desc .= ' / ' . trim($specs_arr[1]);

        $html .= '
        <div class="product-card-premium fade-in-up">
            <div>
                <div class="premium-img-container">
                    ' . ($discount > 0 ? '<div style="position: absolute; top: 12px; left: 12px; background: #fee2e2; color: #ef4444; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; z-index: 2;">-' . $discount . '%</div>' : '') . '
                    <button style="position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.8); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #9ca3af; cursor: pointer; z-index: 2; backdrop-filter: blur(4px);"><i class="far fa-heart"></i></button>
                    <a href="product-details.php?id='.$p['id'].'">
                        <img src="' . htmlspecialchars($p['main_image']) . '" alt="' . htmlspecialchars($p['name']) . '">
                    </a>
                </div>
                
                <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin-top: 16px; margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px; line-height: 1.2;">' . htmlspecialchars($p['name']) . '</h3>
                <p style="font-size: 14px; color: #6b7280; line-height: 1.5; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 42px;">' . htmlspecialchars($short_desc) . '</p>
            </div>
            
            <div>
                <div style="margin-bottom: 16px;">
                    <span style="font-size: 20px; font-weight: 700; color: #111827;">₹' . number_format($p['offer_price']) . '</span>
                    ' . ($p['price'] > $p['offer_price'] ? '<span style="font-size: 13px; text-decoration: line-through; color: #9ca3af; margin-left: 8px;">₹' . number_format($p['price']) . '</span>' : '') . '
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <span class="premium-tag">' . htmlspecialchars($p['category']) . '</span>
                    </div>
                    <a href="product-details.php?id=' . $p['id'] . '" class="premium-btn">
                        View Product
                    </a>
                </div>
            </div>
        </div>';
    }
} else {
    $html = '<div style="grid-column: 1/-1; text-align: center; padding: 120px 0;">
                <p style="color: #6b7280; font-size: 15px;">No products match your current filters.</p>
                <button onclick="clearAllFilters()" style="margin-top: 16px; background: none; border: 1px solid #e5e7eb; padding: 8px 24px; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 600;">Clear All Filters</button>
            </div>';
}

$total_pages = ceil($total_products / $limit);
$pagination_html = '';
if ($total_pages > 1) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $activeClass = ($i == $page) ? 'active' : '';
        $pagination_html .= '<button onclick="filterProducts(' . $i . ')" class="lean-page-btn ' . $activeClass . '">' . $i . '</button>';
    }
}

echo json_encode([
    'html' => $html,
    'pagination' => $pagination_html,
    'total' => $total_products,
    'from' => $total_products > 0 ? $offset + 1 : 0,
    'to' => min($offset + $limit, $total_products)
]);
?>
