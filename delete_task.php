<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'todo_list');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $sql = "DELETE FROM tasks WHERE id=$task_id";
    if ($mysqli->query($sql)) {
        $_SESSION['message'] = "Task deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete task!";
    }
}

header('Location: index.php');
?>
