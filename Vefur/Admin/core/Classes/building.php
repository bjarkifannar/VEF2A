<?php
	class RemoveBuilding {
		public function Remove($db, $id) {
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
	};
?>