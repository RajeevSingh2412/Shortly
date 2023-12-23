<?php


  define('DB_Server','localhost');
  define('DB_USERNAME','root');
  define('DB_Password','');
  define('DB_DataBaseName','urlshort');
  $conn=mysqli_connect(DB_Server,DB_USERNAME,DB_Password,DB_DataBaseName);
  if($conn==false){
        die('Error : Cannot connect');
  }

?>