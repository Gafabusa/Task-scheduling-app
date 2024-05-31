<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'todo_list');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $edit_task = $_POST['edit_task'];
    $edit_due_date = $_POST['edit_due_date'];

    // Validate input (you can add more validation as needed)
    if (empty($edit_task)) {
        $_SESSION['error'] = "Task description cannot be empty.";
        header("Location: index.php");
        exit();
    }

    // Update task in the database
    $sql = "UPDATE tasks SET description = ?, due_date = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $edit_task, $edit_due_date, $task_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Task updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating task: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();

header("Location: index.php");
exit();
