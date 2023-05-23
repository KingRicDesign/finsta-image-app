<?php 
require_once( 'config.php' ); 
require_once( 'includes/functions.php' );
require( 'includes/header.php' );

if(! $loggedin_user){
    exit('Log in to view this page');
}
?>
<main class="content">
    <?php require 'includes/parse-upload.php' ?>
    <h2>Upload a post</h2>
    
    <?php show_feedback($feedback, $feedback_class, $errors);?>
    <form action="new-post.php" method="post" enctype="multipart/form-data">
    <label >Upload a .jpg, .gif, or .png file</label>
    <input type="file" name="uploadedfile" required accept="images/*">
    <input type="submit" value="Next: Add Post Details &rarr;">
    <input type="hidden" name="did_upload" value="1">
    </form>



</main>		
<?php 
require('includes/sidebar.php');
require('includes/footer.php');
?>