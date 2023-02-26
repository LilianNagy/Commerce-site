<?php
	ob_start();
	session_start();
	$pageTitle = 'Register';
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'init.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			// Check If The User Exist In Database

			$stmt = $con->prepare("SELECT 
										UserID, Username, Password 
									FROM 
										users 
									WHERE 
										Username = ? 
									AND 
										Password = ?");

			$stmt->execute(array($user, $hashedPass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// If Count > 0 This Mean The Database Contain Record About This Username

			if ($count > 0) {

				$_SESSION['user'] = $user; // Register Session Name

				$_SESSION['uid'] = $get['UserID']; // Register User ID in Session

				header('Location: index.php'); // Redirect To Dashboard Page

				exit();
			}

		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'Sorry Password Cant Be Empty';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'Sorry Password Is Not Match';

				}

			}


			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {

				// Check If User Exist in Database

				$check = checkItem("Username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'Sorry This User Is Exists';

				} else {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 
											users(Username, Password, Email, GroupID, Date)
										VALUES(:zuser, :zpass, :zmail, 0, now())");
					$stmt->execute(array(

						'zuser' => $username,
						'zpass' => sha1($password),
						'zmail' => $email

					));

					// Echo Success Message

					$succesMsg = 'Congrats You Are Now Registerd User';

				}

			}

		}

	}

?>
<style>
    .input-container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: grey;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

    </style>

    <center><h1><span data-class="signup">Sign up</span> </h1> </center>

<!-- Start Login Form -->
<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
        <label ><b>Username</b></label>
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
		
                <label><b>Password</b></label>
			<input 
				minlength="4"
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type a Complex password" 
				required />
                <label><b>Repeat Password</b></label>
			<input 
				minlength="4"
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="Type a password again" 
				required />
                <label for="email"><b>Email</b></label>
			<input 
				type="email" 
				name="email" 
				placeholder="Type a Valid email" />
		
		<input class="registerbtn" name="signup" type="submit" value="Signup" />
    </div>
</form>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div>' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div >' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>