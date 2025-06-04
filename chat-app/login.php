<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $conn->query("UPDATE users SET last_active = NOW() WHERE id = " . $user['id']);

            header("Location: rooms.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Chat App</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background-color: rgba(255, 255, 255, 0.92);
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
    .register-link {
      margin-top: 20px;
      font-size: 14px;
      color: #333;
    }
    .register-link a {
      color: #2e8b57;
      text-decoration: none;
      font-weight: bold;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Welcome to Chat App</h2>
  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <form method="post">
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
  </form>

  <div class="register-link">
    Don't have an account? <a href="register.php">Register</a>
  </div>
</div>

</body>
</html>
