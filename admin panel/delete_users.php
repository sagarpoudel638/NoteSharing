<?php
$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die(json_encode(['message' => 'Connection failed: ' . mysqli_connect_error()]));
}

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId']; // Expecting JSON payload

if (empty($userId)) {
    echo json_encode(['message' => 'Invalid user ID']);
    exit;
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    echo json_encode(['message' => 'User deleted successfully.']);
} else {
    echo json_encode(['message' => 'Failed to delete user.']);
}

$stmt->close();
$conn->close();
?>
