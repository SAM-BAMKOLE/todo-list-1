<?php 
$host ="localhost" ;
$username = 'root';
$password = "";
$database = "todo_list_1";

// create a connection
$conn = new mysqli($host, $username, $password, $database);

// confrm the connection
if ($conn->connect_error) {
    die("Connection failed " . $conn->connect_error);
}

?>