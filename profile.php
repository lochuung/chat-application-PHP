<?php
$title = 'Sửa hồ sơ';
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
$user_data = array();
require_once "bin/profile_function.php";
$message = '';
$message_pass = '';
editProfileHandle();

editPasswordHandle();
?>
<?php include_once "part/header.php"?>
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
                                                echo $_POST["current-password"] ?? ''
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
<?php include_once "part/signed_footer.php"?>