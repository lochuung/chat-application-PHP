<?php 
session_start();
if (isset($_SESSION["user_data"])) {
    echo var_dump($_SESSION['user_data']);
} else {
    echo "No";
}
?>