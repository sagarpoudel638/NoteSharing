<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test File Upload</title>
</head>
<body>
    <form action="test_upload.php" method="post" enctype="multipart/form-data">
        Select PDF to upload:
        <input type="file" name="pdf" accept="application/pdf">
        <input type="submit" value="Upload PDF">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
            $uploadDir = 'uploads/';
            $pdf_name = $_FILES['pdf']['name'];
            $destination = $uploadDir . basename($pdf_name);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['pdf']['tmp_name'], $destination)) {
                echo "File uploaded successfully!";
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "No file uploaded or error occurred.";
        }
    }
    ?>
</body>
</html>
