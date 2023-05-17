


<?php 
#get all the approved comments on THIS specific post.
#1)prepare
$result = $DB->prepare('
                        SELECT comments.*, users.username, users.profile_pic
                        FROM comments,users
                        WHERE comments.user_id = users.user_id
                        AND is_approved = 1
                        AND post_id = ?
                        ORDER BY date DESC
                        LIMIT 5
                        ');
#2) execute
$result->execute( array( $post_id ));
#3) check
$totalcomments = $result->rowCount();
if($totalcomments){


?>
<section class="comments">
    <h2><?php echo $totalcomments; ?> comments on this post</h2>

    <?php #4) loop
    while( $row = $result->fetch() ){ ?>
    <div class="card">
    <div class="user">
					<?php user_info( $row['user_id'], $row['username'], $row['profile_pic']); ?>
					</div>


        <p><?php echo $row['body']; ?></p>
        <span class="date"><?php echo time_ago($row['date']); ?></span>
    </div>
    <?php }//ends step 4)loop ?>
</section>
<?php }//ends step 3)check ?>