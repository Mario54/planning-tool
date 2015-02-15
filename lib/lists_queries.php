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
