<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    
        require 'ODBC.php';
        $conn = new mysqli($url, $user, $pass, $db);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $stmt = $conn->prepare("SELECT userID FROM users WHERE username=? AND password=?");
        $stmt->bind_param("ss", $_POST["user"], hash("sha512", $_POST["pass"]));
        
        $stmt->execute();
        $stmt->bind_result($userID);
        
        if ($stmt->fetch()) {
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['user'] = true;
            $_SESSION['userID'] = $userID;
        } else {
            session_unset();
            session_destroy();
        }
        
        
        $stmt->close();
        $conn->close();
    }
    header('Location: https://web.njit.edu/~cb283/planner/planner.php');
}

?>