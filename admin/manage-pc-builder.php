<?php
include_once '../includes/config.php';
requireAdmin();

$msg = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') $msg = "PC Combo/Setup saved successfully!";
    if ($_GET['msg'] === 'deleted') $msg = "Item deleted successfully!";
    if ($_GET['msg'] === 'upload_error') $msg = "Error uploading image.";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect('admin/manage-pc-builder.php?msg=deleted');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $category = $_POST['category']; // Pre-Built Combo / Full Setup
    $processor = $_POST['processor'];
    $motherboard = $_POST['motherboard'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $gpu = $_POST['gpu'];
    $smps = $_POST['smps'];
    $cabinet = $_POST['cabinet'];
    $monitor = $_POST['monitor'] ?? '';
    $peripherals = $_POST['peripherals'] ?? '';
    $price = $_POST['price'];
    $offer_price = $_POST['offer_price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $status = $_POST['status'] ?? 'Active';

    // Image Upload logic
    $image_path = $_POST['current_image'] ?? '';
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_name = time() . '_' . basename($_FILES['main_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
            $image_path = 'uploads/' . $file_name;
        }
    }

    // Build specification text for general display
    $specifications = "Processor: $processor\nMotherboard: $motherboard\nRAM: $ram\nStorage: $storage\nGPU: $gpu\nSMPS: $smps\nCabinet: $cabinet";
    if (!empty($monitor)) $specifications .= "\nMonitor: $monitor";
    if (!empty($peripherals)) $specifications .= "\nPeripherals: $peripherals";

    if ($id) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, processor=?, motherboard=?, ram=?, storage=?, gpu=?, smps=?, cabinet=?, monitor=?, peripherals=?, price=?, offer_price=?, stock=?, main_image=?, specifications=?, description=?, status=? WHERE id=?");
        $stmt->execute([$name, $category, $processor, $motherboard, $ram, $storage, $gpu, $smps, $cabinet, $monitor, $peripherals, $price, $offer_price, $stock, $image_path, $specifications, $description, $status, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, processor, motherboard, ram, storage, gpu, smps, cabinet, monitor, peripherals, price, offer_price, stock, main_image, specifications, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $processor, $motherboard, $ram, $storage, $gpu, $smps, $cabinet, $monitor, $peripherals, $price, $offer_price, $stock, $image_path, $specifications, $description, $status]);
    }

    redirect('admin/manage-pc-builder.php?msg=success');
}

// Fetch only PC Builder items
$stmt = $pdo->query("SELECT * FROM products WHERE category IN ('Pre-Built Combo', 'Full Setup') ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PC Builder Management | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; }
        .modal-content { background:#fff; padding:30px; border-radius:15px; width:90%; max-width:800px; max-height:90vh; overflow-y:auto; position:relative; }
        .form-grid { display:grid; grid-template-columns: 1fr 1fr; gap:20px; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; font-weight:600; font-size:14px; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; }
        .full-width { grid-column: span 2; }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>PC Builder Management</h1>
        <button onclick="openModal()" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Combo/Setup</button>
    </header>

    <?php if ($msg): ?>
        <div style="background: #dcfce7; color: #16a34a; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-weight: 500;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Specs Summary</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td>
                        <?php 
                        $img = $p['main_image'];
                        if (empty($img)) {
                            $img = '../assets/placeholder.png';
                        } elseif (strpos($img, 'http') === false) {
                            $img = '../' . $img;
                        }
                        ?>
                        <div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: #f9f9f9; border: 1px solid #eee; display: flex; align-items: center; justify-content: center;">
                            <img src="<?php echo $img; ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                    </td>
                    <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                    <td><?php echo $p['category']; ?></td>
                    <td><small><?php echo htmlspecialchars($p['processor']); ?> / <?php echo htmlspecialchars($p['gpu']); ?></small></td>
                    <td>₹<?php echo number_format($p['offer_price']); ?></td>
                    <td><?php echo $p['stock']; ?></td>
                    <td><span class="status-badge" style="background:<?php echo $p['status']=='Active'?'#dcfce7':'#fee2e2'; ?>; color:<?php echo $p['status']=='Active'?'#16a34a':'#ef4444'; ?>;"><?php echo $p['status']; ?></span></td>
                    <td style="text-align: right;">
                        <button onclick='editProduct(<?php echo json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT); ?>)' class="btn btn-outline"><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-outline" style="color:#ef4444;" onclick="return confirm('Delete this item?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="productModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Add New PC Combo/Setup</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="p_id">
            <input type="hidden" name="current_image" id="p_current_image">
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Product Name</label>
                    <input type="text" name="name" id="p_name" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="p_category" required>
                        <option value="Pre-Built Combo">Pre-Built Combo</option>
                        <option value="Full Setup">Full Setup</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="p_status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" name="processor" id="p_processor" required>
                </div>
                <div class="form-group">
                    <label>Motherboard</label>
                    <input type="text" name="motherboard" id="p_motherboard" required>
                </div>
                <div class="form-group">
                    <label>RAM</label>
                    <input type="text" name="ram" id="p_ram" required>
                </div>
                <div class="form-group">
                    <label>Storage</label>
                    <input type="text" name="storage" id="p_storage" required>
                </div>
                <div class="form-group">
                    <label>GPU</label>
                    <input type="text" name="gpu" id="p_gpu" required>
                </div>
                <div class="form-group">
                    <label>SMPS (Power Supply)</label>
                    <input type="text" name="smps" id="p_smps" required>
                </div>
                <div class="form-group">
                    <label>Cabinet</label>
                    <input type="text" name="cabinet" id="p_cabinet" required>
                </div>
                <div class="form-group">
                    <label>Monitor (Optional)</label>
                    <input type="text" name="monitor" id="p_monitor">
                </div>
                <div class="form-group">
                    <label>Keyboard & Mouse (Optional)</label>
                    <input type="text" name="peripherals" id="p_peripherals">
                </div>
                <div class="form-group">
                    <label>Price (MRP)</label>
                    <input type="number" name="price" id="p_price" required>
                </div>
                <div class="form-group">
                    <label>Offer Price</label>
                    <input type="number" name="offer_price" id="p_offer_price" required>
                </div>
                <div class="form-group">
                    <label>Stock Availability</label>
                    <input type="number" name="stock" id="p_stock" required>
                </div>
                <div class="form-group full-width">
                    <label>Product Image</label>
                    <input type="file" name="main_image">
                    <small id="image_info"></small>
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" id="p_description" rows="3"></textarea>
                </div>
            </div>
            
            <div style="margin-top:20px; text-align:right;">
                <button type="button" onclick="closeModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('p_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add New PC Combo/Setup';
    document.getElementById('image_info').innerText = '';
    document.getElementById('productModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function editProduct(p) {
    document.getElementById('p_id').value = p.id;
    document.getElementById('p_name').value = p.name;
    document.getElementById('p_category').value = p.category;
    document.getElementById('p_processor').value = p.processor;
    document.getElementById('p_motherboard').value = p.motherboard;
    document.getElementById('p_ram').value = p.ram;
    document.getElementById('p_storage').value = p.storage;
    document.getElementById('p_gpu').value = p.gpu;
    document.getElementById('p_smps').value = p.smps;
    document.getElementById('p_cabinet').value = p.cabinet;
    document.getElementById('p_monitor').value = p.monitor || '';
    document.getElementById('p_peripherals').value = p.peripherals || '';
    document.getElementById('p_price').value = p.price;
    document.getElementById('p_offer_price').value = p.offer_price;
    document.getElementById('p_stock').value = p.stock;
    document.getElementById('p_description').value = p.description;
    document.getElementById('p_status').value = p.status;
    document.getElementById('p_current_image').value = p.main_image;
    document.getElementById('image_info').innerText = 'Current image: ' + p.main_image;
    
    document.getElementById('modalTitle').innerText = 'Edit PC Combo/Setup';
    document.getElementById('productModal').style.display = 'flex';
}
</script>

</body>
</html>
