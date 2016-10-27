<?php
	class FileUpload {
		protected $uploaded = [];
		protected $destination;
		protected $max = 51200;
		protected $messages = [];
		protected $permitted = [
			'text/plain'
		];
		protected $typeCheckingOn = true;
		protected $notTrusted = ['bin', 'cgi', 'exe', 'js', 'pl', 'php', 'py', 'sh'];
		protected $suffix = '.upload';
		protected $newName;
		protected $renameDuplicates;
		protected $mDB = null;

		public function __construct($path, $db) {
			if (!is_dir($path) || !is_writable($path)) {
				throw new \Exception("$path must be a valid, writable directory.");
			}

			$this->destination = $path;
			$this->mDB = $db;
		}

		public function Upload($renameDuplicates = true) {
			$this->renameDuplicates = $renameDuplicates;
			$uploaded = current($_FILES);

			if (is_array($uploaded['name'])) {
				/* Deal with multiple uploads */
				foreach ($uploaded['name'] as $key => $value) {
					$currentFile['name'] = $uploaded['name'][$key];
					$currentFile['type'] = $uploaded['type'][$key];
					$currentFile['tmp_name'] = $uploaded['tmp_name'][$key];
					$currentFile['error'] = $uploaded['error'][$key];
					$currentFile['size'] = $uploaded['size'][$key];

					if ($this->CheckFile($currentFile)) {
						$this->MoveFile($currentFile);
					}
				}
			} else {
				if ($this->CheckFile($uploaded)) {
					$this->MoveFile($uploaded);
				}
			}

			return $this->newName;
		}

		public function GetMessages() {
			return $this->messages;
		}

		public function GetMaxSize() {
			return number_format($this->max / 1024, 1).' KB';
		}

		public function SetMaxSize($num) {
			if (is_numeric($num) && $num > 0) {
				$this->max = (int)$num;
			}
		}

		public function AllowAllTypes($suffix = true) {
			$this->typeCheckingOn = false;

			if (!$suffix) {
				$this->suffix = '';
			}
		}

		protected function CheckFile($file) {
			$accept = true;

			if ($file['error'] != 0) {
				$this->GetErrorMessages($file);

				/* Stop checking if no file submitted */
				if ($file['error'] == 4) {
					return false;
				} else {
					$accept = false;
				}
			}

			if (!$this->CheckSize($file)) {
				$accept = false;
			}

			if ($this->typeCheckingOn) {
				if (!$this->CheckType($file)) {
					$accept = false;
				}
			}

			if ($accept) {
				$this->CheckName($file);
			}

			return $accept;
		}

		protected function GetErrorMessages($file) {
			switch ($file['error']) {
				case 1:
				case 2:
					$this->messages[] = $file['name'].' is too big: (max: '.
						$this->GetMaxSize().').';
					break;
				case 3:
					$this->messages[] = $file['name'].' was only partially uploaded.';
					break;
				case 4:
					$this->messages[] = 'No file submitted.';
					break;
				default:
					$this->messages[] = 'Sorry, there was a problem uploading '.$file['name'];
					break;
			}
		}

		protected function CheckSize($file) {
			if ($file['error'] == 1 || $file['error'] == 2) {
				return false;
			} else if ($file['size']  == 0) {
				$this->messages[] = $file['name'].' is an empty file.';
				return false;
			} else if ($file['size'] > $this->max) {
				$this->messages[] = $file['name'].' exceeds the maximum size for a file ('.
					$this->GetMaxSize().').';
				return false;
			} else {
				return true;
			}
		}

		protected function CheckType($file) {
			if (in_array($file['type'], $this->permitted)) {
				return true;
			} else {
				if (!empty($file['type'])) {
					$this->messages[] = $file['name'].' is not a permitted type of file.';
				}

				return false;
			}
		}

		protected function CheckName($file) {
			$this->newName = null;
			$nospaces = str_replace(' ', '_', $file['name']);

			if ($nospaces != $file['name']) {
				$this->newName = $nospaces;
			}

			$extension = pathinfo($nospaces, PATHINFO_EXTENSION);

			if (!$this->typeCheckingOn && !empty($this->suffix)) {
				if (in_array($extension, $this->notTrusted) || empty($extension)) {
					$this->newName = $nospaces.$this->suffix;
				}
			}

			if ($this->renameDuplicates) {
				$name = isset($this->newName) ? $this->newName : $file['name'];
				$existing = scandir($this->destination);

				if (in_array($name, $existing)) {
					/* Rename file */
					$basename = pathinfo($name, PATHINFO_FILENAME);
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$i = 1;

					do {
						$this->newName = $basename.'_'.$i++;

						if (!empty($extension)) {
							$this->newName .= ".$extension";
						}
					} while (in_array($this->newName, $existing));
				}
			}
		}

		protected function MoveFile($file) {
			$filename = isset($this->newName) ? $this->newName : $file['name'];
			$success = move_uploaded_file($file['tmp_name'], $this->destination.$filename);

			if ($success) {
				$fileQuery = "INSERT INTO files (file_name) VALUES (:name)";
				$fileRes = $this->mDB->prepare($fileQuery);
				$fileRes->bindParam(':name', $filename);
				$fileRes->execute();
				$fileQuery = $fileRes = null;

				$result = $file['name'].' was uploaded successfully';

				if (!is_null($this->newName)) {
					$result .= ', and was renamed to '.$this->newName;
				}

				$this->messages[] = $result;
			} else {
				$this->messages[] = 'Could not upload '.$file['name'];
			}
		}
	}
?>