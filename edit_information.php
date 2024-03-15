<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
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

$username = $_SESSION["username"];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["submit"])) {
        $newPass = $_POST["password"];
        $newEmail = $_POST["email"];
        $newPhone = $_POST["phone"];
        $sql = "SELECT * FROM user WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newPass = empty($newPass) ? $row["password"] : $newPass;
            $newEmail = empty($newEmail) ? $row["email"] : $newEmail;
            $newPhone = empty($newPhone) ? $row["phone"] : $newPhone;
            $sql_update = "UPDATE user SET email='$newEmail', phone='$newPhone', password='$newPass' WHERE username='$username'";
            if ($conn->query($sql_update) === TRUE) {
                echo "Thông tin đã được cập nhật thành công.";
            } else {
                echo "Lỗi: " . $sql_update . "<br>" . $conn->error;
            }
        } else {
            echo "Không tìm thấy người dùng.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Information</title>
    <style>
    </style>
</head>
<body>
<div class="edit-container">
    <h1>Edit Information</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php if($role == 1): ?>
        <input type="text" name="username" placeholder="User name" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"><br>
        <input type="text" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"><br>
    <?php endif; ?>   
        <input type="password" name="password" placeholder="Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>"><br>
        <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"><br>
        <input type="tel" name="phone" placeholder="Phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"><br>
        <input type="submit" name="submit" value="Save Changes">
    </form>
</div>
</body>
</html>
