
<style>
	input[type=text], select {
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


div.cont {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
	</style>
<?php

	/*
	================================================
	 Edit | Delete Members From Here
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Members';

	if (isset($_SESSION['user'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Add';

		// Start Manage Page
        if ($do == 'Add') { // Add Page ?>

		<?php 

		}
        elseif ($do == 'Insert') {

			// Insert Member Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		

				// Upload Variables

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Avatar Extension

				$avatarExtension = strtolower(end(explode('.', $avatarName)));

				// Get Variables From The Form

				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				$hashPass = sha1($_POST['password']);

				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}

				if (empty($pass)) {
					$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}

				if (empty($avatarName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}

				if ($avatarSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div>' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$avatar = rand(0, 10000000000) . '_' . $avatarName;

					move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

					// Check If User Exist in Database

					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div>Sorry This User Is Exist</div>';

						redirectHome($theMsg, 'back');

					} else {

						// Insert Userinfo In Database

						$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date, avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
						$stmt->execute(array(

							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name,
							'zavatar'	=> $avatar

						));

						// Echo Success Message

						$theMsg = "<div>" . $stmt->rowCount() . ' Record Inserted</div>';

						redirectHome($theMsg, 'back');

					}

				}


			} else {

				echo "<div >";

				$theMsg = '<div>Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} 
        elseif ($do == 'Edit') {

			// Check If Get Request userid Is Numeric & Get Its Integer Value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

			// Execute Query

			$stmt->execute(array($userid));

			// Fetch The Data

			$row = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<center><h1 >Edit Member</h1></center>
				<div class="cont">
					<form  action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
							<label> Username</label>
								<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
						<label>Password</label>
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
							<label>Email</label>
								<input type="email" name="email" value="<?php echo $row['Email'] ?>"  required="required" />
							<label>Full Name</label>
								<input type="text" name="full" value="<?php echo $row['FullName'] ?>"  required="required" />
								<input type="submit" value="Save" />
					</form>
				</div>
					

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div>";

				$theMsg = '<div>Theres No Such ID</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

		} 
        elseif ($do == 'Update') { // Update Page

			echo "<h1>Update Member</h1>";
			echo "<div>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				// Password Trick

				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div>' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$stmt2 = $con->prepare("SELECT 
												*
											FROM 
												users
											WHERE
												Username = ?
											AND 
												UserID != ?");

					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if ($count == 1) {

						$theMsg = '<div>Sorry This User Is Exist</div>';

						redirectHome($theMsg, 'back');

					} else { 

						// Update The Database With This Info

						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");

						$stmt->execute(array($user, $email, $name, $pass, $id));

						// Echo Success Message

						$theMsg = "<div>" . $stmt->rowCount() . ' Record Updated</div>';

						redirectHome($theMsg, 'back');

					}

				}

			} else {

				$theMsg = '<div>Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} 
        
		include $tpl . 'footer.php';

	} else {
        
		header('Location: members.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>