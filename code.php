<? include('header.php');  

function getItem($id) {
	$query = 'SELECT * FROM portfolio_items WHERE id="'.$id.'"';
	$result = mysql_query($query) or die(mysql_error());
	return mysql_fetch_assoc($result);
}

function printItemImages($item_id) {
	$query = 'SELECT * FROM portfolio_images WHERE item_id="'.$item_id.'"';
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		printLinkedImage($row['url'], $row['alt']);
	}
}

function printLinkedImage($url, $image_alt) {
	echo "<a href='$url'><img src='$url' alt='$image_alt'></a>";
}

?>
<div class="content">
	<? if ($id = $_GET['id']) { 
		$item = getItem($id);
		?>
		<div class="item_images">
		<? printItemImages($id); ?>
		</div>
		<div class="textblock" id="itemdetails"> 
			<h2><? echo $item['title']; ?></h2>
			<span class="subtext"><?  echo $item['date']; ?></span>
			<? if ($item['link']) { echo '<a href="'.$item['link'].'" class="subtext">demo</a>  '; } ?>
			<? if ($item['github']) { echo '<a href="'.$item['github'].'" class="subtext">github</a>'; } ?>
			<p class="tools">Made in <?  echo $item['tools']; ?></p>
			<p><?  echo $item['description']; ?></p>
		</div>
   	<? } else {
   
   
   	}
   	?>
</div>
<? include('footer.php'); ?> 