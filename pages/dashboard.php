<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/header.php';

// ────────────────────────────────────────────────
// Totals
$total_items = $conn->query("SELECT COUNT(*) as cnt FROM inventory")->fetch_assoc()['cnt'] ?? 0;
$total_products = $conn->query("SELECT COUNT(*) as cnt FROM products")->fetch_assoc()['cnt'] ?? 0;
$assigned_count = $conn->query("SELECT COUNT(*) as cnt FROM inventory WHERE assigned_to IS NOT NULL")->fetch_assoc()['cnt'] ?? 0;
$available_count = $total_items - $assigned_count;

// ────────────────────────────────────────────────
// All products with quantities + condition breakdown
$query = "
    SELECT 
        p.id,
        p.name,
        p.code,
        COUNT(i.id) AS total_qty,
        SUM(CASE WHEN i.condition = 'brandnew' THEN 1 ELSE 0 END) AS brandnew,
        SUM(CASE WHEN i.condition = 'used'     THEN 1 ELSE 0 END) AS used,
        SUM(CASE WHEN i.condition = 'personal' THEN 1 ELSE 0 END) AS personal
    FROM products p
    LEFT JOIN inventory i ON p.id = i.product_id
    GROUP BY p.id, p.name, p.code
    ORDER BY total_qty DESC, p.name ASC
";
$products_result = $conn->query($query);
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Navbar (same as other pages) -->
<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background: linear-gradient(135deg, #1e1e2f 0%, #2a2a40 100%); backdrop-filter: blur(10px); box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
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
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2 active" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="employees/index.php">
                        <i class="bi bi-people"></i> Employees
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="products/index.php">
                        <i class="bi bi-box"></i> Products
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="venues/index.php">
                        <i class="bi bi-building"></i> Venues
                    </a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="inventory/index.php">
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
                        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- All Styles (same as other pages) -->
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --info-gradient: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
    --warning-gradient: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%);
}

/* Modern Card Styles */
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

/* Stat Card Styles */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem 1rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.02);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover {
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.stat-icon.primary { background: var(--primary-gradient); color: white; }
.stat-icon.success { background: var(--success-gradient); color: #2c7a4d; }
.stat-icon.info { background: var(--info-gradient); color: #2c6b9e; }
.stat-icon.warning { background: var(--warning-gradient); color: #b45f2e; }

/* Table Styles */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.modern-table thead th {
    background: transparent;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.5rem;
    border: none;
}

.modern-table tbody tr {
    background: white;
    border-radius: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}

.modern-table tbody tr:hover {
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transform: scale(1.01);
}

.modern-table tbody td {
    padding: 1.2rem 1.5rem;
    border: none;
    vertical-align: middle;
}

.modern-table tbody td:first-child { border-radius: 16px 0 0 16px; }
.modern-table tbody td:last-child { border-radius: 0 16px 16px 0; }

/* Badge Styles */
.condition-badge {
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-weight: 500;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    white-space: nowrap;
}

.condition-badge i { font-size: 1rem; }

.condition-badge.brandnew { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; }
.condition-badge.used { background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); color: #856404; }
.condition-badge.personal { background: linear-gradient(135deg, #d1ecf1 0%, #b8daff 100%); color: #0c5460; }

/* Product Code Badge */
.product-code {
    background: #f3f4f6;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.85rem;
    font-family: 'Courier New', monospace;
    color: #4b5563;
    display: inline-block;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .stat-card { padding: 1.2rem 0.8rem; }
    .modern-table thead { display: none; }
    .modern-table tbody tr { display: block; margin-bottom: 1rem; border-radius: 16px; }
    .modern-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .modern-table tbody td:last-child { border-bottom: none; }
    .modern-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .modern-table tbody td:first-child { border-radius: 16px 16px 0 0; }
    .modern-table tbody td:last-child { border-radius: 0 0 16px 16px; }
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

/* Animation */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
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
                Dashboard
            </h1>
            <p class="text-muted mb-0">
                <i class="bi bi-calendar3 me-2"></i><?= date('l, d F Y') ?>
            </p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 g-md-4 mb-5">
        <div class="col-6 col-md-6 col-lg-3 animate-fade-in" style="animation-delay: 0.1s">
            <div class="stat-card">
                <div class="stat-icon primary"><i class="bi bi-box-seam"></i></div>
                <h6 class="text-muted mb-2 small text-uppercase">Total Items</h6>
                <h2 class="fw-bold mb-0"><?= number_format($total_items) ?></h2>
            </div>
        </div>

        <div class="col-6 col-md-6 col-lg-3 animate-fade-in" style="animation-delay: 0.2s">
            <div class="stat-card">
                <div class="stat-icon success"><i class="bi bi-tags"></i></div>
                <h6 class="text-muted mb-2 small text-uppercase">Product Types</h6>
                <h2 class="fw-bold mb-0"><?= number_format($total_products) ?></h2>
            </div>
        </div>

        <div class="col-6 col-md-6 col-lg-3 animate-fade-in" style="animation-delay: 0.3s">
            <div class="stat-card">
                <div class="stat-icon info"><i class="bi bi-person-check"></i></div>
                <h6 class="text-muted mb-2 small text-uppercase">Assigned</h6>
                <h2 class="fw-bold mb-0"><?= number_format($assigned_count) ?></h2>
            </div>
        </div>

        <div class="col-6 col-md-6 col-lg-3 animate-fade-in" style="animation-delay: 0.4s">
            <div class="stat-card">
                <div class="stat-icon warning"><i class="bi bi-boxes"></i></div>
                <h6 class="text-muted mb-2 small text-uppercase">Available</h6>
                <h2 class="fw-bold mb-0"><?= number_format($available_count) ?></h2>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="modern-card p-3 p-md-4 animate-fade-in" style="animation-delay: 0.5s">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h5 class="fw-bold mb-1">Items by Product</h5>
                <p class="text-muted small mb-0">
                    <i class="bi bi-sort-down me-1"></i>Sorted by quantity (highest → lowest)
                </p>
            </div>
            <button class="btn btn-light btn-sm rounded-pill px-3" onclick="window.location.reload()">
                <i class="bi bi-arrow-repeat me-1"></i> Refresh
            </button>
        </div>

        <?php if ($products_result->num_rows === 0): ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <div class="stat-icon primary mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                </div>
                <h5 class="text-muted mb-3">No items in inventory yet</h5>
                <a href="../inventory/create.php" class="btn btn-lg px-5 py-3 mt-3"
                   style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px;">
                    <i class="bi bi-plus-lg me-2"></i> Add your first item
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Condition Breakdown</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Product">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon primary me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        <i class="bi bi-box"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($row['name']) ?></div>
                                        <small class="text-muted">ID: #<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Code" class="text-center">
                                <span class="product-code">
                                    <?= htmlspecialchars($row['code']) ?>
                                </span>
                            </td>
                            <td data-label="Total" class="text-center">
                                <span class="fw-bold fs-5"><?= number_format($row['total_qty']) ?: '0' ?></span>
                            </td>
                            <td data-label="Condition">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <?php if ($row['brandnew'] > 0): ?>
                                        <span class="condition-badge brandnew">
                                            <i class="bi bi-star"></i> New: <?= $row['brandnew'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['used'] > 0): ?>
                                        <span class="condition-badge used">
                                            <i class="bi bi-arrow-repeat"></i> Used: <?= $row['used'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['personal'] > 0): ?>
                                        <span class="condition-badge personal">
                                            <i class="bi bi-person"></i> Personal: <?= $row['personal'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['brandnew'] == 0 && $row['used'] == 0 && $row['personal'] == 0): ?>
                                        <span class="text-muted small">— No items —</span>
                                    <?php endif; ?>
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
    <a href="../inventory/create.php" class="fab-button text-decoration-none">
        <i class="bi bi-plus-lg fs-4"></i>
    </a>

</div>

<?php require_once '../includes/footer.php'; ?>