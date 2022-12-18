<?php
session_start();
if (isset($_POST['action']) && $_POST['action'] == 'leave') {
    require_once ('database/ChatUser.php');
    $user = new ChatUser();
    $token = $_SESSION['user_data'][$_POST['id']]['token'];
    $user->setUserId($_POST['id']);
    $user->setUserLoginStatus('Logout');
    $user->setUserToken($token);
    if ($user->updateUserLoginStatus()) {
        unset($_SESSION['user_data']);
        session_destroy();
        echo json_encode(["status"=>1]);
    }
}