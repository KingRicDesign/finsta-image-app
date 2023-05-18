<?php

#pre-define vars
$feedback = '';
$feedback_class = '';
$errors = array();
$access_token='';
 
                #~~~~~~~~~~~~~~~~~log out function~~~~~~~~~~~~~~~~~~~~

//if they're trying to log out, destroy the session and cookies
if( isset( $_GET['action'] ) AND $_GET['action'] == 'logout' ){
    //expire the cookies
    setcookie( 'access_token', 0, time() - 99999 );
    setcookie( 'user_id', 0, time() - 99999 );
    //destroy the session
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    // Finally, destroy the session.
    session_destroy();
    #redirect after session_destory, this stops the cookies from sticking
    header('location:login.php');


}//end logout




            #~~~~~~~~~~~~~~~~~~~~~~~login function~~~~~~~~~~~~~~~

#process the data
if( isset($_POST['did_login']) ){
    #sanitize everything & validate
    #1 sanitize first
    $username = trim(strip_tags($_POST['username']));
    $password = trim(strip_tags($_POST['password']));
    
    #2 then validate
    #validate
    $valid = true;

    # paswword requirements: if you ever need to copy and past a large chunch of code, consider making a function instead 
    // no max length: 8
    // no max length 
    if( strlen($password) < 8 ){
    $valid = false;

    }

    # username requirements:
    // min 5 chars 
    // max 30 chars 
    // username must be unique
    if( strlen($username) < 5 OR strlen($username) > 30 ){
    $valid = false;

    }

    #if valid is true
    if($valid){
        #get the username from the database, the username in the select refers to the username in our database
        $result = $DB->prepare('  SELECT user_id, password FROM users WHERE username = ?  LIMIT 1');
        #this $username is referring to the text our user inputed
        $result->execute(array($username));
        #if we found the user, verify the password
        #when results equals one, you don't need to loop it. but u still need to fetch()
        if( $result->rowCount() ){
            $row = $result->fetch();
            #password_verify is built in to php, only way to check something that has been stored with thapssword hash function
            if( password_verify( $password, $row['password'] ) ){
                #success, log em in
                $feedback = 'Logging in';
                $feedback_class = 'success';
                #generate the access token
                $access_token= bin2hex(random_bytes(30));

                #store it in the db
                $result = $DB->prepare('UPDATE users
                SET access_token = :token
                WHERE user_id = :id ');
                $result->execute(array(
                    'token' => $access_token,
                    'id' => $row['user_id']
                ));

                #store it as a cookie
                $exp = time() + 60 * 60 * 24 * 7;
                setcookie('access_token', $access_token, $exp);

                #hash user ID 
                $hashed_id = password_hash($row['user_id'], PASSWORD_DEFAULT);
                setcookie('user_id', $hashed_id, $exp);

                $_SESSION['access_token'] = $access_token;
                
                $_SESSION['user_id'] = $hashed_id;


            }else{
                $feedback = 'Incorrect username or Password';
                $feeback_class = 'error';
            }
        }else{
            $feedback = 'Incorrect Username or password';
           $feeback_class = 'error';
        }

    }else{ $feedback = 'Incorrect Username or Password';
           $feeback_class = 'error';}




}#end login form parser