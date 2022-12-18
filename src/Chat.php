<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '/database/ChatUser.php';
require dirname(__DIR__) . '/database/ChatRoom.php';

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

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);
        $user = new \ChatUser();
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

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}