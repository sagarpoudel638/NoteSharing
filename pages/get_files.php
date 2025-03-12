<?php
// Database credentials
$host = 'localhost'; // Change this if your database is hosted elsewhere
$db = 'note_sharing'; // Replace with your database name
$user = 'root'; // Replace with your database user
$pass = ''; // Replace with your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch files from the database
try {
    $stmt = $pdo->query("SELECT * FROM uploads ORDER BY uploaded_at DESC");
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($files);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}
?>

