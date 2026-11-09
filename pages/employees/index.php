<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';
require_once '../../includes/header.php';

// ────────────────────────────────────────────────
// Search & Employees Data
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM employees";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " WHERE name LIKE ? OR email LIKE ?";
    $like = "%$search%";
    $params = [$like, $like];
    $types = "ss";
}

$sql .= " ORDER BY name ASC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2 active" href="index.php">
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
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" href="../inventory/index.php">
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

<!-- All Styles -->
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
    .desktop-add-button { display: none; }
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
                Employees
            </h1>
            <p class="text-muted mb-0">
                <i class="bi bi-people-fill me-2"></i>Manage team members
            </p>
        </div>
    </div>

    <!-- Search + Add Button (now near table) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 animate-fade-in" style="animation-delay: 0.1s">
        <form method="get" class="w-100">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 shadow-none" 
                       placeholder="Search name or email..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <?php if ($search): ?>
                    <a href="index.php" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg"></i> Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Add Employee Button - now placed here, just above table -->
        <a href="create.php" class="btn btn-lg px-4 py-3 shadow-sm" 
           style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px; white-space: nowrap;">
            <i class="bi bi-plus-lg me-2"></i> Add Employee
        </a>
    </div>

    <!-- Employees List -->
    <div class="modern-card overflow-hidden animate-fade-in" style="animation-delay: 0.2s">
        <?php if ($result->num_rows === 0): ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-people fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">No employees found</h5>
                <?php if ($search): ?>
                    <p class="text-muted">No results for "<?= htmlspecialchars($search) ?>"</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Name">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon primary me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="fw-medium"><?= htmlspecialchars($row['name']) ?></div>
                                </div>
                            </td>
                            <td data-label="Email"><?= htmlspecialchars($row['email'] ?? '—') ?></td>
                            <td data-label="Phone"><?= htmlspecialchars($row['phone'] ?? '—') ?></td>
                            <td data-label="Actions" class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="edit.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Delete this employee?')">
                                        <i class="bi bi-trash"></i> Delete
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

<?php 
$stmt->close();
require_once '../../includes/footer.php'; 
?>