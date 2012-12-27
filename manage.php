<? 
session_start();
include('header.php'); 


function processNewItem() {
	//Should check to see if file exists already....
			$thumb_path = "assets/curated/".basename( $_FILES['thumbnail']['name']); 
			if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_path)) {
				echo "The file ".  basename( $_FILES['thumbnail']['name']). 
				" has been uploaded<br />";
			} else{
				echo "There was an error uploading the file, please try again!";
				return false;
			}
			
			$image_path = "assets/curated/".basename( $_FILES['image']['name']); 
			if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
				echo "The file ".  basename( $_FILES['image']['name']). 
				" has been uploaded<br />";
			} else{
				echo "There was an error uploading the file, please try again!";
				return false;
			}
			
			$query = 'INSERT INTO portfolio_items (title, date, description, category, subcategory, tools, nsfw, link, github, thumbnail) 
						VALUES("'.$_POST['title'].'","'.$_POST['date'].'","'.$_POST['description'].'","'.$_POST['category'].'",
								"'.$_POST['subcategory'].'","'.$_POST['tools'].'","'.$_POST['nsfw'].'","'.$_POST['link'].'",
								"'.$_POST['github'].'","'.$thumb_path.'")';
			$result = mysql_query($query) or die(mysql_error());
			
			$query = 'SELECT * FROM portfolio_items WHERE title="'.$_POST['title'].'" LIMIT 1';
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			
			$query = 'INSERT INTO portfolio_images (item_id, url, alt) 
						VALUES("'.$row['id'].'", "'.$image_path.'", "'.$_POST['alt'].'")';
			$result = mysql_query($query) or die(mysql_error());
			
			echo "<h2>Your item has been created! Hurrah~</h2>";
}
?> 
<div class="content"><div class="textblock" id="skills">
<? 
if ($_SESSION['adminpw'] == $admin_pw || md5($_POST['pw']) == $admin_pw) {
	if ($_POST['pw']) { $_SESSION['adminpw'] = md5($_POST['pw']); }
	
	switch($_GET['action']) {
		case "new": 
		if ($_POST['process']) {
			processNewItem();
		} else {
		?>
        <h2>Let's make a baby</h2>
        <script>
		function checkField(field, msg) {
			if (field==null || field==""){
		  		alert(msg);
		  		return false;
		  	} else {
				return true;	
			}
		}
		
		function validateForm(){
			var checked = true;
			var fields = [document.forms["newItem"]["title"].value, 
							document.forms["newItem"]["date"].value, 
							document.forms["newItem"]["description"].value, 
							document.forms["newItem"]["thumbnail"].value,
							document.forms["newItem"]["image"].value];
			var fieldnames = ["Title", "Date", "Description", "Thumbnail", "Main image"];
			for (var i=0;i<fields.length && checked;i++) {
				checked = checkField(fields[i], fieldnames[i] + " needs to be set");
			}
			return checked;
			
		}
		</script>
        <form name="newItem" action="manage.php?action=new" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" >
            <input name="title" type="text" placeholder="Title" /><br />
            <input name="date" type="text" placeholder="yyyy-mm-dd" /><br />
            <textarea name="description" placeholder="Description"></textarea><br />
            <input name="category" type="radio" value="art" checked/>Art <input name="category" type="radio" value="code" />Code<br />
            <input name="subcategory" type="text" placeholder="Subcategory" /><br />
            <input name="tools" type="text" placeholder="Tools" /><br />
            <input name="nsfw" type="checkbox" value="1" />NSFW<br />
            <input name="link" type="text" placeholder="Link" /><br />
            <input name="github" type="text" placeholder="Github" /><br />
            Thumbnail: <input name="thumbnail" type="file" /><br />
            Main Image: <input name="image" type="file" /><br />
            <input name="alt" type="text" placeholder="Image Alt Text" /><br />
            <input name="process" type="hidden" value="penis" />
            <input type="submit" value="Make new item" />
		</form>
        
		<? 
		}
		break;
		
		default:
			?>
			<h2>What do you want to do?</h2>
            <p> <a href="manage.php?action=new">Create a new item</a></p>
			<?
		break;	
	}
} else {
	?>
	
		You must be logged in!
		<form action="manage.php" method="post">
		<input name="pw" type="password" />
		<input type="submit" value="Log in~" />
		</form>
	<?
}
?>
</div></div>
<? include('footer.php'); ?> 