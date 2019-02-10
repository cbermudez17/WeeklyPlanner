<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
//     $task = json_decode($_POST["task"], false);
    
    $stmt = $conn->prepare("UPDATE tasks SET title=?, details=?, time=?, day=?, color=? WHERE taskID=?");
//     $stmt->bind_param("sssssi", $task->title, $task->details, $task->time, $task->day, $task->color, $task->taskID);
    $stmt->bind_param("sssssi", $_POST["title"], $_POST["details"], $_POST["time"], $_POST["day"], $_POST["color"], $_POST["taskID"]);
    
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
}

?>