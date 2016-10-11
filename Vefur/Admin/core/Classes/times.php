<?php
	class TimesClass {
		public function GetTimes($db, $classroom_id) {
			$results = [];

			$timesQuery = "SELECT times.id AS time_id,
									times.time_from AS time_from,
									times.time_to AS time_to,
									times.day_id AS day_id,
									days.name AS day_name
										FROM times
											INNER JOIN days
												ON times.day_id=days.id
										WHERE times.classroom_id=:classroom_id ORDER BY times.day_id ASC,
											times.time_from ASC";
			$timesRes = $db->prepare($timesQuery);
			$timesRes->bindParam(':classroom_id', $classroom_id);
			$timesRes->execute();

			if ($timesRes->rowCount() < 1) {
				$timesRes = null;
				return "No times found";
			}

			while ($row = $timesRes->fetch(PDO::FETCH_ASSOC)) {
				$results[] = ["time_id" => $row['time_id'],
								"time_from" => $row['time_from'],
								"time_to" => $row['time_to'],
								"day" => $row['day_name']];
			}

			$timesRes = null;

			return $results;
		}

		public function RemoveTime($db, $time_id) {
			$removeQuery = "DELETE FROM times WHERE id=:time_id";
			$removeRes = $db->prepare($removeQuery);
			$removeRes->bindParam(':time_id', $time_id);
			$removeRes->execute();
			$removeRes = null;
		}
	};
?>