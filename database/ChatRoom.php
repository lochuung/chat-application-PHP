<?php
require_once "DatabaseConnection.php";

class ChatRoom
{
    private $message_id;
    private $user_id;
    private $message;
    private $created_on;
    public $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->connect();
    }

    function saveMessageData()
    {
        $sql = "INSERT INTO chatroom (
    user_id,
    message,
    created_on
) VALUES (
    :user_id,
    :message,
    :created_on
)";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_id', $this->user_id);
        $statement->bindParam(':message', $this->message);
        $statement->bindParam(':created_on', $this->created_on);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    function getAllChatData()
    {
        $sql = "SELECT * FROM chatroom INNER JOIN chat_user_table ON chat_user_table.user_id = chatroom.user_id
        ORDER BY chatroom.id ASC";
        $statement = $this->conn->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getCreatedOn()
    {
        return $this->created_on;
    }

    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }
}