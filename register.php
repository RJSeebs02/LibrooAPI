<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form id="registrationForm" onsubmit="submitForm(event)">
        <label for="user_username">Username:</label>
        <input type="text" id="user_username" name="username" required><br>
        <label for="user_password">Password:</label>
        <input type="password" id="user_password" name="password" required><br>
        <button type="submit">Register</button>
    </form>

    <script>
        function submitForm(event) {
            event.preventDefault(); // Prevent form submission

            // Get form data
            const formData = {
                user_username: document.getElementById('user_username').value,
                user_password: document.getElementById('user_password').value
            };

            // Send data as JSON via AJAX
            fetch('https://zenenix.helioho.st/serve/create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>