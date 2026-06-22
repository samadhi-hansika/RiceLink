<?php
$conn = new mysqli("localhost","root","","ricelink1");

if($conn->connect_error){
    die("Database Error: " . $conn->connect_error);
}
?>