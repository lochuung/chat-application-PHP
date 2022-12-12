<?php
$error = '';
$success_message = '';
if (isset($_POST['register'])) {
    session_start();
    if (isset($_SESSION['user_data'])) {
        header('location:chatroom.php');
    }

    require_once "database/ChatUser.php";
    $user = new ChatUser();
    $user->setUserName($_POST['user_name']);
    $user->setUserEmail($_POST['user_email']);
    $user->setUserPassword($_POST['user_password']);
    $user->setUserProfile("images/placeholder.jpg");
    $user->setUserStatus('disable');
    $user->setUserCreatedOn("d-m-Y H:i:s");
    $user->setUserVerificationCode(md5(uniqid()));
    $user_data = $user->getUserDataByEmail();
    if (is_array($user_data) && count($user_data) > 0) {
        $error = 'Tài khoản đã tồn tại';
    } else {
        if ($user->saveData()) {
            $success_message = 'Đăng ký tài khoản thành công';
        } else {
            $error = 'Đã có lỗi xảy ra, vui lòng thử lại';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Đăng ký - Chat Application</title>

    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
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
                <!-- Sign up START -->
                <div class="card card-body rounded-3 p-4 p-sm-5">
                    <div class="text-center">
                        <!-- Title -->
                        <h1 class="mb-2">Đăng ký</h1>
                        <span class="d-block"
                        >Đã có tài khoản?
                  <a href="#">Đăng nhập ở đây</a></span
                        >
                    </div>
                    <?php 
                    if ($error != '') {
                        echo '<div class="alert alert-danger" role="alert">
                        <strong>'.$error.'</strong>
                    </div>';
                    }

                    if ($success_message != '') {
                        echo '<div class="alert alert-success" role="alert">
                        <strong>'.$success_message.'</strong>
                    </div>';
                    }
                    ?>
                    <!-- Form START -->
                    <form method="post" id="register_form" class="mt-4">
                        <!-- Name -->
                        <div class="mb-3 input-group-lg">
                            <input type="text" name="user_name" id="user_name"
                                   class="form-control" placeholder="Nhập tên"
                                   required>
                        </div>
                        <!-- Email -->
                        <div class="mb-3 input-group-lg">
                            <input
                                    type="email"
                                    name="user_email"
                                    id="user_email"
                                    class="form-control"
                                    placeholder="Nhập email"
                                    required
                            />
                            <small>Chúng tôi sẽ không bao giờ chia sẻ email của bạn với bất kỳ ai khác.</small>
                        </div>
                        <!-- New password -->
                        <div class="mb-3 position-relative">
                            <!-- Input group -->
                            <div class="input-group input-group-lg">
                                <input
                                        class="form-control fakepassword"
                                        type="password"
                                        name="user_password"
                                        id="psw-input"
                                        placeholder="Nhập mật khẩu"
                                        minlength="8"
                                        required
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
