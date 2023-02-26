<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
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
											users(Username, Password, Email, RegStatus, Date)
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

input[type=text]{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: grey;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

div.container-login {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
.signin {
  background-color: #f1f1f1;
  text-align: center;
}

	</style>
<div class="container-login">
		<center><h1><span  data-class="login">Login</span> </h1> </center>

	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<label >First Name</label>
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
	
				<label >Password</label>
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type your password" 
				required />
		
		<input  name="login" type="submit" value="Login" />
		<div class="container-login signup">
    <p>Create an Account<a href="signup.php">Sign up</a>.</p>
	<p>Create an Account as a market<a href="admin/signup.php">Sign up</a>.</p>
    <p>Login AS Market<a href="admin/index.php">Click Here</a>.</p>

  </div>
	</form>
</div>

	<div >
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div>' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div>' . $succesMsg . '</div>';

			}

		?>
	</div>

<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>