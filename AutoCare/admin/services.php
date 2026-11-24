<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
$title = 'Shërbimet';
$error = '';
$edit = null;

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id > 0) {
        try {
            stmt($conn, 'DELETE FROM services WHERE id = ?', 'i', [$id]);
            flash('Shërbimi u fshi.');
        } catch (mysqli_sql_exception $e) {
            flash('Ky shërbim nuk mund të fshihet sepse është i lidhur me një termin.', 'error');
        }
    }
    redirect('services.php');
}

if (isset($_GET['edit'])) {
    $result = stmt($conn, 'SELECT * FROM services WHERE id = ?', 'i', [(int) $_GET['edit']])->get_result();
    $edit = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['service_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $duration = (int) ($_POST['duration'] ?? 0);

    if ($name === '' || $price < 0 || $duration < 1) {
        $error = 'Shkruani emrin e shërbimit, një çmim të vlefshëm dhe kohëzgjatje më të madhe se zero.';
    } else {
        if ($id > 0) {
            stmt($conn, 'UPDATE services SET service_name = ?, description = ?, price = ?, duration = ? WHERE id = ?', 'ssdii', [$name, $description, $price, $duration, $id]);
        } else {
            stmt($conn, 'INSERT INTO services (service_name, description, price, duration) VALUES (?, ?, ?, ?)', 'ssdi', [$name, $description, $price, $duration]);
        }
        flash('Shërbimi u ruajt me sukses.');
        redirect('services.php');
    }
}

$rows = $conn->query('SELECT * FROM services ORDER BY id DESC');
include __DIR__ . '/../includes/admin_header.php';
?>
<div class="form-card" style="margin-bottom:20px">
    <div class="panel-head">
        <h3><?= $edit ? 'Ndrysho shërbimin' : 'Shto shërbim' ?></h3>
        <?php if ($edit): ?><a class="btn btn-light btn-sm" href="services.php">Anulo</a><?php endif; ?>
    </div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <div class="form-grid">
            <div class="field"><label>Emri i shërbimit</label><input name="service_name" required maxlength="100" value="<?= e($edit['service_name'] ?? '') ?>" placeholder="Ndërrim i vajit"></div>
            <div class="field"><label>Çmimi (€)</label><input type="number" name="price" min="0" step="0.01" required value="<?= e($edit['price'] ?? '') ?>"></div>
            <div class="field"><label>Kohëzgjatja (minuta)</label><input type="number" name="duration" min="1" required value="<?= e($edit['duration'] ?? 60) ?>"></div>
            <div class="field full"><label>Përshkrimi</label><textarea name="description"><?= e($edit['description'] ?? '') ?></textarea></div>
        </div>
        <div class="form-actions"><button class="btn">Ruaj shërbimin</button></div>
    </form>
</div>

<div class="toolbar">
    <h3>Lista e shërbimeve</h3>
    <input class="search" placeholder="Kërko shërbime..." data-search="#services">
</div>
<div class="table-wrap">
    <table class="data-table" id="services">
        <thead><tr><th>Shërbimi</th><th>Përshkrimi</th><th>Çmimi</th><th>Kohëzgjatja</th><th>Veprimet</th></tr></thead>
        <tbody>
        <?php if (!$rows->num_rows): ?>
            <tr><td colspan="5" class="empty">Ende nuk është shtuar asnjë shërbim.</td></tr>
        <?php else: while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><b><?= e($row['service_name']) ?></b></td>
                <td><?= e($row['description']) ?></td>
                <td>€<?= number_format((float) $row['price'], 2) ?></td>
                <td><?= e($row['duration']) ?> min</td>
                <td class="actions">
                    <a class="btn btn-light btn-sm" href="?edit=<?= (int) $row['id'] ?>">Ndrysho</a>
                    <a class="btn btn-danger btn-sm" data-confirm="Të fshihet ky shërbim?" href="?delete=<?= (int) $row['id'] ?>">Fshi</a>
                </td>
            </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
