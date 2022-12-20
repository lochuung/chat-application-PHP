<?php
$title = 'Đăng nhập';
session_start();
require_once "bin/auth_function.php";
if (isset($_SESSION["user_data"])) {
    header('location: chatroom.php');
}
$error = '';
LoginHandle();
?>

<?php include_once "part/header.php"?>
<body>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- Container START -->
    <div class="container">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '
          <div class="alert alert-success text-center" role="alert">
            <div>' . $_SESSION['success_message'] . '</div>
          </div>';
            unset($_SESSION['success_message']);
        }
        ?>
        <div class="row justify-content-center align-items-center vh-100 py-5">
            <!-- Main content START -->
            <div class="col-sm-10 col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <!-- Sign in START -->
                <div class="card card-body text-center p-4 p-sm-5">
                    <!-- Title -->
                    <h1 class="mb-2">Đăng nhập</h1>
                    <p class="mb-0">
                        Không có tài khoản?<a href="register.php">
                            Nhấn vào đây để đăng ký</a>
                    </p>
                    <?php
                    if ($error != '') {
                        echo '<div class="alert alert-danger text-center" role="alert">
                    ' . $error . '
                </div>';
                    }
                    ?>
                    <!-- Form START -->
                    <form method="post" class="mt-sm-4">
                        <!-- Email -->
                        <div class="mb-3 input-group-lg">
                            <input type="email" name="user_email" value="<?php
                            echo $_POST['user_email'] ?? '';
                            ?>" class="form-control" placeholder="Nhập email"/>
                        </div>
                        <!-- New password -->
                        <div class="mb-3 position-relative">
                            <!-- Password -->
                            <div class="input-group input-group-lg">
                                <input class="form-control fakepassword" type="password" name="user_password"
                                       id="psw-input" placeholder="Nhập mật khẩu"/>
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
                        <!-- Remember me -->
                        <div class="mb-3 d-sm-flex justify-content-between">
                            <a href="forgot.php">Quên mật khẩu?</a>
                        </div>
                        <!-- Button -->
                        <div class="d-grid">
                            <button name="login" type="submit" class="btn btn-lg btn-primary">
                                Đăng nhập
                            </button>
                        </div>
                        <!-- Copyright -->
                        <p class="mb-0 mt-3">
                            ©Project cuối kỳ nhóm 8
                        </p>
                    </form>
                    <!-- Form END -->
                </div>
                <!-- Sign in START -->
            </div>
        </div>
        <!-- Row END -->
    </div>
    <!-- Container END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->

<?php include_once "part/footer.php" ?>