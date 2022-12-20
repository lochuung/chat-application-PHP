<?php
$title = 'Đăng ký';
session_start();
require_once "bin/auth_function.php";
if (isset($_SESSION['user_data'])) {
    header('location:chatroom.php');
}
$error = '';
$success_message = '';
RegisterHandle();
?>

<?php include_once "part/header.php"?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- Container START -->
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100 py-5">
            <!-- Main content START -->
            <div class="col-sm-10 col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <!-- Sign up START -->
                <div class="card card-body rounded-3 p-4 p-sm-5">
                    <div class="text-center">
                        <!-- Title -->
                        <h1 class="mb-2">Đăng ký</h1>
                        <span class="d-block">Đã có tài khoản?
                                <a href="index.php">Đăng nhập ở đây</a></span>
                    </div>
                    <?php
                    if ($error != '') {
                        echo '<div class="alert alert-danger text-center" role="alert">
                        ' . $error . '
                    </div>';
                    }

                    if ($success_message != '') {
                        echo '<div class="alert alert-success text-center" role="alert">
                        ' . $success_message . '
                    </div>';
                    }
                    ?>
                    <!-- Form START -->
                    <form method="post" id="register_form" class="mt-4">
                        <!-- Name -->
                        <div class="mb-3 input-group-lg">
                            <input type="text" name="user_name" id="user_name" class="form-control"
                                   placeholder="Nhập tên" required>
                        </div>
                        <!-- Email -->
                        <div class="mb-3 input-group-lg">
                            <input type="email" name="user_email" id="user_email" class="form-control"
                                   placeholder="Nhập email" required/>
                            <small>Chúng tôi sẽ không bao giờ chia sẻ email của bạn với bất kỳ ai khác.</small>
                        </div>
                        <!-- New password -->
                        <div class="mb-3 position-relative">
                            <!-- Input group -->
                            <div class="input-group input-group-lg">
                                <input class="form-control fakepassword" type="password" name="user_password"
                                       id="psw-input" placeholder="Nhập mật khẩu" minlength="8" required/>
                                <span class="input-group-text p-0">
                                        <i class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                            </div>
                            <!-- Pswmeter -->
                            <div id="pswmeter" class="mt-2"></div>
                            <div class="d-flex mt-1">
                                <div id="pswmeter-message" class="rounded"></div>
                                <!-- Password message notification -->
                                <div class="ms-auto">
                                    <i class="bi bi-info-circle ps-1" data-bs-container="body" data-bs-toggle="popover"
                                       data-bs-placement="top"
                                       data-bs-content="Bao gồm ít nhất một chữ hoa, một chữ thường, một ký tự đặc biệt, một số và dài 8 ký tự."
                                       data-bs-original-title="" title=""></i>
                                </div>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="d-grid">
                            <button type="submit" name="register" class="btn btn-lg btn-primary">
                                Đăng ký
                            </button>
                        </div>
                        <!-- Copyright -->
                        <p class="mb-0 mt-3 text-center">
                            ©Project cuối kỳ nhóm 8.
                        </p>
                    </form>
                    <!-- Form END -->
                </div>
                <!-- Sign up END -->
            </div>
        </div>
        <!-- Row END -->
    </div>
    <!-- Container END -->
</main>

<!-- **************** MAIN CONTENT END **************** -->


<?php include_once "part/footer.php" ?>