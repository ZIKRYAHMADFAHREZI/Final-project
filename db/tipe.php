<?php 
require 'connection.php';

$tipeid = $_GET["tipe"];
$id = $conn->prepare("SELECT * FROM types WHERE id=$tipeid");
?>