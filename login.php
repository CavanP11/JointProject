<?php
session_start();
// Check if user is logged in, and if so, redirect them
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "connection.php";

$username = ""; $username_error = "";
$password = ""; $password_error = "";
$found = "";
$cipher = 'AES-256-CTR';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $key = $_POST['username'];
              
	if (empty($_POST["username"])) {
		$username_error = "Enter a username";
        echo $username_error;
	} else {
		$username = trim($_POST["username"]);
	}

	if (empty($_POST["password"])) {
		$password_error = "Please enter a password";
        echo $password_error;
	} else {
		$password = $_POST["password"];
	}

    $sql = "SELECT id, username, password, fullname, iv FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $iv = hex2bin($row['iv']);
            $hashedpassword = $row['password'];
                 
            $user = $row['username'];
            $usernameCompare = hex2bin($row['username']);
            $usernameCompare2 = openssl_decrypt($usernameCompare, $cipher, $key, OPENSSL_RAW_DATA, $iv);

            if ($username == $usernameCompare2) {
                $found = "found";
                echo "Username is correct";
                
            $fullname = hex2bin($row['fullname']);
            $fullname = openssl_decrypt($fullname, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            }
            
            if (!empty($found)) {
                if (password_verify($password, $hashedpassword)) {
                    echo "Password is correct";
                    session_start();
                    
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["user"] = $user;
                    $_SESSION["username"] = $username;
                    $_SESSION["fullname"] = $fullname;
                    header("location: welcome.php");
                    break;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HSE Registration</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
        body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: white;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
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

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
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

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
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
	</head>
	<body>
		<div class="container">
			<h2>NHS Registration</h2>
			<p>Please fill this form to log in</p>
			<form id="myForm" action=""<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"" method="post">

					<label for="username"><b>Username</b></label>
					<input type ="text" placeholder= "Enter Username" name="username" class="form" required>

					<label for="password"><b>Password</b></label>
					<input type ="password" placeholder= "Enter Password" name="password" class="form" required>

					<button type="submit" class="submitbutton">Login</button>
                
				<p>If you are not already registered, <a href="register.php">register here</a></p>
			</form>

		</div>
	</body>
	</html>