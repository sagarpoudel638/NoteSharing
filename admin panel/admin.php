<?php
$conn = mysqli_connect("localhost", "root", "", "note_sharing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
// Debugging: Print the email and password received
// echo "Email: " . $email . "<br>";
// echo "Password: " . $password . "<br>";
    $stmt = $conn->prepare("SELECT * FROM admin WHERE Email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
//         echo "Password: " . $password . "<br>"; 
// echo "Hashed Password: " . $hashed_password . "<br>"; 


        
        // if (password_verify($password, $hashed_password)) {
        //     header('Location: ../pages/uploadpage.html');
        //     exit;
        // } 
        if ($password=="admin123") {
            header('Location: ../pages/manage_notes.php');
           exit;
        }
        else {
            echo "Invalid credentials";
            exit;
        }
    } else {
        echo "No data found";
        exit;
    }
}

mysqli_close($conn);
?>
