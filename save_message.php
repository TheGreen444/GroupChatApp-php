<?php
// Start the session to store user colors
session_start();

// Get the message and username from POST data, default to 'Anonymous' if no username is provided
$message = $_POST['message'] ?? '';
$username = $_POST['username'] ?? 'Anonymous';

// Check if message is not empty
if (!empty($message)) {
    // Check if user color is already set in session
    if (isset($_SESSION['user_colors'][$username])) {
        // Use the stored color
        $userColor = $_SESSION['user_colors'][$username];
    } else {
        // Set color for specific username
        if ($username === 'TheGreen') {
            $userColor = '#00ff00'; // Set color to green for username 'TheGreen'
        } else {
            // Generate a random color for other users
            $colors = array('#fff', '#FFA500', '#EE82EE', '#a903fc', '#03fcfc', '#fc8403');
            shuffle($colors);
            $userColor = array_pop($colors);
        }

        // Save the color in session for future use
        $_SESSION['user_colors'][$username] = $userColor;
    }

    // Modify the formatted message
    $formattedMessage = '<div class="message" data-sender="' . $username . '">' .
    ($username === 'TheGreen' ? '<span style="color: #ff0000;">&nbsp;&nbsp;&nbsp;[HOST]&nbsp;&nbsp;&nbsp;&nbsp;</span><br>' : '') .
    '<span style="padding: 0 2px; color: ' . $userColor . ';">' . $username . '</span> ' . 
    ($username === 'TheGreen' ? '<span style="color: #00ff00;">~#</span><br> ' : '<span style="color: #00ff00;">~$</span><br> ') .
    $message . 
    '<span style="color: ' . $userColor . ';">  `</span>' .
    '</div>' . PHP_EOL;

    // Open the database file in append mode
    $file = fopen('database.txt', 'a');

    // Append the formatted message to the file
    fwrite($file, $formattedMessage);

    // Close the file
    fclose($file);
}
?>
