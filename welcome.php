<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
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
						<a class="nav-link active" href="welcome.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="close.php">Close Contacts</a>
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
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
        span{ font: 30px sans-serif; }
    </style>
</head>
<body>
    <br><br>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["fullname"]); ?></b>, welcome to the HSE customer portal.</h1>
    <span>Positive tests can now be uploaded to the HSE portal. Click <a href="tests.php">here</a> if you wish to upload a positive antigen test now.<br>
    We encourage everyone within Ireland to adhere to Government advice on the situation relating to COVID-19.</span><br><br><br>
    <img src="img/covid.jpg"><br><br>
    <span>The health of our citizens if our primary concern at all time and we wish everyone stays safe during this tough time.<br>
        If you test positive for COVID-19, please provide details of your close contacts <a href="close.php">here</a>.</span><br><br>
    <span>If you wish to view our terms & conditions again, please click <a href="terms.php">here.</a></span><br>
    <span> To view or privacy policies, please click <a href="privacy.php">here.</a></span>
</body>
</html>