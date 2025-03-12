<?php
$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, address, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $fullName, $email, $phone, $address, $password);

    if ($stmt->execute()) {
        header("Location: /project-main/login/login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($conn);
?>
