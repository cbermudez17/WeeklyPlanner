<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $task = json_decode($_POST["task"], false);
    
    $stmt = $conn->prepare("INSERT INTO tasks(`title`, `details`, `time`, `day`, `color`) VALUES (?, ?, ?, ?, ?)");   
    $stmt->bind_param("sssss", $task->title, $task->details, $task->time, $task->day, $task->color);
    
    $stmt->execute();
    
    $id = $conn->insert_id;
    echo $id;
    
    $stmt->close();
    $conn->close();
    
}

?>