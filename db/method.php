<?php 
require 'connection.php';

$payntid = $_GET["pay"];
$id = $conn->prepare("SELECT * FROM pay_methods WHERE id=$payntid");
?>