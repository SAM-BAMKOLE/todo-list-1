<?php 
require_once("./db.php");
$query = "SELECT * FROM todos ORDER BY id DESC LIMIT 5";
$response = $conn->query($query);
$response = $response->fetch_assoc();

$conn->close();
?>