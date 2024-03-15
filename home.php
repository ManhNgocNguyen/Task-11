<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Function to render the upload form
function uploadForm(){
    echo 'Select file to upload:
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>';
}

// Function to render the list of uploaded files
function viewList($role){
    $uploadDir = "upload/";
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            // Check access based on role
            if ($role == 0 || $role == 1) {
                echo "<p>File: <a href='$uploadDir$file' target='_blank'>$file</a></p>";
            }
        }
    }
}

if(isset($_POST["submit"])) {
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if($imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "txt") {
        echo "Sorry, only PDF, DOCX, TXT files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
function redirectToChallenge() {
    echo "<script>window.location.href='challenge_student.php';</script>"; 
}

function redirectToEditInformation() {
    echo "<script>window.location.href='edit_information.php';</script>";
}

function redirectToLogin() {
    echo "<script>window.location.href='index.php';</script>";
}

function redirectToUserList() {
    echo "<script>window.location.href='user_list.php';</script>";
}
// Check for redirections
if(isset($_GET['redirectTo'])) {
    $redirectTo = $_GET['redirectTo'];
    switch ($redirectTo) {
        case 'assignments':
            uploadForm();
            echo '<div class="file-list">';
            viewList($role);
            echo '</div>';
            break;
        case 'userList':
            redirectToUserList();
            break;
        case 'editInformation':
            redirectToEditInformation();
            break;
        case 'game':
            redirectToChallenge();
            break;
        case 'logout':
            redirectToLogin();
            break;
        default:
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        .container {
            text-align: center;
        }

        .welcome-container {
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            color: green;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            color: #555;
        }

        .button-container {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            margin-top: 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-left: 10px;
        }

        .edit-input {
            margin-bottom: 10px;
            padding: 10px;
        }

        .file-list {
            margin-top: 20px;
        }

        .file-list p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>


<div class="button-container">
    <?php
    echo '<a href="?redirectTo=assignments" class="button">Assignments</a>';
    echo '<a href="?redirectTo=userList" class="button">View User List</a>';
    echo '<a href="?redirectTo=editInformation" class="button">Edit Information</a>';
    echo '<a href="?redirectTo=game" class="button">Game</a>';
    echo '<a href="?redirectTo=logout" class="button">Logout</a>';
    ?>
</div>

<div class="welcome-container" id="welcomeContainer">
    <h1>Welcome to the Home Page</h1>
    <?php
    echo '<p class="message">You are logged in.</p>';
    ?>
    <p>This is a simple and clean home page design. You can customize it further based on your needs.</p>
</div>

<div class="edit-container" id="editStudent" style="display: none;">
    <h1>Edit Information</h1>
    <input type="password" id="editPassword" placeholder="Password" class="edit-input"><br>
    <input type="email" id="editEmail" placeholder="Email" class="edit-input"><br>
    <input type="tel" id="editPhone" placeholder="Phone" class="edit-input"><br>

    <button class="edit-input" onclick="saveChanges()">Save Changes</button>
</div>

<div class="edit-container" id="editTeacher" style="display: none;">
    <h1>Edit Information</h1>
    <input type="text" id="editUsername" placeholder="Username" class="edit-input"><br>
    <input type="text" id="editName" placeholder="Name" class="edit-input"><br>
    <input type="password" id="editPasswordTeacher" placeholder="Password" class="edit-input"><br>
    <input type="email" id="editEmailTeacher" placeholder="Email" class="edit-input"><br>
    <input type="tel" id="editPhoneTeacher" placeholder="Phone" class="edit-input"><br>

    <button class="edit-input" onclick="saveChangesTeacher()">Save Changes</button>
</div>

</body>
</html>

