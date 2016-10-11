<?php
	class LoginClass {
		private $mDB;
		private $username;
		private $password;

		function sec_session_start() {
			$sessionName = 'sec_session_id';
			$secure = false; /* false is for development ONLY! */
			$httponly = true;

			/* If the session is not secure */
			if (ini_set('session.use_only_cookies', 1) === FALSE) {
				/* Show an error and exit */
				header('Location: error.php/Could not start a secure session');
				exit();
			}
			
			/* Set up cookie parameters */
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params($cookieParams["lifetime"],
				$cookieParams["path"],
				$cookieParams["domain"],
				$secure,
				$httponly);
			
			/* Set the session name */
			session_name($sessionName);
			
			/* Start the session and regenerate the session id */
			session_start();
			session_regenerate_id(true);
		}

		function login_check() {
			/* If the session variables are set */
			if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
				/* The user is logged in */
				return true;
			} else {
				/* The user is not logged in */
				return false;
			}
		}

		function Login($username, $password, $db) {
			$loginQuery = "SELECT id, full_name, password FROM admins WHERE username=:username LIMIT 1";
			$loginRes = $db->prepare($loginQuery);
			$loginRes->bindParam(':username', $username);
			$loginRes->execute();

			/* Fetch the user information */
			while ($row = $loginRes->fetch(PDO::FETCH_ASSOC)) {
				/* If the password matches */
				if ($row['password'] === $password) {
					$userBrowser = $_SERVER['HTTP_USER_AGENT'];

					/* Set the session variables */
					$_SESSION['user_id'] = $row['id'];
					$_SESSION['username'] = $username;
					$_SESSION['login_string'] = hash('sha512', $password.$userBrowser);
					$_SESSION['full_name'] = $row['full_name'];

					/* Login successful */
					return "Success";
				} else {
					/* Login failed */
					return "Invalid Password";
				}
			}

			/* Login failed */
			return "Fail";
		}
	};
?>