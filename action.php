<?php
session_start();
if (isset($_POST['action']) && $_POST['action'] == 'leave') {
    require_once ('database/ChatUser.php');
    $user = new ChatUser();
    $user->setUserId($_POST['id']);
    $user->setUserLoginStatus('Logout');
    if ($user->updateUserLoginStatus()) {
        unset($_SESSION['user_data']);
        session_destroy();
        echo json_encode(["status"=>1]);
    }
}