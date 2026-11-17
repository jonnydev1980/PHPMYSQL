
<?php
require_once '../config/app.php';
require_role('admin');

$title = 'Mechanics';

/* Delete mechanic */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    stmt($conn, 'DELETE FROM mechanics WHERE id = ?', 'i', [$id]);

    flash('Mechanic deleted successfully.');
    redirect('mechanics.php');
}

/* Load mechanic for editing */
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
        flash('Mechanic not found.');
        redirect('mechanics.php');
    }
}

/* Save mechanic */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) ($_POST['id'] ?? 0);

    $name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $status = $_POST['status'] ?? 'available';

    $allowedStatuses = ['available', 'busy'];

    if ($name === '') {
        flash('Please enter the mechanic name.');
        redirect('mechanics.php');
    }

    if (!in_array($status, $allowedStatuses, true)) {
        $status = 'available';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('Please enter a valid email address.');
        redirect('mechanics.php');
    }

    if ($id > 0) {

        stmt(
            $conn,
            'UPDATE mechanics
             SET full_name = ?,
                 phone = ?,
                 email = ?,
                 specialization = ?,
                 status = ?
             WHERE id = ?',
            'sssssi',
            [$name, $phone, $email, $specialization, $status, $id]
        );

        flash('Mechanic updated successfully.');

    } else {

        stmt(
            $conn,
            'INSERT INTO mechanics
             (full_name, phone, email, specialization, status)
             VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$name, $phone, $email, $specialization, $status]
        );

        flash('Mechanic added successfully.');
    }

    redirect('mechanics.php');
}

/* Get workshop team */
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
            <?= $edit ? 'Edit mechanic' : 'Add mechanic' ?>
        </h3>

        <?php if ($edit): ?>
            <a href="mechanics.php" class="btn btn-light btn-sm">
                Cancel
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
                <label>Full name</label>
                <input
                    type="text"
                    name="full_name"
                    required
                    value="<?= e($edit['full_name'] ?? '') ?>"
                    placeholder="Enter full name"
                >
            </div>

            <div class="field">
                <label>Phone</label>
                <input
                    type="text"
                    name="phone"
                    value="<?= e($edit['phone'] ?? '') ?>"
                    placeholder="+383..."
                >
            </div>

            <div class="field">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="<?= e($edit['email'] ?? '') ?>"
                    placeholder="mechanic@example.com"
                >
            </div>

            <div class="field">
                <label>Specialization</label>
                <input
                    type="text"
                    name="specialization"
                    value="<?= e($edit['specialization'] ?? '') ?>"
                    placeholder="Oil Change, Diagnostics..."
                >
            </div>

            <div class="field">
                <label>Status</label>

                <select name="status">

                    <option
                        value="available"
                        <?= ($edit['status'] ?? 'available') === 'available' ? 'selected' : '' ?>
                    >
                        Available
                    </option>

                    <option
                        value="busy"
                        <?= ($edit['status'] ?? '') === 'busy' ? 'selected' : '' ?>
                    >
                        Busy
                    </option>

                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn">
                <?= $edit ? 'Update mechanic' : 'Save mechanic' ?>
            </button>
        </div>

    </form>
</div>


<!-- Workshop team -->

<div class="toolbar">

    <h3>Workshop team</h3>

    <input
        class="search"
        type="text"
        placeholder="Search mechanics..."
        data-search="#mechanics"
    >

</div>


<div class="table-wrap">

    <table class="data-table" id="mechanics">

        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Jobs</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!$rows->num_rows): ?>

            <tr>
                <td colspan="7" class="empty">
                    No mechanics added.
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

                    <td><?= e($r['specialization'] ?? '') ?></td>

                    <td><?= e($r['jobs']) ?></td>

                    <td>
                        <span class="badge <?= e($r['status']) ?>">
                            <?= e(ucfirst($r['status'])) ?>
                        </span>
                    </td>

                    <td class="actions">

                        <a
                            class="btn btn-light btn-sm"
                            href="?edit=<?= (int) $r['id'] ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="btn btn-danger btn-sm"
                            data-confirm="Are you sure you want to delete this mechanic?"
                            href="?delete=<?= (int) $r['id'] ?>"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<?php include '../includes/footer.php'; ?>
