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
						//$this->mMsg[] = 'Buildings:';
					} else if (strtolower(substr($line, 1, 10)) === 'classrooms') {
						$curData = 'classrooms';
						//$this->mMsg[] = 'Classrooms:';
					} else if (strtolower(substr($line, 1, 5)) === 'times') {
						$curData = 'times';
						//$this->mMsg[] = 'Times:';
					}
				} else {
					if ($curData === 'buildings') {
						$data = explode(';', $line);

						if (count($data) === 2) {
							$this->mBuildings[] = ['building_id' => $data[0], 'building_name' => $data[1]];
							//$this->mMsg[] = 'Building ID: '.$data[0].', Building name: '.$data[1];
						}
					} else if ($curData === 'classrooms') {
						$data = explode(';', $line);

						if (count($data) === 3) {
							$this->mClassrooms[] = ['building_id' => $data[0], 'classroom_id' => $data[1], 'classroom_name' => $data[2]];
							//$this->mMsg[] = 'Building ID: '.$data[0].', Classroom ID: '.$data[1].', Classroom name: '.$data[2];
						}
					} else if ($curData === 'times') {
						$data = explode(';', $line);

						if (count($data) === 4) {
							$this->mTimes[] = ['classroom_id' => $data[0], 'time_from' => $data[1], 'time_to' => $data[2], 'day_id' => $data[3]];
							//$this->mMsg[] = 'Classroom ID: '.$data[0].', Time from: '.$data[1].', Time to: '.$data[2].', Day ID: '.$data[3];
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

			$timeRes = $this->mDB->prepare($timeQuery);

			foreach ($this->mTimes as $times) {
				$timeRes->bindParam(':classroom_id', $times['classroom_id']);
				$timeRes->bindParam(':time_from', $times['time_from']);
				$timeRes->bindParam(':time_to', $times['time_to']);
				$timeRes->bindParam(':day_id', $times['day_id']);
				$timeRes->execute();
			}
		}
	}
?>