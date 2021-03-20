<?php
/* Connect to an ODBC database using driver invocation */
$host="localhost";
$user = "danda";
$password = "i3FJQ8o9fujv2c";
$dbname = "danda_sammyeka_danda";

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