<?php
session_start();
$selectedFile = '';

if ($_SESSION['role'] == 0) {
    $challengeDir = "challenge/";

    if (!is_dir($challengeDir)) {
        echo "Error: Challenge directory does not exist.";
        exit();
    }

    $files = glob($challengeDir . "*");

    if (count($files) == 0) {
        echo "No challenge available.";
    } else {
        echo "<h1>Choose Challenge</h1>";
        echo "<ul>";
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME); 
            echo "<li><a href=\"?file=" . basename($file) . "\">" . $filename . "</a></li>";
        }
        echo "</ul>";
        if (isset($_GET['file'])) {
            $selectedFile = $_GET['file'];
            $selectedFilePath = $challengeDir . $selectedFile;
            if (file_exists($selectedFilePath)) {
                echo "<h2>Challenge Content</h2>";
                echo file_get_contents($selectedFilePath);
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <label for="answer">Your Answer:</label>
                    <input type="text" name="answer" id="answer"><br>
                    <input type="hidden" name="selectedFile" value="<?php echo htmlspecialchars($selectedFile); ?>">
                    <input type="submit" value="Submit">
                </form>
                <?php
            } else {
                echo "Selected challenge does not exist.";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['role'] == 1) {

        $uploadDir = "challenge/";
        $uploadedFile = $uploadDir . basename($_FILES["file"]["name"]);
        $hint = $_POST["hint"];

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadedFile)) {
            echo "Challenge created successfully. Hint: $hint";
        } else {
            echo "Failed to create challenge.";
        }
    } else {
        $submittedAnswer = $_POST["answer"];
        $selectedFile = isset($_POST['selectedFile']) ? $_POST['selectedFile'] : '';
        $correctAnswer = pathinfo($selectedFile, PATHINFO_EXTENSION); 
        if ($submittedAnswer === trim($correctAnswer)) {
            echo "Correct answer!";
        } else {
            echo "Incorrect answer. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['role'] == 1 ? "Create Challenge" : "View Challenge"; ?></title>
</head>
<body>
    <?php if ($_SESSION['role'] == 1): ?>
    <!-- Phần tạo thử thách -->
    <h1>Create Challenge</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <label for="file">Upload File:</label>
        <input type="file" name="file" id="file"><br>
        <label for="hint">Hint:</label>
        <input type="text" name="hint" id="hint"><br>
        <input type="submit" value="Submit">
    </form>
    <?php endif; ?>
</body>
</html>
