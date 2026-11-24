<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
$title = 'Automjetet';
$error = '';
$edit = null;

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id > 0) {
        stmt($conn, 'DELETE FROM vehicles WHERE id = ?', 'i', [$id]);
        flash('Automjeti u fshi.');
    }
    redirect('vehicles.php');
}

if (isset($_GET['edit'])) {
    $edit = stmt($conn, 'SELECT * FROM vehicles WHERE id = ?', 'i', [(int) $_GET['edit']])->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $userId = (int) ($_POST['user_id'] ?? 0);
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $plate = strtoupper(trim($_POST['plate_number'] ?? ''));

    if (!$userId || $brand === '' || $model === '' || $plate === '') {
        $error = 'Plotësoni të gjitha të dhënat e kërkuara të automjetit.';
    } else {
        try {
            if ($id > 0) {
                stmt($conn, 'UPDATE vehicles SET user_id = ?, brand = ?, model = ?, year = NULLIF(?, 0), plate_number = ? WHERE id = ?', 'issisi', [$userId, $brand, $model, $year, $plate, $id]);
            } else {
                stmt($conn, 'INSERT INTO vehicles (user_id, brand, model, year, plate_number) VALUES (?, ?, ?, NULLIF(?, 0), ?)', 'issis', [$userId, $brand, $model, $year, $plate]);
            }
            flash('Automjeti u ruajt me sukses.');
            redirect('vehicles.php');
        } catch (mysqli_sql_exception $e) {
            $error = $e->getCode() === 1062 ? 'Kjo targë është e regjistruar tashmë.' : 'Automjeti nuk mund të ruhej.';
        }
    }
}

$users = $conn->query("SELECT id, full_name FROM users WHERE role = 'customer' ORDER BY full_name");
$rows = $conn->query('SELECT v.*, u.full_name FROM vehicles v INNER JOIN users u ON u.id = v.user_id ORDER BY v.id DESC');
include __DIR__ . '/../includes/admin_header.php';
?>
<div class="form-card" style="margin-bottom:20px">
    <div class="panel-head">
        <h3><?= $edit ? 'Ndrysho automjetin' : 'Regjistro automjet' ?></h3>
        <?php if ($edit): ?><a class="btn btn-light btn-sm" href="vehicles.php">Anulo</a><?php endif; ?>
    </div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <div class="form-grid">
            <div class="field full"><label>Pronari</label><select name="user_id" required><option value="">Zgjidh klientin</option><?php while ($user = $users->fetch_assoc()): ?><option value="<?= (int) $user['id'] ?>" <?= (($edit['user_id'] ?? '') == $user['id']) ? 'selected' : '' ?>><?= e($user['full_name']) ?></option><?php endwhile; ?></select></div>
            <div class="field"><label>Marka</label><input name="brand" required value="<?= e($edit['brand'] ?? '') ?>" placeholder="p.sh. Volkswagen"></div>
            <div class="field"><label>Modeli</label><input name="model" required value="<?= e($edit['model'] ?? '') ?>" placeholder="p.sh. Golf 7"></div>
            <div class="field"><label>Viti</label><input type="number" name="year" min="1950" max="2099" value="<?= e($edit['year'] ?? '') ?>"></div>
            <div class="field"><label>Targa</label><input name="plate_number" required value="<?= e($edit['plate_number'] ?? '') ?>" placeholder="01-123-AB"></div>
        </div>
        <div class="form-actions"><button class="btn">Ruaj automjetin</button></div>
    </form>
</div>

<div class="toolbar"><h3>Automjete të regjistruara</h3><input class="search" placeholder="Kërko automjete..." data-search="#vehicles"></div>
<div class="table-wrap">
    <table class="data-table" id="vehicles">
        <thead><tr><th>Automjeti</th><th>Pronari</th><th>Viti</th><th>Targa</th><th>Veprimet</th></tr></thead>
        <tbody>
        <?php if (!$rows->num_rows): ?>
            <tr><td colspan="5" class="empty">Ende nuk është regjistruar asnjë automjet.</td></tr>
        <?php else: while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><b><?= e($row['brand'] . ' ' . $row['model']) ?></b></td>
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['year'] ?: '—') ?></td>
                <td><?= e($row['plate_number']) ?></td>
                <td class="actions"><a class="btn btn-light btn-sm" href="?edit=<?= (int) $row['id'] ?>">Ndrysho</a><a class="btn btn-danger btn-sm" data-confirm="Të fshihet ky automjet?" href="?delete=<?= (int) $row['id'] ?>">Fshi</a></td>
            </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
