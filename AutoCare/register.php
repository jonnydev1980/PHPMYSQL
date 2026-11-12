
<?php
require_once "config/database.php";

$message = "";

if (isset($_POST['register'])) {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $message = "Password must contain at least 6 characters.";
    } else {

        $check_email = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE email = '$email'"
        );

        if (mysqli_num_rows($check_email) > 0) {
            $message = "This email is already registered.";
        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $hashed_password = mysqli_real_escape_string(
                $conn,
                $hashed_password
            );

            $sql = "INSERT INTO users
                    (full_name, email, password, role)
                    VALUES
                    ('$full_name', '$email', '$hashed_password', 'customer')";

            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AutoCare</title>
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

        .register-box {
            background: white;
            width: 400px;
            max-width: 92%;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        h2 {
            color: #102b46;
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 14px 0 7px;
            color: #333;
            font-weight: bold;
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
            color: #c0392b;
            text-align: center;
            font-size: 14px;
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

<div class="register-box">

    <h2>Create Account</h2>
    <p class="subtitle">Join AutoCare today</p>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>
        <input type="text" name="full_name"
               placeholder="Enter your full name" required>

        <label>Email Address</label>
        <input type="email" name="email"
               placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Create a password" required>

        <button type="submit" name="register">
            Create Account
        </button>

    </form>

    <div class="bottom">
        Already have an account?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>
