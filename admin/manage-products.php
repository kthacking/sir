<?php
include_once '../includes/config.php';
requireAdmin();

$msg = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') $msg = "Product saved successfully!";
    if ($_GET['msg'] === 'deleted') $msg = "Product deleted successfully!";
    if ($_GET['msg'] === 'upload_error') $msg = "Error uploading image.";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect('admin/manage-products.php?msg=deleted');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $offer_price = $_POST['offer_price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $specifications = $_POST['specifications'];
    $condition = $_POST['condition'];

    // Image Upload & URL Logic
    $image_path = $_POST['current_image'] ?? ''; 
    
    // Priority 1: Check if a URL was provided
    if (!empty($_POST['image_url'])) {
        $image_path = $_POST['image_url'];
    } 
    // Priority 2: Check if a file was uploaded
    elseif (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
        $upload_dir = '../uploads/';
        $file_name = time() . '_' . basename($_FILES['main_image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
            $image_path = 'uploads/' . $file_name; 
        }
    }

    if ($id) {
        // Update
        $stmt = $pdo->prepare("UPDATE products SET name=?, brand=?, category=?, price=?, offer_price=?, stock=?, main_image=?, specifications=?, description=?, `condition`=? WHERE id=?");
        $stmt->execute([$name, $brand, $category, $price, $offer_price, $stock, $image_path, $specifications, $description, $condition, $id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO products (name, brand, category, price, offer_price, stock, main_image, specifications, description, `condition`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $brand, $category, $price, $offer_price, $stock, $image_path, $specifications, $description, $condition]);
    }
    redirect('admin/manage-products.php?msg=success');
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>Inventory</h1>
        <button onclick="openModal()" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</button>
    </header>

    <?php if ($msg): ?>
        <div style="background: var(--primary-light); color: var(--primary); padding: 15px; border-radius: 10px; margin-bottom: 25px; font-weight: 500;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <!-- Draft Saved Indicator -->
    <div id="draftIndicator" style="position: fixed; bottom: 30px; left: 30px; background: #1d1d1f; color: #fff; padding: 10px 20px; border-radius: 30px; font-size: 13px; font-weight: 500; display: none; align-items: center; gap: 8px; z-index: 10000; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transform: translateY(100px); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <i class="fas fa-check-circle" style="color: #34c759;"></i> <span>Draft saved</span>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td style="width: 80px;">
                        <?php 
                        $img_src = $p['main_image'];
                        if (!empty($img_src) && !str_starts_with($img_src, 'http')) {
                            $img_src = '../' . $img_src;
                        } elseif (empty($img_src)) {
                            $img_src = '../assets/placeholder.png';
                        }
                        ?>
                        <img src="<?php echo $img_src; ?>" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px; border: 1px solid #eee;">
                    </td>
                    <td>
                        <div style="font-weight: 600;"><?php echo htmlspecialchars($p['name']); ?></div>
                        <div style="font-size: 12px; color: var(--text-grey);"><?php echo htmlspecialchars($p['brand']); ?> | <?php echo $p['condition']; ?></div>
                    </td>
                    <td><?php echo htmlspecialchars($p['category']); ?></td>
                    <td>
                        <div style="font-weight: 600;">₹<?php echo number_format($p['offer_price']); ?></div>
                        <div style="font-size: 11px; text-decoration: line-through; color: var(--text-grey);">₹<?php echo number_format($p['price']); ?></div>
                    </td>
                    <td>
                        <?php if($p['stock'] <= 0): ?>
                            <span class="status-badge badge-danger">Out of Stock</span>
                        <?php else: ?>
                            <span style="color: <?php echo $p['stock'] < 5 ? '#ff3b30' : 'inherit'; ?>;">
                                <?php echo $p['stock']; ?> units
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <button onclick='editProduct(<?php echo json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; color: #ff3b30;" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="productModal" class="modal">
        <div class="modal-content" style="width: 700px; max-width: 95%;">
            <h2 style="margin-bottom: 25px;">Product Information</h2>
            <form action="manage-products.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="prod_id">
                <input type="hidden" name="current_image" id="prod_current_image">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Product Name</label>
                        <input type="text" name="name" id="prod_name" placeholder="e.g. MacBook Pro M3" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Brand</label>
                        <input type="text" name="brand" id="prod_brand" placeholder="e.g. Apple" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Category</label>
                        <select name="category" id="prod_cat" required>
                            <option value="Workstations">Workstations</option>
                            <option value="Laptops">Laptops</option>
                            <option value="Components">Components</option>
                            <option value="Printers & Peripherals">Printers & Peripherals</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Condition</label>
                        <select name="condition" id="prod_cond" required>
                            <option value="New">New</option>
                            <option value="Demo">Demo</option>
                            <option value="Refurbished">Refurbished</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">MRP (₹)</label>
                        <input type="number" name="price" id="prod_price" placeholder="0" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Sale Price (₹)</label>
                        <input type="number" name="offer_price" id="prod_offer" placeholder="0" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Stock</label>
                        <input type="number" name="stock" id="prod_stock" placeholder="0" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Upload Image File</label>
                        <input type="file" name="main_image" accept="image/*" style="border: 1px dashed #ccc; padding: 10px; margin-bottom: 0;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">OR Image URL</label>
                        <input type="url" name="image_url" id="prod_img_url" placeholder="https://..." style="margin-bottom: 0;">
                    </div>
                    <div id="image_preview_text" style="grid-column: 1/-1; font-size: 11px; color: var(--text-grey); margin-top: -10px;">Leave both empty to keep current image</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Description</label>
                        <textarea name="description" id="prod_desc" placeholder="General description..." rows="4"></textarea>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Specifications</label>
                        <textarea name="specifications" id="prod_specs" placeholder="Technical specs (one per line)..." rows="4"></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px;">
                    <button type="button" onclick="closeModal(true)" class="btn btn-outline">Cancel</button>
                    <button type="submit" onclick="clearDraftOnSubmit()" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    let isEditing = false;
    let initialValues = {};
    let saveTimeout = null;

    function openModal() {
        isEditing = false;
        resetForm();
        document.getElementById('productModal').style.display = 'flex';
        restoreDraft();
        captureInitialValues();
    }

    function editProduct(p) {
        isEditing = true;
        document.getElementById('prod_id').value = p.id;
        document.getElementById('prod_current_image').value = p.main_image;
        document.getElementById('prod_img_url').value = p.main_image.startsWith('http') ? p.main_image : '';
        document.getElementById('prod_name').value = p.name;
        document.getElementById('prod_brand').value = p.brand;
        document.getElementById('prod_cat').value = p.category;
        document.getElementById('prod_cond').value = p.condition;
        document.getElementById('prod_price').value = p.price;
        document.getElementById('prod_offer').value = p.offer_price;
        document.getElementById('prod_stock').value = p.stock;
        document.getElementById('prod_desc').value = p.description;
        document.getElementById('prod_specs').value = p.specifications;
        document.getElementById('image_preview_text').style.display = 'block';
        document.getElementById('productModal').style.display = 'flex';
        
        restoreDraft(); // Check if there's a draft for this specific ID
        captureInitialValues();
    }

    function resetForm() {
        document.getElementById('prod_id').value = '';
        document.getElementById('prod_current_image').value = '';
        document.getElementById('prod_name').value = '';
        document.getElementById('prod_brand').value = '';
        document.getElementById('prod_cat').value = 'Workstations';
        document.getElementById('prod_cond').value = 'New';
        document.getElementById('prod_price').value = '';
        document.getElementById('prod_offer').value = '';
        document.getElementById('prod_stock').value = '';
        document.getElementById('prod_desc').value = '';
        document.getElementById('prod_specs').value = '';
        document.getElementById('prod_img_url').value = '';
        document.getElementById('image_preview_text').style.display = 'none';
    }

    function closeModal(confirmNeeded = false) { 
        if (confirmNeeded && isFormDirty()) {
            if (!confirm("You have unsaved changes. Do you want to discard them?")) return;
        }
        document.getElementById('productModal').style.display = 'none'; 
        clearDraft();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('productModal');
        if (event.target == modal) {
            closeModal(true);
        }
    }

    // --- Draft Logic ---
    const productForm = document.querySelector('#productModal form');
    const inputs = productForm.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            scheduleSaveDraft();
        });
    });

    function captureInitialValues() {
        initialValues = {};
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                initialValues[input.name] = input.checked;
            } else if (input.type !== 'file') {
                initialValues[input.name] = input.value;
            }
        });
    }

    function isFormDirty() {
        let dirty = false;
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (initialValues[input.name] !== input.checked) dirty = true;
            } else if (input.type !== 'file') {
                if (initialValues[input.name] !== input.value) dirty = true;
            }
        });
        return dirty;
    }

    function scheduleSaveDraft() {
        if (saveTimeout) clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveDraft, 1000);
    }

    function saveDraft() {
        if (!isFormDirty()) return;
        
        const draft = {};
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                draft[input.name] = input.checked;
            } else if (input.type !== 'file') {
                draft[input.name] = input.value;
            }
        });
        
        localStorage.setItem('product_draft', JSON.stringify(draft));
        showDraftIndicator();
    }

    function restoreDraft() {
        const saved = localStorage.getItem('product_draft');
        if (!saved) return;
        
        try {
            const draft = JSON.parse(saved);
            // Only restore if the ID matches
            const currentId = document.getElementById('prod_id').value;
            if (draft.id !== currentId) return;

            inputs.forEach(input => {
                if (draft[input.name] !== undefined) {
                    if (input.type === 'checkbox') {
                        input.checked = draft[input.name];
                    } else if (input.type !== 'file') {
                        input.value = draft[input.name];
                    }
                }
            });
            
            // Notification
            const msg = document.createElement('div');
            msg.style = 'position: absolute; top: 10px; right: 20px; font-size: 11px; color: var(--primary); font-weight: 600;';
            msg.innerText = 'Draft restored';
            document.querySelector('.modal-content').appendChild(msg);
            setTimeout(() => msg.remove(), 3000);
            
        } catch (e) {
            console.error("Failed to restore draft", e);
        }
    }

    function clearDraft() {
        localStorage.removeItem('product_draft');
    }

    function clearDraftOnSubmit() {
        localStorage.removeItem('product_draft');
    }

    function showDraftIndicator() {
        const el = document.getElementById('draftIndicator');
        el.style.display = 'flex';
        el.style.transform = 'translateY(0)';
        setTimeout(() => {
            el.style.transform = 'translateY(100px)';
            setTimeout(() => el.style.display = 'none', 400);
        }, 3000);
    }

    // Warn before leaving
    window.onbeforeunload = function() {
        if (isFormDirty() && document.getElementById('productModal').style.display === 'flex') {
            return "You have unsaved changes. Are you sure you want to leave?";
        }
    };
</script>
</body>
</html>
