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
			$t = mysqli_real_escape_string($dbc, $_POST['title']);
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

<div id="add-task">
	<form action="index.php" method="post" name="add_task">
		<input type="text" name="title">
		<?php lists_dropdown($lists); ?>
		<input type="submit" value="Add Task">
	</form>
</div>

<div class="completed">
	<h1>Completed tasks</h1>
	<?php display_completed($completed_todos); ?>
</div>

<script charset="utf-8">
	var validator = new FormValidator('add_task', [{
		name: 'title',
		display: 'task description',
		rules: 'required'
	}], function (errors, event) {
		if (errors.length > 0) {
			node = document.createElement("p");
			node.setAttribute("class", "error");
			node.appendChild(document.createTextNode(errors[0]['message']));
			document.getElementById('add-task').appendChild(node);
		}
	});
</script>

<?php include ('includes/footer.html');
