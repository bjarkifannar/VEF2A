<?php
	class Classroom {
		public function GetNameFromID($db, $classroom_id) {
			$classroomName = 'Not Found';

			$classroomNameQuery = "SELECT name FROM classrooms WHERE id=:classroom_id LIMIT 1";
			$classroomNameRes = $db->prepare($classroomNameQuery);
			$classroomNameRes->bindParam(':classroom_id', $classroom_id);
			$classroomNameRes->execute();

			if ($classroomNameRes->rowCount() === 1) {
				while ($row = $classroomNameRes->fetch(PDO::FETCH_ASSOC)) {
					$classroomName = $row['name'];
				}
			}

			return $classroomName;
		}
	}

	class AddClassroom {
		private $mDB = null;
		private $buildingID = -1;
		private $classroomName = '';
		private $errorMsg = [];

		private function CheckClassroom() {
			if (empty($this->errorMsg)) {
				$checkQuery = "SELECT id FROM classrooms WHERE building_id=:building_id AND name=:name LIMIT 1";
				$checkRes = $this->mDB->prepare($checkQuery);
				$checkRes->bindParam(':building_id', $this->buildingID);
				$checkRes->bindParam(':name', $this->classroomName);
				$checkRes->execute();

				if ($checkRes->rowCount() > 0) {
					$this->errorMsg[] = 'This classroom is already in the database.';
				}

				$checkQuery = $checkRes = null;
			}
		}

		public function InsertClassroom($db, $building_id, $name) {
			$building_id = filter_var($building_id, FILTER_VALIDATE_INT);
			$name = filter_var($name, FILTER_SANITIZE_STRING);

			$this->mDB = $db;
			$this->buildingID = $building_id;
			$this->classroomName = $name;

			if ($this->buildingID < 1) {
				$this->errorMsg[] = 'Invalid building ID.';
			}

			if (strlen($this->classroomName) < 1) {
				$this->errorMsg[] = 'Invalid classroom name.';
			}

			$this->CheckClassroom();

			if (empty($this->errorMsg)) {
				$insertQuery = "INSERT INTO classrooms (building_id, name) VALUES (:building_id, :classroom_name)";
				$insertRes = $this->mDB->prepare($insertQuery);
				$insertRes->bindParam(':building_id', $this->buildingID);
				$insertRes->bindParam(':classroom_name', $this->classroomName);

				if (!$insertRes->execute()) {
					$this->errorMsg[] = 'Failed to insert classroom into database.';
				}

				$insertQuery = $insertRes = null;
			}

			return $this->errorMsg;
		}
	}

	class RemoveClassroom {
		public function Remove($db, $building_id, $name) {
			$building_id = filter_var($building_id, FILTER_VALIDATE_INT);
			$name = filter_var($name, FILTER_SANITIZE_STRING);
			$name = trim($name);
			$errorMsg = [];
			$classroomID = null;

			$classroomIDQuery = "SELECT id FROM classrooms WHERE building_id=:building_id AND name=:name LIMIT 1";
			$classroomIDRes = $db->prepare($classroomIDQuery);
			$classroomIDRes->bindParam(':building_id', $building_id);
			$classroomIDRes->bindParam(':name', $name);
			$classroomIDRes->execute();

			while ($row = $classroomIDRes->fetch(PDO::FETCH_ASSOC)) {
				$classroomID = $row['id'];
			}

			$classroomIDQuery = $classroomIDRes = null;

			$removeTimesQuery = "DELETE FROM times WHERE classroom_id=:classroom_id";
			$removeTimesRes = $db->prepare($removeTimesQuery);
			$removeTimesRes->bindParam(':classroom_id', $classroomID);
			$removeTimesRes->execute();
			$removeTimesQuery = $removeTimesRes = null;

			$removeQuery = "DELETE FROM classrooms WHERE building_id=:building_id AND name=:name";
			$removeRes = $db->prepare($removeQuery);
			$removeRes->bindParam(':building_id', $building_id);
			$removeRes->bindParam(':name', $name);

			if (!$removeRes->execute()) {
				$errorMsg[] = 'Could not remove classroom.';
			}

			$removeRes = null;

			return $errorMsg;
		}
	}
?>