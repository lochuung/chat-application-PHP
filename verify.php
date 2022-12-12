<?php  
session_start();
require_once("database/ChatUser.php");
$user = new ChatUser();
$user->setUserVerificationCode($_GET['code']);
if ($user->isExistVerificationCode()) {
    $user->setUserStatus('Enable');
    if ($user->enableUserAccount()) {
        $_SESSION['success_message'] = "Tài khoản của bạn xác thực thành công, giờ đây bạn có thể đăng nhập";
        header("Location: index.php");
    }
} 
$error = 'Có lỗi xảy ra vui lòng thử lại.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực - Chat Application</title>
</head>
<body>
    <?php echo $error; ?>
</body>
</html>