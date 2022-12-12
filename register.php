<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();
if (isset($_SESSION['user_data'])) {
    header('location:chatroom.php');
}

$error = '';
$success_message = '';
if (isset($_POST['register'])) {
    if (!isset($_POST['user_name']) || strlen($_POST['user_name']) < 1) {
        $error = "Tên người dùng không được để trống.";
    } else if (strlen($_POST['user_email']) < 1 || !filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else if (strlen($_POST['user_password']) < 8) {
        $error = 'Mật khẩu không hợp lệ';
    } else {
        require_once "database/ChatUser.php";
        $user = new ChatUser();
        $user->setUserName($_POST['user_name']);
        $user->setUserEmail($_POST['user_email']);
        $user->setUserPassword(password_hash($_POST['user_password'], PASSWORD_DEFAULT));
        $user->setUserProfile("images/placeholder.jpg");
        $user->setUserStatus('Disable');
        $user->setUserCreatedOn(date("Y-m-d H:i:s"));
        $user->setUserVerificationCode(md5(uniqid()));
        $user_data = $user->getUserDataByEmail();
        if (is_array($user_data) && count($user_data) > 0) {
            $error = 'Tài khoản đã tồn tại';
        } else {
            if ($user->saveData()) {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '22110179@student.hcmute.edu.vn';
                $mail->Password = 'TEST@123';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom("22110179@student.hcmute.edu.vn", "Nhom 8");
                $mail->addAddress($user->getUserEmail());
                $mail->isHTML(true);

                $mail->Subject = 'Xac minh dang ky - Chat App';

                $verify_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/"
                    . "verify.php?code=" . $user->getUserVerificationCode();
                $mail->Body = '
                <p>Chúng tôi chỉ cần xác minh địa chỉ email của bạn trước khi bạn có thể truy cập vào Chat App</p>
                
                <p>Xác minh địa chỉ email của bạn: </p> <a href="' . $verify_link . '">
                Nhấn vào đây!
                </a>
                
                <p>Cảm ơn! – Nhóm 8</p>';
                $mail->send();

                $success_message = 'Kiểm tra email được gửi tới ' . $user->getUserEmail()
                    . ' để xác thực đăng ký.';
            } else {
                $error = 'Đã có lỗi xảy ra, vui lòng thử lại';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng ký - Chat Application</title>

    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap-icons/bootstrap-icons.css" />
    <!-- Theme CSS -->
    <link id="style-switch" rel="stylesheet" type="text/css" href="assets/css/style.css" />
</head>

<body>
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
                                <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Nhập tên" required>
                            </div>
                            <!-- Email -->
                            <div class="mb-3 input-group-lg">
                                <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Nhập email" required />
                                <small>Chúng tôi sẽ không bao giờ chia sẻ email của bạn với bất kỳ ai khác.</small>
                            </div>
                            <!-- New password -->
                            <div class="mb-3 position-relative">
                                <!-- Input group -->
                                <div class="input-group input-group-lg">
                                    <input class="form-control fakepassword" type="password" name="user_password" id="psw-input" placeholder="Nhập mật khẩu" minlength="8" required />
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
                                        <i class="bi bi-info-circle ps-1" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Bao gồm ít nhất một chữ hoa, một chữ thường, một ký tự đặc biệt, một số và dài 8 ký tự." data-bs-original-title="" title=""></i>
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