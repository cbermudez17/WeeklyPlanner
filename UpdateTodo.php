<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("UPDATE todoItems SET urgent=?, checked=? WHERE itemID=?");
    $stmt->bind_param("iii", $_POST["urgent"], $_POST["checked"], $_POST["todoID"]);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>