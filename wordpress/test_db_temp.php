<?php
$host = 'db';
$user = 'toysport';
$pass = 'toysport123';
$dbname = 'toysport_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}
echo "Connected successfully to MySQL\n";
$conn->close();
