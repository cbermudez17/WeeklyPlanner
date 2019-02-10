<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("DELETE FROM deadlines WHERE deadlineID=?");
    $stmt->bind_param("i", $_POST["deadlineID"]);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>