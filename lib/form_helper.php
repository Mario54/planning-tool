<?php



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
