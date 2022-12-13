<?php
$message = '';
$message_pass = '';
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
require_once "database/ChatUser.php";
$user = new ChatUser();
$user_id = '';
foreach ($_SESSION['user_data'] as $key => $value) {
    $user_id = $value['id'];
}

$user->setUserId($user_id);
$user_data = $user->getUserDataById();
$user->setUserName($user_data['user_name']);
$user->setUserEmail($user_data['user_email']);
$user->setUserPassword($user_data['user_password']);
$user->setUserProfile($user_data['user_profile']);

if (isset($_POST['edit'])) {
    if (isset($_FILES['user_profile']['name'])) {
        $user_profile = $user->uploadImageFile($_FILES['user_profile']);
        if ($user_profile == '') {
            $message = 'Đã có lỗi xảy ra khi upload file';
        } else {
            $user->setUserProfile($user_profile);
            $_SESSION['user_data'][$user_id]['profile'] = $user_profile;
        }
    }
    if (strlen($_POST['user_name']) > 2) {
        $user->setUserName($_POST['user_name']);
        $_SESSION['user_data'][$user_id]['name'] = $_POST['user_name'];
    }
    if ($user->getUserProfile() != $user_data['user_profile'] || $user->getUserName() != $user_data['user_name']) {
        if ($user->updateData()) {
            $message = 'Cập nhật thành công';
            $user_data = $user->getUserDataById();
        } else {
            $message = 'Có lỗi xảy ra, vui lòng thử lại';
        }
    }
}

if (isset($_POST['edit-password'])) {
    if (password_verify($_POST['current-password'], $user_data['user_password'])) {
        if (strlen($_POST['new-password']) < 8) {
            $message_pass = 'Mật khẩu mới không hợp lệ';
        } else {
            $user->setUserPassword(password_hash($_POST['new-password'], PASSWORD_DEFAULT));
            if ($user->updateData()) {
                $message_pass = 'Cập nhật thành công';
                $user_data = $user->getUserDataById();
            } else {
                $message_pass = 'Có lỗi xảy ra, vui lòng thử lại';
            }
        }
    } else {
        $message_pass = 'Mật khẩu không chính xác';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Social - Network, Community and Event Theme</title>

    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="author" content="Webestica.com"/>
    <meta
            name="description"
            content="Bootstrap based News, Magazine and Blog Theme"
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
    <link
            rel="stylesheet"
            type="text/css"
            href="assets/vendor/choices.js/public/assets/styles/choices.min.css"
    />
    <link
            rel="stylesheet"
            type="text/css"
            href="assets/vendor/dropzone/dist/dropzone.css"
    />
    <link
            rel="stylesheet"
            type="text/css"
            href="assets/vendor/flatpickr/dist/flatpickr.css"
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
<?php
include_once "part/navbar.php";
?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- Container START -->
    <div class="container">
        <div class="row">
            <!-- Main content START -->
            <div class="col-lg-6 vstack gap-4">
                <!-- Setting Tab content START -->
                <div class="tab-content py-0 mb-0">
                    <!-- Account setting tab START -->
                    <div class="tab-pane show active fade" id="nav-setting-tab-1">
                        <!-- Account settings START -->
                        <div class="card mb-4">
                            <?php
                            if ($message != '') {
                                echo '<div class="alert alert-warning text-center" role="alert">
                        ' . $message . '
                    </div>';
                            }
                            ?>
                            <!-- Title START -->
                            <div class="card-header border-0 pb-0">
                                <h1 class="h5 card-title">Cài đặt tài khoản</h1>
                            </div>

                            <!-- Card header START -->
                            <!-- Card body START -->

                            <div class="card-body">
                                <!-- Form settings START -->
                                <form method="post" class="row g-3" enctype="multipart/form-data">
                                    <div class="avatar avatar-xxl mb-4">
                                        <img class="avatar-img rounded-circle border border-white border-3"
                                             src="<?php
                                             echo $user_data['user_profile'] ?>" alt="">
                                        <input type="file" name="user_profile" class="form-control"/>
                                    </div>
                                    <!-- First name -->
                                    <div class="col-sm-6 col-lg-4">
                                        <label class="form-label">Tên</label>
                                        <input
                                                name="user_name"
                                                type="text"
                                                class="form-control"
                                                placeholder=""
                                                value="<?php echo $user_data['user_name'] ?>"
                                                required
                                        />
                                    </div>
                                    <!-- Phone number -->
                                    <div class="col-sm-6">
                                        <label class="form-label">Email</label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                placeholder=""
                                                value="<?php echo $user_data['user_email'] ?>"
                                                required
                                                disabled
                                        />
                                    </div>
                                    <!-- Button  -->
                                    <div class="col-12 text-end">
                                        <button
                                                name="edit"
                                                type="submit"
                                                class="btn btn-sm btn-primary mb-0"
                                        >Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                                <!-- Settings END -->
                            </div>
                            <!-- Card body END -->
                        </div>
                        <!-- Account settings END -->

                        <!-- Change your password START -->
                        <div class="card">
                            <?php
                            if ($message_pass != '') {
                                echo '<div class="alert alert-warning text-center" role="alert">
                        ' . $message_pass . '
                    </div>';
                            }
                            ?>
                            <!-- Title START -->
                            <div class="card-header border-0 pb-0">
                                <h5 class="card-title">Sửa mật khẩu</h5>
                            </div>
                            <!-- Title START -->
                            <div class="card-body">
                                <!-- Settings START -->
                                <form method="post" class="row g-3">
                                    <!-- Current password -->
                                    <div class="col-12">
                                        <label class="form-label">Mật khẩu hiện tại</label>
                                        <input
                                                type="password"
                                                name="current-password"
                                                class="form-control"
                                                value="<?php
                                                echo isset($_POST["current-password"]) ? $_POST["current-password"] : ''
                                                ?>"
                                                placeholder="Nhập mật khẩu hiện tại"
                                                required
                                        />
                                    </div>
                                    <!-- New password -->
                                    <div class="col-12">
                                        <label class="form-label">Mật khẩu mới</label>
                                        <!-- Input group -->
                                        <div class="input-group">
                                            <input
                                                    class="form-control fakepassword"
                                                    name="new-password"
                                                    type="password"
                                                    id="psw-input"
                                                    placeholder="Nhập mật khẩu mới"
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
                                        <div id="pswmeter-message" class="rounded mt-1"></div>
                                    </div>
                                    <!-- Button  -->
                                    <div class="col-12 text-end">
                                        <button type="submit" name="edit-password" class="btn btn-primary mb-0">
                                            Cập nhật mật khẩu
                                        </button>
                                    </div>
                                </form>
                                <!-- Settings END -->
                            </div>
                        </div>
                        <!-- Card END -->
                    </div>
                    <!-- Account setting tab END -->
                </div>
                <!-- Setting Tab content END -->
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
<script src="assets/vendor/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="assets/vendor/dropzone/dist/dropzone.js"></script>
<script src="assets/vendor/flatpickr/dist/flatpickr.min.js"></script>
<script src="assets/vendor/pswmeter/pswmeter.min.js"></script>

<!-- Template Functions -->
<script src="assets/js/functions.js"></script>
</body>
</html>
