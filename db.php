<?php

$server="localhost";
$db="zgas";
$user="root";
$password="";

try{
    $conexion = new PDO("mysql:host=$server;dbname=$db",$user,$password);
}catch(Exception $ex){
    echo $ex->getMessage();
}

?>