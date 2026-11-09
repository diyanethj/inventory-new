<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';
require_once '../../includes/header.php';

// ────────────────────────────────────────────────
// Get Item Data
$id = (int)($_GET['id'] ?? 0);
$item = $conn->query("
    SELECT i.code, p.name AS product_name 
    FROM inventory i 
    JOIN products p ON i.product_id = p.id 
    WHERE i.id = $id
")->fetch_assoc();

if (!$item) {
    echo '<div class="alert alert-danger text-center py-5 m-4 animate-fade-in">Item not found.</div>';
    require_once '../../includes/footer.php';
    exit;
}

// Load employees
$employees = $conn->query("SELECT id, name FROM employees ORDER BY name");

// ────────────────────────────────────────────────
// Handle Form Submission
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = (int)$_POST['employee_id'];

    // Check if already assigned
    $check = $conn->query("SELECT assigned_to FROM inventory WHERE id = $id")->fetch_assoc();
    if ($check['assigned_to'] !== null) {
        $error = "This item is already assigned to someone.";
    } else {
        $stmt = $conn->prepare("UPDATE inventory SET assigned_to = ?, assigned_at = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $employee_id, $id);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Error assigning item: " . $conn->error;
        }
        $stmt->close();
    }
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

.form-select {
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
}

.form-select:focus {
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

<!-- Content -->
<div class="container-fluid py-4 px-3 px-md-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 animate-fade-in">
        <div>
            <h1 class="display-6 fw-bold mb-1" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Assign Item
            </h1>
            <p class="text-muted mb-0">
                <i class="bi bi-person-plus me-2"></i>Assign <?= htmlspecialchars($item['code']) ?> (<?= htmlspecialchars($item['product_name']) ?>)
            </p>
        </div>
    </div>

    <!-- Assign Form Card -->
    <div class="modern-card p-4 p-md-5 animate-fade-in" style="animation-delay: 0.2s">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> Item assigned successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select form-select-lg" required>
                        <option value="">— Choose an employee —</option>
                        <?php while ($e = $employees->fetch_assoc()): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= htmlspecialchars($e['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="form-text text-muted mt-1">
                        Only unassigned items can be assigned.
                    </div>
                </div>

                <div class="col-12 mt-5">
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <button type="submit" class="btn btn-lg px-5 flex-grow-1" 
                                style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px;">
                            <i class="bi bi-person-check me-2"></i> Assign Item
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