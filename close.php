<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HSE Registration</title>
	<link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
    </head>
    <style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: white;
}

* {
  box-sizing: border-box;
}

.container {
  padding: 16px;
  background-color: white;
}

input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

.submitbutton {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.submitbutton:hover {
  opacity: 1;
}

a {
  color: dodgerblue;
}

.signin {
  background-color: #f1f1f1;
  text-align: center;
}
        
h2 {
    text-align: center;
}
p {
    font-size: 20px;
    text-align: center;      
}
	</style>
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
						<a class="nav-link active" href="close.php">Close Contacts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="tests.php">Antigen Tests
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
		<div class="container">
            <br><br><br>
			<h2>HSE Close Contacts</h2>
			<p>Please fill in the details of your close contacts below</p>
			<form id="myForm" action=""<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"" method="post">

					<label for="fullname"><b>Contacts Full Name</b></label>
					<input type ="text" placeholder= "Enter Full Name" name="fullname" class="form" required>

					<label for="phone"><b>Contacts Phone Number</b></label>
					<input type ="text" placeholder= "Enter Phone Number" name="phone" class="form" required>


					<button type="submit" class="submitbutton">Add Contact</button>
                
			</form>
		</div>
	</body>
	</html>

<?php
session_start();
require_once "connection.php";
$cipher = 'AES-256-CTR';
$id = $_SESSION["id"];
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $sql = "SELECT * FROM users WHERE id=$id";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    
    $key = $_SESSION['username'];
    
    $iv = hex2bin($row['iv']);
    
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $fullname = openssl_encrypt($fullname, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $fullname = bin2hex($fullname);
    
    $phone = $conn->real_escape_string($_POST['phone']);
    $phone = openssl_encrypt($phone, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $phone = bin2hex($phone);

    if (!empty($fullname) && !empty($phone)) {
        $sql = "INSERT INTO closecontacts (id, fullname, phone) VALUES ('$id', '$fullname', '$phone')";
        
        if ($conn->query($sql) === TRUE) {
            echo 'Success';
            header("location: close.php");
        } else { die; }
    }
}

$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

$key = $_SESSION['username'];
$iv = hex2bin($row['iv']);

$sql = "SELECT * FROM closecontacts where id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "
        <br><Center><span> Close Contact Table</span></center>
        <style>
            table, th, tr {
                border:2px solid black;
                font: 30px sans-serif;
                text-align: center;
                font-weight: bold;
            }
            span {
              font: 40px sans-serif;
              text-align: center;
            }
        </style>
        <center><table style='width:43.2%'>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
            </tr>";
}
  
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $fullname = hex2bin($row['fullname']);
        $fullname = openssl_decrypt($fullname, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            
        $phone = hex2bin($row['phone']);
        $phone = openssl_decrypt($phone, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        echo "
        <style>
            td {
            border:2px solid black;
              font: 30px sans-serif;
              text-align: center;
            }
        </style>
        <center>
            <tr>
                <td>$fullname</td>
                <td>$phone</td>
            </tr>
            <br>
        </center>";
        }
    }
?>