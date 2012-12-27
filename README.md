# Portfolio Code

This is the php code for my portfolio site. See (hopefully live) demo at [emihaumut.com](http://emihaumut.com/ "Emihaumut.com")

---
###What does it do???
It has a password protected admin interface to upload, categorize, add description to, and delete images. It will query the database to fetch the proper images to be displayed in the portfolio. 

###config.php Setup
The site requires db setup and an admin password to run. The config.php file should look something like:

    $link = mysql_connect("localhost", "USERNAME","PASSWORD") or die(mysql_error());
    @mysql_select_db("DATABASENAME",$link) or die(mysql_error());
    $admin_pw = 'md5(PASSWORD)';

The password does have to be an md5 hash because that's what it uses. 
