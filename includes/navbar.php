<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background: linear-gradient(135deg, #1e1e2f 0%, #2a2a40 100%); backdrop-filter: blur(10px); box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
    <div class="container-fluid px-3 px-md-4">
        <!-- Brand with icon -->
        <a class="navbar-brand d-flex align-items-center" href="../dashboard.php">
            <div class="brand-icon me-2 d-flex align-items-center justify-content-center" 
                 style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                <i class="bi bi-box-seam text-white"></i>
            </div>
            <span class="fw-bold" style="font-size: 1.3rem;">CSBM<span style="color: #a78bfa;"> Inventory</span></span>
        </a>

        <!-- Mobile Toggle Button with animation -->
        <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                style="background: rgba(255,255,255,0.1); border-radius: 12px;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" 
                       href="dashboard.php"
                       style="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'background: rgba(255,255,255,0.15); color: white;' : 'color: rgba(255,255,255,0.8);' ?> 
                              transition: all 0.3s ease;">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" 
                       href="employees/index.php"
                       style="<?= strpos($_SERVER['PHP_SELF'], 'employees') !== false ? 'background: rgba(255,255,255,0.15); color: white;' : 'color: rgba(255,255,255,0.8);' ?>
                              transition: all 0.3s ease;">
                        <i class="bi bi-people"></i>
                        <span>Employees</span>
                    </a>
                </li>

                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" 
                       href="products/index.php"
                       style="<?= strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'background: rgba(255,255,255,0.15); color: white;' : 'color: rgba(255,255,255,0.8);' ?>
                              transition: all 0.3s ease;">
                        <i class="bi bi-box"></i>
                        <span>Products</span>
                    </a>
                </li>

                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" 
                       href="venues/index.php"
                       style="<?= strpos($_SERVER['PHP_SELF'], 'venues') !== false ? 'background: rgba(255,255,255,0.15); color: white;' : 'color: rgba(255,255,255,0.8);' ?>
                              transition: all 0.3s ease;">
                        <i class="bi bi-building"></i>
                        <span>Venues</span>
                    </a>
                </li>

                <li class="nav-item mx-lg-1">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center gap-2" 
                       href="inventory/index.php"
                       style="<?= strpos($_SERVER['PHP_SELF'], 'inventory') !== false ? 'background: rgba(255,255,255,0.15); color: white;' : 'color: rgba(255,255,255,0.8);' ?>
                              transition: all 0.3s ease;">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Inventory</span>
                    </a>
                </li>

                <!-- User Menu Dropdown -->
                <li class="nav-item dropdown mx-lg-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-3" 
                       href="#" 
                       id="userDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px;">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-lg-inline"><?= $_SESSION['username'] ?? 'User' ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow-lg" 
                        style="border-radius: 16px; background: white; min-width: 220px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="../profile.php">
                                <i class="bi bi-person text-primary"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="../settings.php">
                                <i class="bi bi-gear text-secondary"></i>
                                Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Navbar hover effects */
.navbar-nav .nav-item .nav-link:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    transform: translateY(-2px);
}

/* Dropdown styles */
.dropdown-menu {
    animation: dropdownFade 0.3s ease;
    border-radius: 16px !important;
}

.dropdown-item {
    transition: all 0.3s ease;
    border-radius: 10px;
    margin: 4px 8px;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    transform: translateX(5px);
}

/* Mobile responsive */
@media (max-width: 991px) {
    .navbar-collapse {
        background: rgba(30, 30, 47, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .navbar-nav .nav-item {
        margin: 0.3rem 0;
    }
    
    .navbar-nav .nav-item .nav-link {
        padding: 0.8rem 1rem !important;
        border-radius: 12px !important;
    }
    
    .dropdown-menu {
        background: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        margin-top: 0.5rem !important;
    }
    
    .dropdown-item {
        color: white !important;
    }
    
    .dropdown-item:hover {
        background: rgba(255,255,255,0.1) !important;
        color: white !important;
    }
    
    .dropdown-divider {
        background-color: rgba(255,255,255,0.1);
    }
}

/* Animations */
@keyframes dropdownFade {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Active link indicator */
.nav-item .nav-link.active-indicator {
    position: relative;
}

.nav-item .nav-link.active-indicator::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 3px 3px 0 0;
}

/* Brand icon animation */
.brand-icon {
    transition: all 0.3s ease;
}

.navbar-brand:hover .brand-icon {
    transform: rotate(5deg) scale(1.1);
}
</style>

<!-- Add Bootstrap Icons if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">