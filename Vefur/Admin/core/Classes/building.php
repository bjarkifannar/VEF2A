<?php
	class Building {
		public function Add($db, $building_name) {
			$errorFlag = FALSE;
			$errorMsg = [];

			$checkBuildingQuery = "SELECT id FROM buildings WHERE name=:name LIMIT 1";
			$checkBuildingRes = $db->prepare($checkBuildingQuery);
			$checkBuildingRes->bindParam(':name', $building_name);
			$checkBuildingRes->execute();

			if ($checkBuildingRes->rowCount() > 0) {
				$errorFlag = TRUE;
				$errorMsg[] = 'ERROR! This building is already in the database';
			}

			if (strlen($building_name) < 1) {
				$errorFlag = TRUE;
			}

			$checkBuildingRes = null;

			if (!$errorFlag) {
				$insertBuildingQuery = "INSERT INTO buildings (name) VALUES (:name)";
				$insertBuildingRes = $db->prepare($insertBuildingQuery);
				$insertBuildingRes->bindParam(':name', $building_name);

				if(!$insertBuildingRes->execute()) {
					$errorMsg[] = 'ERROR! Could not insert the building into the database';
				} else {
					return true;
				}
			}

			return $errorMsg;
		}

		public function Remove($db, $id) {
			$classroomQuery = "SELECT id FROM classrooms WHERE building_id=:building_id";
			$classroomRes = $db->prepare($classroomQuery);
			$classroomRes->bindParam(':building_id', $id);
			$classroomRes->execute();

			$removeTimesQuery = "DELETE FROM times WHERE classroom_id=:classroom_id";
			$removeTimesRes = $db->prepare($removeTimesQuery);

			while ($row = $classroomRes->fetch(PDO::FETCH_ASSOC)) {
				$removeTimesRes->bindParam(':classroom_id', $row['id']);
				$removeTimesRes->execute();
			}

			$removeTimesRes = $classroomRes = null;

			$removeClassroomsQuery = "DELETE FROM classrooms WHERE building_id=:building_id";
			$removeClassroomsRes = $db->prepare($removeClassroomsQuery);
			$removeClassroomsRes->bindParam(':building_id', $id);
			$removeClassroomsRes->execute();
			$removeClassroomsRes = null;

			$removeBuildingQuery = "DELETE FROM buildings WHERE id=:building_id";
			$removeBuildingRes = $db->prepare($removeBuildingQuery);
			$removeBuildingRes->bindParam(':building_id', $id);
			$removeBuildingRes->execute();
			$removeBuildingRes = null;

			$db = null;
		}
	}
?>