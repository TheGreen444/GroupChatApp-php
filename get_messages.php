<?php
// Read the contents of the database file
$messages = file_get_contents('database.txt');
// Output the messages
echo nl2br($messages);
?>


