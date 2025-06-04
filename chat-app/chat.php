<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Guest';
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

if ($room_id == 0) {
    echo "Invalid room.";
    exit;
}

$result = $conn->query("SELECT room_name FROM chat_rooms WHERE id = $room_id");
if ($result->num_rows == 0) {
    echo "Chat room not found.";
    exit;
}
$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat Room: <?= htmlspecialchars($room['room_name']) ?></title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1f1f1f, #121212);
      color: #fff;
      padding: 20px;
    }
    h2 {
      margin-bottom: 10px;
      font-size: 1.8em;
      color: #00e676;
    }
    p a {
      color: #00bfa5;
      text-decoration: none;
      font-weight: bold;
    }
    p a:hover {
      text-decoration: underline;
    }
    .container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    #userList, #chatBox {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }
    #userList {
      max-height: 150px;
      overflow-y: auto;
    }
    #userList ul {
      list-style: none;
    }
    #userList li {
      margin-bottom: 6px;
      font-size: 0.95em;
    }
    .online { color: #76ff03; font-weight: bold; }
    .offline { color: #ff1744; }
    #chatBox {
      height: 400px;
      overflow-y: scroll;
    }
    .message {
      margin-bottom: 15px;
      padding: 10px;
      background: rgba(0,0,0,0.3);
      border-radius: 8px;
    }
    .message .sender {
      font-weight: bold;
      color: #00bcd4;
    }
    .message .time {
      font-size: 0.8em;
      color: #bbb;
      margin-left: 6px;
    }
    .file-link {
      display: inline-block;
      margin-top: 5px;
      color: #80d8ff;
      text-decoration: underline;
    }
    .file-link:hover {
      color: #40c4ff;
    }
    form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      background: rgba(255,255,255,0.05);
      padding: 12px;
      border-radius: 10px;
      backdrop-filter: blur(10px);
    }
    form input[type="text"] {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      background: #333;
      color: #fff;
    }
    form input[type="file"] {
      color: #fff;
    }
    form button {
      padding: 10px 20px;
      background: #00e676;
      border: none;
      border-radius: 8px;
      color: #000;
      font-weight: bold;
      cursor: pointer;
    }
    form button:hover {
      background: #00c853;
    }

    @media (max-width: 768px) {
      form {
        flex-direction: column;
      }
      form input[type="text"],
      form input[type="file"],
      form button {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<h2>Chat Room: <?= htmlspecialchars($room['room_name']) ?></h2>
<p>Welcome, <?= htmlspecialchars($username) ?> | <a href="logout.php">Logout</a></p>

<div class="container">
  <div id="userList">
    <strong>Users Online:</strong>
    <ul id="users"></ul>
  </div>

  <div id="chatBox"></div>

  <form id="messageForm" enctype="multipart/form-data">
    <input type="hidden" name="room_id" value="<?= $room_id ?>" />
    <input type="text" name="message" id="messageInput" placeholder="Type your message..." />
    <input type="file" name="file" id="fileInput" />
    <button type="submit">Send</button>
  </form>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const messageForm = document.getElementById('messageForm');
const messageInput = document.getElementById('messageInput');
const fileInput = document.getElementById('fileInput');
const usersList = document.getElementById('users');

const roomId = <?= $room_id ?>;

function fetchMessages() {
  fetch(`fetch_messages.php?room_id=${roomId}`)
    .then(response => response.json())
    .then(data => {
      chatBox.innerHTML = '';
      data.forEach(msg => {
        const div = document.createElement('div');
        div.classList.add('message');

        let fileHtml = '';
        if (msg.file_path) {
          if (msg.file_path.match(/\.(jpeg|jpg|png|gif)$/i)) {
            fileHtml = `<img src="${msg.file_path}" style="max-width:150px; border-radius:6px; display:block; margin-top:5px;" />`;
          } else {
            fileHtml = `<a href="${msg.file_path}" target="_blank" class="file-link">Download File</a>`;
          }
        }

        div.innerHTML = `
          <span class="sender">${msg.username}</span> 
          <span class="time">[${msg.created_at}]</span><br/>
          <span class="text">${msg.message ?? ''}</span>
          ${fileHtml}
        `;
        chatBox.appendChild(div);
      });
      chatBox.scrollTop = chatBox.scrollHeight;
    });
}

messageForm.addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(messageForm);
  fetch('send_message.php', {
    method: 'POST',
    body: formData
  }).then(() => {
    messageInput.value = '';
    fileInput.value = '';
    fetchMessages();
  });
});

function updatePresence() {
  fetch('update_presence.php', { method: 'POST' }).catch(console.error);
}

function fetchUsers() {
  fetch('fetch_users.php')
    .then(res => res.json())
    .then(users => {
      usersList.innerHTML = '';
      users.forEach(user => {
        const li = document.createElement('li');
        li.innerHTML = `${user.username} - <span class="${user.status.toLowerCase()}">${user.status}</span>`;
        usersList.appendChild(li);
      });
    });
}

setInterval(() => {
  updatePresence();
  fetchUsers();
}, 5000);

fetchMessages();
updatePresence();
fetchUsers();
</script>

</body>
</html>
