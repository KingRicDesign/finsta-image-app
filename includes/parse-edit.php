<?php 
$errors = array();
$feedback = '';
$feedback_class = '';

//which post are we trying to edit?
//URL Will look like edit-post.php?post_id=X
if(isset($_GET['post_id'])){
    $post_id = filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
    #step 2B - after retrieving the post_id and checking if it is a safe and valid number, check the numbers value. IF less than zero, set it to zero. 
    if($post_id < 0){
        $post_id = 0;
    }
}


//parse the form if they hit submit
if( isset( $_POST['did_edit'] ) ){
	//sanitize everything
	$title = trim(strip_tags($_POST['title']));
	$body = trim(strip_tags($_POST['body']));
	
	$category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
if(isset($_POST['allow_comments']) ){
	$allow_comments = 1; 
}else{
	$allow_comments = 0;
}


#this is a shorter version of writing a if else,ternary operator example 
$is_published = isset($_POST['is_published']) ? 1 : 0;

	


	//validate	
	$valid = true;
		//title blank or longer than 100
if($title == '' OR strlen($title) > 100){
	$valid = false;
	$errors['title'] = 'Title must be between 1 - 100 characters long.';

}

		//body longer than 500		
		if($body == '' OR strlen($body) > 500){
			$valid = false;
			$errors['body'] = 'Caption must be less than 500 characters long.';
		
		}

		//category must be positive int
		if($category_id < 1){
			$valid = false;
			$errors['category_id'] = 'Must select a category.';
		}

	
	//if valid, update the post in the DB
	if($valid){
		$result = $DB->prepare('UPDATE posts SET title = :title, body = :body, category_id = :cat,
		allow_comments = :comments, is_published = :publish 
		WHERE post_id = :post_id AND user_id = :user_id
		LIMIT 1');
		$result->execute( array(
			'title' => $title,
			'body' => $body,
			'cat' => $category_id,
			'comments' => $allow_comments,
			'publish' => $is_published,
			'post_id' => $post_id,
			'user_id' => $loggedin_user['user_id']
		));
		if( $result->rowCount() ){
			#success
			$feedback = 'Save Successful';
			$feedback_class = 'success';
		}else{
			#if no success - this error is about the changes to the post, Say something like, Nothing changed 
			$feedback = 'Your post was NOT saved';
			$feedback_class = 'info';


		}


	}else{
		$feedback = 'Your post could not be saved, Please fix the following:';
		$feedback_class = 'error';
	}	


}//end if did edit


//Pre-fill the form values/security check 
//is the viewer of the page the author of this post? (if so, grab all the info to fill the form)


$result = $DB->prepare('SELECT * FROM posts WHERE post_id = :post_id AND user_id = :user_id LIMIT 1 ');
$result->execute(array(
	'post_id' => $post_id,
	'user_id' => $loggedin_user['user_id']
));
if($result->rowCount()){
	$row = $result->fetch();
#extract converts array into variables like $title, $body
	extract($row);

}else{
	exit('you are not allowed to edit this post');
}