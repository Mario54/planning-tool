<?php

// echoes html select menu with all the $lists
// and preselects the $current_list
function lists_dropdown($lists, $current_list = null) {
    if (!isset($lists) || empty($lists) || !is_array($lists)) {
        return False;
    }

    echo "<select name=\"list_id\">\n";
    foreach($lists as $list_id => $list_info) {
        echo '<option value="'. $list_id . '"';

        if (isset ($current_list) && $list_id == $current_list) {
            echo ' selected';
        }

        echo '>' . $list_info['list_name'] .'</option>\n';
    }
    echo "</select>\n";
}

function display_lists($lists) {
    // display all the lists if there are any
    if (isset($lists) && count($lists) > 0) {
        foreach ($lists as $list_id => $list_info) {
            echo '<div><h1>' . $list_info['list_name'] . '</h1>';
            if (count($list_info['todos']) > 0) {
                echo '<ul>';
                foreach($list_info['todos'] as $todo_info) {
                    echo '<li><a href="todo.php?id=' . $todo_info['todo_id'] .
                    '">' . $todo_info['title'] . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No items</p>';
            }

        }
    } else {
        echo 'No list';
    }
}

function display_completed($completed_todos) {
    echo "<ul>\n";
    foreach ($completed_todos as $todo) {
        echo '<li>' . $todo['title'] . ' - Completed on: ' . $todo['date_completed'] . '</li>';
    }
    echo "</ul>\n";
}
