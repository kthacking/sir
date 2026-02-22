<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=need4it', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all categories to create a map
    $cat_stmt = $pdo->query("SELECT id, name, parent_id FROM categories");
    $all_cats = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

    $cat_map = [];
    $parent_map = [];
    foreach ($all_cats as $c) {
        if (!$c['parent_id']) {
            $parent_map[$c['id']] = $c['name'];
        }
    }
    foreach ($all_cats as $c) {
        if ($c['parent_id']) {
            $cat_map[$c['name']] = [
                'id' => $c['id'],
                'main' => $parent_map[$c['parent_id']]
            ];
        }
    }

    // Special cases for existing category names that might be parents
    $parents_as_sub = [
        'Laptops' => 'Business Laptops',
        'Workstations' => 'Workstations',
        'Components' => 'CPU',
        'Printers & Peripherals' => 'Printers',
        'Gaming' => 'Gaming Accessories',
        'Services' => 'Hardware Repair'
    ];

    $products = $pdo->query("SELECT id, name, category, main_category, subcategory FROM products")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $p) {
        $searchCat = $p['category'];
        $main = null;
        $sub = null;
        $id = null;

        if (isset($cat_map[$searchCat])) {
            $main = $cat_map[$searchCat]['main'];
            $sub = $searchCat;
            $id = $cat_map[$searchCat]['id'];
        } elseif (isset($parents_as_sub[$searchCat])) {
            $subName = $parents_as_sub[$searchCat];
            $main = $cat_map[$subName]['main'];
            $sub = $subName;
            $id = $cat_map[$subName]['id'];
        }

        if ($main && $sub) {
            $stmt = $pdo->prepare("UPDATE products SET main_category = ?, subcategory = ?, category = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$main, $sub, $sub, $id, $p['id']]);
        }
    }

    echo "Product migration completed!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
