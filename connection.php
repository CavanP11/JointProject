<?php
// Login to databse
$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
	die('Connection Failed: ' . $conn->connect_error);
}

$sql = 'CREATE DATABASE IF NOT EXISTS hse;';
if (!$conn->query($sql) === TRUE) {
	die('Error creating database: ' . $conn->error);
}

$sql = 'USE hse;';
if (!$conn->query($sql) == TRUE) {
	die('Error using database: ' .$conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS users (
id int NOT NULL AUTO_INCREMENT,
username varchar(256) NOT NULL,
password varchar(256) NOT NULL,
fullname varchar(256) NOT NULL,
address varchar(256) NOT NULL,
dob varchar(256) NOT NULL,
phone varchar(256) NOT NULL,
iv varchar(256) NOT NULL,
PRIMARY KEY (id));';

if (!$conn->query($sql) === TRUE) {
	die('Error creating table: ' .$con->error);
}
?>