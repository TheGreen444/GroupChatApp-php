<?php
ob_start();
// Check if the username parameter is set in the URL
if(isset($_GET['username'])) {
$username = $_GET['username'];

} else {
    // Default username if not provided in the URL
    $username = "Error? => [ !=definedUsername ]";
}

if ($username === "Error? => [ !=definedUsername ]") {
  // remove cookie for undifined user
   setcookie('login', '', time() - 3600, '/');
    // Redirect to index.php
    header("Location: index.php?error=unauthorizedAccessAttempt...");
    exit();
}

// Check if the cookie 'login' is set and not empty
if(!empty($_COOKIE['login'])) {
    $login_data = json_decode($_COOKIE['login'], true);
    $cookie_username = $login_data['username'];
} else {
    // Redirect to the login page if the cookie is not set
    header("Location: index.php?error=unauthorizedAccessAttempt...");
    exit();
}

// Check if the username from the cookie matches the username from the URL parameter
if($cookie_username !== $username) {
    // If they don't match, redirect to the login page with an alert message
    header("Location: index.php?error=Unauthorized access attempt..."); // Redirect to the login page with an error message
    exit();
}

// Check if the logout button is clicked

if(isset($_POST['logout'])) {
    // Unset the 'login' cookie
    setcookie('login', '', time() - 3600, '/');

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Redirect to index.php
    header("Location: index.php?redirrection=Logout...");
    exit();
}



  // Check if the password reset form is submitted
    if (isset($_POST['reset'])) {
        $resetUsername = $_POST["resetUsername"];
        $oldPassword = $_POST["oldPassword"];
        $newPassword = $_POST["newPassword"];

        // Check if the username and old password combination exists
        $file = file_get_contents('idPass.txt');
        $credentials = "$resetUsername,$oldPassword";
        if (strpos($file, $credentials) !== false) {
            // Update the database with the new password
            $file = str_replace($credentials, "$resetUsername,$newPassword", $file);
            file_put_contents('idPass.txt', $file);
      $resetSuccessMessage = "Password successfully reset...";
        } else {
            $resetErrorMessage = "Invalid username or old password...";
        }
    }


          // Check if the change username form is submitted

// Function to check if username already exists
function isUsernameTaken($username) {
    // Get the contents of the idPass.txt file
    $file = file_get_contents('idPass.txt');

    // Explode the file contents into an array of lines
    $lines = explode("\n", $file);

    // Iterate through each line
    foreach ($lines as $line) {
        // Explode each line into an array of username and OTP
        $data = explode(",", $line);

        // Check if the username matches
        if ($data[0] === $username) {
            return true; // Username already exists
        }
    }

    return false; // Username does not exist
}

// Handle username change form submission
if (isset($_POST['changeUsername'])) {
    $oldUsername = $_POST["oldUsername"];
    $newUsername = $_POST["newUsername"];
    $changingOTP = $_POST["changingOTP"];

    // Check if the new username already exists
    if (isUsernameTaken($newUsername)) {
        $changeErrorMessage = "Username already taken...";
    } else {
        // Check if the old username and OTP combination exists
        $file = file_get_contents('idPass.txt');
        $credentials = "$oldUsername,$changingOTP";

        if (strpos($file, $credentials) !== false) {  
            // Update the database with the new username
            $file = str_replace("$oldUsername,", "$newUsername,", $file);
            file_put_contents('idPass.txt', $file);
            $changeSuccessMessage = "Username successfully changed...";
        } else {
            $changeErrorMessage = "Invalid old username or password...";
        }
    }
}
            ob_end_flush();

session_start();
$_SESSION['username'] = $username;


?>




<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>
  Messaging Web Application...
  </title>
  <style>

    @media only screen and (max-width: 980px) {
      .allItems > div {
        flex-basis: calc(100% - 28px);
      }
      .topBar #h {
      display: none;
      }
      .topBar #hee {
      display: block;
      }


       }

    .topBar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #f0f0f0; /* Background color */
      border-bottom: 1px solid #ccc; /* Border */
      padding: 10px 0; /* Padding */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Box shadow */
      z-index: 1000; /* Ensure it's above other content */
    }

    .topBar #h {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
      text-align: center;
      color: #00ff00; /* Green color */
      animation: pulse 2s infinite; /* Animation */
    }

    @keyframes pulse {
      0% {
        color: #00cc00; /* Dark green */
        transform: scale(1);
      }
      50% {
        color: #33ff33; /* Light green */
        transform: scale(1.1);
      }
      100% {
        color: #00cc00; /* Dark green */
        transform: scale(1);
      }
    }

    #image{
      width: 31px;
      height : 31px;
      border-radius: 50%;
      top: 9px;
        left: 8px;
        position: absolute;
      box-shadow: 0 0 2px #111;
    }
    .topBar #he {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
      top: 10px;
      left: 46px;
      position: absolute;
      padding: 4px;
      color: black;
      text-align: center;
      border-radius: 4px;
      opacity: 59%;
    }
   #hee{
     font-size: 24px;
     font-weight: bold;
     margin: 0;
     text-align: center;
     display: none;
   }
    .logout-button {
      position: absolute;
      top: 7px;
      right: 6px;
      padding: 7px 11px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #f0f0f0;
      text-decoration: none;
      color: #333;
      z-index: 9999;
      cursor: pointer;
    }

       #heee{
      position: absolute;
      top: 11px;
      right: 78px;
      padding: 3px 6px;
      border: 1px solid #ccc;
      border-radius: 50%;
      background-color: #f0f0f0;
      text-decoration: none;
      color: #333;
      z-index: 9999;
      cursor: pointer;
      animation: flame 1.5s infinite alternate; 
    }

    @keyframes flame {
      0% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.1);
        opacity: 0.8;
        background-color: #00ff00;

      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    #download-suggestion {
      font-weight: bold;
      color: red;
      position: absolute;
      top: 54px;
      right: -200px;
      transform: translateX(-50%);
      background-color: #f0f0f0;
      padding: 10px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: none; /* Hide by default */
      z-index: 9999;
    }
    /*  */
 /*  */
    *{
      overflow-x:hidden;
       overflow-x:hidden;
    }
    html{
      overflow-x:hidden;
       overflow-x:hidden;
    }
    html{
     overflow:hidden;
    }
      body {
          cursor: url('https://d2sjggqnc2w7ft.cloudfront.net/home/img/cursor.png'),auto;
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
        background:#111;
        color: #32CD32;
        overflow:hidden;
      }

    #messages {
        position: absolute;
        top: 20px; /* Adjust this value based on your layout */
        left: 0;
        width: calc(100% - 28px);

        padding: 14px;
        padding-left: 14px;
        border-radius: 5px;
        overflow-y: auto;
        overflow-x: auto;
        font-family: Hack;
        font-size: 18px;
        font-weight: bold;
    }

    #message-input-container {
        position: fixed; /* Position fixed to keep it in place */
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: space-between; /* Align input and button to opposite ends */
        align-items: center; /* Center items vertically */
        padding: 10px;
        background: #111;
        z-index: 4;
        border-top: 1px solid #333; /* Add border-top to separate from messages */
    }

     #message-input {
            width: calc(100% - 44px); /* Adjust width to leave space for the button */
            padding: 10px;
            background: #111;
            border: 1px solid #333;
            border-radius: 8px;
            color: #32CD32;
            font-family: Hack;
            font-size: 18px;
            font-weight: bold;
            white-space: pre;
            resize: none; /* Prevent resizing */
            transition: height 0.3s ease; /* Add transition for smooth height change */
            overflow-y: hidden; /* Hide vertical scrollbar */
            height: 20px; /* Set initial height */
              max-height: 236px;
              overflow-y: auto;
        }

    #message-input:focus {
        outline: none;
    }

    #Btn {
        margin-left: 6px;
        margin-right: 13px;
        padding: 14px;
        border: none;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        border-radius: 4px;
    }


    #Btn:hover {
        background-color: #0056b3;
    }


      #scroll-up {
          position: fixed;
          bottom: 140px;
          right: 10px;
          font-size: 24px;
          cursor: pointer;
      display: none;
      }
      #scroll-down {
          position: fixed;
          bottom: 100px;
          right: 10px;
          font-size: 24px;
          cursor: pointer;
        display: none;

      }
      #messages {
height:  calc(100% - 118px);  
        width: calc(100% - 28px);  
          padding: 14px;
          padding-left:14px;
          border-radius: 5px;
          overflow-y: auto;
          overflow-x: auto;
       font-family:Hack;
        font-size:18px;
         font-weight: bold;
      }

/* scroll  */
    ::-webkit-scrollbar {
      width: 3px; /* Adjust the width as desired */
    }

    /* Apply styles to the scrollbar track */
    ::-webkit-scrollbar-track {
      background: #000; /* Define 7 colors of the rainbow */
      border-radius: 444px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #666; /* Change to desired thumb color */
      border-radius: 444px; /* Adjust the border radius as desired */
      height: 0px; /* Change the height as desired */
    }

    /*  */
    /* For desktop view */


/*  */
    .user-message {
        text-align: right;
    }

        .message {
       border: none;
    }


/* t&c  */


      /*  */
       /* down */
    .scroll-button {
      position: fixed;
      bottom: 89px;
     left: 50%;
     transform: translate(-50%, -50%);
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 50%;
      padding: 2px;
      cursor: pointer;
      transition: background-color 0.3s ease, bottom 0.3s ease;
      z-index: 1;
    }

    .scroll-button:hover {
      background-color: #ccc;
    }

    .scroll-arrow {
      color: #ff0000;
      font-family: bolder;
      width: 28px;
      height: 22px;
     text-align: center;
    }
    /*  */

    .options {

      position:absolute;
      top: 54px;
      left: 4px;
      border:1px solid #333;
      background-color: #fff;
      z-index: 999;
      padding: 10px;
       padding-left: 4px;
       padding-right: 4px;
      padding-bottom: 12px;
      border-radius: 4px;
      color: #333;
      font-weight: bold;
      display: none;
    }
    .options a{
      border-bottom: 2px solid #333;
      cursor: pointer;
      padding: 8px;
      box-shadow: 0 0 6px #111;
    }
        .options a:hover{
          border-bottom: 2px solid #00ff00;
          cursor: pointer;
          padding: 8px;
          box-shadow: 0 0 6px #00ff00;
        }



        /* for forms */
        /* Styles for the form container */
        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            z-index: 9999999999999999999999;
        }

        /* Styles for the change container */
        .change-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            z-index: 9999999999999999999999;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #btn {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #btn:hover {
            background-color: #45a049;
        }

        /* Style for resetUsername input only */
        #resetUsername {
            border-color: #007bff; /* Blue border color */
            outline: none; /* Remove default focus outline */
        }

    .up-arrow {
      width: 0;
      height: 0;
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-bottom: 15px solid #ff0000;
      border-radius: 4px;
       margin: 0 auto 15px auto;
      animation: launch 1s ease-in-out infinite;
    }

    @keyframes launch {
      0% {
        transform: translateY(5);
      }
      50% {
        transform: translateY(-8px);
      }
      100% {
        transform: translateY(2px);
      }
    }

    .popup {
      position: fixed;
      top: 69px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #2ecc71;
      color: #fff;
      padding: 10px;
      border-radius: 8px;
      font-size: 18px;
      animation: popupAnimation 0.5s ease-out;
      z-index: 999;
      text-align: center;
    }

    /* Keyframe animation */
    @keyframes popupAnimation {
      0% {
        top: 20px;
        opacity: 1;
        border-radius: 50%;
        background-color: yellow;
      }
      10% {
        top: 36px;
        opacity: 1;
        border-radius: 50%;
        background-color: #ff0000;
      }
      25% {
        top: 49px; /* Slight downward movement */
        border-radius: 50%;
        opacity: 1;
        background-color: orange;
      }
      50% {
        top: 90px; /* Slight upward movement */
        opacity: 1;
        background-color: #2ecc71;
      }
      75% {
        top: 100px; /* Slight downward movement */
        opacity: 1;
        background-color: #ff0000;
      }
      90% {
        top: 89px; /* Return to original position */
        opacity: 1;
        background-color: yellow;
      }
      95% {
        top: 80px; /* Return to original position */
        opacity: 1;
        background-color: #2ecc71;
      }
      100% {
        top: 60px;
        opacity: 1;
        background-color: #2ecc71;
      }
    }





      img {
          loading: lazy;
      }






  .loader-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
      z-index: 99999; /* Ensure loader stays on top */
      display: none;
    }

    .loader {
      border: 6px solid #00ff00; /* Green color */
      border-top: 4px solid transparent; /* Transparent top border */
      border-radius: 50%; /* Circle shape */
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite; /* Rotate animation */
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Hide loader when loading completed */
    body.loading .loader-wrapper {
      display: none;
    }
  </style>
   <script>
        // Function to append a unique query string to the image URL
        function refreshImage() {
            var image = document.getElementById('image');
            var uniqueString = new Date().getTime(); // Generate a unique string using current time
            var originalSrc = image.src.split('?')[0]; // Get the original image source without query string
            image.src = originalSrc + '?' + uniqueString; // Append unique query string
        }

        // Call the function to refresh the image
        window.onload = refreshImage;


function updateOnlineStatus() {
  const loaderWrapper = document.getElementById('loader-wrapper');

  if (navigator.onLine) {
    loaderWrapper.style.display = 'none'; // Hide loader
  } else {
    loaderWrapper.style.display = 'flex'; // Show loader
  }
}

window.addEventListener('online', updateOnlineStatus);
window.addEventListener('offline', updateOnlineStatus);

// Check the initial status
updateOnlineStatus();

    </script>
</head>
<body>
 <div id="loader-wrapper" class="loader-wrapper">
    <div class="loader"></div>
  </div>
    <!--  -->
   <div class="topBar">
      <h3 id="h">Messaging Web Application<br> </h3>
       <div class="settings" onclick="toggleOptions()" style="cursor: pointer;">
         <img draggable="false" id="image" loading="lazy" src="usrPics/<?php echo $username; ?>.png">
      <h3 id="he"><?php echo $username; ?></h3>
      </div>
      <h3 id="hee">&nbsp;</h3>
            <a id="heee" href="download.html" target="_blank">&#8681;</a>
      <form method="post">
          <button type="submit" name="logout" class="logout-button">Logout</button>
      </form>
    </div>
     <img id="view" draggable="false" style="display: none;" loading="lazy" src="usrPics/<?php echo $username; ?>.png">
    <div class="options" id="options">
       <div class="up-arrow"></div>
      <!-- Add this to the "Change Username" link -->
       <a onclick="viewusrPics()">View Your Profile </a>
      <br><br>
      <a onclick="usrPics()">Upload Profile Pic&nbsp;&nbsp;</a>
      <br><br>
      <a onclick="showChangeForm()">Change Username&nbsp;</a>
  <br><br>
      <a onclick="showResetForm()">Change Password </a>
      <br><br>
           <a onclick="requestNotificationPermission()">Allow Notifications</a>
    </div>


    <div id="download-suggestion">
      <p>Download application for better experience!</p>
      <a href="download.html" id="download-link" target="_blank">Download Now</a>
      <button id="dismiss-suggestion">Dismiss</button>
    </div>


    <div id="messages"></div>
    </div>
    <div id="message-input-container">
        <textarea id="message-input" placeholder="Type your message..."></textarea>
        
       <button id="Btn">>></button>
    </div>
    <div id="scroll-up">&#9650;</div>
    <div id="scroll-down">&#9660;</div>

    <div class="scroll-button" id="scrollButton">
      <button style="border: none"class="scroll-arrow">↓</button>
    </div>

    <!--  -->
      <!-- t&c -->
  <!-- Popup container for new user...-->
  <div id="popup" class="popup"></div>
      <!--  -->
    <!--  -->




            <!-- Add this HTML code inside the existing HTML structure -->
            <div id="change-container" class="change-container" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
                <div style="position: absolute; top: 10px; right: 10px; cursor: pointer;" onclick="closeChangeForm()">✖</div>
                <h2 style="color: #333;">Change Username:</h2>
                <form id="changeForm" method="post">
                    <div class="form">
                        <label for="oldUsername">Old Username:</label>
                        <input type="text" id="oldUsername" name="oldUsername" required>
                    </div>
                    <div class="form-group"><br>
                        <label for="newUsername">New Username:</label>
                        <input type="text" id="newUsername" name="newUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="changingOTP">Password:</label>
                        <input type="password" id="changingOTP" name="changingOTP" required minlength="8" maxlength="30">
                    </div>
                    <button type="submit" name="changeUsername" id="btn">Make Changes</button>
                </form>
                <div id="changeResult">
                   <?php
                   if (isset($changeSuccessMessage)) {
                       echo $changeSuccessMessage;
                   } elseif (isset($changeErrorMessage)) {
                       echo $changeErrorMessage;
                   }
                   ?>
                </div>
            </div>







                     <!-- Reset Password Popup Form -->
                     <div id="resetPasswordForm" class="form-container" style="display: none;">
                         <div style="position: absolute; top: 10px; right: 10px; cursor: pointer;" onclick="closeResetForm()">✖</div>
                         <h2 style="color: #333;">Change Password:</h2>
                         <form id="resetForm" action="" method="post">
                             <div class="form-group">
                                 <label for="resetUsername">Username:</label>
                                 <input type="text" id="resetUsername" name="resetUsername" required>
                             </div>
                             <div class="form-group">
                                 <label for="oldPassword">Old Password:</label>
                                 <input type="password" id="oldPassword" name="oldPassword" required minlength="8" maxlength="30">
                             </div>
                             <div class="form-group">
                                 <label for="newPassword">New Password:</label>
                                 <input type="password" id="newPassword" name="newPassword" required minlength="8" maxlength="30">
                             </div>
                             <button type="submit" name="reset" id="btn">Make Changes</button>
                         </form>
                       <div
                         <?php
                           if (isset($resetSuccessMessage)) {
                               echo $resetSuccessMessage;
                           } elseif (isset($resetErrorMessage)) {
                               echo $resetErrorMessage;
                           }
                           ?>
                         </div>
                     </div>

<script>
   // Get the message input textarea
const messageInput = document.getElementById('message-input');

// Function to adjust the height based on the input field content
function adjustHeight() {
    // If there's no text, set the height to default
    if (messageInput.value === '') {
        messageInput.style.height = '20px';
        return;
    }



    // Calculate the new height based on the input field content
    const lines = messageInput.value.split('\n').length;
 const newHeight = Math.min(Math.max(lines * 20, 20), 236);

    // Set the height of the input field
    messageInput.style.height = newHeight + 'px';
}


function handleInputChange(event) {
    adjustHeight();
}


// Function to handle the input event
function handleInput(event) {
   adjustHeight();
}



// Function to handle paste events
function handlePaste(event) {
    event.preventDefault();

    // Get the pasted text from the clipboard
    const pastedText = (event.clipboardData || window.clipboardData).getData('text');
    const Btn = document.getElementById("Btn");
    // Get the current cursor position
    const start = messageInput.selectionStart;
    const end = messageInput.selectionEnd;

    // Insert the pasted text at the cursor position
    const textBefore = messageInput.value.substring(0, start);
    const textAfter = messageInput.value.substring(end);
    messageInput.value = textBefore + pastedText + textAfter;

    // Move the cursor to the end of the pasted text
    messageInput.selectionStart = messageInput.selectionEnd = start + pastedText.length;

    // Trigger the input event manually
    messageInput.dispatchEvent(new Event('input'));
}


Btn.addEventListener("click", () => {
    setTimeout(() => {
        messageInput.style.height = '20px';
    }, 144);
});

// Function to handle keydown events
function handleKeyDown(event) {
    if (event.key === 'Enter') {
                setTimeout(() => {
            messageInput.style.height = '20px';
        }, 144);
    }
if (event.key === 'ArrowDown') {
        // Get the current cursor position
        const start = messageInput.selectionStart;
        const end = messageInput.selectionEnd;

        // Insert a new line at the cursor position
        const textBefore = messageInput.value.substring(0, start);
        const textAfter = messageInput.value.substring(end);
        messageInput.value = textBefore + '\n' + textAfter;

        // Move the cursor to the end of the new line
        messageInput.selectionStart = messageInput.selectionEnd = start + 1;

        // Prevent the default behavior of the down arrow key
        event.preventDefault();

        // Trigger the input event manually to adjust the height
        messageInput.dispatchEvent(new Event('input'));
    }
}



// Add event listener for the keydown event
// Add event listeners for the input and paste events
messageInput.addEventListener('input', handleInputChange);
messageInput.addEventListener('paste', handlePaste);
messageInput.addEventListener('keydown', handleInput);
messageInput.addEventListener('keydown', handleKeyDown);

// Call the function initially to set the initial height
adjustHeight();













        function viewusrPics() {
            var profile = document.getElementById("view");
            var options = document.getElementById("options");
  var messages = document.getElementById("messages");
            // Store original styles
            var originalStyles = {
                display: options.style.display,
                background: document.body.style.background
            };

            // Apply new styles
            options.style.display = "none";
            profile.style.display = "block";
            profile.style.width = "300px";
            profile.style.height = "300px";
            profile.style.borderRadius = "50%";
            profile.style.border = "2px solid #333";
            profile.style.objectFit = "cover";
                      profile.style.boxShadow = "0 0 9px #302f2a";
            profile.style.zIndex = "9999";
            // Centering the profile element
            profile.style.position = "absolute";
            profile.style.top = "50%";
            profile.style.left = "50%";
            profile.style.marginTop = "-150px"; // Half of the height
            profile.style.marginLeft = "-150px"; // Half of the width
          //   document.body.style.background = "#302f2a";
            messages.style.display = "none";

            var btn = document.createElement("button");
            btn.innerHTML = "X";
            btn.style.fontWeight = "bold";
            btn.style.textTransform = "uppercase";
            btn.style.fontSize = "20px";
            btn.style.position = "absolute";
            btn.style.top = "32%";
            btn.style.left = "calc(50% + 133px)"; // Adjusted to the right side of the profile picture
            btn.style.transform = "translate(0, -50%)";
            btn.style.zIndex = "9999999";
            btn.style.cursor = "pointer";
            document.body.appendChild(btn);

            btn.addEventListener("click", function() {
                // Restore original styles
                profile.style.display = "none";
                options.style.display = "block";
              messages.style.display = "block";
                document.body.style.background = originalStyles.background;

                // Remove the button
                btn.parentNode.removeChild(btn);
            });
        }



        // 
        function usrPics() {

          alert("Make sure you wiil upload an image named as username of this account if not  rename it as your username and .png file format, otherwise the image may not display correctly.");

            var input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';

            input.onchange = function(event) {
                var file = event.target.files[0];
                var formData = new FormData();
                formData.append('profilePic', file);

                fetch('upload_profile_pic.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        location.reload(); // Reload the page after successful upload
                    } else {
                        console.error('Failed to upload profile picture');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            };

            input.click();
        }



        // 
        // 
         // Function to check if the user is on a desktop device
        function isDesktop() {
            return window.innerWidth > 1200; // You can adjust this threshold as needed
        }

        // Function to update the placeholder text
        function updatePlaceholder() {
            var messageInput = document.getElementById('message-input');
            if (isDesktop()) {
                messageInput.placeholder = "Type your message...   (Press '/' to start typing)";
            } else {
                messageInput.placeholder = "Type your message...";
            }
        }

        // Call the function initially and listen for window resize events
        updatePlaceholder();
        window.addEventListener('resize', updatePlaceholder);

        // Select the input field
        const inputField = document.getElementById('message-input');

        // Add event listener to the document for the '/' key
        document.addEventListener('keydown', function(event) {
            // Check if the key pressed is '/'
            if (event.key === '/') {
                // Prevent the default behavior of '/' key
                event.preventDefault();

                // Check if the input field is already focused
                if (document.activeElement === inputField) {
                    // If already focused, insert '/' character into the input field
                    inputField.value += '/';
                } else {
                    // If not focused, focus on the input field
                    inputField.focus();
                }
            } else if (event.key === 'Escape') { // Check if the key pressed is 'Escape'
                // Blur the input field to remove focus
                inputField.blur();
            }
        });

        // 
            // Check if the page is loaded
            // Check if the page is loaded
        window.addEventListener('load', function() {
            // Check if the button with id "btn" was clicked before reloading
            if (sessionStorage.getItem('btnClicked')) {
                // Alert after a delay
                setTimeout(function() {
                    alert("Please re-login to make changes.");
                    // Remove the stored data to avoid showing the alert again on subsequent reloads
                    sessionStorage.removeItem('btnClicked');
                    // Destroy the cookie after 2 seconds
                    setTimeout(function() {
                        // Destroy the login cookie
                        document.cookie = 'login=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                        // Reload the page
                      window.location.href = window.location.href;
                    }, 3444);
                }, 1000);
            }
        });

        // Event listener for button click
        document.getElementById("btn").addEventListener("click", function() {
            // Store a flag in session storage indicating that the button was clicked
            sessionStorage.setItem('btnClicked', 'true');
        });


        // 
        // 
        const options = document.getElementById("options");

    function showChangeForm() {
      var first = document.getElementById("change-container");  
      var sec = document.getElementById("resetPasswordForm");
      sec.style.display = "none";
      first.style.display = "block";
      options.style.display = "none"; // Hide the options
    }
        function closeChangeForm() {
          var first = document.getElementById("change-container");
          first.style.display = "none";
        }




        function showResetForm() {
          var sec = document.getElementById("resetPasswordForm");
          var first = document.getElementById("change-container");
          first.style.display = "none";
          sec.style.display = "block";
          options.style.display = "none"; // Hide the options
        }
            function closeResetForm() {
              var sec = document.getElementById("resetPasswordForm");
              sec.style.display = "none";
            }

    // 



        // 
        // JavaScript for toggling options
                               document.addEventListener('DOMContentLoaded', function() {
                                   // Get the options div
                                   // Function to toggle the visibility of options
                                   function toggleOptions() {
                                       // Toggle the display style of the options div
                                       options.style.display = (options.style.display === "block") ? "none" : "block";
                                   }

                                   // Function to hide options when clicking outside the div
                                   function hideOptionsOnClickOutside(event) {
                                       // Check if options are visible and the click target is not within the options div
                                       if (options.style.display === "block" && !options.contains(event.target)) {
                                           options.style.display = "none"; // Hide the options
                                       }
                                   }

                                   // Add event listener to the body to hide options when clicking outside the div
                                   document.body.addEventListener('click', hideOptionsOnClickOutside);

                                   // Add event listener to the settings div to toggle options visibility
                                   document.querySelector('.settings').addEventListener('click', function(event) {
                                       event.stopPropagation(); // Prevent the click event from bubbling up to the body
                                       toggleOptions();
                                   });
                               });




        const scrollButton = document.getElementById('scrollButton');
        const container = document.querySelector('#messages');

        // Function to handle scroll event
        function handleScroll() {
          // Show or hide the scroll button based on scroll position
          if (container.scrollTop >= container.scrollHeight - container.clientHeight - 200) {
            scrollButton.style.bottom = '-44px';
          } else {
            scrollButton.style.bottom = '100px';
          }
        }

        // Add scroll event listener to container
        container.addEventListener('scroll', handleScroll);

        // Function to hide the scroll button for 1 second
        function hideScrollButton() {
          scrollButton.style.display = 'none'; // Hide the scroll button
        }

        // Function to show the scroll button
        function showScrollButton() {
          scrollButton.style.display = 'block'; // Show the scroll button
        }

        // Add click event listener to scroll button
        scrollButton.addEventListener('click', () => {
          const scrollTo = {
            top: container.scrollHeight - 44,
            behavior: 'smooth'
          };

          if (container.scrollTop >= container.scrollHeight - container.clientHeight - 44) {
            scrollButton.style.bottom = '-44px';
          }

          container.scrollTo(scrollTo);
        });

        // Hide the scroll button for 1 second after page load
        document.addEventListener('DOMContentLoaded', () => {
          hideScrollButton(); // Initially hide the scroll button

        // Hide the scroll button for 1 second when the page unloads
        window.addEventListener('beforeunload', () => {
          hideScrollButton();
        });

          // Show the scroll button after 1 second
          setTimeout(() => {
            showScrollButton();
          }, 1044);
        });



        window.onload = handleScroll();

        // 
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                // Check if suggestion was dismissed within the last 4 days
                var dismissedTime = localStorage.getItem('dismissedTime');
                if (!dismissedTime || (Date.now() - parseInt(dismissedTime)) >= 7 * 24 * 60 * 60 * 1000) {
                    // Show the suggestion
                    document.getElementById('download-suggestion').style.display = 'block';
                }
            }, 7000); // Delayed display after 7 seconds

            // Dismiss the suggestion when the Dismiss button is clicked
            document.getElementById('dismiss-suggestion').addEventListener('click', function() {
                // Hide the suggestion
                document.getElementById('download-suggestion').style.display = 'none';
                // Store the current timestamp in local storage
                localStorage.setItem('dismissedTime', Date.now());
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var inputField = document.getElementById('message-input');

            inputField.addEventListener('select', function(event) {
                event.preventDefault(); // Prevent default selection behavior
            });
        });

        // t&c



        // 
        // scrool down message


        // 
    // 
        window.onload = function() {
            // Check if the alert has been shown today
            var lastAlertDate = localStorage.getItem("lastAlertDate");
            var currentDate = new Date().toDateString();
            if (lastAlertDate !== currentDate) {
                setTimeout(function() {
                    // Create popup
                    var popup = document.createElement("div");
                    popup.id = "popup";
                    popup.style.position = "fixed";
                    popup.style.top = "50%";
                    popup.style.left = "50%";
                    popup.style.transform = "translate(-50%, -50%)";
                    popup.style.backgroundColor = "white";
                    popup.style.padding = "22px";
                    popup.style.borderRadius = "5px";
                    popup.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
                    popup.style.zIndex = "9999";

                    // Error message
                    var errorMessage = document.createElement("div");
                    errorMessage.innerHTML = "<center>Chart Alert:<br>your message can be seen by<br>all the registered users on<br>this Application...</center><br><center>Made by => TheGreenH4ck3r</center>";
                    errorMessage.style.color = "red";
                    errorMessage.style.marginBottom = "15px";
                    errorMessage.style.fontWeight = "bold";
                    popup.appendChild(errorMessage);

                    // Button container
                    var buttonContainer = document.createElement("div");
                    buttonContainer.style.display = "flex";
                    buttonContainer.style.justifyContent = "center"; // Center horizontally
                    buttonContainer.style.alignItems = "center"; // Center vertically
                    popup.appendChild(buttonContainer);

                    // OK button
                    var okButton = document.createElement("button");
                    okButton.innerHTML = "OK";
                    okButton.style.padding = "8px 20px";
                    okButton.style.backgroundColor = "#007bff";
                    okButton.style.color = "white";
                    okButton.style.border = "none";
                    okButton.style.borderRadius = "5px";
                    okButton.style.cursor = "pointer";
                    okButton.style.zIndex = "9999";
                    okButton.addEventListener("click", function() {
                        // Remove the popup when OK button is clicked
                        popup.remove();
                        document.body.removeChild(back);
                    });
                    buttonContainer.appendChild(okButton);

                    // Append popup to body
                    document.body.appendChild(popup);

                    // Create background div
                    var back = document.createElement("div");
                    back.style.height = "100%";
                    back.style.width = "100%";
                    back.style.position = "fixed";
                    back.style.top = "0";
                    back.style.left = "0";
                    back.style.backgroundColor = "rgba(0, 0, 0, 0.5)"; // Semi-transparent black background
                    back.style.zIndex = "9998"; // Behind the popup
                    back.style.cursor = "not-allowed"; // Disable mouse interaction
                    document.body.appendChild(back);

                    // Save the date of the last alert
                    localStorage.setItem("lastAlertDate", currentDate);
                }, 444);
            }
        };

        // 

        document.addEventListener("DOMContentLoaded", function() {
            var scrollPosition = 0;

            // Function to load messages from the server
            function loadMessages() {
                var messagesContainer = document.getElementById('messages');
                fetch('get_messages.php')
                .then(response => response.text())
                .then(data => {
                    // Preserve scroll position
                    scrollPosition = messagesContainer.scrollHeight - messagesContainer.scrollTop;
                    document.getElementById('messages').innerHTML = data;
                    // Adjust scroll position after loading new messages
                    messagesContainer.scrollTop = messagesContainer.scrollHeight - scrollPosition;
                    alignMessages();
                });
            }

            // Function to send message
            function sendMessage() {
                var message = document.getElementById('message-input').value;
                if (message.trim() !== '') {
                    // Fetch the stored username directly from PHP
                    var username = "<?php echo $username; ?>";
                    fetch('save_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        // Include both message and username in the request
                        body: 'message=' + encodeURIComponent(message) + '&username=' + encodeURIComponent(username)
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Clear input field after sending message
                        document.getElementById('message-input').value = '';
                        // Load messages after sending
                        loadMessages();
                    });
                }
            }

            // Function to align messages based on sender
            function alignMessages() {
                var messages = document.querySelectorAll('.message');
                messages.forEach(function(message) {
                    var sender = message.dataset.sender;
                    var currentUser = "<?php echo $username; ?>";
                    if (sender === currentUser) {
                        message.classList.add('user-message'); // Add class to align user's messages to the right
                    } else {
                        message.classList.remove('user-message'); // Remove class for other users' messages
                    }
                });
            }

            // Event listener for Send button click
            document.getElementById('Btn').addEventListener('click', sendMessage);

            // Event listener for Enter key to send message
            document.getElementById('message-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage()
                }
            });

            // Event listener for scroll-up button
            document.getElementById('scroll-up').addEventListener('click', function() {
                document.getElementById('messages').scrollTop -= 1;
            });

            // Event listener for scroll-down button
            document.getElementById('scroll-down').addEventListener('click', function() {
                document.getElementById('messages').scrollTop += 1;
            });

            // Function to periodically load messages
            setInterval(loadMessages, 444);

            // Initial load of messages
            loadMessages();

        });








        // 

        // 
        function showPopup() {
          // Get the URL parameters
          var urlParams = new URLSearchParams(window.location.search);

          // Check if the URL parameter registrationSuccess exists and its value is true
          if (urlParams.has('registrationSuccess') && urlParams.get('registrationSuccess') === 'true') {
            // If registrationSuccess=true is in the URL, show the popup
            var popup = document.getElementById('popup');
            popup.innerText = "<?php echo $username; ?>, Welcome To Messaging Web Application...";

            // Show popup
            popup.style.display = 'block';

            // Hide popup after 4 seconds
            setTimeout(function() {
              popup.style.display = 'none';
              // Create a new anchor element
              var anchor = document.createElement('a');
              // Set the href attribute to the desired URL
              anchor.href = 'http://thegreen-chat.42web.io/indexx.php?username=';
              // Append the anchor to the document body (or any other element)
              document.body.appendChild(anchor);
              // Trigger a click event on the anchor
              anchor.click();

            }, 8999);
          } else {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
          }
        }

         setTimeout(showPopup, 444);


  // 






  // Notification of messages










        // Function to check for new messages and display notifications
        function checkForNewMessages() {
            // Send an AJAX request to get_messages.php to fetch new messages
            fetch('get_messages.php')
                .then(response => response.text())
                .then(data => {
                    // Compare the current messages with the previous messages
                    if (data.trim() !== previousMessages.trim()) {
                        // If new messages are different, update the previous messages and display a notification
                        previousMessages = data.trim();
                        // Extract username and message from the new messages data
                        const [username, message] = extractUsernameAndMessage(data);
                        // Call displayNotification with the extracted username and message
                        displayNotification(username, message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to extract username and message from the new messages data
        function extractUsernameAndMessage(data) {
            // Split the data into lines
            const lines = data.trim().split('\n');
            // The last line contains the most recent message
            const lastLine = lines[lines.length - 1];
            // Extract the username and message from the last line using a regular expression
            const matches = lastLine.match(/data-sender="([^"]+)">.*?<span.*?>(.*?)<\/span>(.*?)<\/div>/);
            // Extract username and message from the matches
            const username = sanitizeHTML(matches[1].trim());
            const message = sanitizeHTML(matches[3].trim()); // Combine both parts of the message
            // Return username and message
            return [username, message];
        }

        // Function to sanitize HTML tags from the input
        function sanitizeHTML(input) {
            return input.replace(/<[^>]+>/g, '');
        }

        // Function to display a notification with username and message
        function displayNotification(username, message) {
            // Construct the notification title and body
            const notificationTitle = "New Message From: " + username;
            const notificationBody = "" + message;

            // Check if the browser supports notifications
            if ('Notification' in window && Notification.permission === 'granted') {
                // If notifications are supported and permission is granted, display the notification
                new Notification(notificationTitle, { body: notificationBody });
            }
        }

        // Check for new messages every 5 seconds (you can adjust the interval as needed)
        setInterval(checkForNewMessages, 444);

        // Variable to store previous messages
        var previousMessages = '';

        // Request permission for notifications
        if ('Notification' in window) {
            Notification.requestPermission();
        }



          function requestNotificationPermission() {
            // Check if the browser supports notifications
            if ('Notification' in window) {
              Notification.requestPermission().then(function(permission) {
                // Display a message based on the permission status
                if (permission === 'granted') {
                  alert('Notification permission granted!');
                } else if (permission === 'denied') {
                  alert('Notification permission denied. You can change this in your browser settings.');
                } else {
                  alert('Notification permission has not been granted yet. Please allow notifications.');
                }
              });
            } else {
              alert('Notifications are not supported in your browser.');
            }
          }










          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");
          console.warn("Stop!!");




</script>


  </body>
  </html>
