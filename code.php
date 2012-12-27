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
			<span class="subtext"><?  echo $item['date']; ?></span>
			<? if ($item['link']) { echo '<a href="'.$item['link'].'" class="subtext">View code in action</a>  '; } ?>
			<? if ($item['github']) { echo '<a href="'.$item['github'].'" class="subtext">github</a>'; } ?>
			<p class="tools">Made in <?  echo $item['tools']; ?></p>
			<p><?  echo $item['description']; ?></p>
		</div>
        
        <div class="textblock" id="itemdetails"> 
			<h2>Other Projects</h2>
			<ul class="allitems" id="centeredlist">
            	<? listAllItems('code', $id); ?>
            </ul>
		</div>
   	<? } else {
		echo '<ul class="allitems">';
   		listAllItems('code');
		echo '</ul>';
   
   	}
   	?>
</div>
<? include('footer.php'); ?> 