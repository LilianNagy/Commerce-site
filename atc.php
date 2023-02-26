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
        <center><h1 >Added To Cart Items</h1></center>
<?php
        $stmt = $con->prepare("SELECT * FROM cart WHERE Member_P_ID = ?");
			// Execute The Statement
			$stmt->execute(array($userid));
			// Assign To Variable 
			$items = $stmt->fetchAll();
            foreach($items as $itemonly)
            {
                   // Select All Data Depend On This ID
	           $stmt = $con->prepare("SELECT 
								items.*, 
								brands.Name AS brand_name, 
								users.Username 
							FROM 
								items
							INNER JOIN 
								brands 
							ON 
								brands.ID = items.Brand_ID 
							INNER JOIN 
								users 
							ON 
								users.UserID = items.Member_ID 
							WHERE 
								Item_ID = ?");

                    // Execute Query
                    $stmt->execute(array($itemonly['Item_ID']));

                    $count = $stmt->rowCount();

                    if ($count >= 0) {
                        $item = $stmt->fetch();
?>
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
<h1 style="text-align:center"><?php echo $item['Name'] ?></h1>
	<div class="item">
			<img  src="img.png" alt="" />
			<h2><?php echo $item['Name'] ?></h2>
			<p><?php echo $item['Description'] ?></p>
			<ul style="margin: 24px 0;">
				<li>
					<span>Added Date</span> : <?php echo $item['Add_Date'] ?>
				</li>
				<li>
					<span>Price</span> : <?php echo $item['Price'] ?>
				</li>
				<li>
					<span>Brand</span> : <a href="brands.php?pageid=<?php echo $item['Brand_ID'] ?>"><?php echo $item['brand_name'] ?></a>
				</li>		
                <?php 
                    echo '<h3><a href="purchase.php?cartid='. $itemonly['Cart_ID'] .'">Purchase</a></h3>';
		          ?>
        </ul>
</div>                 
<?php
                    }
 
            }
} 
else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>