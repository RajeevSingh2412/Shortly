<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    header("location:signin.php");
}

if(isset($_GET['id'])){
    require_once "confiq.php";
    $sql="DELETE FROM `urltable` WHERE id=?";
    $stmt=mysqli_prepare($conn,$sql);
    if($stmt){
        $param_id=$_GET['id'];
        mysqli_stmt_bind_param($stmt,"i",$param_id);
        if(mysqli_stmt_execute($stmt)){
            echo '<script>window.alert(Deleted Successfuly)</script>';
        }
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
            <div class="navbar">
                <a href="index.php"><div class="logo"></div></a>
                <?php
                echo '<h2>'.$_SESSION['username'].'</h2>';
                ?>
            </div>
            <div class="content">
                <table>
                    <tr>
                      <th>S.No</th>
                      <th>ShortName</th>
                      <th>Shortlink</th>
                      <th>Delete</th>
                    </tr>
                    <?php
                       require_once "confiq.php";
                       $sql="SELECT id,short_link,txt FROM `urltable` WHERE username=?";
                       $stmt=mysqli_prepare($conn,$sql);
                       if($stmt){
                        $param_username=$_SESSION['username'];
                        mysqli_stmt_bind_param($stmt,"s",$param_username);
                        
                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);
                            
                            if(mysqli_stmt_num_rows($stmt)>0){
                                mysqli_stmt_bind_result($stmt,$id,$shortlink,$shorttext);
                                $i=1;
                                while(mysqli_stmt_fetch($stmt)){
                                    echo '<tr>
                                             <td>'.$i.'</td>
                                             <td>'.$shorttext.'</td>
                                             <td>'.$shortlink.'</td>
                                             <td><a href="Dashboard.php?id='.$id.' "><i class="fa-solid fa-trash"></i></a></td>
                                        </tr>';
                                        $i++;
                                }
                            }
                            else{
                              echo '<tr><td colspan="4">No records found</td></tr>';
                            }
                        }
                       }
                       mysqli_stmt_close($stmt);
                       mysqli_close($conn);
                    
                    ?>
                </table>

            </div>
        </div>
    </div>
</body>

<style>
     *{
        margin:0;
        padding:0;
        border:0;
        font-family: Arial, sans-serif;
    }
    .main{
       width:100%;
       height:100vh;
       background-color: lightslategrey;
       display:flex;
       justify-content:center;
       align-items:center;
    }
    .container{
        width:90%;
        height:90vh;
        background-color:#fff;
        display:flex;
        flex-direction:column;
        align-items:center;
    }
    .navbar{
        width:92%;
        height:15%;
        background-color:#ba68c8;
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding-left:4%;
        padding-right:4%;
    }
    .navbar a{
        width:10%;
        height:100%;
    }
    .navbar h2{
        color:#fff;
    }
    .logo{
        width:100%;
        height:100%;
        background-image:url("favicon.png");
        background-size:contain;
        background-repeat:no-repeat;
        transform:translate(0%,15%);
        cursor:pointer;
    }
    .content{
        width:92%;
        height:80%;
        box-shadow: 0 0 14px rgba(0, 0, 0, 0.6);
        margin-top:1rem;
        margin-left:1rem;
        border-radius: 12px 12px 0 0;
    }
    table{
        width:100%;
        border-collapse: collapse;
        border-spacing: 0;
        box-shadow: 0 2px 15px rgba(64,64,64,.7);
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }
    td , th{
        padding: 15px 20px;
        text-align: center;
    }
    th{
      background-color: #ba68c8;
      color: #fafafa;
      font-family: 'Open Sans',Sans-serif;
      font-weight: 200;
      text-transform: uppercase;
    }
    tr{
      width: 100%;
      background-color: #fafafa;
      font-family: 'Montserrat', sans-serif;
    }
    tr:nth-child(even){
     background-color: #eeeeee;
    }

</style>