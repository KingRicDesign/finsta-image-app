<?php 
$feedback = '';
$feedback_class='';

$errors = array();
#the user submitted the rigster form
if( isset($_POST['did_register'])){

#sanitize everything
$username = trim(strip_tags($_POST['username']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = trim(strip_tags($_POST['password']));
#the array item in post, named policy, will not exsist if the user does not check the box. So check if policy is set
if(isset($_POST['policy']) ){
    $policy = 1;
}else{
    $policy = 0;
}

#validate
$valid = true;

# paswword requirements:
// no max length: 8
// no max length 
if( strlen($password) < 8 ){
    $valid = false;
    $errors['password']='Password must be longer than 8 characters';
}

# username requirements:
// min 5 chars 
// max 30 chars 
// username must be unique
if( strlen($username) < 5 OR strlen($username) > 30 ){
    $valid = false;
    $errors['username']='Username must be between 5 and 30 characters.';
}else{
    #if the length of the username is fine check the database to see if username exists
    $result = $DB->prepare('
    SELECT username FROM users WHERE username = ? LIMIT 1');
    $result->execute( array($username));
    if($result->rowCount()){
        $valid = false;
        $errors['username']='Username not available, Try another';
    }//end of $->rowCount()
}//end of else


//must be valid email that isn't blank
//must be unique
if( ! filter_var($email, FILTER_VALIDATE_EMAIL) ){
    $valid = false;
    $errors['email'] = 'Not a valid email,try another';

}else{
    #if the length of the username is fine check the database to see if username exists
    $result = $DB->prepare('
    SELECT email FROM users WHERE email = ? LIMIT 1');
    $result->execute( array($email));
    if($result->rowCount()){
        $valid = false;
        $errors['email']='email in use, Try logging in';
    }//end of $->rowCount()
}//end of else


# policy must be checked
if($policy = 0){
    $valid =false;
    $errors['policy']='Please agree to our policies';
}

if($valid){
    #a salt and hash of the password
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    $result = $DB->prepare(
        'INSERT INTO users
        (username, profile_pic, password, email, bio, is_admin, join_date)
        VALUES
        (:username, :pic, :pass, :email, :bio, 0, NOW() )
        ');
    $result->execute(array(
        'username' => $username,
        'pic' => '',
        'pass' => $hashed_pass,
        'email' => $email,
        'bio' => ''
    ));
    if($result->rowCount()){
        $feedback = 'Your sign up was a success';
        $feedback_class = 'success';
    }else{
        $feedback = 'something went wrong with the form, Please try again in a few minutes';
        $feedback_class = 'errors';
    }
}else{
    //invalid form submission
    $feedback = 'Your registration is incomplete, Fix the following';
    $feedback_class = 'errors';
}
}


#ifvalid, add to user database


#handle feedback

//no need to close, the ened of the file wil close for you