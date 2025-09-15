<?php
include 'includes/db.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $imgName = $_FILES['profile']['name'];
    $imgTmp = $_FILES['profile']['tmp_name'];
    $uploadPath = "assets/images/profiles/" . $imgName;
    move_uploaded_file($imgTmp, $uploadPath);

    $sql = "INSERT INTO users (username, email, address, password, profile_img) 
            VALUES ('$username', '$email', '$address', '$password', '$imgName')";
    if ($conn->query($sql)) {
        header("Location: login.php?signup=success");
        exit();
    } else {
        $error = "Signup failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - SipNServe</title>
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
            <form method="post" enctype="multipart/form-data">
                <img src="assets/images/login/avatar.svg">
                <h2 class="title">Register</h2>

                <div class="input-div one">
                    <div class="i"><i class="fas fa-user"></i></div>
                    <div class="div">
                        <h5>Username</h5>
                        <input type="text" name="username" class="input" required>
                    </div>
                </div>

                <div class="input-div one">
                    <div class="i"><i class="fas fa-envelope"></i></div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="email" name="email" class="input" required>
                    </div>
                </div>

                <div class="input-div one">
                    <div class="i"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="div">
                        <h5>Address</h5>
                        <input type="text" name="address" class="input" required>
                    </div>
                </div>

                <div class="input-div pass">
                    <div class="i"><i class="fas fa-lock"></i></div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input" required>
                    </div>
                </div>

                <label style="color:#555; font-size: 0.9rem;">Profile Picture:</label>
                <input type="file" name="profile" required style="margin: 10px 0;">

                <input type="submit" name="signup" class="btn" value="Register">
                <button type="button" class="btn" onclick="window.location.href='login.php'">Already have an Account?</button>

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
