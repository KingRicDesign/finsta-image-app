<footer class="footer">&copy; 2023 Finsta</footer>
<!-- closes the css grip for the site -->
</div>
<?php 
if(DEBUG_MODE){
    include('includes/debugoutput.php');
}

?>

<?php 

if($loggedin_user){?>

<script type="text/javascript">
    document.body.addEventListener('click', function(e){
        if(e.target.className == 'heart-button'){


            likeUnlike(e.target);
        }
    });
//fetch has to be set to an async function for it to work properly, el is the span that is clicked
    async function likeUnlike(el){
        var userId = <?php echo $loggedin_user['user_id']?>;
        var postId = el.dataset.postid;
        //this makes sure only the button.likes that was clicked gets liked. Not every button with a class of .likes
        var container = el.closest('.likes');


        //this is the data that will be sent to the PHP handler after the trigger
        let formData = new FormData();
        formData.append('postId', postId);
        formData.append('userId', userId);
        // you must call fetch by using the var await, default to POST unless you have a specific reason to do GET - also no trailing commas - this is a JSON
        let response = await fetch('async-handlers/like-unlike.php', {
            method: 'POST', 
            body: formData 
        });
        if( response.ok ){
            //success
            let result = await response.text();
            container.innerHTML = result;
        }else{
            //error
            console.log(response.status)
        }

    }//end of async function


</script>

    <?php
}
?>

</body>
</html>