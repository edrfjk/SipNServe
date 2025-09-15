<?php
include 'includes/db.php';
session_start();

if (isset($_POST['login'])) {
    $emailOrUsername = $_POST['email_or_username'];
    $password = $_POST['password'];

    if ($emailOrUsername == 'admin' && $password == 'admin') {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE (email='$emailOrUsername' OR username='$emailOrUsername') AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_img'] = $user['profile_img'];
        header("Location: user/home.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SipNServe</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="email_or_username" placeholder="Email or Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
