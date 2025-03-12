<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$conn = mysqli_connect("localhost", "root", "", "note_sharing");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$note_id = intval($_POST['note_id']);
$rating = intval($_POST['rating']);
$review_text = mysqli_real_escape_string($conn, $_POST['review']);
$user_id = $_SESSION['user_id'];

// GET user_name FROM users table
$user_query = "SELECT name FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("User query failed: " . mysqli_error($conn));
}

$user_row = mysqli_fetch_assoc($user_result);
$user_name = mysqli_real_escape_string($conn, $user_row['name']);

// INSERT review
$sql = "INSERT INTO reviews (note_id, user_name, rating, review) 
        VALUES ($note_id, '$user_name', $rating, '$review_text')";

if (mysqli_query($conn, $sql)) {
    echo "Review submitted successfully!";
    header("Location: /project-main/front page/frontpage.php"); // optional
} else {
    echo "Error inserting review: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
