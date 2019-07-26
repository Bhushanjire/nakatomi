<?php
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");
 header('Content-Type: application/json');
 $postdata = file_get_contents("php://input");
 $conn=mysqli_connect('localhost','root','','nakatomi');
?>