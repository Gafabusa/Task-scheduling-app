<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'todo_list');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    // Validate input
    if (empty($task) || empty($due_date)) {
        $_SESSION['error'] = "Task description and due date are required.";
    } else {
        // Sanitize inputs (if needed)
        $task = htmlspecialchars($task);
        $due_date = htmlspecialchars($due_date);

        // Insert task into the database
        $sql = "INSERT INTO tasks (description, due_date) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $task, $due_date);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Task added successfully.";
        } else {
            $_SESSION['error'] = "Error adding task: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
header("Location: index.php");
exit();
