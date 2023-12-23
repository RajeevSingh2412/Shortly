<?php

session_start();
if(isset($_SESSION['loggedin'])){
    header("location:index.php");
}

require_once "confiq.php";
$username=$password=$confirm_password="";
$username_err=$password_err=$confirm_password_err="";
if(isset($_POST['submit'])){
    if(empty(trim($_POST['username']))){
         $username_err="Username can't be empty";
         echo '<script>window.alert("Username cannot be blank")</script>';
    }
    else{
        $sql="SELECT * FROM `user` WHERE username=?";
        $stmt=mysqli_prepare($conn,$sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt,"s",$param_username);
            $param_username=trim($_POST['username']);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)!=0){
                    $username_err="Username already taken";
                    echo '<script>window.alert("Username already taken")</script>';
                }
                else{
                    $username=trim($_POST['username']);
                }
            }
        }
    }
    if(empty(trim($_POST['password']))){
        $password_err="Password cannot be empty";
        echo '<script>window.alert("Password cannot be blank")</script>';
    }
    if((strlen(trim($_POST['password']))<=5 || (strlen(trim($_POST['password'])))>=16)){
        $password_err="Password length should be between 5 and 16 characters";
        echo '<script>window.alert("Password length error")script>';
    }
    if(trim($_POST['password'])!=trim($_POST['confirm_password'])){
        $confirm_password_err="Confirm password didn't matched your password";
        echo '<script>window.alert("Confirm password did not matched")</script>';
    }
    if(empty($username_err) && empty($password_err) && empty($password_confirm_err)){
        $sql="INSERT INTO `user` (username,password) VALUES(?,?)";
        $stmt=mysqli_prepare($conn,$sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt,"ss",$param_username,$param_password);
            $param_username=$username;
            $param_password=password_hash((trim($_POST['password'])),PASSWORD_DEFAULT);
            if(mysqli_stmt_execute($stmt)){
                header("location: Signin.php");
            }
            else{
                die("Error");
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
        <div class="container">
            
            <form action="" method="post">
                <h2>Signup</h2>
                <label for="username">Username</label>
                
                <?php 
                   if($username_err){
                      echo '<input type="text" name="username" id="errors" placeholder=" '.$username_err. ' " required>';
                   }
                   else{
                    echo '<input type="text" name="username" placeholder="Enter your username" required>';
                   }
                   echo '<label for="password" required >Password</label>';
                   if($password_err){
                     echo '<input type="password" name="password" id="errors" placeholder=" '.$password_err.'" required>';
                   }
                   else{
                    echo '<input type="password" name="password" id="password" placeholder="Enter your password" required>';
                    // echo '<i class="fa-solid fa-eye" id="togglePassword"></i>';
                   }
                   echo '<label for="password" required>Confirm Password</label>';
                   if($confirm_password_err){
                      echo '<input type="password" name="confirm_password" id="errors" placeholder="Confirm password did not matched" required>';
                   }
                   else{
                      echo '<input type="password" name="confirm_password" placeholder="Enter confirm password" id="password" required>';
                   }
                ?>
                <h3>Already have an account? <a href="Signin.php">Login</a></h3>
                <button name="submit">Submit</button>
                
            </form>
            
        </div>
</body>
<style>
    *{
        margin:0;
        padding:0;
        border:0;
        font-family: Arial, sans-serif;
        background-color: lightslategrey;
    }
    .container{
         display:flex;
         background-color: #fff;
         width:40%;
         height:90vh;
         margin-top:5vh;
         margin-bottom:5vh;
         position:relative;
         left:30%;
         border-radius:2em;
    }
    form{
        background-color: #fff;
        display:flex;
        flex-direction:column;
        justify-content:space-evenly;
        width:100%;
        height:100%;
        border-radius:2em;
    }
    form h2{
        background-color: #fff;
        text-align:center;
    }
    form label{
        background-color: #fff;
        position:relative;
        left:5%;
        width:95%;
        font-weight:bold;
        font-size:1.2rem;
    }
    form input{
        background-color: #fff;
        border:2px solid white;
        width:80%;
        position:relative;
        left:10%;
        top:-1.5rem;
        height:5%;
        border-radius:0.5rem;
        background-color:thistle;
        color:black;
        font-size:1.2rem;
    }
    form input::placeholder{
        position:relative;
        left:1.2rem;
    }
    form input:focus{
        border:2px solid midnightblue;
        /* background-color: midnightblue; */
    }
    form i{
        background-color: thistle;
        position:relative;
        left:80%;
        top:-12.5%;
        width:5%;
        cursor:pointer;
    }
    form h3{
        background-color: #fff;
        position:relative;
        left:5%;
        width:95%;
    }
    #errors::placeholder{
        color:red;
        position:relative;
        left:1.2rem;
    }
    form h3 a{
        background-color: #fff;
    }
    form button{
        background-color: #fff;
        width:20%;
        position:relative;
        left:40%;
        border:2px solid black;
        height:8%;
        border-radius:2em;
        font-size:1.3rem;
        background-color:thistle;
        cursor:pointer;
    }
    form button:active{
        border:2px solid thistle;
        background-color: midnightblue;
    }

</style>

<script>
    
    const togglepassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglepassword.addEventListener('click', function (e) {
        togglePassword(this);
    });
    function togglePassword(icon) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        icon.classList.toggle("fa-eye-slash");
    }
</script>