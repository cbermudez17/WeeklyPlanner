<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("DELETE FROM tasks WHERE taskID=?");
    $stmt->bind_param("i", $_POST["taskID"]);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>