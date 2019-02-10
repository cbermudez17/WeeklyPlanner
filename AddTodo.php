<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO todoItems(`userID`, `description`, `urgent`, `checked`) VALUES (?, ?, ?, 0)");   
    $stmt->bind_param("isi", $_SESSION["userID"], $_POST["text"], $_POST["urgent"]);
    
    $stmt->execute();
    
    $id = $conn->insert_id;
    echo $id;
    
    $stmt->close();
    $conn->close();
    
}

?>