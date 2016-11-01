<?php
	class Admin {
		protected $errorMsg = [];

		protected function FilterName($name) {
			return filter_var($name, FILTER_SANITIZE_STRING);
		}

		protected function FilterUsername($username) {
			return filter_var($username, FILTER_SANITIZE_STRING);
		}

		protected function FilterPassword($password) {
			return filter_var($password, FILTER_SANITIZE_STRING);
		}

		protected function ValidatePermission($permission) {
			if (intval($permission) === 0 || intval($permission) === 1)
				return true;
			else
				return false;
		}

		protected function ValidateID($id) {
			return filter_var($id, FILTER_VALIDATE_INT);
		}

		protected function CheckAdmin() {
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
	}

	class AddAdmin extends Admin {
		private $mDB;
		private $adminFullName;
		private $adminUsername;
		private $adminPassword;
		private $adminPermission;

		public function __construct($db, $fullName, $username, $password, $max_permission) {
			$this->mDB = $db;
			$this->adminFullName = $this->FilterName($fullName);
			$this->adminUsername = $this->FilterUsername($username);
			$this->adminPassword = $this->FilterPassword($password);

			if ($this->ValidatePermission($max_permission))
				$this->adminPermission = $max_permission;
			else
				$this->errorMsg[] = 'Invalid admin permission.';
		}

		public function InsertAdmin() {
			$this->CheckAdmin();

			if (empty($this->errorMsg)) {
				$insertQuery = "INSERT INTO admins (full_name, username, password, has_max_permission) VALUES (:full_name, :username, :password, :permission)";
				$insertRes = $this->mDB->prepare($insertQuery);
				$insertRes->bindParam(':full_name', $this->adminFullName);
				$insertRes->bindParam(':username', $this->adminUsername);
				$insertRes->bindParam(':password', $this->adminPassword);
				$insertRes->bindParam(':permission', $this->adminPermission);

				if (!$insertRes->execute()) {
					$this->errorMsg[] = 'Could not insert admin into database.';
				}
			}

			return $this->errorMsg;
		}
	}

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
	}

	class EditAdmin extends Admin {
		private $mDB;
		private $mAdminID;
		private $mAdminFullName;
		private $mAdminPermission;

		public function __construct($db, $admin_id, $full_name, $permission) {
			$this->mDB = $db;
			$this->mAdminID = $this->ValidateID($admin_id);
			$this->mAdminFullName = $this->FilterName($full_name);
			$this->mAdminPermission = $permission;

			$this->UpdateAdmin();
		}

		private function UpdateAdmin() {
			if (empty($this->errorMsg)) {$updateQuery = "UPDATE admins SET full_name=:full_name, has_max_permission=:has_max_permission WHERE id=:admin_id";
				$updateRes = $this->mDB->prepare($updateQuery);
				$updateRes->bindParam(':full_name', $this->mAdminFullName);
				$updateRes->bindParam(':has_max_permission', $this->mAdminPermission);
				$updateRes->bindParam(':admin_id', $this->mAdminID);
				$updateRes->execute();
				$updateQuery = $updateRes = null;
			}
		}
	}
?>