<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO tasks(`userID`, `title`, `details`, `time`, `day`, `color`) VALUES (?, ?, ?, ?, ?, ?)");   
    $stmt->bind_param("isssss", $_SESSION["userID"], $_POST["title"], $_POST["details"], $_POST["time"], $_POST["day"], $_POST["color"]);
    
    $stmt->execute();
    
    $id = $conn->insert_id;
    echo $id;
    
    $stmt->close();
    $conn->close();
    
}

?>