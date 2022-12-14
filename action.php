<?php
session_start();
if (isset($_POST['action']) && isset($_SESSION['user_data'])) {
    require_once('database/ChatUser.php');
    require_once "database/PrivateChat.php";
    $user = new ChatUser();
    $chat = new PrivateChat();

    if ($_POST['action'] == 'leave') {
        $token = $_SESSION['user_data'][$_POST['id']]['token'];
        $user->setUserId($_POST['id']);
        $user->setUserLoginStatus('Logout');
        $user->setUserToken($token);

        if ($user->updateUserLoginStatus()) {
            unset($_SESSION['user_data']);
            session_destroy();
            echo json_encode(["status" => 1]);
        }
    } else if ($_POST['action'] == 'fetch_chat_data') {
        $chat->setFromUserId($_POST['to_user_id']);
        $chat->setToUserId($_POST['from_user_id']);
        $chat->setStatus('Y');

        $chat->changeStatus();
        $data = $chat->getAllChatData();
        foreach ($data as $key => $value) {
            $user->setUserId($value['from_user_id']);
            $user_data = $user->getUserDataById();
            $data[$key]['user_name'] = $user_data['user_name'];
            $data[$key]['user_profile'] = $user_data['user_profile'];
        }
        echo json_encode($data);
    } else if ($_POST['action'] == 'read_message') {
        $user_id = '';
        foreach ($_SESSION['user_data'] as $key => $value) {
            $user_id = $value['id'];
        }
        $chat->setFromUserId($_POST['from_user_id']);
        $chat->setToUserId($user_id);
        $chat->setStatus('Y');
        $chat->changeStatus();
        json_encode(["status" => 1]);
    } else if ($_POST['action'] == 'private_select') {
        $_SESSION['private_select'] = $_POST['select_id'];
        echo json_encode(["status" => 1]);
    }
}