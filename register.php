<?php
require_once "connection.php";
 
$cipher = 'AES-256-CTR';
$iv = random_bytes(16); $iv_hex = bin2hex($iv);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $key = $_POST['password'];
    
    $usernameOriginal = $conn->real_escape_string($_POST['username']);
    $username = openssl_encrypt($usernameOriginal, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $username = bin2hex($username);
    $address = $conn->real_escape_string($_POST['address']);
    $address = openssl_encrypt($address, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $address = bin2hex($address);

    $dob = $conn->real_escape_string($_POST['dob']);
    $dob = openssl_encrypt($dob, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $dob = bin2hex($dob);

    $phone = $conn->real_escape_string($_POST['phone']);
    $phone = openssl_encrypt($phone, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $phone = bin2hex($phone);
    
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $fullname = openssl_encrypt($fullname, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $fullname = bin2hex($fullname);
    
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];  
    if ($password == $confirm_password) {
        $hashedpassword = password_hash($confirm_password, PASSWORD_DEFAULT);
    } else { $error = "Passwords do not match"; echo $error; }
    
    $sql = "SELECT id, username, iv FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $iv = hex2bin($row['iv']);
            $usernameCompare = hex2bin($row['username']);
            $usernameCompare2 = openssl_decrypt($usernameCompare, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                  
            if ($usernameCompare2 == $usernameOriginal) {
                $error = "Username already in use"; echo $error;
                break;
            }
        }
    }
    
    if (empty($username_error) && empty($confirm_password_error)) {
        $sql = "INSERT INTO users (username, password, fullname, address, dob, phone, iv) VALUES ('$username', '$hashedpassword', '$fullname', '$address', '$dob', '$phone','$iv_hex')";
        
        if ($conn->query($sql) === TRUE) {
            echo 'Success';
            header("location: login.php");
        } else { die; }
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
			<h2>HSE Registration</h2>
			<p>Please fill this form to create an account</p>
			<form id="myForm" action=""<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"" method="post">

					<label for="username"><b>Username</b></label>
					<input type ="text" placeholder= "Enter Username" name="username" class="form" required>

					<label for="password"><b>Password</b></label>
					<input type ="password" placeholder= "Enter Password" name="password" class="form" required>

					<label for="confirm_password"><b>Confirm Password</b></label>
					<input type ="password" placeholder= "Confirm Password" name="confirm_password" class="form" required>

					<label for="fullname"><b>Full Name</b></label>
					<input type="text" placeholder= "Full Name" name="fullname" class="form" required>

				
					<label for="address"><b>Address</b></label>
					<input type="text" placeholder= "Enter Address" name="address" class="form" required>

					<label for="dob"><b>Date Of Birth</b></label>
					<input type="text" placeholder= "Enter Date of Birth (DD-MM-YYYY)" name="dob" class="form" required>

					<label for="phone"><b>Phone number</b></label>
					<input type="text" placeholder= "Enter Phone Number" name="phone" class="form" required>

					<button type="submit" class="submitbutton">Register</button>
					<button type="reset" class="submitbutton">Reset Form</button>
                
                <p>By creating an account you agree to our <a href="terms.php">Terms & Conditions</a>.</p>
				<p>If you are already registered, <a href="login.php">login here</a></p>
			</form>
		</div>
	</body>
	</html>