<?php
	class TimesClass {
		private $errorMsg = [];

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

		public function AddTime($db, $classroom_id, $time_from, $time_to, $day_id) {
			$time_from = filter_var($time_from, FILTER_SANITIZE_STRING);
			$time_to = filter_var($time_to, FILTER_SANITIZE_STRING);
			$errorFlag = FALSE;

			if (strlen($time_from) > 10) {
				$this->errorMsg[] = 'Time from cannot be more than 10 characters.';
				$errorFlag = TRUE;
			}

			if (strlen($time_to) > 10) {
				$this->errorMsg[] = 'Time to cannot be more than 10 characters.';
				$errorFlag = TRUE;
			}

			if (!filter_var($classroom_id, FILTER_VALIDATE_INT)) {
				$this->errorMsg[] = 'Classroom ID is not an integer.';
				$errorFlag = TRUE;
			}

			if (!filter_var($day_id, FILTER_VALIDATE_INT)) {
				$this->errorMsg[] = 'Day ID is not an integer.';
				$errorFlag = TRUE;
			}

			if ($errorFlag) {
				return 'Error';
			} else {
				$insertTimeQuery = "INSERT INTO times
												(classroom_id, time_from, time_to, day_id)
										VALUES (:classroom_id, :time_from, :time_to, :day_id)";
				$insertTimeRes = $db->prepare($insertTimeQuery);

				$insertTimeRes->bindParam(':classroom_id', $classroom_id);
				$insertTimeRes->bindParam(':time_from', $time_from);
				$insertTimeRes->bindParam(':time_to', $time_to);
				$insertTimeRes->bindParam(':day_id', $day_id);

				$insertTimeRes->execute();

				$insertTimeQuery = $insertTimeRes = null;

				return 'Success';
			}
		}

		public function GetErrorMsg() {
			return $this->errorMsg;
		}
	}
?>