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
    require_once "database/ChatUser.php";
    $chat = new PrivateChat();
    $chat->setFromUserId($_POST['to_user_id']);
    $chat->setToUserId($_POST['from_user_id']);
    $chat->setStatus('Y');
    $chat->changeStatus();
    $data = $chat->getAllChatData();
    $user = new ChatUser();
    foreach ($data as $key => $value) {
        $user->setUserId($value['from_user_id']);
        $user_data = $user->getUserDataById();
        $data[$key]['user_name'] = $user_data['user_name'];
        $data[$key]['user_profile'] = $user_data['user_profile'];
    }
    echo json_encode($data);
}