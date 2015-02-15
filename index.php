<?php
	$page_title = 'Planning Tool';
	include ('includes/header.html');
	require ('lib/form_helper.php');
	require ('lib/db_queries.php');

	require ('../mysqli_connect.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$errors = []; // array to store errors

		if (empty($_POST['title'])) {
			$errors[] = 'You did not enter a name for the task';
		} else {
			$t = $_POST['title'];
		}
		if (empty($_POST['list_id'])) {
			$errors[] = 'List doesn\'t exists';
		} else {
			$l = $_POST['list_id'];
		}

		// check if there were any errors
		if (empty($errors)) {
			$q = "INSERT INTO todos (title, completed,
				date_added, list_id) VALUES ('" . $t . "', 0, NOW(), $l)";
			$r = mysqli_query($dbc, $q);

			if ($r) {
				echo '<p>Successfuly added the new task</p>';
			}
		} else { // display the errors
			foreach ($errors as $msg) {
				echo '<p class="error">' . $msg . '</p>';
			}
		}
	}

	$lists = get_lists($dbc); // will be false if there are no lists

	if ($lists) {
		$completed_todos = [];

		get_todos($dbc, $lists, $completed_todos); // puts the todos in $lists array

	} else { // no list
		echo 'No list to display';
	}

	mysqli_close($dbc); // close connection

	display_lists($lists);
?>

<div class="add-task">
	<form action="index.php" method="post">
		<input type="text" name="title">
		<?php lists_dropdown($lists); ?>
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
		<?php
		foreach ($completed_todos as $todo) {
			echo '<li>' . $todo['title'] . '</li>';
		}
		?>
	</ul>
</div>

<?php include ('includes/footer.html');
