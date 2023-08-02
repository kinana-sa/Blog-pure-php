<?php

require_once 'connect.php';
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $id = $_GET["id"];
        $db = Database::getInstance();
        $connection = $db->getConnection();
        $query = "UPDATE users SET type = 'premium'  WHERE id ='$id' ";
        $stmt = mysqli_query($connection,$query);
        header('Location: admin.php');
    }