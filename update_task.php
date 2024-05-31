<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'todo_list');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];

    // Check if task is completed or not
    $sql = "SELECT * FROM tasks WHERE id = $task_id";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $completed = $row['completed'];

        // Toggle completion status
        $new_completed = $completed ? 0 : 1;
        $completed_at = $new_completed ? date('Y-m-d H:i:s') : null;

        $update_sql = "UPDATE tasks SET completed = $new_completed, completed_at = '$completed_at' WHERE id = $task_id";
        if ($mysqli->query($update_sql)) {
            $_SESSION['message'] = "Task updated successfully!";
        } else {
            $_SESSION['message'] = "Failed to update task!";
        }
    }
}

header('Location: index.php');
