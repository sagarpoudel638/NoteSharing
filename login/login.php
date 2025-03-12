<?php
session_start(); 

$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Secure input handling
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $password = $_POST['Password'];

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to the front page
            header("Location: /project-main/front page/frontpage.php");
            exit;
        } else {
            // Wrong password
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
            exit;
        }
    } else {
        // User not found
        echo "<script>alert('No account found! Please register.'); window.location.href='/project-main/regstration/registration.html';</script>";
        exit;
    }
}

// Close DB connection
mysqli_close($conn);
?>
