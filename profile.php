<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	if (isset($_SESSION['user'])) {
		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
		$userid = $info['UserID'];
?>
<center>
<style>
	.item {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}


a {
  text-decoration: none;
  font-size: 22px;
  color: black;
}
button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}


button:hover, a:hover {
  opacity: 0.7;
}
</style>
<h1 >My Profile</h1>
</center>
<center><h3>My Information</h3></center>

<div class="item">
	<p>
			<img  src="img.png" alt="" />
				    <li>Login Name: <?php echo $info['Username'] ?> </li>
					<li>Email: <?php echo $info['Email'] ?> </li>
					<li>Full Name : <?php echo $info['FullName'] ?> </li>
					<li>Registered Date : <?php echo $info['Date'] ?> </li>
    </p>
</div>
<?php 
            echo "<center><p><a href='members.php?do=Edit&userid=" . $userid . "' </a>Edit</p></center>";
?>

		
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>