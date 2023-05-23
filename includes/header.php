<?php 
$loggedin_user = check_login();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Finsta - Image Sharing</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" type="image/x-icon" sizes="32x32" href="favicon.ico">
</head>
<body>
	<div class="site">
		<header class="header">
			<nav>
				<a href="index.php" class="brand">
					<img class="logo" src="images/logo-color.png" />
					<span>Finsta</span>
				</a>
				
				<!-- responsive-->
<input id="menu-button" type="checkbox" class="show">
<label for="menu-button" class="burger pseudo button">&#9776;</label>

<div class="menu">
                       
    <form action="search.php" method="get" class="searchform">
        <input type="search" name="phrase" placeholder="Search">
        <input type="submit" style="background-color: #00bfff;"value="search">
    </form>

<?php if( $loggedin_user ){
 ?>
 	<a href="login.php?action=logout"  style="background-color: #00bfff;" class="button">
		Log Out
	</a>
	<a href="new-post.php" class="button">
		&plus; New Post
	</a>
		<a href="profile.php"class="button">
		<?php echo $loggedin_user['username']; ?>
	</a>

<?php }else{
 ?>
	<a href="register.php" style="background-color: #00bfff;"class="button">
		Sign Up
	</a>
	<a href="login.php"  class="button">
		Login
	</a>


<?php }
 ?>




</div>  
				
			</nav>
		</header>