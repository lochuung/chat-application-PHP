<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '/database/ChatUser.php';
require dirname(__DIR__) . '/database/ChatRoom.php';
require dirname(__DIR__) . '/database/PrivateChat.php';

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery();

        $queryarray = array();
        parse_str($querystring, $queryarray);
        $user = new \ChatUser();
        $user->setUserToken($queryarray['token']);
        $user->setUserConnectionId($conn->resourceId);
        $user->updateUserConnectionId();
        $data['status_type'] = 'online';
        $data['status_user_id'] = $user->getUserIdByToken();
        $user->setUserId($data['status_user_id']);
        $user->setUserLoginStatus('Login');
        $user->setUserToken($queryarray['token']);
        $user->updateUserLoginStatus();
        $data['online_user'] = $user->countOnlineUser();
        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        $user = new \ChatUser();
        if ($data['command'] == 'private') {
            $chat = new \PrivateChat();
            $chat->setToUserId($data['pv_receiverId']);
            $chat->setFromUserId($data['pv_userId']);
            $chat->setChatMessage($data['pv_msg']);
            $chat->setStatus('N');
            //set time
            $data['pv_time'] = time();
            $chat->setTimestamp($data['pv_time']);

            $message_id = $chat->saveChat();
            $user->setUserId($data['pv_userId']);
            $sender_data = $user->getUserDataById();

            $user->setUserId($data['pv_receiverId']);
            $receiver_data = $user->getUserDataById();
            //set profile
            $data['pv_userProfile'] = $sender_data['user_profile'];
            //online user
            $data['online_user'] = $user->countOnlineUser();
            foreach ($this->clients as $client) {
                if ($from == $client) {
                    $data['pv_from'] = 'me';
                } else {
                    $data['pv_from'] = $sender_data['user_name'];
                }

                if ($client->resourceId == $receiver_data['user_connection_id'] || $from == $client) {
                    $client->send(json_encode($data));
                }
            }

        } else {
            $user->setUserId($data['userId']);
            $user_data = $user->getUserDataById();

            $data['userProfile'] = $user_data['user_profile'];
            //set time
            $data['time'] = time();

            //save message data to chatroom table
            $chat = new \ChatRoom();
            $chat->setUserId($data['userId']);
            $chat->setMessage($data['msg']);
            $chat->setCreatedOn($data['time']);
            $chat->saveMessageData();

            foreach ($this->clients as $client) {
                if ($from == $client) {
                    $data['from'] = 'me';
                } else {
                    $data['from'] = $user_data['user_name'];
                }
                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery();
        $queryarray = array();
        parse_str($querystring, $queryarray);
        $user = new \ChatUser();
        $data['status_type'] = 'offline';
        $user->setUserToken($queryarray['token']);
        $data['status_user_id'] = $user->getUserIdByToken();

        $user->setUserId($data['status_user_id']);
        $user->setUserLoginStatus('Logout');
        $user->setUserToken($queryarray['token']);
        $user->updateUserLoginStatus();
        $data['online_user'] = $user->countOnlineUser();

        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}