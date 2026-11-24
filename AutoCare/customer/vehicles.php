<?php
require_once __DIR__ . '/../config/app.php';
require_role('customer');
$title = 'Automjetet e mia';
$error = '';

if (isset($_GET['delete'])) {
    $vehicleId = (int) $_GET['delete'];
    if ($vehicleId > 0) {
        stmt($conn, 'DELETE FROM vehicles WHERE id = ? AND user_id = ?', 'ii', [$vehicleId, $_SESSION['user_id']]);
        flash('Automjeti u largua.');
    }
    redirect('vehicles.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $plate = strtoupper(trim($_POST['plate_number'] ?? ''));

    if ($brand === '' || $model === '' || $plate === '') {
        $error = 'Plotësoni markën, modelin dhe targën.';
    } else {
        try {
            stmt($conn, 'INSERT INTO vehicles (user_id, brand, model, year, plate_number) VALUES (?, ?, ?, NULLIF(?, 0), ?)', 'issis', [$_SESSION['user_id'], $brand, $model, $year, $plate]);
            flash('Automjeti u regjistrua me sukses.');
            redirect('vehicles.php');
        } catch (mysqli_sql_exception $e) {
            $error = $e->getCode() === 1062 ? 'Kjo targë është e regjistruar tashmë.' : 'Automjeti nuk mund të ruhej.';
        }
    }
}

$rows = stmt($conn, 'SELECT * FROM vehicles WHERE user_id = ? ORDER BY id DESC', 'i', [$_SESSION['user_id']])->get_result();
include __DIR__ . '/../includes/customer_header.php';
?>
<div class="form-card">
    <h3>Regjistro automjet</h3>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <div class="form-grid">
            <div class="field"><label>Marka</label><input name="brand" required placeholder="p.sh. Volkswagen"></div>
            <div class="field"><label>Modeli</label><input name="model" required placeholder="p.sh. Golf 7"></div>
            <div class="field"><label>Viti</label><input type="number" name="year" min="1950" max="2099"></div>
            <div class="field"><label>Targa</label><input name="plate_number" required placeholder="p.sh. 01-123-AB"></div>
        </div>
        <div class="form-actions"><button class="btn">Shto automjet</button></div>
    </form>
</div>

<div class="section-head"><h2>Automjete të regjistruara</h2></div>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Automjeti</th><th>Viti</th><th>Targa</th><th>Veprimi</th></tr></thead>
        <tbody>
        <?php if (!$rows->num_rows): ?>
            <tr><td colspan="4" class="empty">Ende nuk keni regjistruar automjet.</td></tr>
        <?php else: while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><b><?= e($row['brand'] . ' ' . $row['model']) ?></b></td>
                <td><?= e($row['year'] ?: '—') ?></td>
                <td><?= e($row['plate_number']) ?></td>
                <td><a class="btn btn-danger btn-sm" data-confirm="Të largohet ky automjet?" href="?delete=<?= (int) $row['id'] ?>">Largo</a></td>
            </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
