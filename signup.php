<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $photoName = '';

    // 🟡 1. Handle file upload (if user selected an image)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $photoName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
    }

    // 🟡 2. Handle captured camera photo (base64 image)
    if (isset($_POST['captured_photo']) && !empty($_POST['captured_photo'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $base64Image = $_POST['captured_photo'];
        $imageParts = explode(";base64,", $base64Image);

        if (count($imageParts) === 2) {
            $imageBase64 = base64_decode($imageParts[1]);
            $photoName = "cam_" . time() . ".png";
            $filePath = $targetDir . $photoName;
            file_put_contents($filePath, $imageBase64);
        }
    }

    // ✅ Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $photoName);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['photo'] = $photoName;
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
