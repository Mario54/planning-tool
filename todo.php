<?php

require ('lib/db_queries.php');
require ('../mysqli_connect.php');
require ('lib/form_helper.php');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $page_title = 'Todo';
    include ('includes/header.html');

    if (isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])) {

        $todo_id = mysqli_real_escape_string($dbc, $_GET['id']);

        $todo = get_todo($dbc, $todo_id);

        if (!$todo) {
            echo 'Item not found';
            exit();
        }

        $lists = get_lists($dbc);

    } else {
        echo 'Didn\'t specify any id or id is not valid';
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (ctype_digit($_POST['todo_id'])) {
        $todo_id = mysqli_real_escape_string($dbc, $_POST['todo_id']);
        $errors = array();

        // see if the 'completed' checkbox was checked
        if (isset($_POST['completed'])) {
            $c = 1;
        } else {
            $c = 0;
        }

        if (isset($_POST['title']) && !empty($_POST['title'])) {
            $t = $_POST['title'];
        } else {
            $errors[] = 'Title is empty';
        }

        if (isset($_POST['list_id']) && ctype_digit($_POST['list_id'])) {
            $list_id = $_POST['list_id'];
        } else {
            $errors[] = 'Todo item ID doesn\'t match';
        }

        if (count($errors) == 0) { // no errors -> put chagnes in DB
            $original_todo = get_todo($dbc, $todo_id);

            $date_completed = '';

            // if the status (completed column) of the todo changed
            // we update the date_completed field
            if ($original_todo['completed'] != $c) {
                if ($c == 0) {
                    $date_completed = ', date_completed=NULL';
                } else {
                    $date_completed = ', date_completed=NOW()';
                }

            }

            $q = "UPDATE todos SET list_id=$list_id, completed=$c, title='$t'
                     $date_completed WHERE todo_id=$todo_id";
            $r = mysqli_query($dbc, $q);

            header("Location: index.php");
            mysqli_close($dbc);
            exit();
        } else {
            var_dump($errors);
            exit();
        }

    } else {
        mysqli_close($dbc);
        exit();
    }
}
?>

<form method="post">
    <p>
        <label for="title">Task title</label>
        <input type="text" name="title" value="<?= $todo['title'] ?>">
    </p>

    <p>
        <label for="completed">Completed? </label>
        <input type="checkbox" name="completed" <?php if($todo['completed']) echo 'checked="' . $todo['completed'] .'"'; ?>>
    </p>

    <p>
        <label for="list_title">List: </label>
        <?php lists_dropdown($lists, $todo['list_id']); ?>
    </p>

    <p>
        <input type="hidden" name="todo_id" value="<?= $todo['todo_id'] ?>">
        <input type="submit" value="Save">
    </p>
</form>


<?php include ('includes/footer.html'); ?>
