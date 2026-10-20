
<?php
session_start();

include_once('config.php');

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Check if the user ID exists in the URL
$id = $_GET['id'] ?? null;

if ($id === null || !is_numeric($id)) {
    exit('Invalid or missing user ID.');
}

// Get user information from the database
$sql = "SELECT * FROM users WHERE id = :id";
$selectUser = $conn->prepare($sql);
$selectUser->bindValue(':id', $id, PDO::PARAM_INT);
$selectUser->execute();

$user_data = $selectUser->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$user_data) {
    exit('User not found.');
}
?>

<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">
        Welcome,
        <i><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></i>
    </a>

    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="logout.php">SIGN OUT</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">

        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <span data-feather="home"></span>
                            Dashboard
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active"
                           href="profile.php?id=<?php echo $user_data['id']; ?>">
                            <span data-feather="file"></span>
                            Edit Profile
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <div class="container">
                <div class="row">

                    <div class="col-md-5">

                        <form class="form-profile" action="update.php" method="post">

                            <span class="text-muted" for="id">Id</span>
                            <input
                                type="number"
                                class="form-control"
                                id="floatingInput"
                                placeholder="Id"
                                name="id"
                                value="<?php echo htmlspecialchars((string) $user_data['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                readonly
                            >

                            <span class="text-muted" for="name">Name</span>
                            <input
                                class="form-control"
                                type="text"
                                name="name"
                                value="<?php echo htmlspecialchars($user_data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            ><br>

                            <span class="text-muted" for="surname">Surname</span>
                            <input
                                class="form-control"
                                type="text"
                                name="surname"
                                value="<?php echo htmlspecialchars($user_data['surname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            ><br>

                            <span class="text-muted" for="username">Username</span>
                            <input
                                class="form-control"
                                type="text"
                                name="username"
                                value="<?php echo htmlspecialchars($user_data['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            ><br>

                            <span class="text-muted" for="email">Email</span>
                            <input
                                class="form-control"
                                type="email"
                                name="email"
                                value="<?php echo htmlspecialchars($user_data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            ><br>

                            <span class="text-muted" for="password">Password</span>
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                autocomplete="new-password"
                                required
                            ><br><br>

                            <button
                                class="btn btn-lg btn-primary"
                                type="submit"
                                name="update"
                            >Update</button>

                        </form>

                    </div>

                </div>
            </div>

        </main>

    </div>
</div>
</div>
