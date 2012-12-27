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

function processEditingItem() {
	$query = 'UPDATE portfolio_items SET title="'.$_POST['title'].'", date="'.$_POST['date'].'", description="'.$_POST['description'].'", subcategory="'.$_POST['subcategory'].'", tools="'.$_POST['tools'].'", nsfw="'.$_POST['nsfw'].'", link="'.$_POST['link'].'", github="'.$_POST['github'].'" WHERE id="'.$_POST['id'].'"';
	$result = mysql_query($query) or die(mysql_error());
	
	if ($_FILES['thumbnail'] && $_FILES['thumbnail']['name'] !='') {
		$thumb_path = "assets/curated/".basename($_FILES['thumbnail']['name']); 
		if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_path)) {
			echo "The file ".  basename( $_FILES['thumbnail']['name']). 
			" has been uploaded<br />";
			
			$query = 'UPDATE portfolio_items SET thumbnail="'.$thumb_path.'" WHERE id="'.$_POST['id'].'"';
			$result = mysql_query($query) or die(mysql_error());
		} else{
			echo "There was an error uploading the file, please try again!";
			return false;
		}
	}
	
	echo 'Item has been updated!';
}

function processNewImage() {
	
	$image_path = "assets/curated/".basename( $_FILES['image']['name']); 
	if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
		echo "The file ".  basename( $_FILES['image']['name']). 
		" has been uploaded<br />";
	} else{
		echo "There was an error uploading the file, please try again!";
		return false;
	}
	$query = 'INSERT INTO portfolio_images (item_id, url, alt) 
						VALUES("'.$_POST['id'].'", "'.$image_path.'", "'.$_POST['alt'].'")';
		$result = mysql_query($query) or die(mysql_error());
	echo 'Image has been added!';
}

function processDeleteImage() {
	$query = 'DELETE FROM portfolio_images WHERE id="'.$_POST['id'].'"';
		$result = mysql_query($query) or die(mysql_error());
	echo 'Image has been deleted!';
}

function generateAdminBackLink($action='', $id='') {
	if ($id && $action) {
		return '<a href="manage.php?action='.$action.'&id='.$id.'">Back to '.$action.'</a>';
	} elseif ($action) {
		return '<a href="manage.php?action='.$action.'">Back to '.$action.'</a>';
	} else {
		return '<a href="manage.php">Back to Admin Panel</a>';
	}
}

?> 
<div class="content"><div class="textblock" id="admin">
<? 
if ($_SESSION['adminpw'] == $admin_pw || md5($_POST['pw']) == $admin_pw) {
	if ($_POST['pw']) { $_SESSION['adminpw'] = md5($_POST['pw']); }
	
	switch($_GET['action']) {
		case "new": 
		if ($_POST['process']) {
			processNewItem();
			echo generateAdminBackLink("new").' or '. generateAdminBackLink();
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
        <form class="admin" name="newItem" action="manage.php?action=new" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" >
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
		echo generateAdminBackLink();
		}
		break;
		case "edit":
		if ($_POST['process']=='newImage') {
			processNewImage();
		}elseif ($_POST['process']=='delete') {
			processDeleteImage();
		}elseif ($_POST['process']) {
			processEditingItem();
		} else {
			$item = getItem($_GET['id']);
			echo '<ul class="allitems" id="editing">';
			printItemImages($_GET['id'], true);
			echo '</ul>';
			echo '<form class="admin" name="editin" action="manage.php?action=edit&id='.$_GET['id'].'" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" >
				<input name="title" type="text" placeholder="Title" value="'.$item['title'].'"/><br />
				<input name="date" type="text" placeholder="yyyy-mm-dd" value="'.$item['date'].'"/><br />
				<textarea name="description" placeholder="Description">'.$item['description'].'</textarea><br />
				<input name="subcategory" type="text" placeholder="Subcategory" value="'.$item['subcategory'].'"/><br />
				<input name="tools" type="text" placeholder="Tools" value="'.$item['tools'].'"/><br />
				<input name="nsfw" type="checkbox" value="'.$item['nsfw'].'" />NSFW<br />
				<input name="link" type="text" placeholder="Link" value="'.$item['link'].'"/><br />
				<input name="github" type="text" placeholder="Github" value="'.$item['github'].'"/><br />
				Thumbnail: <input name="thumbnail" type="file" /><br />
				<input name="id" type="hidden" value="'.$_GET['id'].'" />
				<input name="process" type="hidden" value="penis" />
				<input type="submit" value="Edit item" />
			</form>';
			
			echo '<h3>Add another associated image?</h3>';
			echo '<form class="admin" name="editin" action="manage.php?action=edit&id='.$_GET['id'].'" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" >
				Thumbnail: <input name="image" type="file" /><br />
				<input name="alt" type="text" placeholder="Image Alt Text" /><br />
				<input name="id" type="hidden" value="'.$_GET['id'].'" />
				<input name="process" type="hidden" value="newImage" />
				<input type="submit" value="Add detail image" />
			</form>';
		}
		
		if ($_POST['process']) {echo generateAdminBackLink("edit", $_GET['id']).'or ';}
		echo generateAdminBackLink();
		break;
		case "logout":
			session_destroy();
			echo "You've been logged out.";
		break;
		default:
			?>
			<h2>What do you want to do?</h2>
            <p> <a href="manage.php?action=new">Create a new item</a></p>
            <p> <a href="manage.php?action=logout">Logout?</a></p>
            <p>Edit existing items?</p>
			<?
			echo '<ul class="allitems">';
			listAllItems('art',"",true);
   			listAllItems('code',"",true);
			echo '</ul>';
		break;	
	}
} else {
	?>
	
		You must be logged in!
		<form class="admin" action="manage.php" method="post">
		<input name="pw" type="password" />
		<input type="submit" value="Log in~" />
		</form>
	<?
}
?>
</div></div>
<? include('footer.php'); ?> 