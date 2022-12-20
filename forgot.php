<?php
$title = 'Quên mật khẩu';
require_once "bin/auth_function.php";
session_start();
$error = '';
if (isset($_SESSION['user_data'])) {
    header('location: chatroom.php');
}
$user = null;
sendResetPasswordCodeHandle();
editPasswordInForgotHandle();
?>
<?php include_once "part/header.php" ?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- Container START -->
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100 py-5">
            <!-- Main content START -->
            <div class="col-sm-10 col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <!-- Forgot password START -->
                <div class="card card-body rounded-3 text-center p-4 p-sm-5">
                    <!-- Title -->
                    <h1 class="mb-2">Quên mật khẩu?</h1>
                    <p>Nhập địa chỉ email được liên kết với tài khoản.</p>
                    <?php
                    if ($error != '') {
                        echo '<div class="alert alert-danger text-center" role="alert">
                    ' . $error . '
                </div>';
                    }
                    ?>
                    <!-- form START -->
                    <form method="post" class="mt-3">
                        <?php
                        if (isset($_GET['code'])) {
                            $user->setUserVerificationCode($_GET['code']);
                            if ($user->isExistVerificationCode()) { ?>
                                <!-- New password -->
                                <div class="mb-3">
                                    <!-- Input group -->
                                    <div class="input-group input-group-lg">
                                        <input
                                                class="form-control fakepassword"
                                                type="password"
                                                name="user_password"
                                                id="psw-input"
                                                placeholder="Nhập mật khẩu mới"
                                        />
                                        <span class="input-group-text p-0">
                                          <i
                                                  class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"
                                          ></i>
                                        </span>
                                    </div>
                                    <!-- Pswmeter -->
                                    <div id="pswmeter" class="mt-2"></div>
                                    <div class="d-flex mt-1">
                                        <div id="pswmeter-message" class="rounded"></div>
                                        <!-- Password message notification -->
                                        <div class="ms-auto">
                                            <i
                                                    class="bi bi-info-circle ps-1"
                                                    data-bs-container="body"
                                                    data-bs-toggle="popover"
                                                    data-bs-placement="top"
                                                    data-bs-content="Bao gồm ít nhất một chữ hoa, một chữ thường, một ký tự đặc biệt, một số và dài 8 ký tự."
                                                    data-bs-original-title=""
                                                    title=""
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                header('location: forgot.php');
                            }
                        } else {
                            ?>
                            <!-- Email -->
                            <div class="mb-3 input-group-lg">
                                <input type="email" name="user_email" value="<?php
                                echo $_POST['user_email'] ?? '';
                                ?>" class="form-control" placeholder="Nhập email" required/>
                            </div>
                            <?php
                        }
                        ?>
                        <!-- Back to sign in -->
                        <div class="mb-3">
                            <p>Quay lại <a href="index.php">Đăng nhập</a></p>
                        </div>
                        <!-- Button -->
                        <div class="d-grid">
                            <button type="submit" name="forgot" class="btn btn-lg btn-primary">
                                Đặt lại mật khẩu
                            </button>
                        </div>
                        <!-- Copyright -->
                        <p class="mb-0 mt-3">
                            ©Project cuối kỳ nhóm 8
                        </p>
                    </form>
                    <!-- form END -->
                </div>
                <!-- Forgot password END -->
            </div>
        </div>
        <!-- Row END -->
    </div>
    <!-- Container END -->
</main>

<!-- **************** MAIN CONTENT END **************** -->

<?php include_once "part/footer.php" ?>
