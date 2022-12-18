<?php

use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once "database/ChatUser.php";
session_start();
$error = '';
if (isset($_SESSION['user_data'])) {
    header('location: chatroom.php');
}
$user = new ChatUser();

if (isset($_POST['forgot']) && isset($_GET['code'])) {
    if (strlen($_POST['user_password']) < 8) {
        $message_pass = 'Mật khẩu mới không hợp lệ';
    } else {
        $user->setUserVerificationCode($_GET['code']);
        $user_data = $user->getUserDataByVerificationCode();
        $user->setUserId($user_data['user_id']);
        $user->setUserName($user_data['user_name']);
        $user->setUserEmail($user_data['user_email']);
        $user->setUserPassword(password_hash($_POST['user_password'], PASSWORD_DEFAULT));
        $user->setUserProfile($user_data['user_profile']);
        if ($user->updateData()) {
            $_SESSION['success_message'] = 'Đã đặt lại mật khẩu thành công.';
            header('location: index.php');
        } else {
            $error = 'Có lỗi xảy ra, vui lòng thử lại';
        }
    }
}

if (isset($_POST['forgot'])) {
    if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } else {
        $user->setUserEmail($_POST['user_email']);
        $user_data = $user->getUserDataByEmail();
        if (!is_array($user_data) || count($user_data) == 0) {
            $error = 'Email không tồn tại';
        } else if ($user_data['user_status'] != 'Enable') {
            $error = 'Tài khoản chưa được kích hoạt';
        } else {
            $user->setUserVerificationCode(md5(uniqid()) . time());
            if ($user->updateVerificationCode()) {
                $title = 'Doi mat khau - Chat App';
                $verify_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/"
                    . "forgot.php?code=" . $user->getUserVerificationCode();
                $body = '
                <p>Có phải bạn đã gửi yêu cầu đổi mật khẩu không?</p>
                
                <p>Nếu bạn muốn đổi mật khẩu thì: </p> <a href="' . $verify_link . '">
                Nhấn vào đây!
                </a>
                
                <p>Cảm ơn! – Nhóm 8</p>';
                try {
                    $_SESSION['success_message'] = 'Kiểm tra mail ' . $_POST['user_email'] . ' để đặt lại mật khẩu.';
                    $user->sendMail($_POST['user_email'], $title, $body);
                    header('location: index.php');
                } catch (Exception $e) {
                    $error = 'Có lỗi xảy ra khi gửi xác thực đến mail của bạn, vui lòng liên hệ với ban quản trị';
                    $error = $error . '<br> ' . $e->errorMessage();
                }
            } else {
                $error = 'Có lỗi xảy ra vui lòng thử lại';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quên mật khẩu</title>

    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="author" content="Webestica.com"/>
    <meta
            name="description"
            content="Bootstrap 5 based Social Media Network and Community Theme"
    />

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico"/>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
    />

    <!-- Plugins CSS -->
    <link
            rel="stylesheet"
            type="text/css"
            href="assets/vendor/font-awesome/css/all.min.css"
    />
    <link
            rel="stylesheet"
            type="text/css"
            href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
    />

    <!-- Theme CSS -->
    <link
            id="style-switch"
            rel="stylesheet"
            type="text/css"
            href="assets/css/style.css"
    />
</head>

<body>
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

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="assets/vendor/pswmeter/pswmeter.min.js"></script>

<!-- Template Functions -->
<script src="assets/js/functions.js"></script>
</body>
</html>
