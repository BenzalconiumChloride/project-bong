<nav class="navbar navbar-expand-lg glass-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= WEB_ROOT; ?>">
            <div class="logo-box me-2">
                <i class="bi bi-stars text-white"></i>
            </div>
            <span class="fw-bold fs-5 gradient-text">BONG AI</span>
        </a>

        <div class="d-none d-lg-block">
            <ul class="navbar-nav d-flex align-items-center flex-row">
                <li class="nav-item"><a class="nav-link" href="#dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#portals">Learning Hub</a></li>
                <li class="nav-separator"></li>
                <li class="nav-item dropdown">
                    <a class="nav-link profile-pill d-flex align-items-center gap-2 dropdown-toggle" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span>Account</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end glass-dropdown mt-2">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-2"></i> Edit Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="mobile-bottom-nav d-lg-none">
    <div class="d-flex justify-content-around align-items-center h-100">
        <a href="#dashboard" class="nav-tab-item active">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="#portals" class="nav-tab-item">
            <i class="bi bi-mortarboard-fill"></i>
            <span>Learning</span>
        </a>
        
        
            <a href="#" class="nav-tab-item" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end glass-dropdown">
                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-2"></i> Edit Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
    </div>
</div>