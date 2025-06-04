 ✅ 🔍 Project Description – Real-Time Chat Room Web App

### ✨ Features:

* Secure login with session
* Multiple **chat rooms**
* Real-time **message display** using AJAX polling
* **File upload support** (images + files)
* Show **online/offline status** of users
* Auto-update of messages and user list
* Dark mode UI with styled messages and file previews
* Clean, attractive UI using modern HTML/CSS

---

## 🧠 Tech Stack:

* **Frontend**: HTML, CSS, JavaScript (AJAX)
* **Backend**: PHP (procedural)
* **Database**: MySQL (via phpMyAdmin)
* **Environment**: XAMPP on localhost

---

## 🗃️ MySQL Database Structure

Here are the SQL commands to **create the full database schema** for your chat app.

### 📦 1. `users` Table

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_active DATETIME
);
```

### 📦 2. `chat_rooms` Table

```sql
CREATE TABLE chat_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL
);
```

### 📦 3. `messages` Table

```sql
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    message TEXT,
    file_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id)
);
```

---

## 🌐 URL to Use in Chrome

If your XAMPP is running and your files are in:

```
D:\xampp\htdocs\chat-app\
```

And you created a room with `id = 1`, open this URL in Chrome:

```
http://localhost/chat-app/chat.php?room_id=1
```

You can change `room_id=1` to any other existing room’s ID.

---

## 📁 Folder Structure (Expected)

```
chat-app/
├── db.php
├── login.php
├── register.php
├── logout.php
├── rooms.php
├── chat.php
├── fetch_messages.php
├── send_message.php
├── fetch_users.php
├── update_presence.php
├── uploads/ (folder with write permissions)
└── style.css (if separated)
```

---

## 📸 File Upload Notes

* Ensure folder `uploads/` exists in `chat-app/`:

```bash
mkdir uploads
```

* Give write permission:

```bash
chmod 777 uploads
```

(Or just allow full permissions via Windows folder properties.)

---

## 🧪 Sample Data Insertion

```sql
-- Add a sample user
INSERT INTO users (username, password) VALUES ('sakthi', 'sakthi');

-- Add a sample room
INSERT INTO chat_rooms (room_name) VALUES ('General Chat');
