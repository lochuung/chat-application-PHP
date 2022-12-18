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

if (isset($_POST['action']) && $_POST['action'] == 'fetch_chat_data') {
    require_once "database/PrivateChat.php";
    $chat = new PrivateChat();
    $chat->setFromUserId($_POST['from_user_id']);
    $chat->setToUserId($_POST['to_user_id']);
    $data = $chat->getAllChatData();
    echo json_encode($data);
}