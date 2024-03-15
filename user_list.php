<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "id21978976_account";
$password = "Ngocmanh13@";
$dbname = "id21978976_root";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

$sql = "SELECT * FROM user";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách người dùng</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            width: 100%;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        section {
            max-width: 800px;
            margin: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #f0f0f0;
        }

        a.logout {
            display: inline-block;
            margin-top: 20px;
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a.logout:hover {
            background-color: #d32f2f;
        }

        .chat-container {
            display: none;
            position: fixed;
            bottom: 0;
            right: 20px;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .chat-header {
            background-color: #4e67c8;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-body {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
        }

        .chat-input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border-top: 1px solid #ddd;
            outline: none;
        }

        .message {
            background-color: #e1e0f6;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            position: relative;
        }

        .message span {
            display: inline-block;
        }

        .message.sender {
            background-color: #4e67c8;
            color: white;
        }

        .message-options {
            display: none;
            position: absolute;
            top: 5px;
            right: 5px;
            bottom: 5px;
            display: flex; /* Sử dụng flexbox */
        }

        .message:hover .message-options {
            display: flex;
        }

        .message-options span {
            padding: 5px;
            cursor: pointer;
        }

        .message-options span:nth-child(1) {
            margin-right: 5px; /* Tạo khoảng cách giữa "Chỉnh sửa" và "Xóa" */
        }
    </style>
</head>
<body>
    <header>
        <h1>Danh sách người dùng</h1>
    </header>
    <section>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="#" class="user-link" data-user-id="<?php echo $user['name']; ?>">
                        <?php echo $user['name']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="index.php" class="logout">Đăng xuất</a>

        <!-- Thêm cửa sổ chat giống Messenger -->
        <div class="chat-container" id="chatContainer">
            <div class="chat-header">
                <span>Tên người dùng</span>
                <span class="close-btn" onclick="closeChat()">Đóng</span>
            </div>
            <div class="chat-body" id="chatContent"></div>
            <input type="text" id="messageInput" class="chat-input" placeholder="Nhập tin nhắn...">
        </div>
    </section>
<!-- ... (previous HTML code) ... -->

<script>
    function editMessage(editButton) {
        const messageElement = editButton.closest('.message');
        const messageText = messageElement.querySelector('span');
        const newText = prompt('Chỉnh sửa tin nhắn:', messageText.innerText);
        if (newText !== null) {
            messageText.innerText = newText;
        }
    }

    function deleteMessage(deleteButton) {
        const messageElement = deleteButton.closest('.message');
        messageElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const userLinks = document.querySelectorAll('.user-link');
        const chatContainer = document.getElementById('chatContainer');
        const chatContent = document.getElementById('chatContent');

        userLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const userId = this.getAttribute('data-user-id');
                openChat(userId, this.innerText);
            });
        });

        function openChat(userId, userName) {
            chatContainer.style.display = 'block';
            document.querySelector('.chat-header span').innerText = userName;
            chatContent.innerHTML = '';
        }

        function closeChat() {
            chatContainer.style.display = 'none';
        }

        document.getElementById('messageInput').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const messageInput = this.value.trim();
                if (messageInput !== '') {
                    receiveMessage('me', messageInput);
                    this.value = '';
                }
            }
        });

        function receiveMessage(sender, content) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', sender === 'me' ? 'sender' : '');
            messageElement.innerHTML = `
                <span>${content}</span>
                <div class="message-options">
                    <span class="edit" onclick="editMessage(this)">Chỉnh sửa</span>
                    <span class="delete" onclick="deleteMessage(this)">Xóa</span>
                </div>
            `;
            messageElement.addEventListener('mouseenter', function () {
                this.querySelector('.message-options').style.display = 'flex';
            });
            messageElement.addEventListener('mouseleave', function () {
                this.querySelector('.message-options').style.display = 'none';
            });
            chatContent.appendChild(messageElement);
            chatContent.scrollTop = chatContent.scrollHeight;
        }
    });

</script>

<!-- ... (remaining HTML code) ... -->

</body>
</html>
