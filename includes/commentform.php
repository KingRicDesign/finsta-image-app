<section class="comment-form">
    <h2>Leave a Comment</h2>



    <?php show_feedback($feedback, $feedback_class); ?>
    <!-- post is a good default method -->

    <form action="single.php?post_id= <?php echo $post_id ?> " method="post">
    <label for="thebody">Your comment</label>
    <textarea name="body" id="thebody" ></textarea>
    <input type="submit" value="comment">
    <input type="hidden" name="did_comment" value="1"> 
    </form>
</section>