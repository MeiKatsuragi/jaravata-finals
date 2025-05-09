<?php
function getDBConnection(){

$host = 'maglev.proxy.rlwy.net';
$port = 25832;
$user = 'root';
$password = 'ELuySJlrhYXuqotUgqxMZdJdyOmXVeOh';
$dbname = 'railway';

// Create connection
$connection = new mysqli($host, $user, $password, $dbname, $port);

if($connection->connect_error){
    die("Error: Failed to connect to MySQL. ".$connection->connect_error);
}

return $connection;
}

?>
