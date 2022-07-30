<?php
    // iclude connection with database
    include("dbConnection.php");
    // start session
    if ( session_id() === "" ) { session_start(); }

    // select username and password from user table in database
    $query = mysqli_query($conn,"SELECT name, password From pharmacist");

    // if user submit username and password
    if(isset($_POST['submit'])){
        $username = $_POST['username']; // get username from input field
        $password = $_POST['password']; // get password from input field
        if(mysqli_num_rows($query) > 0){
            while ($row = mysqli_fetch_assoc($query)){
                if($username == $row['name'] && $password == $row['password']){
                    $_SESSION['name'] = $username;         // if user entered username and password matched with database username and password start session
                    $_SESSION['pass'] = $password;
                    header('location:home.php');            // if username and password is correct redirect to home page
                }
                else{
                    $_SESSION['error_msg'] = "Username or Password is incorrect!";
                }
            } 
        }
        else{
            echo "query failed";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    
    <style class="">
        .bottom-margin{
            margin-bottom:10px;
        }
        .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -15px;
            border-width: 15px;
            border-style: solid;
            border-color: white transparent transparent transparent;
        }
        input[type='text']{
            background-image:url('image/user.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
        }
        input[type='password']{
            background-image:url('image/password.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
        }


    </style>

    <!--Starting Javascript codes-->
    <script>
        //check if the input is empty
        function validate(){
            let user = document.getElementById('user').value;
            let pass = document.getElementById('pass').value;
            let noneCharacter = /\W/g;
            let digits = /[0-9]/g;
            let capitalLetters = /[A-Z]/g;    
            if (user == "" || pass == ""){
                document.getElementById('message').style.display = "block";
                document.getElementById('message').innerText = "Username or Password can't be empty!";
                return false;
            }
            else if(noneCharacter.test(user)){
                document.getElementById('message').style.display = "block";
                document.getElementById('message').innerText = "Only digits and letters are allowed!";
                return false;
            }
            else if (!noneCharacter.test(pass)){
                document.getElementById('message').style.display = "block";
                document.getElementById('message').innerText = "Password must contain symbols!";
                return false;
            }

            else{
                return true;
            }
        }
        
        //clean the input on focus
        function clean(e){
            e.value = "";
            if(document.getElementById('err_msg') != null){
                document.getElementById('err_msg').style.display = "none";
            }
            if(document.getElementById('message') != null){
                document.getElementById('message').style.display = "none";
            }        
        }

    </script>
</head>

<body class="w3-container" style="background-image:url('image/cupsol.jpg')">
    

    <div class="w3-content w3-card-4 w3-display-middle" style="width:50%;">
        <div class="tooltip w3-display-container w3-light-gray w3-center w3-round">
                <img src="image/login_img.png" alt="Avatar" class="w3-image w3-circle w3-margin-top" style="width:20%;">
                <div class="w3-container w3-padding-16">Authentication</div>
        </div>

        <form onsubmit="return validate();" name="login_form" class="w3-container w3-indigo" action='<?php echo htmlspecialchars("login.php");?>' method="post">
            <p class="w3-padding-16">
                <input autocomplete="off" id="user" onfocus="clean(this)" class="w3-input w3-border w3-round-large" type="text" name="username"
                placeholder="| Username" style="padding-left:40px;">
            </p>
            <p>
                <input autocomplete="off" id="pass" onfocus="clean(this)" class="w3-input w3-border w3-round-large" type="password" name="password" placeholder="| Password" style="padding-left:40px;">
            </p>
            <p id="message" class="w3-panel w3-pale-red w3-text-red w3-round" style="display:none;">
                <?php
                    
					if (isset($_SESSION['error_msg'])) {
				     ?>
				     	<div id="err_msg" class="w3-panel w3-pale-red w3-text-red">
				     		<?php
				     		   echo $_SESSION['error_msg'];
				     		   unset($_SESSION['error_msg']);
				     		?>
				     	</div>
				     <?php
					}
				?>
            </p>
            <p>
                <input type="submit" name="submit" value="LOGIN" class="w3-btn w3-black w3-right w3-round w3-mobile bottom-margin">
            </p>
        </form>   
    </div>


</body>
</html>