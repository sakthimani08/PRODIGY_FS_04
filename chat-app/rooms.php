<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Create a new room
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["room_name"])) {
    $room_name = $conn->real_escape_string($_POST["room_name"]);
    if (!empty($room_name)) {
        $conn->query("INSERT INTO chat_rooms (room_name) VALUES ('$room_name')");
    }
}

// Fetch all chat rooms
$rooms = $conn->query("SELECT * FROM chat_rooms ORDER BY room_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Rooms</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Quicksand', sans-serif;
            background: url('https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 50px 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .header a {
            background: #f44336;
            padding: 10px 16px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .header a:hover {
            background: #c0392b;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .form-section h3 {
            margin-bottom: 15px;
        }

        input[type="text"] {
            padding: 12px;
            width: 70%;
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            outline: none;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            background: #2ecc71;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #27ae60;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .room-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-card a {
            text-decoration: none;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .room-card a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?></h2>
        <a href="logout.php">Logout</a>
    </div>

    <div class="form-section">
        <h3>Create a Chat Room</h3>
        <form method="post">
            <input type="text" name="room_name" placeholder="Enter room name" required>
            <button type="submit">Create</button>
        </form>
    </div>

    <h3>Available Rooms</h3>
    <div class="rooms-grid">
        <?php while ($room = $rooms->fetch_assoc()): ?>
            <div class="room-card">
                <a href="chat.php?room_id=<?= $room['id'] ?>">
                    <?= htmlspecialchars($room['room_name']) ?>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
