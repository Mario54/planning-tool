<?php


require ('../mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $page_title = 'Todo';
    include ('includes/header.html');

    if (isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])) {

        $todo_id = mysqli_real_escape_string($dbc, $_GET['id']);

        $q = "SELECT t.todo_id, t.title, t.completed, t.date_added,
                 t.date_completed, l.name AS list_name FROM todos AS t
                 INNER JOIN lists AS l USING (list_id)
                 WHERE t.todo_id=$todo_id";
        $r = mysqli_query($dbc, $q);

        $num = mysqli_num_rows($r);

        if ($num > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $todo = $row;
                // $todo['completed'] = 1;
                // var_dump($todo);
            }
        } else {
            echo 'No match';
        }

    } else {
        echo 'Didn\'t specify any id or id is not valid';
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (ctype_digit($_POST['todo_id'])) {
        $todo_id = mysqli_real_escape_string($dbc, $_POST['todo_id']);

        // see if the 'completed' checkbox was checked
        if (isset($_POST['completed'])) {
            $c = 1;
        } else {
            $c = 0;
        }

        if (isset($_POST['title'])) {
            $t = $_POST['title'];
        }

        $q = "UPDATE todos SET completed=$c, title='$t' WHERE todo_id=$todo_id";
        $r = mysqli_query($dbc, $q);

        // var_dump(mysqli_error($dbc));
        header("Location: index.php");
        mysqli_close($dbc);
        exit();
    }

    //header("Location: index.php");

    mysqli_close($dbc);
    exit();
}
?>

<form method="post">
    <p>
        <label for="title">Task title</label>
        <input type="text" name="title" value="<?= $todo['title'] ?>">
    </p>

    <p>
        <label for="completed">Task title</label>
        <input type="checkbox" name="completed" <?php if($todo['completed']) echo 'checked="' . $todo['completed'] .'"'; ?>>
    </p>

    <p>
        <label for="list_title">List: </label>
        <input type="input" name="list_title" value="<?= $todo['list_name'] ?>">
    </p>

    <p>
        <input type="hidden" name="todo_id" value="<?= $todo['todo_id'] ?>">
        <input type="submit" value="Save">
    </p>
</form>


<?php include ('includes/footer.html'); ?>
