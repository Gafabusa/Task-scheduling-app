<?php
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
    </li>
<?php endwhile; ?>
