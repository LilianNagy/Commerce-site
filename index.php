<?php
	ob_start();
	session_start();
	$pageTitle = 'Homepage';
	include 'init.php';
?>
<style>
.column {
  float: left;
  width: 25%;
  padding: 10px;
}
.caption {
  padding-top: 8px;
  padding-bottom: 8px;
  color: #777;
  text-align: left;
}
/* The expanding image container */



	</style>
<div class="container">
	<div class="row">
        <div> <center> <h1>Shop</h1> </center> </div>
		<?php
			$allItems = getAllFrom('*', 'items', '', '', 'Item_ID');
			foreach ($allItems as $item) {
				echo '<div class="column">';
						echo '<span $' . $item['Price'] . '</span>';
						echo '<img src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div class="date">' . $item['Add_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>
<?php
	include $tpl . 'footer.php'; 
	ob_end_flush();
?>