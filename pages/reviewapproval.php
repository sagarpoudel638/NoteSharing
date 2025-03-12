<?php
$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$data = json_decode(file_get_contents("php://input"), true);
$reviewId = $data['reviewId'];
$status = $data['status']; // 'approved' or 'disapproved'

// Optional: Validate status value
if (!in_array($status, ['approved', 'disapproved'])) {
    echo json_encode(['message' => 'Invalid status value.']);
    exit;
}

// Convert status to 1 for 'approved' and 0 for 'disapproved'
if ($status === 'approved') {
    $status = 1;
} else {
    $status = 0;
}

$sql = "UPDATE reviews SET approved = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status, $reviewId);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Review status updated successfully.']);
} else {
    echo json_encode(['message' => 'Failed to update review status.']);
}

$stmt->close();
$conn->close();
?>
