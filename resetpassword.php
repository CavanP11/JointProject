<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "connection.php";

$new_password = ""; $confirm_password = "";
$new_password_err = ""; $confirm_password_err = "";
$id; $found = ""; $password;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["new_password"])) {
        $new_password_err = "Please enter a password";
    } else if (strlen($_POST["new_password"]) < 8) {
        $new_password_err = "Password must contain more than 8 characters";
    } else {
        $new_password = $_POST["new_password"];
    }
    
    if (empty($_POST["confirm_password"])) {
        $confirm_password_err = "Please confirm password";
    } else {
        $confirm_password = $_POST["confirm_password"];
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Passwords did not match";
        }
    }
    
    $id = $_SESSION["id"];
    $sql = "SELECT * FROM users WHERE id=$id";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    
    $password = $_POST["password"];
    $hashedpassword = $row['password'];
    
    if (password_verify($password, $hashedpassword)) {
        $found = "true";
    }
         if(empty($new_password_err) && !empty($found)) {
             $hashedpassword = password_hash($new_password, PASSWORD_DEFAULT);
             $sql = "UPDATE users SET password = '$hashedpassword' WHERE id = '$id'";
             $result = $conn->query($sql);
             session_destroy();
             header("location: login.php");
         } else { echo "Verification not working"; }
    echo $id;
}
?>

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
        <style>
        body{ font: 14px sans-serif; text-align: center;}
        span{ font: 30px sans-serif;}
        p{ font: 30px sans-serif; }
        label{ font: 20px sans-serif;}
        </style>
    <div class="wrapper">
        <br><br><br><br><br><br>
        <h2>Upload Password</h2>
        <br>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>