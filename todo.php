<?php
$page_title = 'Todo';
include ('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        require ('../mysqli_connect.php');
        $todo_id = $_GET['id'];
        $q = "SELECT * FROM todos WHERE todo_id=$todo_id";
        $r = mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                var_dump($row);
            }
        } else {
            echo 'No match';
        }

    } else {
        echo 'Didn\'t specify any id';
    }
}
?>


<?php include ('includes/footer.html'); ?>
