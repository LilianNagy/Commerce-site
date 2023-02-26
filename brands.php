<?php 
	session_start();
	include 'init.php';
?>
<style>
.caption {
  padding-top: 8px;
  padding-bottom: 8px;
  color: #777;
  text-align: left;
}
.column {
  float: left;
  width: 25%;
  padding: 10px;
}
</style>

<div class="container">
	<center>
	<h1 >Show Brands Items</h1></center>
	<div class="row">
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
			$brand = intval($_GET['pageid']);
			$allItems = getAllFrom("*", "items", "where Brand_ID = {$brand}", "", "Item_ID");
			foreach ($allItems as $item) {
					echo '<div class="column">';
						echo '<span ' . $item['Price'] . '</span>';
						echo '<img  src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div >' . $item['Add_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		} else {
			echo 'You Must Add Page ID';
		}
		?>
	</div>
</div>

<?php include $tpl . 'footer.php'; ?>