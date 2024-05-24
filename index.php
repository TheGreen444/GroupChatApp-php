<?php
$loginError = ""; // Define the login error message variable
$RegesterError = ""; // Initialize $RegErr variable
$registrationSuccess = false; // Flag to indicate successful registration

// Function to redirect to indexx.php
function redirectToIndexx($username) {
    header("Location: indexx.php?username=$username");
    exit();
}

// Function to redirect to indexx.php with username and registration success status in URL
function redirectToIndexxWithSuccess($username, $success = false) {
    $url = 'indexx.php?username=' . urlencode($username);
    if ($success) {
        $url .= '&registrationSuccess=true';
    }
    header("Location: $url");
    exit();
}

// Function to check login credentials
function checkLogin($username, $otp) {
    // Read user data from database.txt
    $users = file_get_contents('idPass.txt');
    $lines = explode("\n", $users);
    foreach ($lines as $line) {
        $data = explode(",", $line);
        if ($data[0] === $username && $data[1] === $otp) {
            return true; // Return true if credentials are correct
        }
    }
    return false; // Return false if credentials are incorrect
}

// Function to set a login cookie
function setLoginCookie($username, $otp) {
    // Save username and OTP in the cookie
    setcookie('login', json_encode(array('username' => $username, 'otp' => $otp)), time() + (30 * 24 * 60 * 60), "/"); // 1 week expiration
}

// Check if the user is already logged in via cookie
if (isset($_COOKIE['login'])) {
    $login_data = json_decode($_COOKIE['login'], true);
    $username = $login_data['username'];
    $otp = $login_data['otp'];
    if (checkLogin($username, $otp)) {
        // Redirect to indexx.php with the username
        redirectToIndexx($username);
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST["loginUsername"];
    $otp = $_POST["loginOTP"];
    if (checkLogin($username, $otp)) {
        // Set login cookie
        setLoginCookie($username, $otp);
        // Redirect to indexx.php with the username
        redirectToIndexx($username);
    } else {
        $loginError = "Invalid username or Password...";
    }
}

// Function to check if a username already exists in the database
function isUsernameTaken($username) {
    $users = file_get_contents('idPass.txt');
    $lines = explode("\n", $users);
    foreach ($lines as $line) {
        $data = explode(",", $line);
        if ($data[0] === $username) {
            return true; // Return true if username exists
        }
    }
    return false; // Return false if username does not exist
}

// Handle registration form submission
if (isset($_POST['register'])) {
    $username = $_POST["registerUsername"];
    $otp = $_POST["otp"];

    // Check if the username already exists
    if (isUsernameTaken($username)) {
        $RegesterError = "Username already taken...";
    } else {
        // Add user to the database
        $user_data = "$username,$otp\n";
        file_put_contents('idPass.txt', $user_data, FILE_APPEND | LOCK_EX);

        // Set login cookie
        setLoginCookie($username, $otp);

        // Indicate successful registration
        $registrationSuccess = true;

        // Redirect to indexx.php with the username and registration success flag
        redirectToIndexxWithSuccess($username, $registrationSuccess);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Login-signup</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
<style>
@media only screen and (max-width: 600px) {
#burning-text{
display: none;
}
#wrapper{
display: none;
}
}
h3{
position: fixed;
top: 15%;
left: 50%;
transform: translate(-50%,-50%);
background-color: #f0f0f0;
border: 1px solid #ccc;
border-radius: 5px;
padding: 10px;
cursor: pointer;
}
body {
cursor: url('https://d2sjggqnc2w7ft.cloudfront.net/home/img/cursor.png'),auto;
font-family: Arial, sans-serif;
background-color: #f4f4f4;
margin: 0;
padding: 0;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
}
.form-container {
top: 52%;
left: 50%;
transform: translate(-50%, -50%);
background-color: #fff;
padding: 20px;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
width: 300px;
}
.form-containerOne{
  position: fixed;
  top:50%;
  left: 50%;
  transform: translate(-50%, -50%);
background-color: #fff;
padding: 20px;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
width: 300px;
display: none;
}
.form-group {
margin-bottom: 20px;
}
label {
display: block;
margin-bottom: 6px;
color: #333;
}
input[type="password"] {
width: 100%;
padding: 8px;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}
input[type="text"] {
width: 100%;
padding: 8px;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}
button {
width: 100%;
padding: 10px;
border: none;
background-color: #4CAF50;
color: white;
border-radius: 4px;
cursor: pointer;
transition: background-color 0.3s ease;
}
button:hover {
background-color: #45a049;
}

/* */
.change-container {
position: fixed;
background-color: #fff;
padding: 20px;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
width: 300px;
display: none;
}

.change-container h2 {
margin-bottom: 20px;
color: #333;
}

.change-container .form-group {
margin-bottom: 20px;
}

.change-container label {
display: block;
margin-bottom: 6px;
color: #333;
}

.change-container input[type="text"],
.change-container input[type="password"] {
width: 100%;
padding: 8px;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

.change-container button {
width: 100%;
padding: 10px;
border: none;
background-color: #4CAF50;
color: white;
border-radius: 4px;
cursor: pointer;
transition: background-color 0.3s ease;
}

.change-container button:hover {
background-color: #45a049;
}

</style>
</head>
<body>
<div id="ToggleBtn">
<h3 id="give" onclick="toggle()">✎&nbsp;CreateAccount&nbsp;↩</h3>
</div>
<div class="container">
<div id="form-containerOne" class="form-containerOne">
<h2>Create Account:</h2>
<form id="registerForm" method="post">
<div class="form-group">
<label for="registerUsername">Username:</label>
<input type="text" id="registerUsername" name="registerUsername" required>
</div>
<div class="form-group">
<label for="otp">Password:</label>
<input type="password" id="otp" name="otp" required minlength="8" maxlength="18">
</div>
<button type="submit" name="register">Register</button>
</form>
  <div id="registerResult">

    <?php if (isset($RegesterError)) echo $RegesterError; ?>
  </div>
</div>

<div id="form-container" class="form-container" style="position: fixed;">
<h2>Login</h2>
<form id="loginForm" method="post">
<div class="form-group">
<label for="loginUsername">Username:</label>
<input type="text" id="loginUsername" name="loginUsername" required>
</div>
<div class="form-group">
<label for="loginOTP">Password:</label>
<input type="password" id="loginOTP" name="loginOTP" required minlength="8" maxlength="18">
</div>
<button type="submit" name="login">Login</button>
</form>
<div id="loginResult">
  
<?php if (isset($loginError)) echo $loginError; ?>
</div>
</div>
</div>
<!-- -->
<script>

var currentForm = 1; // Variable to keep track of the current form

function toggle() {
var login = document.getElementById("form-container");
var register = document.getElementById("form-containerOne");
var give = document.getElementById("give");

if (currentForm === 1) {
give.innerHTML = " 🌐 Login↩";
login.style.display = "none";
register.style.display = "block";
currentForm = 2;
} else {
give.innerHTML = " ✎&nbsp;CreateAccount&nbsp;↩";
login.style.display = "block";
register.style.display = "none";
currentForm = 1;
}
}




// Prevent zooming when clicking on input fields
document.getElementById("loginUsername").addEventListener('click', function(event) {
event.preventDefault();
});

document.getElementById("loginOTP").addEventListener('click', function(event) {
event.preventDefault();
});

document.getElementById("registerUsername").addEventListener('click', function(event) {
event.preventDefault();
});

document.getElementById("otp").addEventListener('click', function(event) {
event.preventDefault();
});

//
// Prevent form submission and handle it with JavaScript
document.addEventListener('DOMContentLoaded', function () {

// Remove event listeners from forms to allow default submission behavior
document.getElementById("loginForm").removeEventListener("submit", handleLoginSubmit);
document.getElementById("registerForm").removeEventListener("submit", handleRegisterSubmit);
document.getElementById("changeForm").removeEventListener("submit", handleChangeSubmit);


// Login form
document.getElementById("loginForm").addEventListener("submit", function (event) {
event.preventDefault(); // Prevent default form submission

// Your login form handling code here
// For example, you can use AJAX to submit the form data asynchronously
});

// Registration form
document.getElementById("registerForm").addEventListener("submit", function (event) {
event.preventDefault(); // Prevent default form submission

// Your registration form handling code here
// For example, you can use AJAX to submit the form data asynchronously
});

// Change username form
document.getElementById("changeForm").addEventListener("submit", function (event) {
event.preventDefault(); // Prevent default form submission

// Your change username form handling code here
// For example, you can use AJAX to submit the form data asynchronously
});
});

//


</script>
</body>
</html>