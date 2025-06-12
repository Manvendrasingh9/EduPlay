<?php
include 'db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    header("Location: contact.php?error=" . urlencode("Please fill in all fields."));
    exit();
}

$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    header("Location: contact.php?success=" . urlencode("✅ Message sent successfully!"));
} else {
    header("Location: contact.php?error=" . urlencode("❌ Failed to send message."));
}
exit();
