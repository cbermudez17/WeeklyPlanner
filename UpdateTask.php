<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $task = json_decode($_POST["task"], false);
    
    $stmt = $conn->prepare("UPDATE tasks SET title=?, details=?, time=?, day=?, color=? WHERE taskID=?");
    $stmt->bind_param("sssssi", $task->title, $task->details, $task->time, $task->day, $task->color, $task->taskID);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>