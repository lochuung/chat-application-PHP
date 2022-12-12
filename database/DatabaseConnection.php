<?php
class DatabaseConnection {
    function connect() {
        $host = "localhost";
        $db = "chatnhom8";
        $username = "root";
        $password = "";
        $conn = new PDO("mysql:host=$host; dbname = $db", $username, $password);
        return $conn;
    }
}