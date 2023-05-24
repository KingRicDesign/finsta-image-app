<?php 
#template for a single posts

require_once( 'config.php' ); 
require_once( 'includes/functions.php' );

#how to get the ID of the post clicked on, The post that will be shown in our template.

#step 1 - pre determine $post_id variable
$post_id = 0;


#step 2 - validate the _GET data (sanitize and validate)
if(isset($_GET['post_id'])){
    $post_id = filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
    #step 2B - after retrieving the post_id and checking if it is a safe and valid number, check the numbers value. IF less than zero, set it to zero. 
    if($post_id < 0){
        $post_id = 0;
    }
}
#parse before the header
require( 'includes/header.php' );
require('includes/parse-comment.php');

?>

<style>

    .success{background: cyan;}
    .error{background: orange;}
</style>
<main class="content">

    <?php #get all the info about THIS post
    #write it, run it, check it, loop it
	$result = $DB->prepare('SELECT po.*, ca.*, users.username, users.profile_pic
							FROM posts AS po, categories AS ca, users
							WHERE po.user_id = users.user_id
                            AND po.category_id = ca.category_id
							AND po.is_published = 1
                            AND po.post_id = ?
							ORDER BY po.date DESC
							LIMIT 1');
$result->execute( array($post_id));


if( $result->rowCount() ){
        //loop it
        while( $row = $result->fetch()){
    ?>
    <article class="post">
        <div class="card flex one two-700">
            <div class="post-image-header two-third-700">     
            <?php show_post_image( $row['image'], 'large', $row['title']); ?>
            </div>
            <footer class="third-700">   
            <div class="post-header">
                <!-- todo code in the actual profile pic -->
                    <div class="user">
					<?php user_info( $row['user_id'], $row['username'], $row['profile_pic']); ?>
					</div>
					
                <h3><?php echo $row['title'] ?></h3>
                <p><?php echo $row['body'] ?></p>
                
				<div class="flex post-info">							
					<span class="date"><?php echo time_ago($row['date']); ?></span>	
					<span class="comment-count">
						<?php echo count_comments( $row['post_id'] ); ?>
					</span>
					<span class="category"><?php echo $row['name']; ?></span>		
				</div>
            </footer>
        </div>

    </article>
    <?php 
    //load the comments on this post

#sometimes when queries interact with each other you need to create a new variable bfore the if statement and then call to that variable.
$allow_comments = $row['allow_comments'];

}//close while
require('includes/comments.php');

if( $allow_comments == 1 AND $loggedin_user){
    require('includes/commentform.php');
}
}//close if
else{
    echo '<h2>Post not found</h2>';
}//close else
?>




</main>		
<?php 
require('includes/sidebar.php');
require('includes/footer.php');
?>