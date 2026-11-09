<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';
require_once '../../includes/header.php';

// ────────────────────────────────────────────────
// Filters & Search (same as before)
$search = trim($_GET['search'] ?? '');
$filter_product   = $_GET['product']   ?? [];
$filter_venue     = $_GET['venue']     ?? [];
$filter_condition = $_GET['condition'] ?? [];
$filter_assigned  = $_GET['assigned']  ?? 'all';
$filter_employee  = $_GET['employee']  ?? 'all';

// Build WHERE clause
$where = [];
$params = [];
$types = "";

if ($search !== '') {
    $where[] = "(i.code LIKE ? OR p.name LIKE ?)";
    $like = "%$search%";
    $params = array_merge($params, [$like, $like]);
    $types .= "ss";
}

if (!empty($filter_product)) {
    $placeholders = implode(',', array_fill(0, count($filter_product), '?'));
    $where[] = "i.product_id IN ($placeholders)";
    $params = array_merge($params, $filter_product);
    $types .= str_repeat('i', count($filter_product));
}

if (!empty($filter_venue)) {
    $placeholders = implode(',', array_fill(0, count($filter_venue), '?'));
    $where[] = "i.venue_id IN ($placeholders)";
    $params = array_merge($params, $filter_venue);
    $types .= str_repeat('i', count($filter_venue));
}

if (!empty($filter_condition)) {
    $placeholders = implode(',', array_fill(0, count($filter_condition), '?'));
    $where[] = "i.condition IN ($placeholders)";
    $params = array_merge($params, $filter_condition);
    $types .= str_repeat('s', count($filter_condition));
}

if ($filter_assigned === 'assigned') {
    $where[] = "i.assigned_to IS NOT NULL";
} elseif ($filter_assigned === 'unassigned') {
    $where[] = "i.assigned_to IS NULL";
}

if ($filter_employee !== 'all') {
    if ($filter_employee === 'unassigned') {
        $where[] = "i.assigned_to IS NULL";
    } else {
        $where[] = "i.assigned_to = ?";
        $params[] = (int)$filter_employee;
        $types .= "i";
    }
}

// Base query
$sql = "
    SELECT i.*, 
           p.name as product_name, 
           v.name as venue_name, 
           e.name as employee_name 
    FROM inventory i 
    LEFT JOIN products p ON i.product_id = p.id 
    LEFT JOIN venues v ON i.venue_id = v.id 
    LEFT JOIN employees e ON i.assigned_to = e.id
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY i.code ASC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Count filtered results
$filtered_count = $result->num_rows;

// ────────────────────────────────────────────────
// Filter dropdown data
$products  = $conn->query("SELECT id, name FROM products ORDER BY name");
$venues    = $conn->query("SELECT id, name FROM venues ORDER BY name");
$employees = $conn->query("SELECT id, name FROM employees ORDER BY name");
?>

<?php
// ────────────────────────────────────────────────
// Product Summary (Product + Quantity)
$sql_summary = "
    SELECT p.name AS product_name, COUNT(i.id) AS quantity
    FROM inventory i
    LEFT JOIN products p ON i.product_id = p.id
";

if (!empty($where)) {
    $sql_summary .= " WHERE " . implode(" AND ", $where);
}

$sql_summary .= " GROUP BY i.product_id ORDER BY p.name ASC";

$stmt_summary = $conn->prepare($sql_summary);
if ($types) {
    $stmt_summary->bind_param($types, ...$params);
}
$stmt_summary->execute();
$summary_result = $stmt_summary->get_result();
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Navbar -->
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
                        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger" href="../../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Styles -->
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

/* Filter Select */
.filter-select {
    border-radius: 12px;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
}

/* Floating Action Button */
.fab-button {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 56px;
    height: 56px;
    border-radius: 28px;
    background: var(--primary-gradient);
    color: white;
    display: none;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    z-index: 1000;
    transition: all 0.3s ease;
    border: none;
}

.fab-button:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
}

@media (max-width: 768px) {
    .fab-button { display: flex; }
}

/* Responsive Table */
@media (max-width: 768px) {
    .table thead { display: none; }
    .table tbody tr { display: block; margin-bottom: 1rem; border-radius: 16px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
    .table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.9rem 1.2rem;
        border-bottom: 1px solid #f1f3f5;
    }
    .table tbody td:last-child { border-bottom: none; }
    .table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table tbody td:first-child { border-radius: 16px 16px 0 0; font-weight: 600; }
    .table tbody td:last-child { border-radius: 0 0 16px 16px; justify-content: center; }
}

/* Print Styles */
@media print {
    .no-print { display: none !important; }
    body { background: white !important; color: black !important; }
    .modern-card { box-shadow: none !important; background: white !important; border: 1px solid #ddd !important; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; }
    .badge { background: #eee !important; color: black !important; }
}

/* Animation */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeInUp 0.5s ease forwards; }
</style>

<!-- Content -->
<div class="container-fluid py-4 px-3 px-md-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 animate-fade-in">
        <div>
            <h1 class="display-6 fw-bold mb-1" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Inventory Items
            </h1>
            <p class="text-muted mb-0">
                <i class="bi bi-clipboard-data me-2"></i>Manage all stock items
            </p>
        </div>
    </div>

    <!-- Line 1: Search + Add Button -->
    <div class="modern-card p-3 p-md-4 mb-3 animate-fade-in" style="animation-delay: 0.1s">
        <form method="get" class="row g-3 align-items-end">
            <!-- Search -->
            <div class="col-12 col-md-8 col-lg-9">
                <label class="form-label small fw-semibold">Search</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 shadow-none" 
                           placeholder="Search by code or product name..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-lg"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add New Item Button -->
            <div class="col-12 col-md-4 col-lg-3 text-md-end">
                <a href="create.php" class="btn btn-lg px-4 py-3 shadow-sm d-block d-md-inline-block"
                   style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px;">
                    <i class="bi bi-plus-lg me-2"></i> Add New Item
                </a>
            </div>
        </form>
    </div>

    <!-- Line 2: Filters + Apply + Reset -->
    <div class="modern-card p-3 p-md-4 mb-4 animate-fade-in" style="animation-delay: 0.2s">
        <form method="get" class="row g-3 align-items-end">
            <!-- Product Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label small fw-semibold">Product</label>
                <select name="product[]" class="form-select filter-select" multiple size="1">
                    <option value="">All Products</option>
                    <?php while ($p = $products->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>" <?= in_array($p['id'], (array)$filter_product) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Venue Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label small fw-semibold">Venue</label>
                <select name="venue[]" class="form-select filter-select" multiple size="1">
                    <option value="">All Venues</option>
                    <?php while ($v = $venues->fetch_assoc()): ?>
                        <option value="<?= $v['id'] ?>" <?= in_array($v['id'], (array)$filter_venue) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Condition Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label small fw-semibold">Condition</label>
                <select name="condition[]" class="form-select filter-select" multiple size="1">
                    <option value="">All</option>
                    <option value="brandnew" <?= in_array('brandnew', (array)$filter_condition) ? 'selected' : '' ?>>Brand New</option>
                    <option value="used" <?= in_array('used', (array)$filter_condition) ? 'selected' : '' ?>>Used</option>
                    <option value="personal" <?= in_array('personal', (array)$filter_condition) ? 'selected' : '' ?>>Personal</option>
                </select>
            </div>

            <!-- Assigned Employee Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label small fw-semibold">Assigned Employee</label>
                <select name="employee" class="form-select filter-select">
                    <option value="all">All Employees</option>
                    <option value="unassigned" <?= $filter_employee === 'unassigned' ? 'selected' : '' ?>>Unassigned</option>
                    <?php while ($e = $employees->fetch_assoc()): ?>
                        <option value="<?= $e['id'] ?>" <?= $filter_employee == $e['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Apply & Reset Buttons -->
            <div class="col-12 col-md-auto d-flex gap-2 align-self-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-filter me-1"></i> Apply Filters
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-x-lg me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Inventory List -->
    <div class="modern-card overflow-hidden animate-fade-in" style="animation-delay: 0.3s">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-3 p-md-4 border-bottom">
            <div>
                <h5 class="fw-bold mb-1">Inventory List</h5>
                <p class="text-muted small mb-0">
                    Found <strong><?= number_format($filtered_count) ?></strong> item<?= $filtered_count == 1 ? '' : 's' ?>
                    <?php if ($filtered_count > 0): ?>
                        <?= $filtered_count == $result->num_rows ? '(all matching filters)' : '' ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-outline-primary btn-sm px-4" onclick="printInventory()">
                    <i class="bi bi-printer me-1"></i> Print List
                </button>
                <button class="btn btn-outline-success btn-sm px-4 ms-2" onclick="printSummary()">
                    <i class="bi bi-bar-chart me-1"></i> Print Summary
                </button>
            </div>
        </div>

        <?php if ($filtered_count === 0): ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">No inventory items found</h5>
                <p class="text-muted">Try adjusting your filters or search term</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Venue</th>
                            <th>Brand</th>
                            <th>Serial</th>
                            <th>Condition</th>
                            <th>Assigned To</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Code">
                                <span class="badge bg-secondary"><?= htmlspecialchars($row['code']) ?></span>
                            </td>
                            <td data-label="Product"><?= htmlspecialchars($row['product_name'] ?? '—') ?></td>
                            <td data-label="Venue"><?= htmlspecialchars($row['venue_name'] ?? '—') ?></td>
                            <td data-label="Brand"><?= htmlspecialchars($row['brand'] ?? '—') ?></td>
                            <td data-label="Serial"><?= htmlspecialchars($row['serial_number'] ?? '—') ?></td>
                            <td data-label="Condition">
                                <?php
                                $cond = $row['condition'];
                                $badge_class = $cond === 'brandnew' ? 'bg-success' : ($cond === 'used' ? 'bg-warning' : 'bg-info');
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= ucfirst($cond) ?></span>
                            </td>
                            <td data-label="Assigned To">
                                <?= $row['employee_name'] ? htmlspecialchars($row['employee_name']) : '<span class="text-muted">Unassigned</span>' ?>
                            </td>
                            <td data-label="Actions" class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if (!$row['assigned_to']): ?>
                                        <a href="assign.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-person-plus"></i> Assign
                                        </a>
                                    <?php endif; ?>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Delete this item?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile FAB -->
    <a href="create.php" class="fab-button text-decoration-none">
        <i class="bi bi-plus-lg fs-4"></i>
    </a>

</div>

<!-- Print-friendly version (hidden) -->
<div id="printArea" class="d-none">
    <style>
        @media print {
            body { font-family: Arial, sans-serif; margin: 20px; }
            h2 { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .badge { background: #eee; color: black; padding: 4px 8px; border-radius: 4px; }
        }
    </style>
    <h2>Inventory List - <?= date('d M Y') ?></h2>
    <p>Found <?= number_format($filtered_count) ?> item<?= $filtered_count == 1 ? '' : 's' ?> (This is System Generated Report)</p>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Venue</th>
                <th>Brand</th>
                <th>Serial</th>
                <th>Condition</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Reset result pointer
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= htmlspecialchars($row['product_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($row['venue_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($row['brand'] ?? '—') ?></td>
                <td><?= htmlspecialchars($row['serial_number'] ?? '—') ?></td>
                <td><?= ucfirst($row['condition']) ?></td>
                <td><?= $row['employee_name'] ? htmlspecialchars($row['employee_name']) : 'Unassigned' ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Product Summary Print -->
<div id="printSummaryArea" class="d-none">
    <style>
        @media print {
            body { font-family: Arial, sans-serif; margin: 20px; }
            h2 { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        }
    </style>

    <h2>Product Summary - <?= date('d M Y') ?></h2>
    <p>This is System Generated Report</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $summary_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name'] ?? '—') ?></td>
                <td><?= $row['quantity'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript for Print -->
<script>
function printInventory() {
    const printContent = document.getElementById('printArea').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head><title>Inventory List</title></head>
        <body onload="window.print();window.close()">${printContent}</body>
        </html>
    `);
    printWindow.document.close();
}

function printSummary() {
    const printContent = document.getElementById('printSummaryArea').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head><title>Product Summary</title></head>
        <body onload="window.print();window.close()">${printContent}</body>
        </html>
    `);
    printWindow.document.close();
}
</script>

<?php 
$stmt->close();
require_once '../../includes/footer.php'; 
?>