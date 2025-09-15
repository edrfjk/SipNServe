<?php
include 'includes/db.php';
session_start();

if (isset($_POST['login'])) {
    $user = $_POST['email_or_username'];
    $pass = $_POST['password'];

    if ($user === "admin" && $pass === "admin") {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE (email='$user' OR username='$user') AND password='$pass' AND status='active'";
    $result = $conn->query($sql);

$sql = "SELECT * FROM users WHERE (email='$user' OR username='$user') AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['status'] === 'inactive') {
        $error = "Your account has been deactivated. Please contact support.";
    } else {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['profile_img'] = $row['profile_img'];
        header("Location: user/home.php");
        exit();
    }
} else {
    $error = "Invalid login.";
}

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SipNServe</title>
    <link rel="stylesheet" type="text/css" href="assets/css/regstyle.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <img class="wave" src="assets/images/login/wave.png">
    <div class="container">
        <div class="img">
            <img src="assets/images/login/bg.svg">
        </div>
        <div class="login-content">
            <form method="post">
                <img src="assets/images/login/avatar.svg">
                <h2 class="title">Welcome</h2>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Email or Username</h5>
                        <input type="text" name="email_or_username" class="input" required>
                    </div>
                </div>

                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input" required>
                    </div>
                </div>

                <input type="submit" name="login" class="btn" value="Login">
                <button type="button" class="btn" onclick="window.location.href='signup.php'">Sign Up</button>


                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            </form>
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll(".input");

function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value === ""){
		parent.classList.remove("focus");
	}
}

// For focus and blur
inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);

	// ✅ NEW: Add focus class if input already has value
	if(input.value !== ""){
		let parent = input.parentNode.parentNode;
		parent.classList.add("focus");
	}
});

    </script>
</body>
</html>
