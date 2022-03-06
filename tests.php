<!DOCTYPE html>
<html lang="en">
<head>
    	<meta charset="utf-8">
	<title>HSE Customer Portal</title>
	<link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-dark bg-dark navbar-expand-md fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand" href="welcome.php"><img src="img/hse.png"></a> <button class="navbar-toggler" data-target="#navbarResponsive" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="welcome.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="close.php">Close Contacts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="tests.php">Antigen Tests
                        </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="resetpassword.php">Account</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">Log Out</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
    </body>
    <body>
        <style>
        body{ font: 14px sans-serif; text-align: center;}
        span{ font: 30px sans-serif;}
        p{ font: 30px sans-serif; }
        label{ font: 20px sans-serif;}
        </style>
    <div class="wrapper">
        <br><br><br><br><br><br>
        <h2>Upload Antigen Test</h2>
        <br>
        <span>Select image to upload:</span>
        <form action="tests.php" method="post" enctype="multipart/form-data">
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="file" name="image" id="image">
            <br><br>
          <input type="submit" value="Upload Image" name="submit">
          <input type="submit" name="view" value="View Image">
            <br><br>
        </form>
    </div>    
</body>
</html>


<?php
require_once "connection.php";
session_start();

$cipher = 'AES-256-CTR';
$key = '1lgs2gjwjPZpeqUHlYD9ktJBXfsuH5al';
    
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

$test = $_SESSION["id"];
$sql = "SELECT * FROM users WHERE id=$test";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

$imageraw = file_get_contents($_FILES["image"]["tmp_name"]);
$iv = hex2bin($row['iv']);

    
$image = openssl_encrypt($imageraw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    
$sql = "UPDATE users set image=\"". addslashes($image)."\" WHERE id=$test";
$result = $conn->query($sql);

    
} 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view'])){
        $test = $_SESSION["id"];
        
        $sql = "SELECT * FROM users WHERE id=$test";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
    
        $iv = hex2bin($row['iv']);

        $imgcipher = $row['image'];
        $img = openssl_decrypt($imgcipher, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        echo '<img src="data:image/jpeg;base64,'.base64_encode($img).'"/>';
}
        
?>
