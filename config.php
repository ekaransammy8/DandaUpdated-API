<?php

/* Connect to an ODBC database using driver invocation */
$host="localhost";
$user = "root";
$password = "";
$dbname = "dandapp_dandapp";

/*danda@sammyekaran.com
v!5psI+SPC0p*/

try 
{
    
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname",$user,$password);  


} 
catch (PDOException $e)
{
    $e->getMessage();
   // $pdo=null;
   
}
?>