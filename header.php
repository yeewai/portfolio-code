<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>About Yee</title>
<link rel="stylesheet" type="text/css" href="style2012.css" />
<link rel="stylesheet" type="text/css" href="normalize.css" />
<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
</head>

<body>
	<? include('config.php'); ?>
    <?
	
	function getItem($id) {
	$query = 'SELECT * FROM portfolio_items WHERE id="'.$id.'"';
	$result = mysql_query($query) or die(mysql_error());
	return mysql_fetch_assoc($result);
}

function printItemImages($item_id, $admin=false) {
	$query = 'SELECT * FROM portfolio_images WHERE item_id="'.$item_id.'"';
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		if ($admin) {
			echo '<li>';
			printLinkedImage($row['url'], $row['alt']);
			echo '<form class="admin" name="editin" action="manage.php?action=edit&id='.$_GET['id'].'" method="post" >
				<input name="id" type="hidden" value="'.$row['id'].'" />
				<input name="process" type="hidden" value="delete" />
				<input type="submit" value="Delete Image" />
			</form>';
			echo '</li>';
		}else{
			printLinkedImage($row['url'], $row['alt']);
		}
	}
}

function printLinkedImage($url, $image_alt, $link ="") {
	if ($link =="") {$link = $url;}
	echo "<a href='$link'><img src='$url' alt='$image_alt'></a>";
}

function listAllItems($category, $exclude_id = "", $admin = false) {
	$query = 'SELECT * FROM portfolio_items WHERE category="'.$category.'"';
	if ($exclude_id) {$query .= ' AND id != "'.$exclude_id.'"'; }
	$query .= ' ORDER BY date DESC';
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		printItem($row, $admin);
	}
}

function printItem($item, $admin = false) {
	echo '<li>';
		if ($admin) {
			printLinkedImage($item['thumbnail'], $item['alt'], 'manage.php?action=edit&id='.$item['id']);
		} else {
			printLinkedImage($item['thumbnail'], $item['alt'], $item['category'].'.php?id='.$item['id']);
		}
	echo '	<span class="caption"><a href="code.php?id='.$item['id'].'">'.$item['title'].'</a></span>
		  </li>';
}

	?>
	<nav>
	<ul>
        <li id="blog"><a class="nav" href="http://emisketch.tumblr.com">Blog</a><a class="sub" href="http://emisketch.tumblr.com">emisketch.tumblr.com</a></li>
        <li id="art"><a class="nav" href="art.php">Art</a></li>
        <li id="center"></li>
        <li id="code"><a class="nav" href="code.php">Code</a><a class="sub" href="http://github.com/yeewai">github.com/yeewai</a></li>
        <li id="about"><a class="nav" href="about.php">About</a></li>
       
    </ul> 
    </nav>