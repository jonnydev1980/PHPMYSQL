<?php
require_once __DIR__ . '/../config/app.php';
require_role('customer');
$title = 'My Vehicles';
$error = '';

if (isset($_GET['delete'])) {
    $vehicleId = (int) $_GET['delete'];
    if ($vehicleId > 0) {
        stmt($conn, 'DELETE FROM vehicles WHERE id = ? AND user_id = ?', 'ii', [$vehicleId, $_SESSION['user_id']]);
        flash('Vehicle removed.');
    }
    redirect('vehicles.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $plate = strtoupper(trim($_POST['plate_number'] ?? ''));

    if ($brand === '' || $model === '' || $plate === '') {
        $error = 'Complete the brand, model and plate number fields.';
    } else {
        try {
            stmt($conn, 'INSERT INTO vehicles (user_id, brand, model, year, plate_number) VALUES (?, ?, ?, NULLIF(?, 0), ?)', 'issis', [$_SESSION['user_id'], $brand, $model, $year, $plate]);
            flash('Vehicle registered successfully.');
            redirect('vehicles.php');
        } catch (mysqli_sql_exception $e) {
            $error = $e->getCode() === 1062 ? 'This plate number is already registered.' : 'The vehicle could not be saved.';
        }
    }
}

$rows = stmt($conn, 'SELECT * FROM vehicles WHERE user_id = ? ORDER BY id DESC', 'i', [$_SESSION['user_id']])->get_result();
include __DIR__ . '/../includes/customer_header.php';
?>
<div class="form-card">
    <h3>Register a vehicle</h3>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <div class="form-grid">
            <div class="field"><label>Brand</label><input name="brand" required placeholder="e.g. Volkswagen"></div>
            <div class="field"><label>Model</label><input name="model" required placeholder="e.g. Golf 7"></div>
            <div class="field"><label>Year</label><input type="number" name="year" min="1950" max="2099"></div>
            <div class="field"><label>Plate number</label><input name="plate_number" required placeholder="e.g. 01-123-AB"></div>
        </div>
        <div class="form-actions"><button class="btn">Add vehicle</button></div>
    </form>
</div>

<div class="section-head"><h2>Registered vehicles</h2></div>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Vehicle</th><th>Year</th><th>Plate</th><th>Action</th></tr></thead>
        <tbody>
        <?php if (!$rows->num_rows): ?>
            <tr><td colspan="4" class="empty">No vehicles registered yet.</td></tr>
        <?php else: while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><b><?= e($row['brand'] . ' ' . $row['model']) ?></b></td>
                <td><?= e($row['year'] ?: '—') ?></td>
                <td><?= e($row['plate_number']) ?></td>
                <td><a class="btn btn-danger btn-sm" data-confirm="Remove this vehicle?" href="?delete=<?= (int) $row['id'] ?>">Remove</a></td>
            </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
