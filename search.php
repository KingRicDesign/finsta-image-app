<?php 
require_once( 'config.php' ); 
require_once( 'includes/functions.php' );

#paginate with LIMIT and limit offet
#set per page
$per_page=9;

$current_page=1;
#override current page
if(isset($_GET['page'])){
	$current_page = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
}


#parsing search data
#sanatize and validat
if(isset($_GET['phrase'])){
    $phrase =   trim(strip_tags($_GET['phrase']));
}else{
    $phrase = '';
}



require( 'includes/header.php' );
?>
<main class="content">

	<div class="posts-container flex three four-600 five-900">
		<?php 
		//get all the MATCHING published posts from the DB
		//1. write it (prepare the statement)
 #using named place holders over ?, :phrase, our named place holder, will be used as the container holding the search string submited by the user. 


 #creating a variable named query
 $query = 'SELECT *
 FROM posts
 WHERE posts.is_published = 1
 AND (title LIKE :phrase OR body LIKE :phrase)
 ORDER BY posts.date DESC';
		$result = $DB->prepare($query); 
		//2. run it (execute)
 #the percentages create the phrase variable to be used in our LIKE. This is for the named place holder
		$result->execute( array('phrase' => "%$phrase%") );

        $total = $result->rowCount();
		//3. check it (were any posts found?)


    $max_pages =ceil($total / $per_page);
	if($current_page < 1 OR $current_page > $max_pages){ $current_page = 1;}

#if you want to use variables with limit u cannot just pass it through an array because it will treat the numeric value as a string. INSTEAD try DATABINDING for LIMIT
	$offset = ($current_page - 1) * $per_page;

	$query .= ' LIMIT :offset, :per_page';
	#prepare it agian
	$result = $DB->prepare($query);
	#run it again
	$wildcard = "%$phrase%";
	$result->bindParam('phrase', $wildcard, PDO::PARAM_STR);
	
	$result->bindParam('offset', $offset, PDO::PARAM_INT);	
	$result->bindParam('per_page', $per_page, PDO::PARAM_INT);



	$result->execute();

	// debug_statement($result);




         ?>   
         <section class="full"><h2><?php echo $total ?> Search Results for <?php echo  $phrase ?></h2></section>
         <?php
		if( $result->rowCount() ){
		//4. loop it
			while( $row = $result->fetch() ){

?>

	<article class="post">
		<div class="card">
			<div class="post-image-header">

				<a href="single.php?post_id=<?php echo $row['post_id']?>">
				<?php show_post_image( $row['image'], 'small', $row['title']); ?>
				</a>
			</div>

			<footer>
				<h3 class="post-title clamp"><?php echo $row['title']; ?></h3>
				<p class="post-excerpt clamp"><?php echo $row['body']; ?></p>
				<div class="flex post-info">							
					<span class="date"><?php echo time_ago($row['date']); ?></span>	
					<span class="comment-count">
						<?php echo count_comments( $row['post_id'] ); ?>
					</span>
				</div>
			</footer>
		</div><!-- .card -->
	</article> <!-- .post -->
		<?php 
			} //end while
			$back= $current_page - 1;
			$next =$current_page + 1;
			?>
<section class="pagination full" style="text-align: center;">
<?php if( $current_page > 1 ){ ?>
	<a class="button" href="search.php?phrase=<?php echo $phrase?>&amp;page=<?php echo $back?>"><- Back</a>
<?php }?>
<?php if( $current_page < $max_pages ){ ?>
	<a class="button" href="search.php?phrase=<?php echo $phrase?>&amp;page=<?php echo $next?>">Next -></a>
<?php }?>
</section>


		<?php
		}else{
			echo '<h2>No posts to show</h2>';
		} 
		?>

        <section class="full" style="text-align: center;">
		<h4>Showing page <?php echo $current_page; ?> of <?php echo $max_pages; ?></h4>

		</section>
	</div><!-- .posts-container -->
</main>		
<?php 
require('includes/sidebar.php');
require('includes/footer.php');
?>