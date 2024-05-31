<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do List</h1>
        <form method="post" action="add_task.php" class="form-inline justify-content-center mb-4" onsubmit="return validateForm()">
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control" name="task" id="task" placeholder="Enter new task" required>
                <div class="invalid-feedback">Please enter a task description.</div>
            </div>
            <div class="form-group mb-2">
                <input type="date" class="form-control" name="due_date" id="due_date" required>
                <small class="form-text text-muted">Date task is expected to be completed</small>
                <div class="invalid-feedback">Please enter a valid due date.</div>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Add Task</button>
        </form>
        <ul class="list-group">
            <?php
            session_start();
            $mysqli = new mysqli('localhost', 'root', '', 'todo_list');

            if ($mysqli->connect_error) {
                die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }

            $sql = "SELECT * FROM tasks";
            $result = $mysqli->query($sql);

            while ($row = $result->fetch_assoc()):
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <form method="post" action="update_task.php" class="d-inline">
                        <input type="checkbox" name="completed" value="1" <?php if ($row['completed']) echo 'checked'; ?> onchange="this.form.submit()" class="mr-2">
                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                        <span style="text-decoration: <?php echo $row['completed'] ? 'line-through' : 'none'; ?>">
                            <?php echo $row['description']; ?>
                        </span>
                    </form>
                    <small>
                        <span class="ml-3">Created: <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></span>
                        <?php if ($row['due_date']): ?>
                            <span>Due: <?php echo date('Y-m-d', strtotime($row['due_date'])); ?></span>
                        <?php endif; ?>
                    </small>
                    <div class="btn-group" role="group" aria-label="Task Actions">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                        <form method="post" action="delete_task.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </li>

                <!-- Edit Task Modal -->
                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Task</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="edit_task.php">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="edit_task<?php echo $row['id']; ?>">Task Description</label>
                                        <input type="text" class="form-control" id="edit_task<?php echo $row['id']; ?>" name="edit_task" value="<?php echo $row['description']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_due_date<?php echo $row['id']; ?>">Due Date</label>
                                        <input type="date" class="form-control" id="edit_due_date<?php echo $row['id']; ?>" name="edit_due_date" value="<?php echo $row['due_date']; ?>">
                                    </div>
                                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </ul>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            var task = document.getElementById('task').value;
            var due_date = document.getElementById('due_date').value;

            if (task.trim() === '') {
                document.getElementById('task').classList.add('is-invalid');
                return false;
            }

            if (due_date === '') {
                document.getElementById('due_date').classList.add('is-invalid');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
