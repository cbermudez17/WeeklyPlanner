<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("DELETE FROM todoItems WHERE itemID=?");
    $stmt->bind_param("i", $_POST["todoID"]);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>