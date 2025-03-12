<?php
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['noteCategory']);
    $price = $_POST['price'] ?? 0;
    $uploaded_by = 1; // Replace with actual user ID

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['pdf']['tmp_name'];
        $fileName = uniqid() . '_' . $_FILES['pdf']['name'];
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destPath = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO notes (title, description, category, file_path, uploaded_by, created_at) 
                                       VALUES (:title, :description, :category, :file_path, :uploaded_by, NOW())");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':file_path', $destPath);
                $stmt->bindParam(':uploaded_by', $uploaded_by);

                $stmt->execute();

                // Redirect after success
                header("Location: manage_notes.php?upload=success");
                exit();
            } catch (PDOException $e) {
                die('❌ Error saving file data: ' . $e->getMessage());
            }
        } else {
            die('❌ Error moving uploaded file.');
        }
    } else {
        die('❌ No file uploaded or there was an upload error.');
    }
}
?>
