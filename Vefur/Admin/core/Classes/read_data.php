<?php
	class ReadClassroomData {
		private $mFile = '';
		private $mBuildings = [];
		private $mClassrooms = [];
		private $mTimes = [];
		private $mMsg = [];
		private $mDB = null;

		public function __construct($file, $db) {
			$this->mDB = $db;

			if (is_file($file))
				$this->mFile = $file;
			else
				$this->mMsg[] = 'The file cannot be found.';

			if (empty($this->mMsg))
				$this->ReadFile();
		}

		public function GetMessages() {
			return $this->mMsg;
		}

		private function ReadFile() {
			$fileHandle = fopen($this->mFile, 'r');
			$curData = null;

			while (($line = fgets($fileHandle)) !== false) {
				if (substr($line, 0, 1) === '#') {
					if (strtolower(substr($line, 1, 9)) === 'buildings') {
						$curData = 'buildings';
					} else if (strtolower(substr($line, 1, 10)) === 'classrooms') {
						$curData = 'classrooms';
					} else if (strtolower(substr($line, 1, 5)) === 'times') {
						$curData = 'times';
					}
				} else {
					if ($curData === 'buildings') {
						$data = explode(';', $line);

						if (count($data) === 2) {
							$this->mBuildings[] = ['building_id' => $data[0], 'building_name' => $data[1]];
						}
					} else if ($curData === 'classrooms') {
						$data = explode(';', $line);

						if (count($data) === 2) {
							$this->mClassrooms[] = ['building_id' => $data[0], 'classroom_name' => $data[1]];
						}
					} else if ($curData === 'times') {
						$data = explode(';', $line);

						if (count($data) === 5) {
							$this->mTimes[] = ['building_id' => $data[0], 'classroom_name' => $data[1], 'time_from' => $data[2], 'time_to' => $data[3], 'day_id' => $data[4]];
						}
					}
				}
			}

			fclose($fileHandle);

			$this->UpdateDatabase();
		}

		private function UpdateDatabase() {
			$resetQuery = "DELETE FROM times; DELETE FROM classrooms; DELETE FROM buildings;";
			$resetQuery .= "ALTER TABLE buildings AUTO_INCREMENT = 1;ALTER TABLE classrooms AUTO_INCREMENT = 1;";
			$resetQuery .= "ALTER TABLE times AUTO_INCREMENT = 1";

			$resetRes = $this->mDB->prepare($resetQuery);
			$resetRes->execute();

			$resetQuery = $resetRes = null;

			$buildingQuery = "INSERT INTO buildings (name) VALUES (:building_name)";
			$classroomQuery = "INSERT INTO classrooms (building_id, name) VALUES (:building_id, :classroom_name)";
			$timeQuery = "INSERT INTO times (classroom_id, time_from, time_to, day_id) VALUES (:classroom_id, :time_from, :time_to, :day_id)";

			$buildingRes = $this->mDB->prepare($buildingQuery);

			foreach ($this->mBuildings as $building) {
				$buildingRes->bindParam(':building_name', $building['building_name']);
				$buildingRes->execute();
			}

			$classroomRes = $this->mDB->prepare($classroomQuery);

			foreach ($this->mClassrooms as $classrooms) {
				$tmpClassroomName = trim($classrooms['classroom_name']);

				$classroomRes->bindParam(':building_id', $classrooms['building_id']);
				$classroomRes->bindParam(':classroom_name', $tmpClassroomName);
				$classroomRes->execute();
			}

			$getClassroomIDQuery = "SELECT id FROM classrooms WHERE building_id=:building_id AND name=:classroom_name LIMIT 1";
			$getClassroomIDRes = $this->mDB->prepare($getClassroomIDQuery);

			$timeRes = $this->mDB->prepare($timeQuery);

			foreach ($this->mTimes as $times) {
				$buildingID = null;

				$getClassroomIDRes->bindParam(':building_id', $times['building_id']);
				$getClassroomIDRes->bindParam(':classroom_name', $times['classroom_name']);
				$getClassroomIDRes->execute();

				if ($getClassroomIDRes->rowCount() > 0) {
					while ($row = $getClassroomIDRes->fetch(PDO::FETCH_ASSOC)) {
						$buildingID = $row['id'];
					}

					$timeRes->bindParam(':classroom_id', $buildingID);
					$timeRes->bindParam(':time_from', $times['time_from']);
					$timeRes->bindParam(':time_to', $times['time_to']);
					$timeRes->bindParam(':day_id', $times['day_id']);
					$timeRes->execute();
				} else {
					$this->mMsg[] = 'Cannot add time: Classroom '.$times['classroom_name'].' with building ID '.$times['building_id'].' not found.';
				}
			}

			$getClassroomIDQuery = $getClassroomIDRes = null;
		}
	}
?>