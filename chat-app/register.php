<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password, last_active) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                $success = "Registration successful. <a href='login.php'>Login here</a>";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register - Chat App</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: url('https://images.unsplash.com/photo-1502082553048-f009c37129b9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-box {
      background-color: rgba(255, 255, 255, 0.93);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      width: 400px;
      text-align: center;
    }
    h2 {
      margin-bottom: 25px;
      color: #2f4f4f;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 14px;
      margin: 10px 0 20px;
      border: 1px solid #aaa;
      border-radius: 10px;
      font-size: 16px;
    }
    button {
      width: 100%;
      padding: 14px;
      background-color: #2e8b57;
      color: #fff;
      font-size: 17px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
    button:hover {
      background-color: #206040;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
    .success {
      color: green;
      margin-bottom: 15px;
    }
    .login-link {
      margin-top: 20px;
      font-size: 14px;
      color: #333;
    }
    .login-link a {
      color: #2e8b57;
      text-decoration: none;
      font-weight: bold;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="register-box">
  <h2>Create Your Account</h2>

  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php elseif ($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="username" placeholder="Choose a username" required />
    <input type="password" name="password" placeholder="Choose a password" required />
    <button type="submit">Register</button>
  </form>

  <div class="login-link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

</body>
</html>
