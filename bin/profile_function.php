<?php
session_start();
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

function editProfileHandle()
{
    global $user, $user_id, $user_data, $message;
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
}

function editPasswordHandle() {
    global $user_data, $user, $message_pass;
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
}