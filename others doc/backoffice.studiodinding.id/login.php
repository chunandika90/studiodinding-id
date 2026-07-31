<?php
session_start();
include 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $user;
            $_SESSION['role'] = $role;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Backoffice</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding-top: 80px; }
        .login-box { width: 300px; margin: auto; padding: 20px; background: #fff; box-shadow: 0 0 10px #ccc; }
        input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 5px 0; }
        input[type=submit] { width: 100%; padding: 10px; background: #333; color: #fff; border: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>