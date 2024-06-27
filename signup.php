<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <title>Hello, world!</title>
  </head>
  <body>
 
<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['SignUp_To_savedata'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if email already exists
    $checkEmailSql = "SELECT * FROM users WHERE EMAIL=?";
    $stmt = $conn->prepare($checkEmailSql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Error: The email address is already in use.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (NAME, EMAIL, PASSWORD, ROLE) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $email, $hashed_password, $role);

        if ($stmt->execute() === TRUE) {
            echo "<script>alert('Registration successful! Redirecting to login page.');</script>";
            // Redirect to login page
            echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }
    $stmt->close();
}
?>

<div class="container">
    <form id="contactus" action="" method="post">
        <h3>Registration Form</h3>
        <fieldset> 
            <input name="name" placeholder="Name" type="text" tabindex="1" required autofocus> 
        </fieldset>
        <fieldset> 
            <input name="email" placeholder="Email Address" type="email" tabindex="2" required> 
        </fieldset>
        <fieldset> 
            <input name="password" placeholder="Password" type="password" tabindex="3" required> 
        </fieldset>
        <fieldset> 
            <input name="role" placeholder="Role" type="text" tabindex="4" required> 
        </fieldset>
        <fieldset> 
            <input name="SignUp_To_savedata" type="submit" id="contactus-submit" data-submit="...Sending"> 
            <i id="icon" class=""></i>
        </fieldset>
    </form>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>