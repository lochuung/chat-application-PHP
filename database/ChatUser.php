<?php

use PHPMailer\PHPMailer\PHPMailer;

class ChatUser
{
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_profile;
    private $user_status;
    private $user_created_on;
    private $user_verification_code;
    private $user_login_status;
    public $conn;

    public function __construct()
    {
        require_once("DatabaseConnection.php");
        $db = new DatabaseConnection();
        $this->conn = $db->connect();
    }


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    function sendMail($to, $title, $body)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom("22110179@student.hcmute.edu.vn", "Nhom 8");
        $mail->addAddress($to);
        $mail->isHTML();

        $mail->Subject = $title;
        $mail->Body = $body;
        $mail->send();
    }

    function getUserDataByEmail()
    {
        $sql = "SELECT * FROM chat_user_table WHERE user_email = :user_email";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_email', $this->user_email);
        if ($statement->execute()) {
            $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        }
        return $user_data;
    }

    function getUserDataById()
    {
        $sql = "SELECT * FROM chat_user_table WHERE user_id = :user_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_id', $this->user_id);
        if ($statement->execute()) {
            $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        }
        return $user_data;
    }

    function getUserDataByVerificationCode() {
        $sql = "SELECT * FROM chat_user_table WHERE user_verification_code = :user_verification_code";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);
        if ($statement->execute()) {
            $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        }
        return $user_data;
    }

    function getAllUserData()
    {
        $sql = "SELECT * FROM chat_user_table";
        $statement = $this->conn->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function saveData(): bool
    {
        $sql = "INSERT INTO chat_user_table (
            user_id,
            user_name,
            user_email,
            user_password,
            user_profile,
            user_status,
            user_created_on,
            user_verification_code
        ) VALUES (
            :user_id,
            :user_name,
            :user_email,
            :user_password,
            :user_profile,
            :user_status,
            :user_created_on,
            :user_verification_code
        )";
        $statement = $this->conn->prepare($sql);

        $statement->bindParam(':user_id', $this->user_id);
        $statement->bindParam(':user_name', $this->user_name);
        $statement->bindParam(':user_email', $this->user_email);
        $statement->bindParam(':user_password', $this->user_password);
        $statement->bindParam(':user_profile', $this->user_profile);
        $statement->bindParam(':user_status', $this->user_status);
        $statement->bindParam(':user_created_on', $this->user_created_on);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    function isExistVerificationCode(): bool
    {
        $sql = "SELECT * FROM chat_user_table WHERE user_verification_code = :user_verification_code";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(":user_verification_code", $this->user_verification_code);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }

    function enableUserAccount(): bool
    {
        $sql = "UPDATE chat_user_table SET user_status = :user_status WHERE 
        user_verification_code = :user_verification_code";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(":user_status", $this->user_status);
        $statement->bindParam(":user_verification_code", $this->user_verification_code);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    function updateUserLoginStatus(): bool
    {
        $sql = "UPDATE chat_user_table SET user_login_status = :user_login_status WHERE user_id = :user_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_login_status', $this->user_login_status);
        $statement->bindParam(':user_id', $this->user_id);
        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    function updateData(): bool
    {
        $sql = "UPDATE chat_user_table SET                
    user_name = :user_name,
    user_email = :user_email,
    user_password = :user_password,
    user_profile = :user_profile WHERE user_id = :user_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_name', $this->user_name);
        $statement->bindParam(':user_email', $this->user_email);
        $statement->bindParam(':user_password', $this->user_password);
        $statement->bindParam(':user_profile', $this->user_profile);
        $statement->bindParam(':user_id', $this->user_id);
        if ($statement->execute())
            return true;
        return false;
    }

    function uploadImageFile($user_profile): string
    {
        $target_dir = "images/";

        $extension = explode('.', $user_profile['name']);
        $new_name = rand() . $extension[0] . rand() . '.' . $extension[count($extension) - 1];
        $target_file = $target_dir . basename($new_name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        echo $uploadOk;

        if ($uploadOk == 1 && move_uploaded_file($user_profile["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            return '';
        }
    }

    function updateVerificationCode(): bool
    {
        $sql = "UPDATE chat_user_table SET                
    user_verification_code = :user_verification_code WHERE user_email = :user_email";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);
        $statement->bindParam(':user_email', $this->user_email);
        if ($statement->execute())
            return true;
        return false;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserName()
    {
        return $this->user_name;
    }

    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    public function getUserEmail()
    {
        return $this->user_email;
    }

    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    public function getUserPassword()
    {
        return $this->user_password;
    }

    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    public function getUserProfile()
    {
        return $this->user_profile;
    }

    public function setUserProfile($user_profile)
    {
        $this->user_profile = $user_profile;
    }

    public function getUserStatus()
    {
        return $this->user_status;
    }

    public function setUserStatus($user_status)
    {
        $this->user_status = $user_status;
    }

    public function getUserCreatedOn()
    {
        return $this->user_created_on;
    }

    public function setUserCreatedOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }

    public function getUserVerificationCode()
    {
        return $this->user_verification_code;
    }

    public function setUserVerificationCode($user_verification_code)
    {
        $this->user_verification_code = $user_verification_code;
    }

    public function getUserLoginStatus()
    {
        return $this->user_login_status;
    }

    public function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }
}
