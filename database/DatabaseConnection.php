<?php
class DatabaseConnection {
    function connect() {
        $host = "localhost";
        $db = "chatnhom8";
        $user = "root";
        $password = "";
        $conn = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        if (!$conn) {
            echo "Kết nối không thành công.";
        }
        return $conn;
    }
}