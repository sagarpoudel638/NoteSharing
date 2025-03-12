<?php
// Check if the note ID is provided in the URL
if (isset($_GET['id'])) {
    $noteId = $_GET['id'];

    // Database connection
    $host = 'localhost';
    $db = 'note_sharing';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }

    // Fetch the file path from the database before deleting the record
    $stmt = $pdo->prepare("SELECT file_path FROM notes WHERE id = :id");
    $stmt->bindParam(':id', $noteId, PDO::PARAM_INT);
    $stmt->execute();
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        // Delete the file from the server
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }

        // Delete the record from the database
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $noteId, PDO::PARAM_INT);
        $stmt->execute();

        echo "Note deleted successfully!";
    } else {
        echo "Note not found.";
    }

    // Close the database connection
    $pdo = null;

    // Redirect back to the view page after deletion
    header("Location: manage_notes.php");
    exit;
}
?>
