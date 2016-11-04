<?php
	$pageName = 'Admin - Documentation';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php require_once 'inc/header.php'; ?>
		<?php
			if ($logged !== 'in') {
				header('Location: index.php');
			} else {
		?>
		<div class="section">
			<h2 class="title">Table of contents</h2>
			<ul>
				<li><a href="#Classrooms">1. Classrooms</a></li>
				<ul>
					<li><a href="#ClassroomList">1.1 Classroom List</a></li>
					<li><a href="#AddClassroom">1.2 Add Classroom <sup>1</sup></a></li>
					<li><a href="#RemoveClassroom">1.3 Remove Classroom <sup>1</sup></a></li>
				</ul>
				<li><a href="#Buildings">2. Buildings</a></li>
				<ul>
					<li><a href="#BuildingList">2.1 Building List</a></li>
					<li><a href="#AddBuilding">2.2 Add Building <sup>1</sup></a></li>
					<li><a href="#RemoveBuilding">2.3 Remove Building <sup>1</sup></a></li>
				</ul>
				<li><a href="#Admins">3. Admins</a></li>
				<ul>
					<li><a href="#AdminList">3.1 Admin List</a></li>
					<li><a href="#AddAdmin">3.2 Add Admin <sup>1</sup></a></li>
					<li><a href="#RemoveAdmin">3.3 Remove Admin <sup>1</sup></a></li>
				</ul>
			</ul>
		</div>
		<br>
		<div class="section" id="Classrooms">
			<h2 class="title">1. Classrooms</h2>
			<p>This section is about the classroom tools in the admin panel.</p>
		</div>
		<br>
		<div class="section" id="ClassroomList">
			<h3 class="title">1.1 Classroom List</h3>
			<p>The <b>Classroom List</b> shows you a list of all the classrooms groupped by the building it is in.<br>
			The classrooms are ordered by their name.<br>
			By clicking a classroom name in the list you can add or remove a time where it is free.</p>
		</div>
		<br>
		<div class="section" id="AddClassroom">
			<h3 class="title">1.2 Add Classroom <sup>1</sup></h3>
			<p>The <b>Add Classroom</b> panel is where you can add classrooms to the list.<br>
			To add a classroom simply type the classroom's name in the text box and select the building that classroom is in from the dropdown.</p>
		</div>
		<br>
		<div class="section" id="RemoveClassroom">
			<h3 class="title">1.3 Remove Classroom <sup>1</sup></h3>
			<p>The <b>Remove Classroom</b> panel is where you can remove a classroom from the list.<br>
			<i><b>WARNING!</b> Removing a classroom also removes the times that are connected to that classroom!</i></p>
		</div>
		<br>
		<div class="section">
			<h2 class="title">Other</h2>
			<b><sup>1</sup></b> Only admins with maximum permission can use this feature!
		</div>
		<?php
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>