
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard | AutoCare</title>
</head>
<body>

<h1>Welcome to AutoCare</h1>

<p>Hello, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>

<p>You are logged in as a customer.</p>

<a href="../logout.php">Logout</a>

</body>
</html>
