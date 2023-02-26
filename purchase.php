<?php
	ob_start();
	session_start();
	$pageTitle = 'Show Items';
	include 'init.php';
	// Check If Get Request item Is Numeric & Get Its Integer Value
	$cartid = isset($_GET['cartid']) && is_numeric($_GET['cartid']) ? intval($_GET['cartid']) : 0;

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];


	 $stmt = $con->prepare("SELECT * FROM cart WHERE Member_P_ID = ? LIMIT 1");
        // Execute The Statement
        $stmt->execute(array($userid));
        // Assign To Variable 
        $items = $stmt->fetchAll();
         foreach($items as $itemonly)
            {
                 $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? LIMIT 1");
                // Execute The Statement
                $stmt->execute(array($itemonly['Item_ID']));
                // Assign To Variable 
                $members = $stmt->fetchAll();
                foreach($members as $member)
                {
                     $stmt = $con->prepare("UPDATE 
												users 
											SET 
												Balance = Balance + ? 
											WHERE 
												UserID = ?");

					$stmt->execute(array($member['Price'],$member['Member_ID'])); 
                }
            
            }
    // Select All Data Depend On This ID , update cart with purchase equal 1 lama ndos purchase
    $stmt = $con->prepare("UPDATE 
												cart 
											SET 
												Purchased = ? 
											WHERE 
												Cart_ID = ?");

					$stmt->execute(array(1,$cartid)); 
    echo '<center><h1>Item Has Been Purchased</h1></center>';
        
    
?>

<?php
    include $tpl . 'footer.php';
	ob_end_flush();
?>