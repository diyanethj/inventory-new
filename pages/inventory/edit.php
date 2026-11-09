<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';
require_once '../../includes/header.php';

// ────────────────────────────────────────────────
// Get Item Data
$id = (int)($_GET['id'] ?? 0);
$item = $conn->query("
    SELECT i.*, 
           p.name AS product_name, v.name AS venue_name, v.code AS venue_code, 
           p.code AS product_code, e.name AS employee_name 
    FROM inventory i 
    JOIN products p ON i.product_id = p.id 
    JOIN venues v ON i.venue_id = v.id 
    LEFT JOIN employees e ON i.assigned_to = e.id 
    WHERE i.id = $id
")->fetch_assoc();

if (!$item) {
    echo '<div class="alert alert-danger text-center py-5 m-4 animate-fade-in">Item not found.</div>';
    require_once '../../includes/footer.php';
    exit;
}

// Load dropdown data
$venues   = $conn->query("SELECT id, name, code FROM venues ORDER BY name");
$products = $conn->query("SELECT id, name, code FROM products ORDER BY name");
$employees = $conn->query("SELECT id, name FROM employees ORDER BY name");

// ────────────────────────────────────────────────
// Handle Form Submission
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_id     = (int)$_POST['venue_id'];
    $product_id   = (int)$_POST['product_id'];
    $suffix       = trim($_POST['suffix']);
    $brand        = trim($_POST['brand']) ?: null;
    $serial       = trim($_POST['serial_number']) ?: null;
    $condition    = $_POST['condition'];
    $assigned_to  = $_POST['assigned_to'] === '' ? null : (int)$_POST['assigned_to'];

    // Generate new code
    $v = $conn->query("SELECT code FROM venues WHERE id = $venue_id")->fetch_assoc();
    $p = $conn->query("SELECT code FROM products WHERE id = $product_id")->fetch_assoc();
    $new_code = $v['code'] . '-' . $p['code'] . '-' . $suffix;

    $stmt = $conn->prepare("
        UPDATE inventory 
        SET venue_id = ?, product_id = ?, code = ?, brand = ?, serial_number = ?, 
            `condition` = ?, assigned_to = ?
        WHERE id = ?
    ");
    $stmt->bind_param("iissssii", $venue_id, $product_id, $new_code, $brand, $serial, $condition, $assigned_to, $id);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Error updating item (possibly duplicate code): " . $conn->error;
    }
    $stmt->close();
}
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Navbar (same as dashboard) -->
<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background: linear-gradient(135deg, #1e1e2f 0%, #2a2a40 100%); backdrop-filter: blur(10px); box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand d-flex align-items-center" href="../dashboard.php">
            <div class="brand-icon me-2 d-flex align-items-center justify-content-center" 
                 style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                <i class="bi bi-box-seam text-white"></i>
            </div>
            <span class="fw-bold" style="font-size: 1.3rem;">CSBM<span style="color: #a78bfa;"> Inventory</span></span>
        </a>

        <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="background: rgba(255,255,255,0.1); border-radius: 12px;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="../dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="../employees/index.php">
                        <i class="bi bi-people"></i> Employees
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="../products/index.php">
                        <i class="bi bi-box"></i> Products
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="../venues/index.php">
                        <i class="bi bi-building"></i> Venues
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2 active" href="index.php">
                        <i class="bi bi-clipboard-data"></i> Inventory
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown mx-lg-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-3" 
                       href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px;">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-lg-inline"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow-lg" aria-labelledby="userDropdown"
                        style="border-radius: 16px; background: white; min-width: 220px;">
                        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2" href="#"><i class="bi bi-person text-primary"></i> Profile</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2" href="#"><i class="bi bi-gear text-secondary"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Styles (same as dashboard) -->
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --info-gradient: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
    --warning-gradient: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%);
}

/* Modern Card */
.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
}

/* Form Styles */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 16px;
    padding: 0.75rem 1.75rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-outline-secondary {
    border-radius: 16px;
    padding: 0.75rem 1.75rem;
}

/* Animation */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeInUp 0.5s ease forwards; }
</style>

<!-- JavaScript for Code Preview -->
<script>
function updateCodePreview() {
    const venueSelect = document.getElementById('venue_id');
    const productSelect = document.getElementById('product_id');
    const suffixInput = document.getElementById('suffix');
    const preview = document.getElementById('code_preview');

    const venueCode = venueSelect.options[venueSelect.selectedIndex]?.dataset.code || '';
    const productCode = productSelect.options[productSelect.selectedIndex]?.dataset.code || '';
    const suffix = suffixInput.value.trim().padStart(3, '0');

    if (venueCode && productCode && suffix) {
        preview.textContent = `${venueCode}-${productCode}-${suffix}`;
    } else {
        preview.textContent = 'Select venue, product, and enter suffix';
    }
}
</script>

<!-- Content -->
<div class="container-fluid py-4 px-3 px-md-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 animate-fade-in">
        <div>
            <h1 class="display-6 fw-bold mb-1" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Edit Inventory Item
            </h1>
            <p class="text-muted mb-0">
                <i class="bi bi-pencil-square me-2"></i>Update item details
            </p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="modern-card p-4 p-md-5 animate-fade-in" style="animation-delay: 0.2s">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> Item updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" oninput="updateCodePreview()">
            <div class="row g-4">
                <!-- Venue -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Venue <span class="text-danger">*</span></label>
                    <select name="venue_id" id="venue_id" class="form-select form-select-lg" required onchange="updateCodePreview()">
                        <?php while ($v = $venues->fetch_assoc()): ?>
                            <option value="<?= $v['id'] ?>" data-code="<?= htmlspecialchars($v['code']) ?>"
                                <?= $v['id'] == $item['venue_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['name']) ?> (<?= $v['code'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Product -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select form-select-lg" required onchange="updateCodePreview()">
                        <?php while ($p = $products->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>" data-code="<?= htmlspecialchars($p['code']) ?>"
                                <?= $p['id'] == $item['product_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['name']) ?> (<?= $p['code'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Item Suffix & Code Preview -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Item Number (suffix) <span class="text-danger">*</span></label>
                    <input type="text" name="suffix" id="suffix" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars(substr($item['code'], -3)) ?>" required oninput="updateCodePreview()">
                </div>

                <div class="col-12 col-md-8">
                    <label class="form-label">Generated Code (preview)</label>
                    <div id="code_preview" class="form-control form-control-lg bg-light text-muted">
                        <?= htmlspecialchars($item['code']) ?>
                    </div>
                </div>

                <!-- Brand -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($item['brand'] ?? '') ?>">
                </div>

                <!-- Serial Number -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($item['serial_number'] ?? '') ?>">
                </div>

                <!-- Condition -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Condition <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select form-select-lg" required>
                        <option value="brandnew"  <?= $item['condition'] == 'brandnew'  ? 'selected' : '' ?>>Brand New</option>
                        <option value="used"      <?= $item['condition'] == 'used'      ? 'selected' : '' ?>>Used</option>
                        <option value="personal"  <?= $item['condition'] == 'personal'  ? 'selected' : '' ?>>Personal</option>
                    </select>
                </div>

                <!-- Assigned Employee (NEW FIELD) -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Assigned Employee</label>
                    <select name="assigned_to" class="form-select form-select-lg">
                        <option value="">Unassigned</option>
                        <?php while ($e = $employees->fetch_assoc()): ?>
                            <option value="<?= $e['id'] ?>" <?= $e['id'] == $item['assigned_to'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-12 mt-5">
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <button type="submit" class="btn btn-lg px-5 flex-grow-1" 
                                style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px;">
                            <i class="bi bi-save me-2"></i> Update Item
                        </button>
                        <a href="index.php" class="btn btn-lg btn-outline-secondary px-5 flex-grow-1">
                            <i class="bi bi-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>