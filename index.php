<?php
	$page_title = 'Planning Tool';
	include ('includes/header.html');

	require ('../mysqli_connect.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$errors = [];

		if (empty($_POST['title'])) {
			// error
			$errors[] = 'You did not enter a name for the task';
		} else {
			$t = $_POST['title'];
		}
		if (empty($_POST['list_id'])) {
			$errors[] = 'List doesn\'t exists';
		} else {
			$l = $_POST['list_id'];
		}

		if (empty($errors)) {
			$q = "INSERT INTO todos (title, completed,
				date_added, list_id) VALUES ('" . $t . "', 0, NOW(), $l)";
			$r = mysqli_query($dbc, $q);

			if ($r) {
				echo '<p>Successfuly added the new task</p>';
			}
		} else {
			// display errors
			foreach ($errors as $msg) {
				echo '<p>' . $msg . '</p>';
			}
		}


	}

	// fetch the lists and their items
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

		$q = "SELECT todo_id, list_id, title FROM todos";
		$r = mysqli_query($dbc, $q);

		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$lists[$row['list_id']]['todos'][] = array(
				'todo_id' => $row['todo_id'],
				'title' => $row['title']
			);
		}

	} else { // nothing
		echo 'No tasks';
	}

	mysqli_close($dbc);

	if (isset($lists) && count($lists) > 0) {
		foreach ($lists as $list_id => $list_info) {
			echo '<div><h1>' . $list_info['list_name'] . '</h1>';
			if (count($list_info['todos']) > 0) {
				echo '<ul>';
				foreach($list_info['todos'] as $todo_id => $todo_info) {
					echo '<li>' . $todo_info['title'] . '</li>';
				}
				echo '</ul>';
			}

		}
	}
?>


<div class="add-task">
	<form action="index.php" method="post">
		<input type="text" name="title">
		<select name="list_id">
		<?php
		foreach($lists as $list_id => $list_info) {
			echo '<option value=" '. $list_id . '">' . $list_info['list_name'] .'</option>';
		}
		?>
		</select>
		<input type="submit" value="Add Task">
	</form>
</div>
<br />
<div class="unlock-day">
	<form method="post">
		Unlock weekly tasks on
		<select name="week-days">
			<option value="sunday">Sunday</option>
			<option value="monday">Monday</option>
			<option value="tuesday">Tuesday</option>
			<option value="wednesday">Wednesday</option>
			<option value="thursday">Thursday</option>
			<option value="friday">Friday</option>
			<option value="saturday">Saturday</option>
		</select>
	</form>
</div>
<div class="completed">
	<h1>Completed tasks</h1>
	<ul>
		<li>blablabla - 19/01/2015</li>
		<li>blablabla - 20/01/2015</li>
	</ul>
</div>

<?php include ('includes/footer.html');
