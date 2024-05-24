


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
                    }, 444);
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



