<!DOCTYPE HTML>
<html>
	<head>
		<title>Plan your day</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "planing-tool";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$description = $_POST["task-name"];
			$list = 1;
			if ($_POST["list"]=="week") {
				$list = 1;
			} else if ($_POST["list"] == "future") {
				$list = 2;
			} else {
				$list = 3;
			}
			
			$insert_task_sql = "INSERT INTO tasks (description, list) VALUES ('" . $description . "', " . $list . ")";
				
			echo "POST\n";
				
			if ($conn->query($insert_task_sql) === TRUE) {
				echo "New record created successfully<br />";
			}
		}
		
		
		$sql = "SELECT * FROM tasks";
		$result = $conn->query($sql);
		$future_list = [];
		$week_list = [];
		$today_list = [];
		
		if ($result === FALSE) {
			echo "0 results";
		} else if  ($result && $result->num_rows) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "id: " . $row["id"] . " - Description: " . $row["description"] . " - List: " . $row["list"] . "<br />";
			}
		}
		$conn->close();
	?>
		<div class="daily">
			<h1>Today</h1>
			<ul>
				<li><form action="delete.php" method="post">Test <input type="submit" value="delete"></form></li>
				<li>Test #2</li>
			<ul>
		</div>
		
		<div class="weekly">
			<h1>This week</h1>
			<ul>
				<li>Test</li>
				<li>Test #2</li>
			<ul>
		</div>
		
		<div class="future">
			<h1>Future</h1>
			<ul>
				<li>Test</li>
				<li>Test #2</li>
			<ul>
		</div>
		
		<div class="add-task">
			<form action="index.php" method="post">
				<input type="text" name="task-name">
				<select name="list">
					<option value="week">Week</option>
					<option value="future">Future</option>
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
	</body>
</html>