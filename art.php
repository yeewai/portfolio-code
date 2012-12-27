<? include('header.php'); ?> 
<div class="content">
	<? if ($id = $_GET['id']) { 
		$item = getItem($id);
		?>
		<div class="item_images">
		<? printItemImages($id); ?>
		</div>
		<div class="textblock" id="itemdetails"> 
			<h2><? echo $item['title']; ?></h2>
			<span class="subtext">Date completed: <?  echo $item['date']; ?> <br />
            Media <?  echo $item['tools']; ?></span>
			<p><?  echo $item['description']; ?></p>
		</div>
        
        <div class="textblock" id="itemdetails"> 
			<h2>Other Pieces</h2>
			<ul class="allitems" id="centeredlist">
            	<? listAllItems('art', $id); ?>
            </ul>
		</div>
   	<? } else {
		echo '<ul class="allitems">';
   		listAllItems('art');
		echo '</ul>';
   
   	}
   	?>
</div>
<? include('footer.php'); ?> 