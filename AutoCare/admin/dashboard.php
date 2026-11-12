
<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Dashboard statistics
$customers = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'"
);

$vehicles = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM vehicles"
);

$services = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM services"
);

$appointments = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM appointments"
);

$total_customers = mysqli_fetch_assoc($customers)['total'];
$total_vehicles = mysqli_fetch_assoc($vehicles)['total'];
$total_services = mysqli_fetch_assoc($services)['total'];
$total_appointments = mysqli_fetch_assoc($appointments)['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | AutoCare</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="logo">
            Auto<span>Care</span>
        </div>

        <p>Main Menu</p>

        <a href="dashboard.php" class="active">
            Dashboard
        </a>

        <p>Management</p>

        <a href="customers.php">Customers</a>
        <a href="vehicles.php">Vehicles</a>
        <a href="services.php">Services</a>
        <a href="appointments.php">Appointments</a>
        <a href="mechanics.php">Mechanics</a>

        <p>Account</p>

        <a href="../logout.php" class="logout">
            Logout
        </a>

    </aside>

    <main class="main">

        <div class="topbar">

            <h1>Dashboard</h1>

            <div class="user-info">
                Administrator:
                <strong>
                    <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                </strong>
            </div>

        </div>

        <div class="welcome">

            <h2>Welcome back, <?php
                echo htmlspecialchars($_SESSION['full_name']);
            ?>!</h2>

            <p>
                Here is an overview of your AutoCare service management system.
            </p>

        </div>

        <div class="stats">

            <div class="stat-card">
                <p>Total Customers</p>
                <h2><?php echo $total_customers; ?></h2>
            </div>

            <div class="stat-card">
                <p>Registered Vehicles</p>
                <h2><?php echo $total_vehicles; ?></h2>
            </div>

            <div class="stat-card">
                <p>Available Services</p>
                <h2><?php echo $total_services; ?></h2>
            </div>

            <div class="stat-card">
                <p>Total Appointments</p>
                <h2><?php echo $total_appointments; ?></h2>
            </div>

        </div>

        <div class="section">

            <h3>System Overview</h3>

            <p>
                Manage your customers, vehicles, services and
                appointments from one central dashboard.
            </p>

        </div>

    </main>

</div>

</body>
</html>
