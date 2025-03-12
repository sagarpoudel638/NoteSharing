<?php
$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$noteId = $_GET['noteId'];

$sql = "SELECT id, user_name, rating, review, approved, created_at FROM reviews WHERE note_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $noteId);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

echo json_encode($reviews);

$stmt->close();
$conn->close();
?>
