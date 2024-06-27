<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

        // Insert token into password_resets table
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $token, $expires);
        $stmt->execute();

        // Send reset link via email
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n$resetLink";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Password reset link has been sent to your email.";
        } else {
            echo "Failed to send reset link.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>


<!-- reset_password.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="update_password.php">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" required>
        New Password: <input type="password" name="new_password" required>
        Confirm Password: <input type="password" name="confirm_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
