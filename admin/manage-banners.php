<?php
include_once '../includes/config.php';
requireAdmin();

$msg = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') $msg = "Banner saved successfully!";
    if ($_GET['msg'] === 'deleted') $msg = "Banner deleted successfully!";
    if ($_GET['msg'] === 'upload_error') $msg = "Error uploading image.";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect('admin/manage-banners.php?msg=deleted');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'] ?? '';
    $link = $_POST['link'] ?? '#';
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
    $active = isset($_POST['active']) ? 1 : 0;

    // Image Logic (URL or Upload)
    $image_url = $_POST['current_image'] ?? '';
    
    if (!empty($_POST['image_url_input'])) {
        $image_url = $_POST['image_url_input'];
    } elseif (isset($_FILES['banner_file']) && $_FILES['banner_file']['error'] === 0) {
        $upload_dir = '../uploads/banners/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES['banner_file']['name']);
        if (move_uploaded_file($_FILES['banner_file']['tmp_name'], $upload_dir . $file_name)) {
            $image_url = 'uploads/banners/' . $file_name;
        }
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE banners SET title=?, subtitle=?, image_url=?, link=?, expiry_date=?, active=? WHERE id=?");
        $stmt->execute([$title, $subtitle, $image_url, $link, $expiry_date, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO banners (title, subtitle, image_url, link, expiry_date, active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $image_url, $link, $expiry_date, $active]);
    }
    redirect('admin/manage-banners.php?msg=success');
}

$stmt = $pdo->query("SELECT * FROM banners ORDER BY id DESC");
$banners = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Banners | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>Promotional Banners</h1>
        <button onclick="openModal()" class="btn btn-primary"><i class="fas fa-plus"></i> New Banner</button>
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
                    <th>Preview</th>
                    <th>Banner Information</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($banners as $b): ?>
                <tr>
                    <td style="width: 200px;">
                        <?php 
                        $img_src = $b['image_url'];
                        if (!empty($img_src) && !str_starts_with($img_src, 'http')) {
                            $img_src = '../' . $img_src;
                        }
                        ?>
                        <img src="<?php echo $img_src; ?>" style="width: 180px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid #eee;">
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 16px;"><?php echo htmlspecialchars($b['title']); ?></div>
                        <div style="font-size: 13px; color: var(--text-grey); margin-top: 4px;"><?php echo htmlspecialchars($b['subtitle']); ?></div>
                        <?php if($b['expiry_date']): ?>
                            <div style="font-size: 11px; color: #ff3b30; margin-top: 4px;"><i class="fas fa-clock"></i> Expires: <?php echo date('d M, Y H:i', strtotime($b['expiry_date'])); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $b['active'] ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo $b['active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <button onclick='editBanner(<?php echo json_encode($b, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline" style="padding: 8px 12px;"><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $b['id']; ?>" class="btn btn-outline" style="padding: 8px 12px; color: #ff3b30;" onclick="return confirm('Delete this banner?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Banner Modal -->
    <div id="bannerModal" class="modal">
        <div class="modal-content" style="width: 600px;">
            <h2 style="margin-bottom: 25px;">Banner Configuration</h2>
            <form action="manage-banners.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="banner_id">
                <input type="hidden" name="current_image" id="current_image">

                <div style="margin-bottom: 15px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Main Title</label>
                    <input type="text" name="title" id="banner_title" placeholder="e.g. Summer Sale 2024" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Subtitle / Catchphrase</label>
                    <input type="text" name="subtitle" id="banner_subtitle" placeholder="e.g. Up to 50% off on all components">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Upload Banner Image</label>
                        <input type="file" name="banner_file" accept="image/*" style="border: 1px dashed #ccc; padding: 10px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">OR Image URL</label>
                        <input type="url" name="image_url_input" id="banner_img_url" placeholder="https://...">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Target Link (URL)</label>
                        <input type="text" name="link" id="banner_link" placeholder="e.g. shop.php?category=components">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-grey);">Expiry Date (Optional)</label>
                        <input type="datetime-local" name="expiry_date" id="banner_expiry">
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <input type="checkbox" name="active" id="banner_active" style="width: auto; margin: 0;" checked>
                    <label for="banner_active" style="font-size: 14px; font-weight: 500;">Show this banner on homepage</label>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal(true)" class="btn btn-outline">Cancel</button>
                    <button type="submit" onclick="clearDraftOnSubmit()" class="btn btn-primary">Save Banner</button>
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
        document.getElementById('bannerModal').style.display = 'flex';
        restoreDraft();
        captureInitialValues();
    }

    function editBanner(b) {
        isEditing = true;
        document.getElementById('banner_id').value = b.id;
        document.getElementById('current_image').value = b.image_url;
        document.getElementById('banner_title').value = b.title;
        document.getElementById('banner_subtitle').value = b.subtitle;
        document.getElementById('banner_img_url').value = b.image_url.startsWith('http') ? b.image_url : '';
        document.getElementById('banner_link').value = b.link;
        if(b.expiry_date) {
            document.getElementById('banner_expiry').value = b.expiry_date.replace(' ', 'T');
        } else {
            document.getElementById('banner_expiry').value = '';
        }
        document.getElementById('banner_active').checked = b.active == 1;
        document.getElementById('bannerModal').style.display = 'flex';
        
        restoreDraft(); // Check if there's a draft for this specific ID
        captureInitialValues();
    }

    function resetForm() {
        document.getElementById('banner_id').value = '';
        document.getElementById('current_image').value = '';
        document.getElementById('banner_title').value = '';
        document.getElementById('banner_subtitle').value = '';
        document.getElementById('banner_img_url').value = '';
        document.getElementById('banner_link').value = '';
        document.getElementById('banner_expiry').value = '';
        document.getElementById('banner_active').checked = true;
    }

    async function closeModal(confirmNeeded = false) { 
        if (confirmNeeded && isFormDirty()) {
            const confirmed = await customConfirm(
                'Unsaved Changes',
                'You have unfinished configuration. Are you sure you want to discard these updates?',
                'Back to Editing',
                'Discard Changes'
            );
            if (!confirmed) return;
        }
        document.getElementById('bannerModal').style.display = 'none'; 
        clearDraft();
    }

    // Modal behavior: Disable closing on outside click without confirmation if dirty
    window.onclick = function(e) { 
        if(e.target == document.getElementById('bannerModal')) {
            closeModal(true);
        }
    }

    // --- Draft Logic ---
    const bannerForm = document.querySelector('#bannerModal form');
    const inputs = bannerForm.querySelectorAll('input, select, textarea');

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
        
        localStorage.setItem('banner_draft', JSON.stringify(draft));
        showDraftIndicator();
    }

    function restoreDraft() {
        const saved = localStorage.getItem('banner_draft');
        if (!saved) return;
        
        try {
            const draft = JSON.parse(saved);
            // Only restore if the ID matches (empty ID for NEW, specific ID for EDIT)
            const currentId = document.getElementById('banner_id').value;
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
        localStorage.removeItem('banner_draft');
    }

    function clearDraftOnSubmit() {
        // We set a flag or just clear it. Since it redirects, we clear it here.
        localStorage.removeItem('banner_draft');
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
        if (isFormDirty() && document.getElementById('bannerModal').style.display === 'flex') {
            return "You have unsaved changes. Are you sure you want to leave?";
        }
    };
</script>
</body>
</html>
