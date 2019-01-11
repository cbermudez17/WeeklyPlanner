<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT taskID, title, description, time, color FROM tasks WHERE day=?");
    $stmt->bind_param("s", $day);
    
    $tasks = array();
    
    $days = array("mo", "tu", "we", "th", "fr", "sa", "su");
    foreach ($days as $day) {
        $stmt->execute();
        $stmt->bind_result($taskID, $title, $description, $time, $color);
    }
    
    //     $task = json_decode($_POST["task"], false);
    
    //     $stmt = $conn->prepare("INSERT INTO tasks(`title`, `details`, `time`, `day`, `color`) VALUES (?, ?, ?, ?, ?)");
    //     $stmt->bind_param("sssss", $task->title, $task->details, $task->time, $task->day, $task->color);
    
    $stmt->execute();
    
    $id = $conn->insert_id;
    echo $id;
    
    $stmt->close();
    $conn->close();
    
}

?>