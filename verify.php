<?php  
session_start();
require_once("database/ChatUser.php");
$user = new ChatUser();
$user->setUserVerificationCode($_GET['code']);
if ($user->isExistVerificationCode()) {
    $user->setUserStatus('Enable');
    if ($user->enableUserAccount()) {
        $_SESSION['success_message'] = "Tài khoản của bạn xác thực thành công, giờ đây bạn có thể đăng nhập";
    }
}
header("Location: index.php");
?>