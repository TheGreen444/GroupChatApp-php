<?php
session_start();

// Directory where uploaded images will be saved
$targetDir = "usrPics/";

// Get the username from the session
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Get the filename of the uploaded image
    $uploadedFileName = basename($_FILES["profilePic"]["name"]);

    // Extract only the filename without the extension
    $uploadedFileNameWithoutExtension = pathinfo($uploadedFileName, PATHINFO_FILENAME);

    // Check if the uploaded image's filename matches the username
    if (strcasecmp($uploadedFileNameWithoutExtension, $username) === 0) {
        // Move the file to the target directory
        $targetFile = $targetDir . $uploadedFileName;
        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
            // File uploaded successfully
            echo "<script>alert('File uploaded successfully');</script>";
        } else {
            // Error uploading file
            echo "<script>alert('Error uploading file');</script>";
        }
    } else {
        // File name doesn't match username
        echo "<script>alert('File name does not match username');</script>";
    }
} else {
    // Username not set in session
    echo "<script>alert('Username not found');</script>";
}
?>
