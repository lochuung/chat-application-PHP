<?php
$error = '';
session_start();
if (isset($_SESSION["user_data"])) {
    header('location: chatroom.php');
}

if (isset($_POST['login'])) {
    if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } else if (strlen($_POST['user_password']) < 8) {
        $error = 'Mật khẩu không hợp lệ';
    } else {
        require_once("database/ChatUser.php");
        $user = new ChatUser();
        $user->setUserEmail($_POST['user_email']);
        $data_user = $user->getUserDataByEmail();
        if (!is_array($data_user) || count($data_user) == 0) {
            $error = 'Email không tồn tại';
        } else {
            if (password_verify($_POST['user_password'], $data_user['user_password'])) {
                if ($data_user['user_status'] == 'Disable') {
                    $error = 'Tài khoản chưa xác thực';
                } else {
                    $user->setUserId($data_user['user_id']);
                    $user->setUserLoginStatus('Login');
                    if ($user->updateUserLoginStatus()) {
                        $_SESSION['user_data'][$data_user['user_id']] = [
                            "id" => $data_user['user_id'],
                            "name" => $data_user['user_name'],
                            "profile" => $data_user['user_profile']
                        ];
                        header('location: chatroom.php');
                    } else {
                        $error = 'Đã có lỗi xảy ra, vui lòng thử lại';
                    }
                }
            } else {
                $error = 'Mật khẩu không chính xác';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng nhập - Chat application</title>

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
                            <input type="email" name="user_email" class="form-control" placeholder="Nhập email" />
                        </div>
                        <!-- New password -->
                        <div class="mb-3 position-relative">
                            <!-- Password -->
                            <div class="input-group input-group-lg">
                                <input class="form-control fakepassword" type="password" name="user_password" id="psw-input" placeholder="Nhập mật khẩu" />
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
                                    <i class="bi bi-info-circle ps-1" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Include at least one uppercase, one lowercase, one special character, one number and 8 characters long." data-bs-original-title="" title=""></i>
                                </div>
                            </div>
                        </div>
                        <!-- Remember me -->
                        <div class="mb-3 d-sm-flex justify-content-between">
                            <a href="forgot-password.html">Quên mật khẩu?</a>
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