<?php 
#login
require('config.php'); 
require_once('includes/functions.php');
require_once('includes/parse-login.php');
//doctype and visible header
require('includes/header.php');
?>
<style>
	.errors{
		background-color: orange ;
	}
	.success{
		background-color: cyan ;
	}

</style>
<main class="content">
	<div class="important-form">
	<h1>Login in</h1>
<?php show_feedback($feedback, $feedback_class, $errors) ?>
	<form method="post" action="login.php">
		<label>Username:</label>
		<input type="text" name="username">

		<label>Password:</label>
		<input type="password" name="password">


		<input type="submit" value="Login">
		<input type="hidden" name="did_login" value="1">
	</form>
	</div>
</main>

<?php include('includes/footer.php'); ?>