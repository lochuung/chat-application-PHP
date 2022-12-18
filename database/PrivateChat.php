<?php

class PrivateChat
{
    private $chat_message_id;
    private $to_user_id;
    private $from_user_id;
    private $chat_message;
    private $timestamp;
    private $status;
    public $conn;

    public function __construct() {
        require_once "DatabaseConnection.php";
        $this->conn = (new DatabaseConnection())->connect();
    }

    public function getChatMessageId()
    {
        return $this->chat_message_id;
    }

    public function setChatMessageId($chat_message_id)
    {
        $this->chat_message_id = $chat_message_id;
    }

    public function getToUserId()
    {
        return $this->to_user_id;
    }

    public function setToUserId($to_user_id)
    {
        $this->to_user_id = $to_user_id;
    }

    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;
    }

    public function getChatMessage()
    {
        return $this->chat_message;
    }

    public function setChatMessage($chat_message)
    {
        $this->chat_message = $chat_message;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    function getAllChatData() {
        $sql =
            "SELECT a.user_id as from_user_id, b.user_id as to_user_id, chat_message, timestamp FROM chat_message INNER JOIN chat_user_table a ON chat_message.from_user_id = a.user_id
            INNER JOIN chat_user_table b ON chat_message.to_user_id = b.user_id
            WHERE (chat_message.from_user_id = :from_user_id AND chat_message.to_user_id = :to_user_id) OR 
                  (chat_message.from_user_id = :to_user_id AND chat_message.to_user_id = :from_user_id)";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':from_user_id', $this->from_user_id);
        $statement->bindParam(':to_user_id', $this->to_user_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function saveChat() {
        $sql =
            "INSERT INTO chat_message (
        to_user_id, from_user_id,
        chat_message, timestamp, status
) VALUES (
    :to_user_id,:from_user_id,:chat_message,:timestamp,:status
)";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':to_user_id', $this->to_user_id);
        $statement->bindParam(':from_user_id', $this->from_user_id);
        $statement->bindParam(':chat_message', $this->chat_message);
        $statement->bindParam(':timestamp', $this->timestamp);
        $statement->bindParam(':status', $this->status);
        $statement->execute();

        return $this->conn->lastInsertId();
    }

    function updateStatus() {
        $sql = "UPDATE chat_user_table SET user_login_status = :user_login_status, 
                           user_token = :user_token WHERE user_id = :user_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_login_status', $this->user_login_status);
        $statement->bindParam(':user_token', $this->user_token);
        $statement->bindParam(':user_id', $this->user_id);
        if ($statement->execute()) {
            return true;
        }
        return false;
    }
}