<?php

session_start();
$session=false;

$shorturl="";
$shorturlerror="";
$mainlink="";
$username="";

if(isset($_SESSION['loggedin'])){
    $session=true;
    $username=$_SESSION['username'];
}

require_once "confiq.php";
if(isset($_POST['submit'])){
    $mainurl=$_POST['mainurl'];
    $shorttext=$_POST['shorttext'];
    $shorturl = "http://localhost/Shortly/" . $shorttext;
    $sql1="SELECT * FROM `urltable` WHERE txt='$shorttext' ";
    $res=mysqli_query($conn,$sql1);
    if($res){
        if(mysqli_num_rows($res)>0){
            $shorturlerror="Please enter a unique text";
        }
        else{
            $sql="INSERT INTO `urltable`(link,short_link,txt,statuss,hit_count,username) VALUES(?,?,?,1,0,?)";
             $stmt=$conn->prepare($sql);
             $stmt->bind_param("ssss",$mainurl,$shorturl,$shorttext,$username);
             $stmt->execute();
              if (!$stmt) {
                 die("Error: " . $conn->error);
             }
        }
    }

}
if(isset($_GET['ID']))
{
    $id=$_GET['ID'];
    $sql="SELECT link FROM `urltable` WHERE ID='$id' ";
    $res=mysqli_query($conn,$sql);
    if($res){
        $row=mysqli_fetch_assoc($res);
        $mainlink=$row['link'];
        header("location:$mainlink");
        exit();
    }


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
</head>
<body>
    <div class="main">
        <div class="container">
           <div class="sidepanel">
                <div class="upper">
                    <div class="logo">                     
                    </div>
                    <div class="logotext">
                       <p>Shortly</p>
                    </div>
                </div>
                <div class="middle">
                    <div class="welcome">
                        <?php
                        if($session){
                            echo '<p id="para1">Welcome</p>';
                            echo '<p id="para2">'.$_SESSION['username'].'</p>';
                        }
                        else{
                            echo '<p id="para1">Guest ID</p>';
                            echo '<p id="para2">Please Login to enjoy more features</p>';
                        }
                        ?>
                    </div>
                    <div class="dashboard">
                        <a href="Dashboard.php">Dashboard</a>
                    </div>
                </div>
                <div class="bottom">
                    <div class="signup"><a href="Signup.php">Sign Up</a></div>
                    <div class="signin"><a href="Signin.php">Login</a></div>
                    <div class="logout"><a href="Logout.php">Logout</a></div>
                </div>          
            </div>
            <div class="other">
                <form action="" method="POST">
                  <div class="url-input-container">
                    <input type="url" class="url-input" placeholder="Enter URL" name="mainurl" required>
                    <br><br>
                    <input type="text" class="url-input" placeholder="Enter Short & Unique Text" name="shorttext" required>
                    <br><br>
                    <button type="submit" class="submit-btn" name="submit">GET URL</button>
                  </div>
                  
                </form>
                <div class="output">
                    <div class="short-link-container">
                        <div class="short-link-label">Short Link:</div>
                        <?php
                        
                          if($shorturlerror!=""){
                            echo '<div class="short-link-output"> Please enter a unique text</div>';
                          }
                          else if($shorturl==""){
                            echo '<div class="short-link-output"> No URL Generated Yet!</div>';
                          }
                          else{
                           require_once "confiq.php";
                           $sql="SELECT ID FROM `urltable` WHERE short_link='$shorturl' ";
                           $res=mysqli_query($conn,$sql);
                           $row=mysqli_fetch_assoc($res);
                           $id=$row['ID'];
                            echo '<div class="short-link-output"><a href="index.php?ID='.$id.' ">'.$shorturl.'</a></div>';
                          }
                          ?>
                        
                    </div>

                </div>
                
          </div>
        </div>
        
    </div>
</body>
</html>

<style>
    *{
        margin:0;
        padding:0;
        border:0;
        font-family: Arial, sans-serif;
        background-color: lightslategrey;
    }
    .main{
       width:100%;
       height:100vh;
       display:flex;
       justify-content:center;
       align-items:center;
    }
    .container{
        width:90%;
        height:90vh;
        background-color:#fff;
        border-radius:2%;
        display:flex;
    }
    .sidepanel{
        width:20%;
        background-color:midnightblue;
        height:100%;
        border-radius:5%;
        position:relative;
        top:-5%;
        display:flex;
        flex-direction:column;
        align-items:center;
       
    }
    .upper{
        position:relative;
        top:5%;
        background-color:midnightblue;
        width:80%;
        height:10%;
        display:flex;
        justify-content:space-evenly:  
        /* align-items:center;   */
    }
    .logo{
        width:50%;
        height:80%;
        background-image:url("favicon.png");
        background-size:contain;
        background-repeat:no-repeat;
        background-color:midnightblue;
    }
    .logotext{
        width:30%;
        height:90%;
        background-color:midnightblue;
        color:#fff;
        display:flex;
        align-items:center;
    }
    .logotext p{
        background-color:midnightblue;
        font-size:1.2rem;
        font-weight:bold;
    }
    .middle{
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        align-items:center;
        width:80%;
        height:40%;
        position:relative;
        top:8%;
        background-color:midnightblue;
        
    }
    .welcome{
        background-color:midnightblue;
    }
    .welcome p{
        background-color:midnightblue;
        color:#fff;
        font-weight:bold;
    }
    #para1{
        font-size:1.5rem;
    }
    .dashboard{
        background-color:midnightblue;
    }
    .dashboard a{
        background-color:midnightblue;
        font-weight:bold;
        color:#fff;
        text-decoration:none;
        font-size:1.5rem;
    }
    .bottom{
        display:flex;
        flex-direction:column;
        justify-content:space-evenly;
        align-items:center;
        width:80%;
        height:20%;
        position:relative;
        top:25%;
        background-color:midnightblue;
    }
    .bottom div{
        background-color:midnightblue;
    }
    .bottom div a{
        font-weight:bold;
        text-decoration:none;
        background-color:midnightblue;
        color:#fff;
    }





    .other{
        width:75%;
        height:100%;
        background-color:white;
    }

    form{
        width:100%;
        height:40%;
        background-color:white;
    }
        .url-input-container {
            position:relative;
            left:30%;
            top:10%;
            width: 50%;
            background-color:white;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .url-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #3498db;
            border-radius: 5px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            color:Black;
            background-color:thistle;
        }

        .url-input:focus {
            outline: none;
            border-color: #2ecc71;
            box-shadow: 0 0 5px rgba(46, 204, 113, 0.5);
        }
        .submit-btn{
            position:relative;
            left:30%;
            width:50%;
            height:2rem;
            border-radius:5px;
            background-color:thistle;
            border: 2px solid #3498db;
        }
        .submit-btn:active{
            background-color:#3498db;
        }
    .output{
        width:100%;
        height:40%;
        background-color:white;
    }
    .short-link-container {
            position:relative;
            left:30%;
            top:10%;
            width: 50%;
            height:70%;
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display:flex;
            flex-direction:column;
            justify-content:space-evenly;
            text-align:center;
            align-items:center;
        }

        .short-link-label {
            width:60%;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color:thistle;
            height:20%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .short-link-output {
            width:80%;
            font-size: 20px;
            color: #3498db;
            word-break: break-all;
            background-color:thistle;
            height:20%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .short-link-output a{
            text-decoration:none;
            background-color:thistle;
        }


</style>