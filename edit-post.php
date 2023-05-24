<?php 
require('CONFIG.php'); 
require_once('includes/functions.php');
require('includes/header.php');
if(! $loggedin_user){
	exit('You must be logged in to edit.');
}
require('includes/parse-edit.php'); 
?>
<style>.info{background-color: #ede4d5;}</style>
<main class="content">
	<div class="flex one two-700 reverse">
		<section class="preview-image">		
			<img src="uploads/<?php echo $image;?>_medium.jpg" alt="">	
		</section>
		<section class="edit-form">
			<h2>Edit Post</h2>
			<?php show_feedback( $feedback, $feedback_class, $errors ); ?>
			<form method="post" action="edit-post.php?post_id=<?php echo $post_id;?>">
				<label> Title</label>
				<input type="text" name="title" value="<?php echo $title;?>">
				<label>Caption</label>
				<textarea name="body"><?php echo $body;?></textarea>
				<label>Category</label>
				<?php category_dropdown(); ?>
				<label>
					<input <?php echo checked( $allow_comments, 1); ?> type="checkbox" name="allow_comments" value="1">
					<span class="checkable">Allow Comments</span>					
				</label>
			
				<label>
					<input type="checkbox" name="is_published" value="1"  <?php echo checked( $is_published, 1); ?>>
					<span class="checkable">Make this post public</span>
				</label>

				<input type="submit" value="Save Post" >
				<input type="hidden" name="did_edit" value="1">
			</form>
		</section>
	</div>
</main>
<?php 
require('includes/sidebar.php'); 
require('includes/footer.php'); 