<?php

use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once dirname(__DIR__) . "/database/ChatUser.php";
$user = new ChatUser();
function LoginHandle()
{
    global $error;
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
                        $user_token = md5(uniqid()) . rand();
                        $user->setUserToken($user_token);
                        $user->setUserId($data_user['user_id']);
                        $user->setUserLoginStatus('Login');
                        if ($user->updateUserLoginStatus()) {
                            $_SESSION['user_data'][$data_user['user_id']] = [
                                "id" => $data_user['user_id'],
                                "name" => $data_user['user_name'],
                                "profile" => $data_user['user_profile'],
                                "token" => $user_token
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
}

function RegisterHandle()
{
    global $error, $success_message;
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
            $user->setUserCreatedOn(time());
            $user->setUserVerificationCode(md5(uniqid()) . time());
            $user_data = $user->getUserDataByEmail();
            if (is_array($user_data) && count($user_data) > 0) {
                $error = 'Tài khoản đã tồn tại';
            } else {
                if ($user->saveData()) {
                    $title = 'Xac minh dang ky - Chat App';
                    $verify_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/"
                        . "verify.php?code=" . $user->getUserVerificationCode();
                    $body = '
                <p>Chúng tôi chỉ cần xác minh địa chỉ email của bạn trước khi bạn có thể truy cập vào Chat App</p>
                
                <p>Xác minh địa chỉ email của bạn: </p> <a href="' . $verify_link . '">
                Nhấn vào đây!
                </a>
                
                <p>Cảm ơn! – Nhóm 8</p>';
                    try {
                        $user->sendMail($_POST['user_email'], $title, $body);
                        $success_message = 'Kiểm tra email được gửi tới ' . $user->getUserEmail()
                            . ' để xác thực đăng ký.';
                    } catch (Exception $e) {
                        $error = 'Có lỗi xảy ra khi gửi xác thực đến mail của bạn, vui lòng liên hệ với ban quản trị';
                        $error = $error . '<br> ' . $e->errorMessage();
                    }
                } else {
                    $error = 'Đã có lỗi xảy ra, vui lòng thử lại';
                }
            }
        }
    }
}

function editPasswordInForgotHandle() {
    global $message_pass, $error;
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
}

function sendResetPasswordCodeHandle() {
    global $error, $user;
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
}