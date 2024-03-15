<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username_db = "id21978976_account";
    $password_db = "Ngocmanh13@";
    $dbname = "id21978976_root";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['role'] = $row['role'];
        header("Location: home.php");
        exit();
    } else {
        $login_error = "Tài khoản hoặc mật khẩu không chính xác";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #333;
        }

        input, button {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1 style="text-align: center; color: #333;">Login</h1>

            <?php if(isset($login_error)): ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
