<?php

function get_lists($dbc) {
    $q = "SELECT * FROM lists";
    $r = mysqli_query($dbc, $q);
    $num = mysqli_num_rows($r);
    if ($num > 0) {
        $lists = array();

        // build up an array with all the lists
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $lists[$row['list_id']] = array(
                'list_name' => $row['name'],
                'todos' => array()
            );
        }

        return $lists;
    } else {
        return False;
    }
}

// if lists is null, just returns a list of todos
function get_todos($dbc, &$lists = null, &$completed_todos) {
    // fetch the items in each list
    $q = "SELECT todo_id, list_id, title, completed FROM todos";
    $r = mysqli_query($dbc, $q);

    if (!isset($lists)) {
        $todos = [];
    }

    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        if (isset($lists)) {
            if ($row['completed'] == '0') {
                $lists[$row['list_id']]['todos'][] = $row;
            } else {
                $completed_todos[] = $row;
            }
        } else {
            $todos[] = $row;
        }
    }

    if (isset($lists)) {
        return True;
    } else {
        return $todos;
    }
}

function get_todo($dbc, $todo_id) {
    // fetch the items in each list
    $q = "SELECT t.todo_id, t.title, t.completed, t.date_added,
             t.date_completed, list_id, l.name AS list_name FROM todos AS t
             INNER JOIN lists AS l USING (list_id)
             WHERE t.todo_id=$todo_id LIMIT 1";
    $r = mysqli_query($dbc, $q);
    $num = mysqli_num_rows($r);

    if ($num == 0) {
        return False;
    } else {
        return mysqli_fetch_array($r, MYSQLI_ASSOC);
    }
}
