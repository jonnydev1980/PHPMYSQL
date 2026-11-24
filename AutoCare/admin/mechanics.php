
<?php
require_once '../config/app.php';
require_role('admin');

$title = 'Mekanikët';

/* Fshi mechanic */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    stmt($conn, 'DELETE FROM mechanics WHERE id = ?', 'i', [$id]);

    flash('Mekaniku u fshi me sukses.');
    redirect('mechanics.php');
}

/* Merr mekanikun për ndryshim */
$edit = null;

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $result = stmt(
        $conn,
        'SELECT * FROM mechanics WHERE id = ?',
        'i',
        [$id]
    );

    $edit = $result->get_result()->fetch_assoc();

    if (!$edit) {
        flash('Mekaniku nuk u gjet.');
        redirect('mechanics.php');
    }
}

/* Ruaj mekanikun */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) ($_POST['id'] ?? 0);

    $name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $status = $_POST['status'] ?? 'active';

    $allowedStatuses = ['active', 'inactive'];

    if ($name === '') {
        flash('Ju lutemi shkruani emrin e mekanikut.');
        redirect('mechanics.php');
    }

    if (!in_array($status, $allowedStatuses, true)) {
        $status = 'active';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('Ju lutemi shkruani një adresë të vlefshme emaili.');
        redirect('mechanics.php');
    }

    if ($id > 0) {

        stmt(
            $conn,
            'UPDATE mechanics
             SET full_name = ?,
                 phone = ?,
                 email = ?,
                 specialty = ?,
                 status = ?
             WHERE id = ?',
            'sssssi',
            [$name, $phone, $email, $specialization, $status, $id]
        );

        flash('Të dhënat e mekanikut u përditësuan.');

    } else {

        stmt(
            $conn,
            'INSERT INTO mechanics
             (full_name, phone, email, specialty, status)
             VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$name, $phone, $email, $specialization, $status]
        );

        flash('Mekaniku u shtua me sukses.');
    }

    redirect('mechanics.php');
}

/* Merr ekipin e servisit */
$rows = $conn->query(
    "SELECT
        m.*,
        COUNT(a.id) AS jobs
     FROM mechanics m
     LEFT JOIN appointments a
        ON a.mechanic_id = m.id
     GROUP BY m.id
     ORDER BY m.id DESC"
);

include '../includes/admin_header.php';
?>

<div class="form-card" style="margin-bottom:20px">

    <div class="panel-head">
        <h3>
            <?= $edit ? 'Ndrysho mekanikun' : 'Shto mekanik' ?>
        </h3>

        <?php if ($edit): ?>
            <a href="mechanics.php" class="btn btn-light btn-sm">
                Anulo
            </a>
        <?php endif; ?>
    </div>

    <form method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= e($edit['id'] ?? '') ?>"
        >

        <div class="form-grid">

            <div class="field">
                <label>Emri dhe mbiemri</label>
                <input
                    type="text"
                    name="full_name"
                    required
                    value="<?= e($edit['full_name'] ?? '') ?>"
                    placeholder="Shkruani emrin e plotë"
                >
            </div>

            <div class="field">
                <label>Telefoni</label>
                <input
                    type="text"
                    name="phone"
                    value="<?= e($edit['phone'] ?? '') ?>"
                    placeholder="+383..."
                >
            </div>

            <div class="field">
                <label>Emaili</label>
                <input
                    type="email"
                    name="email"
                    value="<?= e($edit['email'] ?? '') ?>"
                    placeholder="mechanic@example.com"
                >
            </div>

            <div class="field">
                <label>Specializimi</label>
                <input
                    type="text"
                    name="specialization"
                    value="<?= e($edit['specialty'] ?? '') ?>"
                    placeholder="p.sh. Diagnostikim, motor, frena..."
                >
            </div>

            <div class="field">
                <label>Statusi</label>

                <select name="status">

                    <option
                        value="active"
                        <?= ($edit['status'] ?? 'active') === 'active' ? 'selected' : '' ?>
                    >
                        Aktiv
                    </option>

                    <option
                        value="inactive"
                        <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>
                    >
                        Joaktiv
                    </option>

                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn">
                <?= $edit ? 'Përditëso mekanikun' : 'Ruaj mekanikun' ?>
            </button>
        </div>

    </form>
</div>


<!-- Ekipi i servisit -->

<div class="toolbar">

    <h3>Ekipi i servisit</h3>

    <input
        class="search"
        type="text"
        placeholder="Kërko mekanikë..."
        data-search="#mechanics"
    >

</div>


<div class="table-wrap">

    <table class="data-table" id="mechanics">

        <thead>
            <tr>
                <th>Emri</th>
                <th>Telefoni</th>
                <th>Emaili</th>
                <th>Specializimi</th>
                <th>Punët</th>
                <th>Statusi</th>
                <th>Veprimet</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!$rows->num_rows): ?>

            <tr>
                <td colspan="7" class="empty">
                    Nuk është shtuar asnjë mekanik.
                </td>
            </tr>

        <?php else: ?>

            <?php while ($r = $rows->fetch_assoc()): ?>

                <tr>

                    <td>
                        <b><?= e($r['full_name']) ?></b>
                    </td>

                    <td><?= e($r['phone'] ?? '') ?></td>

                    <td><?= e($r['email'] ?? '') ?></td>

                    <td><?= e($r['specialty'] ?? '') ?></td>

                    <td><?= e($r['jobs']) ?></td>

                    <td>
                        <span class="badge <?= e($r['status']) ?>">
                            <?= e($r['status'] === 'active' ? 'Aktiv' : 'Joaktiv') ?>
                        </span>
                    </td>

                    <td class="actions">

                        <a
                            class="btn btn-light btn-sm"
                            href="?edit=<?= (int) $r['id'] ?>"
                        >
                            Ndrysho
                        </a>

                        <a
                            class="btn btn-danger btn-sm"
                            data-confirm="A dëshironi ta fshini këtë mekanik?"
                            href="?delete=<?= (int) $r['id'] ?>"
                        >
                            Fshi
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<?php include '../includes/footer.php'; ?>
