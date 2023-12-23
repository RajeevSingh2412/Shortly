<?php
//resuming the previous session
 session_start();
// clearing the data of sassion array
 $_SESSION=array();
 //destroying the session
 session_destroy();
 header("location:index.php");

?>