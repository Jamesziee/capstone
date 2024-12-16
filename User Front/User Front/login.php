<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    
</head>
<body>
<div class="container">
        <div class="left">
            <img src="pic/aa.jpg" alt="Image" width="650" height="635">
        </div>
        <div class="right">
            <form class="login-form" action="login_process.php" method="POST">
                <img src="pic/logo.png" alt="Logo">
                <h2>LOGIN</h2>

                <!-- Pop-up notification -->
                <div id="notif" class="notif"></div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="remember-me">
                    <input type="checkbox" id="remember-me" name="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>

                <button type="submit">Login</button>
                <a href="#">Forgot Password?</a>
            </form>
        </div>
    </div>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const notifElement = document.getElementById('notif');

            if (status === 'error') {
                notifElement.textContent = "Invalid email or password.";
                notifElement.classList.add('error');
                notifElement.classList.remove('success');
                notifElement.style.display = 'block';
            } else if (status === 'success') {
                notifElement.textContent = "Login successful!";
                notifElement.classList.add('success');
                notifElement.classList.remove('error');
                notifElement.style.display = 'block';
            }

            setTimeout(function() {
                notifElement.style.display = 'none';
            }, 3000); // Hide the notification after 3 seconds
        };
    </script>
</body>
</html>