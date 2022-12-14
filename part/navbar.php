<?php
foreach ($_SESSION['user_data'] as $key => $value) {
    $session_data = $value;
}
?>

<!-- =======================
Header START -->
<header class="navbar-light fixed-top header-static bg-mode">
    <!-- Logo Nav START -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo START -->
            <a class="navbar-brand" href="chatroom.php">
                <img
                        class="light-mode-item navbar-brand-item"
                        src="images/group8.png"
                        alt="logo"
                />
                <img
                        class="dark-mode-item navbar-brand-item"
                        src="images/group8.png"
                        alt="logo"
                />
            </a>
            <!-- Logo END -->
            <!-- Main navbar START -->
            <div class="collapse navbar-collapse" id="navbarCollapse">

                <ul class="navbar-nav navbar-nav-scroll ms-auto">
                    <!-- Nav item 4 Mega menu -->
                    <li class="nav-item">
                        <!-- Dark mode switch START -->
                        <div class="nav-link modeswitch-wrap" id="darkModeSwitch">
                            <div class="modeswitch-item">
                                <div class="modeswitch-icon"></div>
                            </div>
                            <span>Dark mode</span>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- Main navbar END -->

            <!-- Nav right START -->
            <ul class="nav flex-nowrap align-items-center ms-sm-3 list-unstyled">
                <li class="nav-item ms-2 dropdown">
                    <a class="nav-link btn icon-md p-0" href="#" id="profileDropdown" role="button"
                       data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <img class="avatar-img rounded-2" src="<?php echo $session_data['profile'] ?>" alt=""/>
                    </a>
                    <ul class="dropdown-menu dropdown-animation dropdown-menu-end pt-3 small me-md-n3"
                        aria-labelledby="profileDropdown">
                        <!-- Profile info -->
                        <li class="px-3">
                            <div class="d-flex align-items-center position-relative">
                                <!-- Avatar -->
                                <div class="avatar me-3">
                                    <img class="avatar-img rounded-circle" src="<?php echo $session_data['profile'] ?>"
                                         alt="avatar"/>
                                    <input type="hidden" id="login_user_profile" value="<?php echo $session_data['profile'] ?>">
                                </div>
                                <div>
                                    <a class="h6 stretched-link" href="#"><?php echo $session_data['name'] ?></a>
                                    <input type="hidden" id="login_user_name" value="<?php echo $session_data['name'] ?>">
                                </div>
                            </div>
                            <a class="dropdown-item btn btn-primary-soft btn-sm my-2 text-center" href="profile.php">Sửa
                                hồ sơ</a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a id="logout" style="cursor: pointer" class="dropdown-item bg-danger-soft-hover"><i
                                        class="bi bi-power fa-fw me-2"></i>Đăng xuất</a>
                            <input type="hidden" id="login_user_id" value="<?php echo $session_data['id']; ?>">
                        </li>
                        <li>
                            <hr class="dropdown-divider"/>
                        </li>
                    </ul>
                </li>
                <!-- Profile START -->
            </ul>
            <!-- Nav right END -->
        </div>
    </nav>
    <!-- Logo Nav END -->
</header>
<!-- =======================
Header END -->