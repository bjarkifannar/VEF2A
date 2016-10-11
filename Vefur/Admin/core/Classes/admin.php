<?php
	class AddAdmin {
		private $mDB;
		private $adminFullName;
		private $adminUsername;
		private $adminPassword;
		private $errorMsg = [];

		function __construct($db, $fullName, $username, $password) {
			$this->mDB = $db;
			$this->adminFullName = $this->FilterName($fullName);
			$this->adminUsername = $this->FilterUsername($username);
			$this->adminPassword = $this->FilterPassword($password);
		}

		private function FilterName($name) {
			return filter_var($name, FILTER_SANITIZE_STRING);
		}

		private function FilterUsername($username) {
			return filter_var($username, FILTER_SANITIZE_STRING);
		}

		private function FilterPassword($password) {
			return filter_var($password, FILTER_SANITIZE_STRING);
		}

		private function CheckAdmin() {
			$checkQuery = "SELECT id FROM admins WHERE username=:username LIMIT 1";
			$checkRes = $this->mDB->prepare($checkQuery);
			$checkRes->bindParam(':username', $this->adminUsername);
			$checkRes->execute();

			if ($checkRes->rowCount() > 0) {
				$this->errorMsg[] = 'This admin username is already taken.';
			}

			if (strlen($this->adminUsername) < 1) {
				$this->errorMsg[] = 'The username cannot be empty.';
			}

			if (strlen($this->adminFullName) < 1) {
				$this->errorMsg[] = 'The name cannot be empty.';
			}

			$checkQuery = $checkRes = null;
		}

		function InsertAdmin() {
			$this->CheckAdmin();

			if (empty($this->errorMsg)) {
				$insertQuery = "INSERT INTO admins (full_name, username, password) VALUES (:full_name, :username, :password)";
				$insertRes = $this->mDB->prepare($insertQuery);
				$insertRes->bindParam(':full_name', $this->adminFullName);
				$insertRes->bindParam(':username', $this->adminUsername);
				$insertRes->bindParam(':password', $this->adminPassword);

				if (!$insertRes->execute()) {
					$this->errorMsg[] = 'Could not insert admin into database.';
				}
			}

			return $this->errorMsg;
		}
	};

	class RemoveAdmin {
		public function Remove($db, $adminID) {
			if (!filter_var($adminID, FILTER_VALIDATE_INT)) {
				return 'Error removing admin.';
			}

			$removeAdminQuery = "DELETE FROM admins WHERE id=:admin_id";
			$removeAdminRes = $db->prepare($removeAdminQuery);
			$removeAdminRes->bindParam(':admin_id', $adminID);
			$removeAdminRes->execute();
			$db = $removeAdminQuery = $removeAdminRes = null;

			return 'Admin has been removed.';
		}
	};
?>