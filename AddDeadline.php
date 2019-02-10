<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO deadlines(`userID`, `description`, `dueDate`, `alertSent`) VALUES (?, ?, ?, 0)");   
    $stmt->bind_param("iss", $_SESSION["userID"], $_POST["text"], $_POST["date"]);
    
    $stmt->execute();
    
    $id = $conn->insert_id;
    echo $id;
    
    $stmt->close();
    $conn->close();
    
}

?>