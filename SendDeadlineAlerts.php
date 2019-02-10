<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $to = "";
    $subjectWeek = "Deadlines Approaching!";
    $subjectTomorrow = "Deadlines Due Tomorrow!";
    $headers = "From: alerts@weeklyplanner.com";
    
    $weekEmail = "";
    $tomorrowEmail = "";
    $weekIDs = array();
    $tomorrowIDs = array();
    
    require 'ODBC.php';
    $conn = new mysqli($url, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $idList = json_decode($_POST["week"], false);
    if (sizeof($idList) > 0) {
        $sql = "SELECT deadlineID, description, DATE_FORMAT(dueDate, '%m/%d/%y') AS date FROM deadlines WHERE alertSent=0 AND (deadlineID=".join(" OR deadlineID=", $idList).") ORDER BY dueDate";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $weekIDs[] = $row["deadlineID"];
            $weekEmail .= $row["description"] . " (" . $row["date"] . ")\n";
        }
    }
    
    $idList = json_decode($_POST["tomorrow"], false);
    if (sizeof($idList) > 0) {
        $sql = "SELECT deadlineID, description, DATE_FORMAT(dueDate, '%m/%d/%y') AS date FROM deadlines WHERE alertSent<>2 AND (deadlineID=".join(" OR deadlineID=", $idList).") ORDER BY dueDate";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $tomorrowIDs[] = $row["deadlineID"];
            $tomorrowEmail .= $row["description"] . " (" . $row["date"] . ")\n";
        }
    }
    
    if (sizeof($tomorrowIDs) + sizeof($weekIDs) > 0) {
        $sql = "SELECT username FROM users WHERE userID=".$_SESSION["userID"];
        $result = $conn->query($sql);
        
        $row = $result->fetch_assoc();
        $to = $row["username"] . "@njit.edu";
        
        if (sizeof($weekIDs) > 0) {
            $sql = "UPDATE deadlines SET alertSent=1 WHERE deadlineID=".join(" OR deadlineID=", $weekIDs);
            if ($conn->query($sql) === TRUE) {
                mail($to, $subjectWeek, $weekEmail, $headers);
            }
        }
        
        if (sizeof($tomorrowIDs) > 0) {
            $sql = "UPDATE deadlines SET alertSent=2 WHERE deadlineID=".join(" OR deadlineID=", $tomorrowIDs);
            if ($conn->query($sql) === TRUE) {
                mail($to, $subjectTomorrow, $tomorrowEmail, $headers);
            }
        }
    }
    
    $conn->close();
    
}

?>