
<?php
session_start();
require_once "config/database.php";

$message = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: customer/dashboard.php");
            }

            exit();

        } else {
            $message = "Incorrect email or password.";
        }

    } else {
        $message = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AutoCare</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f1f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-box {
            background: white;
            width: 400px;
            max-width: 92%;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #102b46;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 14px 0 7px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d5dce5;
            border-radius: 7px;
            outline: none;
        }

        input:focus {
            border-color: #087c9e;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 22px;
            background: #087c9e;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #065e79;
        }

        .message {
            text-align: center;
            color: #c0392b;
            font-size: 14px;
        }

        .success {
            color: #16834b;
        }

        .bottom {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #087c9e;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Welcome Back</h2>
    <p class="subtitle">Login to your AutoCare account</p>

    <?php if (isset($_GET['registered'])): ?>
        <p class="message success">
            Account created successfully! Please login.
        </p>
    <?php endif; ?>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Email Address</label>
        <input type="email" name="email"
               placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Enter your password" required>

        <button type="submit" name="login">
            Login
        </button>

    </form>

    <div class="bottom">
        Don't have an account?
        <a href="register.php">Register</a>
    </div>

</div>

</body>
</html>
