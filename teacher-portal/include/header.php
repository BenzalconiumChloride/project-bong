<?php
$firstname = ''; 

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT firstname FROM bs_user WHERE user_id = ? AND is_deleted = 0");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $firstname = $user['firstname'];
    }
}
?>
<div class="topbar d-flex align-items-center">
	<nav class="navbar navbar-expand gap-3">
		<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
		</div>
		<div class="search-bar flex-grow-1">
			<div class="position-relative search-bar-box d-none">
				<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
				<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
			</div>
		</div>
		<div class="top-menu ms-auto">
			<ul class="navbar-nav align-items-center gap-1 d-none">
				<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
					<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
					</a>
				</li>
				<li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
					<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="assets/images/county/02.png" width="22" alt="">
					</a>
					<ul class="dropdown-menu dropdown-menu-end ">
						
					</ul>
				</li>

				<li class="nav-item dropdown dropdown-app">
					<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
					<div class="dropdown-menu dropdown-menu-end p-0">
						<div class="app-container p-2 my-2">
							<div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
								
							</div><!--end row-->

						</div>
					</div>
				</li>

				<li class="nav-item dropdown dropdown-large">
					
					<div class="dropdown-menu dropdown-menu-end">
						
						<div class="header-notifications-list">
							
						</div>
						<a href="javascript:;">
							<div class="text-center msg-footer">
								<button class="btn btn-light w-100">View All Notifications</button>
							</div>
						</a>
					</div>
				</li>
				<li class="nav-item dropdown dropdown-large">
					<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
						<i class='bx bx-shopping-bag'></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						
						<div class="header-message-list">
							
						</div>
						
					</div>
				</li>
			</ul>
		</div>
		<div class="user-box dropdown px-3">
			<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">
				<div class="user-info">
					<p class="user-name mb-0"><?=  $firstname ?></p>
				</div>
			</a>
			<ul class="dropdown-menu dropdown-menu-end">
				
				<li>
					<div class="dropdown-divider mb-0"></div>
				</li>
				<li><a class="dropdown-item d-flex align-items-center" href="<?= WEB_ROOT; ?>?logout "><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
				</li
			</ul>
		</div>
	</nav>
</div>